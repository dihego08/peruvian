import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../../services/api';

export default function NewSellView() {
  const navigate = useNavigate();
  const [clients, setClients] = useState([]);
  const [users, setUsers] = useState([]);
  const [unidadesSunat, setUnidadesSunat] = useState([]);

  const [tiposPago, setTiposPago] = useState([]);
  const [tiposEntrega, setTiposEntrega] = useState([]);
  const [formasPago, setFormasPago] = useState([]);

  const [searchQuery, setSearchQuery] = useState('');
  const [searchResults, setSearchResults] = useState([]);

  const [cart, setCart] = useState([]);

  const [formData, setFormData] = useState({
    person_id: '',
    user_id: '1',
    invoice_code: '',
    guia: '',
    discount: 0,
    cash: 0,
    detraccion: 'no',
    detraccion_p: 0,
    incluye_igv: '1',
    tipo_documento: '2',
    // Novedades:
    fecha_emision: new Date().toISOString().slice(0, 10),
    fecha_vencimiento: new Date().toISOString().slice(0, 10),
    tipos_pago: '4',
    tipos_entrega: '',
    forma_pago: '2',
    nuevo_ruc: ''
  });

  const [isSearchingRuc, setIsSearchingRuc] = useState(false);
  const [rucResult, setRucResult] = useState(null);

  const [totals, setTotals] = useState({ subtotal: 0, igv: 0, total: 0 });

  useEffect(() => {
    fetchClients();
    fetchUsers();
    fetchUnidadesSunat();
    fetchDictionaries();
  }, []);

  useEffect(() => {
    calculateTotals();
  }, [cart, formData.discount, formData.incluye_igv, formData.tipo_documento, formData.detraccion]);

  useEffect(() => {
    const fetchCorrelativo = async () => {
      try {
        const res = await api.get(`/transactions/sells/correlativo?tipo_documento=${formData.tipo_documento}`);
        if (res.data && res.data.correlativo) {
          setFormData(prev => ({ ...prev, invoice_code: res.data.correlativo }));
        }
      } catch (error) {
        console.warn("Could not fetch correlativo", error);
      }
    };
    if (formData.tipo_documento) {
      fetchCorrelativo();
    }
  }, [formData.tipo_documento]);

  const fetchClients = async () => {
    const res = await api.get('/clients');
    setClients(res.data);
  };

  const fetchUsers = async () => {
    const res = await api.get('/users');
    setUsers(res.data);
  };

  const fetchUnidadesSunat = async () => {
    try {
      const res = await api.get('/codigos-sunat');
      setUnidadesSunat(res.data);
    } catch (e) {
      console.warn("Tabla codigos_sunat no encontrada", e);
    }
  };

  const fetchDictionaries = async () => {
    try {
      const [pRes, dRes, fRes] = await Promise.all([
        api.get('/tipos-pago'),
        api.get('/tipos-entrega'),
        api.get('/forma-pago')
      ]);
      setTiposPago(pRes.data);
      setTiposEntrega(dRes.data);
      setFormasPago(fRes.data);

      // Auto select first option if available
      /*if (pRes.data.length > 0) setFormData(prev => ({ ...prev, tipos_pago: pRes.data[0].id }));
      if (dRes.data.length > 0) setFormData(prev => ({ ...prev, tipos_entrega: dRes.data[0].id }));*/
    } catch (e) {
      console.warn("Error cargando diccionarios de pago/entrega", e);
    }
  };

  const handleSearchRuc = async () => {
    if (!formData.nuevo_ruc) return;
    setIsSearchingRuc(true);
    setRucResult(null);
    try {
      const response = await fetch(`https://dbusinessaqp.com/api_ruc/api.php?ruc=${formData.nuevo_ruc}`);
      const data = await response.json();
      setRucResult(data);
    } catch (error) {
      console.error(error);
      alert('Error buscando RUC');
    } finally {
      setIsSearchingRuc(false);
    }
  };

  const handleSearch = async (e) => {
    e.preventDefault();
    if (!searchQuery) return;
    try {
      const res = await api.get(`/products-search?q=${searchQuery}`);
      const results = res.data.map(p => ({
        ...p,
        edit_q: 1,
        edit_unidad: p.unit || '',
        edit_pedido: '',
        edit_tipo: 'Producto',
        edit_name: p.name,
        edit_price_unit: p.price_out || p.price_in || 0,
        edit_price_bordado: 0
      }));
      setSearchResults(results);
    } catch (err) {
      console.error(err);
    }
  };

  const handleResultChange = (id, field, value) => {
    setSearchResults(searchResults.map(item =>
      item.id === id ? { ...item, [field]: value } : item
    ));
  };

  const addToCart = (item) => {
    const newItem = {
      id: Date.now() + Math.random(),
      product_id: item.id,
      code: item.code,
      name: item.edit_name,
      tipo: item.edit_tipo,
      unidad: item.edit_unidad,
      pedido: item.edit_pedido,
      q: Number(item.edit_q),
      price_unit: Number(item.edit_price_unit),
      price_bordado: Number(item.edit_price_bordado),
    };

    setCart([...cart, newItem]);
  };

  const removeFromCart = (uniqueId) => {
    setCart(cart.filter(item => item.id !== uniqueId));
  };

  const calculateTotals = () => {
    let rawSubtotal = cart.reduce((acc, item) => acc + ((item.price_unit + item.price_bordado) * item.q), 0);
    let discount = Number(formData.discount) || 0;

    let base = rawSubtotal - discount;
    let sub = 0; let igv = 0; let tot = 0;

    if (formData.tipo_documento === '2') {
      if (formData.incluye_igv === '1') {
        tot = base;
        sub = tot / 1.18;
        igv = tot - sub;
      } else {
        sub = base;
        igv = sub * 0.18;
        tot = sub + igv;
      }
    } else {
      sub = base;
      tot = sub;
      igv = 0;
    }

    setTotals({ subtotal: sub, igv: igv, total: tot });

    if (formData.detraccion === 'yes') {
      setFormData(prev => ({ ...prev, detraccion_p: Math.round(tot * 0.10) }));
    } else {
      setFormData(prev => ({ ...prev, detraccion_p: 0 }));
    }
  };

  const handleSubmit = async () => {
    if (cart.length === 0) {
      alert("El carrito está vacío.");
      return;
    }
    if (!formData.person_id && !formData.nuevo_ruc) {
      alert("Selecciona un cliente o ingresa un RUC nuevo.");
      return;
    }
    if (!formData.invoice_code) {
      alert("Ingresa el número de comprobante.");
      return;
    }

    const payload = {
      ...formData,
      ruc_result: rucResult,
      subtotal: totals.subtotal,
      igv: totals.igv,
      total: totals.total,
      operations: cart.map(item => ({
        product_id: item.product_id,
        q: item.q,
        price_out: item.price_unit + item.price_bordado,
        price_bordado: item.price_bordado,
        unidad: item.unidad,
        codigo_producto: item.code,
        unidad_label: unidadesSunat.find(u => u.codigo === item.unidad)?.unidad || '',
        pedido: item.pedido,
        tipo: item.name,
      }))
    };

    try {
      await api.post('/transactions/sells', payload);
      alert("Venta registrada correctamente!");
      navigate('/sells');
    } catch (error) {
      console.error(error);
      const msg = error.response?.data?.error || error.response?.data?.message || "Error al registrar la venta.";
      alert(msg);
    }
  };

  return (
    <div className="flex flex-col gap-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Nueva Venta</h1>
          <p className="text-sm text-gray-500 mt-0.5">Registro de comprobante y despacho de productos</p>
        </div>
        <button onClick={() => navigate('/sells')} className="text-sm text-gray-500 hover:text-gray-800 flex items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" /></svg>
          Volver
        </button>
      </div>

      {/* Search Bar */}
      <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 className="text-lg font-bold text-gray-800 mb-4">Buscar Producto</h2>
        <form onSubmit={handleSearch} className="flex gap-3">
          <input
            type="text"
            className="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border"
            placeholder="Escribe el nombre o código del producto..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
          />
          <button type="submit" className="bg-gray-800 text-white px-6 py-2.5 rounded-md hover:bg-gray-700 shadow-sm font-medium transition-colors">Buscar</button>
        </form>
      </div>

      {/* Search Results Grid (Editable before adding) */}
      {searchResults.length > 0 && (
        <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <h2 className="text-lg font-bold text-gray-800 mb-4">Resultado de Búsqueda</h2>
          <div className="overflow-x-auto border border-gray-200 rounded-lg">
            <table className="w-full text-left text-sm whitespace-nowrap">
              <thead className="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                  <th className="p-3 w-20 border-b">Cant.</th>
                  <th className="p-3 w-32 border-b">Unidad</th>
                  <th className="p-3 w-32 border-b">Pedido</th>
                  <th className="p-3 border-b">Modelo</th>
                  <th className="p-3 w-32 border-b">Tipo</th>
                  <th className="p-3 border-b">Producto</th>
                  <th className="p-3 w-28 border-b">P. Unit.</th>
                  <th className="p-3 w-28 border-b">P. Bord.</th>
                  <th className="p-3 w-16 border-b text-center">Acción</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-gray-100">
                {searchResults.map(item => (
                  <tr key={item.id} className="hover:bg-gray-50 transition-colors">
                    <td className="p-2">
                      <input type="number" min="1" className="w-full p-2 border border-gray-300 rounded-md text-center focus:border-blue-500 focus:ring-blue-500" value={item.edit_q} onChange={e => handleResultChange(item.id, 'edit_q', e.target.value)} />
                    </td>
                    <td className="p-2">
                      <select className="w-full p-2 border border-gray-300 rounded-md bg-white focus:border-blue-500 focus:ring-blue-500" value={item.edit_unidad} onChange={e => handleResultChange(item.id, 'edit_unidad', e.target.value)}>
                        <option value="">Unidades</option>
                        {unidadesSunat.map(u => (
                          <option key={u.id} value={u.codigo}>{u.unidad}</option>
                        ))}
                      </select>
                    </td>
                    <td className="p-2">
                      <input type="text" className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500" value={item.edit_pedido} onChange={e => handleResultChange(item.id, 'edit_pedido', e.target.value)} />
                    </td>
                    <td className="p-2 font-mono text-gray-600">{item.code || '-'}</td>
                    <td className="p-2">
                      <select className="w-full p-2 border border-gray-300 rounded-md bg-white focus:border-blue-500 focus:ring-blue-500" value={item.edit_tipo} onChange={e => handleResultChange(item.id, 'edit_tipo', e.target.value)}>
                        <option value="Producto">Producto</option>
                        <option value="Servicio">Servicio</option>
                      </select>
                    </td>
                    <td className="p-2">
                      <input type="text" className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500" value={item.edit_name} onChange={e => handleResultChange(item.id, 'edit_name', e.target.value)} />
                    </td>
                    <td className="p-2">
                      <input type="number" step="0.01" className="w-full p-2 border border-gray-300 rounded-md text-right focus:border-blue-500 focus:ring-blue-500" value={item.edit_price_unit} onChange={e => handleResultChange(item.id, 'edit_price_unit', e.target.value)} />
                    </td>
                    <td className="p-2">
                      <input type="number" step="0.01" className="w-full p-2 border border-gray-300 rounded-md text-right focus:border-blue-500 focus:ring-blue-500" value={item.edit_price_bordado} onChange={e => handleResultChange(item.id, 'edit_price_bordado', e.target.value)} />
                    </td>
                    <td className="p-2 text-center">
                      <button onClick={() => addToCart(item)} className="text-white bg-green-600 hover:bg-green-700 transition-colors rounded-md p-2 flex items-center justify-center font-bold mx-auto shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M12 4v16m8-8H4" /></svg>
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* Cart and Checkout Area */}
      <div className="flex flex-col lg:flex-row gap-6">

        {/* Cart */}
        <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex-1 overflow-hidden">
          <h2 className="text-lg font-bold text-gray-800 mb-4">Lista de Venta</h2>
          <div className="overflow-x-auto border border-gray-200 rounded-lg">
            <table className="w-full text-left text-sm whitespace-nowrap">
              <thead className="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                  <th className="p-3 w-20 border-b">Cantidad</th>
                  <th className="p-3 w-32 border-b">Unidad</th>
                  <th className="p-3 border-b">Modelo</th>
                  <th className="p-3 border-b">Pedido</th>
                  <th className="p-3 border-b">Producto</th>
                  <th className="p-3 w-28 text-right border-b">P. Unitario</th>
                  <th className="p-3 w-28 text-right border-b">P. Bordado</th>
                  <th className="p-3 w-32 text-right border-b">P. Total</th>
                  <th className="p-3 w-16 text-center border-b">Quitar</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-gray-100">
                {cart.map(item => (
                  <tr key={item.id} className="hover:bg-gray-50 transition-colors">
                    <td className="p-3 font-medium">{item.q}</td>
                    <td className="p-3 text-gray-600">
                      {unidadesSunat.find(u => u.codigo === item.unidad)?.unidad || 'Unidades'}
                    </td>
                    <td className="p-3 font-mono text-gray-600">{item.code}</td>
                    <td className="p-3 text-gray-600">{item.pedido}</td>
                    <td className="p-3 font-medium truncate max-w-xs">
                      {item.tipo === 'Servicio' ? <span className="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-md mr-2">Servicio</span> : ''}
                      {item.name}
                    </td>
                    <td className="p-3 text-right">S/ {item.price_unit.toFixed(4)}</td>
                    <td className="p-3 text-right">S/ {item.price_bordado.toFixed(2)}</td>
                    <td className="p-3 text-right font-bold text-gray-800">
                      S/ {((item.price_unit + item.price_bordado) * item.q).toFixed(2)}
                    </td>
                    <td className="p-3 text-center">
                      <button onClick={() => removeFromCart(item.id)} className="text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors rounded-md p-1.5 flex items-center justify-center mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                      </button>
                    </td>
                  </tr>
                ))}
                {cart.length === 0 && (
                  <tr>
                    <td colSpan="9" className="p-8 text-center text-gray-400">El carrito está vacío</td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>

        {/* Resumen & Billing Info */}
        <div className="w-full lg:w-[450px] flex flex-col gap-6 shrink-0">
          <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 className="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Información de Venta</h2>

            <div className="space-y-4">

              {/* Client Selection vs New RUC */}
              <div className="bg-blue-50 p-3 rounded-lg border border-blue-100">
                <label className="block text-xs font-bold text-blue-800 uppercase tracking-wider mb-1">Cliente Registrado</label>
                <select className="w-full p-2 border border-blue-200 rounded-md focus:border-blue-500 focus:ring-blue-500 bg-white mb-2 text-sm" value={formData.person_id} onChange={e => setFormData({ ...formData, person_id: e.target.value, nuevo_ruc: '', rucResult: null })}>
                  <option value="">Seleccione...</option>
                  {clients.map(c => <option key={c.id} value={c.id}>{c.name} {c.lastname}</option>)}
                </select>

                <div className="relative flex items-center justify-center my-3">
                  <div className="border-t border-blue-200 w-full"></div>
                  <span className="bg-blue-50 px-2 text-blue-400 text-xs font-bold">O</span>
                  <div className="border-t border-blue-200 w-full"></div>
                </div>

                <label className="block text-xs font-bold text-blue-800 uppercase tracking-wider mb-1">Buscar Nuevo RUC</label>
                <div className="flex gap-2">
                  <input
                    type="text"
                    className="flex-1 p-2 border border-blue-200 rounded-md focus:border-blue-500 text-sm"
                    placeholder="Ingrese RUC..."
                    value={formData.nuevo_ruc}
                    onChange={e => setFormData({ ...formData, nuevo_ruc: e.target.value, person_id: '' })}
                    disabled={!!formData.person_id}
                  />
                  <button
                    type="button"
                    onClick={handleSearchRuc}
                    disabled={!!formData.person_id || isSearchingRuc}
                    className="bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700 disabled:opacity-50 text-sm font-medium"
                  >
                    {isSearchingRuc ? 'Buscando...' : 'Buscar'}
                  </button>
                </div>
                {rucResult && (
                  <div className="mt-2 text-xs bg-white p-2 border border-blue-200 rounded">
                    <strong>{rucResult.nombre}</strong><br />
                    <span className="text-gray-600">{rucResult.direccion}</span>
                  </div>
                )}
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">F. Emisión</label>
                  <input type="date" className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" value={formData.fecha_emision} onChange={e => setFormData({ ...formData, fecha_emision: e.target.value })} />
                </div>
                <div>
                  <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">F. Vencimiento</label>
                  <input type="date" className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" value={formData.fecha_vencimiento} onChange={e => setFormData({ ...formData, fecha_vencimiento: e.target.value })} />
                </div>
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Documento</label>
                  <select className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" value={formData.tipo_documento} onChange={e => setFormData({ ...formData, tipo_documento: e.target.value })}>
                    <option value="2">Factura/Boleta</option>
                    <option value="1">Ticket</option>
                  </select>
                </div>
                <div>
                  <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nº Comprobante *</label>
                  <input type="text" className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 font-mono font-bold text-sm" placeholder="F001-XXXX" value={formData.invoice_code} onChange={e => setFormData({ ...formData, invoice_code: e.target.value })} />
                </div>
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Pago</label>
                  <select className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" value={formData.tipos_pago} onChange={e => setFormData({ ...formData, tipos_pago: e.target.value })}>
                    {tiposPago.map(p => <option key={p.id} value={p.id}>{p.name}</option>)}
                  </select>
                </div>
                <div>
                  <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Entrega</label>
                  <select className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" value={formData.tipos_entrega} onChange={e => setFormData({ ...formData, tipos_entrega: e.target.value })}>
                    {tiposEntrega.map(p => <option key={p.id} value={p.id}>{p.name}</option>)}
                  </select>
                </div>
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Forma Pago</label>
                  <select className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" value={formData.forma_pago} onChange={e => setFormData({ ...formData, forma_pago: e.target.value })}>
                    {formasPago.map(p => <option key={p.id} value={p.id}>{p.name}</option>)}
                  </select>
                </div>
                <div>
                  <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">IGV Incluido</label>
                  <select className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" value={formData.incluye_igv} onChange={e => setFormData({ ...formData, incluye_igv: e.target.value })}>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                  </select>
                </div>
              </div>

              <div className="pt-2 border-t border-gray-100">
                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Descuento Global (S/)</label>
                <input type="number" step="0.01" className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" value={formData.discount} onChange={e => setFormData({ ...formData, discount: e.target.value })} />
              </div>

              <div className="pt-2 border-t border-gray-100">
                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Aplicar Detracción</label>
                <div className="flex gap-4">
                  <label className="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                    <input type="radio" name="detraccion" value="no" checked={formData.detraccion === 'no'} onChange={(e) => setFormData({ ...formData, detraccion: 'no' })} className="text-blue-600 focus:ring-blue-500" />
                    No
                  </label>
                  <label className="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                    <input type="radio" name="detraccion" value="yes" checked={formData.detraccion === 'yes'} onChange={(e) => setFormData({ ...formData, detraccion: 'yes' })} className="text-blue-600 focus:ring-blue-500" />
                    Sí
                  </label>
                </div>
                {formData.detraccion === 'yes' && (
                  <div className="mt-2">
                    <label className="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Monto Detracción (S/)</label>
                    <input type="number" step="0.01" className="w-full p-2 border border-blue-200 rounded-md bg-blue-50 text-blue-800 font-bold text-sm focus:border-blue-500 focus:ring-blue-500" value={formData.detraccion_p} onChange={e => setFormData({ ...formData, detraccion_p: e.target.value })} />
                  </div>
                )}
              </div>

              <div className="bg-gray-50 p-4 rounded-lg border border-gray-200 mt-2">
                <div className="flex justify-between mb-2">
                  <span className="text-gray-600 font-medium text-sm">Subtotal:</span>
                  <span className="font-medium text-gray-800 text-sm">S/ {totals.subtotal.toFixed(2)}</span>
                </div>
                <div className="flex justify-between mb-2">
                  <span className="text-gray-600 font-medium text-sm">IGV (18%):</span>
                  <span className="font-medium text-gray-800 text-sm">S/ {totals.igv.toFixed(2)}</span>
                </div>
                {formData.discount > 0 && (
                  <div className="flex justify-between mb-2 text-red-500">
                    <span className="font-medium text-sm">Descuento:</span>
                    <span className="font-bold text-sm">- S/ {Number(formData.discount).toFixed(2)}</span>
                  </div>
                )}
                {formData.detraccion === 'yes' && (
                  <div className="flex justify-between mb-2 text-orange-500">
                    <span className="font-medium text-sm">Detracción (10%):</span>
                    <span className="font-bold text-sm">S/ {Number(formData.detraccion_p).toFixed(2)}</span>
                  </div>
                )}
                <div className="flex justify-between mt-3 pt-3 border-t border-gray-300">
                  <span className="text-base font-bold text-gray-800">Total a Pagar:</span>
                  <span className="text-xl font-bold text-blue-600">S/ {totals.total.toFixed(2)}</span>
                </div>
              </div>

              <div className="flex gap-3 mt-4">
                <button
                  onClick={() => navigate('/sells')}
                  className="w-1/3 bg-white border border-gray-300 text-gray-700 font-medium py-2.5 rounded-md hover:bg-gray-50 transition-colors text-sm"
                >
                  Cancelar
                </button>
                <button
                  onClick={handleSubmit}
                  className="w-2/3 bg-blue-600 text-white font-medium py-2.5 rounded-md hover:bg-blue-700 shadow-sm transition-colors text-sm"
                >
                  Guardar Venta
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
