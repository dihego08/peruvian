import { useState, useEffect } from 'react';
import api from '../../services/api';

const BANCOS = ['BCP', 'SCOTIABANK', 'BBVA CONTINENTAL', 'INTERBANK'];

export default function SellPaymentsView() {
  const [sells, setSells]         = useState([]);
  const [loading, setLoading]     = useState(false);
  const [totales, setTotales]     = useState({ total_general: 0, total_adeuda: 0 });
  const [clients, setClients]     = useState([]);
  const [tiposDoc, setTiposDoc]   = useState([]);
  const [tiposPago, setTiposPago] = useState([]);

  // Filtros
  const [filters, setFilters] = useState({
    desde: '', hasta: '', tipos_pago: '', tipos_documento: '0', combo_cliente: '0'
  });

  // Modal de detalle + pago
  const [modal, setModal]           = useState(null);   // cabecera seleccionada
  const [historial, setHistorial]   = useState([]);
  const [payForm, setPayForm]       = useState({ monto_pagado: '', fecha: new Date().toISOString().slice(0,10), banco: '', concepto: '' });
  const [adeudaLocal, setAdeudaLocal] = useState(0);
  const [saving, setSaving]         = useState(false);

  // Estado para detracción
  const [detraccionForm, setDetraccionForm] = useState({});

  useEffect(() => {
    fetchClients();
    fetchTiposDoc();
    fetchTiposPago();
    fetchSells({});
  }, []);

  const fetchClients = async () => {
    try { const r = await api.get('/clients'); setClients(r.data); } catch (e) {}
  };
  const fetchTiposDoc = async () => {
    try { const r = await api.get('/sell-payments/tipos-documento'); setTiposDoc(r.data.Records || []); } catch (e) {}
  };
  const fetchTiposPago = async () => {
    try { const r = await api.get('/tipos-pago'); setTiposPago(r.data); } catch (e) {}
  };

  const fetchSells = async (params) => {
    setLoading(true);
    try {
      const res = await api.get('/sell-payments', { params });
      setSells(res.data.Records || []);
      setTotales(res.data.totales || { total_general: 0, total_adeuda: 0 });
    } catch (e) { console.error(e); }
    finally { setLoading(false); }
  };

  const handleFilter = () => {
    const p = {};
    if (filters.desde) p.desde = filters.desde;
    if (filters.hasta) p.hasta = filters.hasta;
    if (filters.tipos_pago) p.tipos_pago = filters.tipos_pago;
    if (filters.tipos_documento !== '0') p.tipos_documento = filters.tipos_documento;
    if (filters.combo_cliente !== '0') p.combo_cliente = filters.combo_cliente;
    fetchSells(p);
  };

  const openModal = async (sell) => {
    setModal(sell);
    setAdeudaLocal(parseFloat(sell.a_cuenta || 0));
    setPayForm({ monto_pagado: '', fecha: new Date().toISOString().slice(0,10), banco: '', concepto: '' });
    try {
      const r = await api.get(`/sell-payments/${sell.codigo_venta}/history`);
      setHistorial(r.data.Records || []);
    } catch (e) { setHistorial([]); }
  };

  const closeModal = () => { setModal(null); setHistorial([]); };

  const handlePayChange = (field, val) => {
    const updated = { ...payForm, [field]: val };
    setPayForm(updated);
    if (field === 'monto_pagado') {
      const base = parseFloat(modal?.a_cuenta || 0);
      const pago = parseFloat(val) || 0;
      setAdeudaLocal(Math.max(0, base - pago));
    }
  };

  const handlePay = async () => {
    if (!payForm.monto_pagado || parseFloat(payForm.monto_pagado) <= 0) {
      alert('Ingresa un monto válido'); return;
    }
    setSaving(true);
    try {
      await api.post(`/sell-payments/${modal.codigo_venta}/pay`, payForm);
      // Refrescar historial y lista
      const r = await api.get(`/sell-payments/${modal.codigo_venta}/history`);
      setHistorial(r.data.Records || []);
      // Actualizar fila en la tabla
      setSells(prev => prev.map(s =>
        s.codigo_venta === modal.codigo_venta
          ? { ...s, a_cuenta: adeudaLocal, pagado: parseFloat(s.pagado || 0) + parseFloat(payForm.monto_pagado) }
          : s
      ));
      setModal(m => ({ ...m, a_cuenta: adeudaLocal }));
      setPayForm(f => ({ ...f, monto_pagado: '' }));
    } catch (e) { alert('Error al registrar pago'); }
    finally { setSaving(false); }
  };

  const handleDeletePayment = async (id) => {
    if (!window.confirm('¿Eliminar este pago del historial?')) return;
    try {
      await api.delete(`/sell-payments/payment/${id}`);
      setHistorial(prev => prev.filter(p => p.id !== id));
      fetchSells({});
    } catch (e) { alert('Error al eliminar pago'); }
  };

  const handleDetraccionChange = (codigo, field, value) => {
    setDetraccionForm(prev => ({
      ...prev,
      [codigo]: {
        ...prev[codigo],
        [field]: value
      }
    }));
  };

  const handleSaveDetraccion = async (codigo) => {
    const form = detraccionForm[codigo];
    if (!form || form.status !== '1' || !form.date) {
      alert('Debes seleccionar PAGADO y establecer una fecha para guardar la detracción.');
      return;
    }

    try {
      await api.post(`/sell-payments/${codigo}/pay-detraccion`, {
        paga: form.status,
        fecha_pago: form.date
      });
      alert('Detracción guardada correctamente');
      
      const p = {};
      if (filters.desde) p.desde = filters.desde;
      if (filters.hasta) p.hasta = filters.hasta;
      if (filters.tipos_pago) p.tipos_pago = filters.tipos_pago;
      if (filters.tipos_documento !== '0') p.tipos_documento = filters.tipos_documento;
      if (filters.combo_cliente !== '0') p.combo_cliente = filters.combo_cliente;
      fetchSells(p);
    } catch (e) {
      alert('Error al guardar la detracción');
    }
  };

  return (
    <div className="flex flex-col gap-6">
      {/* Header */}
      <div>
        <h1 className="text-2xl font-bold text-gray-900">Ventas Pagos</h1>
        <p className="text-sm text-gray-500 mt-0.5">Control de pagos y cuentas por cobrar</p>
      </div>

      {/* Filtros */}
      <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <h2 className="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4">Filtros</h2>
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
          <div>
            <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Desde</label>
            <input type="date" className="w-full p-2 border border-gray-300 rounded-md text-sm focus:border-blue-500" value={filters.desde} onChange={e => setFilters({...filters, desde: e.target.value})} />
          </div>
          <div>
            <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Hasta</label>
            <input type="date" className="w-full p-2 border border-gray-300 rounded-md text-sm focus:border-blue-500" value={filters.hasta} onChange={e => setFilters({...filters, hasta: e.target.value})} />
          </div>
          <div>
            <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Por Pago</label>
            <select className="w-full p-2 border border-gray-300 rounded-md text-sm focus:border-blue-500" value={filters.tipos_pago} onChange={e => setFilters({...filters, tipos_pago: e.target.value})}>
              <option value="">Todos</option>
              {tiposPago.map(t => <option key={t.id} value={t.id}>{t.name}</option>)}
              <option value="-1">Pendiente de Pago</option>
            </select>
          </div>
          <div>
            <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Documento</label>
            <select className="w-full p-2 border border-gray-300 rounded-md text-sm focus:border-blue-500" value={filters.tipos_documento} onChange={e => setFilters({...filters, tipos_documento: e.target.value})}>
              <option value="0">Todos</option>
              {tiposDoc.map(t => <option key={t.id} value={t.id}>{t.tipo_documento}</option>)}
            </select>
          </div>
          <div>
            <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Cliente</label>
            <select className="w-full p-2 border border-gray-300 rounded-md text-sm focus:border-blue-500" value={filters.combo_cliente} onChange={e => setFilters({...filters, combo_cliente: e.target.value})}>
              <option value="0">Todos</option>
              {clients.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
            </select>
          </div>
        </div>
        <div className="mt-4 flex justify-end">
          <button onClick={handleFilter} className="bg-gray-800 text-white px-6 py-2 rounded-md hover:bg-gray-700 text-sm font-medium transition-colors">
            Filtrar
          </button>
        </div>
      </div>

      {/* Resumen de Totales */}
      <div className="grid grid-cols-2 gap-4">
        <div className="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
          <p className="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Facturado</p>
          <p className="text-2xl font-bold text-gray-800 mt-1">S/ {parseFloat(totales.total_general || 0).toFixed(2)}</p>
        </div>
        <div className="bg-white rounded-xl border border-red-200 shadow-sm p-4">
          <p className="text-xs font-bold text-red-500 uppercase tracking-wider">Total por Cobrar</p>
          <p className="text-2xl font-bold text-red-600 mt-1">S/ {parseFloat(totales.total_adeuda || 0).toFixed(2)}</p>
        </div>
      </div>

      {/* Tabla */}
      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-sm">
            <thead className="bg-gray-50 text-gray-600 uppercase text-xs border-b border-gray-200">
              <tr>
                <th className="px-4 py-3">#</th>
                <th className="px-4 py-3">Comprobante</th>
                <th className="px-4 py-3">Fecha</th>
                <th className="px-4 py-3">Tipo</th>
                <th className="px-4 py-3">Pago</th>
                <th className="px-4 py-3">Entrega</th>
                <th className="px-4 py-3 text-right">Total</th>
                <th className="px-4 py-3">Cliente</th>
                <th className="px-4 py-3 text-right">Detracción</th>
                <th className="px-4 py-3 text-right">Adeuda</th>
                <th className="px-4 py-3 text-center">Acción</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-100">
              {loading && <tr><td colSpan="11" className="px-4 py-8 text-center text-gray-400">Cargando...</td></tr>}
              {!loading && sells.length === 0 && <tr><td colSpan="11" className="px-4 py-8 text-center text-gray-400">Aplica filtros para ver las ventas.</td></tr>}
              {sells.map((s, idx) => {
                const tieneDeuda = parseFloat(s.a_cuenta) > 0;
                return (
                  <tr key={s.codigo_venta} className={`transition-colors ${tieneDeuda ? 'bg-red-50 hover:bg-red-100' : 'hover:bg-gray-50'}`}>
                    <td className="px-4 py-3 text-gray-500">{idx + 1}</td>
                    <td className="px-4 py-3 font-mono font-bold text-gray-800">{s.codigo_venta}</td>
                    <td className="px-4 py-3 text-gray-600">{s.fecha_creacion}</td>
                    <td className="px-4 py-3">
                      <span className="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">{s.tipo_documento_nombre || `T${s.tipo_documento}`}</span>
                    </td>
                    <td className="px-4 py-3 text-gray-600 text-xs">{s.pago || '-'}</td>
                    <td className="px-4 py-3 text-gray-600 text-xs">{s.entrega || '-'}</td>
                    <td className="px-4 py-3 text-right font-semibold text-gray-800">S/ {parseFloat(s.valor_pagar || 0).toFixed(2)}</td>
                    <td className="px-4 py-3 text-gray-700 max-w-[160px] truncate">{s.person || '-'}</td>
                    <td className="px-4 py-3 text-right text-gray-600">
                      {s.detraccion_p > 0 ? (
                        <div className="flex flex-col items-end gap-1">
                          <span className="font-semibold text-gray-800">S/ {s.detraccion_p}</span>
                          {s.detraccion_paga == 0 ? (
                            <div className="flex flex-col gap-1 w-28 text-left mt-1">
                              <select 
                                className="w-full p-1 border border-gray-300 rounded text-xs focus:border-blue-500"
                                value={detraccionForm[s.codigo_venta]?.status || '0'}
                                onChange={e => handleDetraccionChange(s.codigo_venta, 'status', e.target.value)}
                              >
                                <option value="0">PENDIENTE</option>
                                <option value="1">PAGADO</option>
                              </select>
                              {detraccionForm[s.codigo_venta]?.status === '1' && (
                                <input 
                                  type="date" 
                                  className="w-full p-1 border border-gray-300 rounded text-xs focus:border-blue-500"
                                  value={detraccionForm[s.codigo_venta]?.date || ''}
                                  onChange={e => handleDetraccionChange(s.codigo_venta, 'date', e.target.value)}
                                />
                              )}
                              <button 
                                className="w-full bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold hover:bg-green-200 transition-colors"
                                onClick={() => handleSaveDetraccion(s.codigo_venta)}
                              >
                                Guardar
                              </button>
                            </div>
                          ) : (
                            <span className="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded font-bold">PAGADO</span>
                          )}
                        </div>
                      ) : '-'}
                    </td>
                    <td className={`px-4 py-3 text-right font-bold ${tieneDeuda ? 'text-red-600' : 'text-green-600'}`}>
                      S/ {parseFloat(s.a_cuenta || 0).toFixed(2)}
                    </td>
                    <td className="px-4 py-3 text-center">
                      <button onClick={() => openModal(s)} title="Ver / Registrar Pago" className="text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-md p-1.5 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                      </button>
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
      </div>

      {/* ===== MODAL DE DETALLE + PAGO ===== */}
      {modal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div className="absolute inset-0 bg-black/50 backdrop-blur-sm" onClick={closeModal}></div>
          <div className="relative bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col overflow-hidden">
            {/* Modal Header */}
            <div className="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
              <div>
                <h2 className="text-lg font-bold text-gray-900">Detalle de Venta — <span className="font-mono text-blue-600">{modal.codigo_venta}</span></h2>
                <p className="text-xs text-gray-500">{modal.person} · {modal.fecha_creacion}</p>
              </div>
              <button onClick={closeModal} className="text-gray-400 hover:text-gray-700 p-1.5 rounded-lg hover:bg-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" /></svg>
              </button>
            </div>

            <div className="overflow-y-auto flex-1 p-6 space-y-6">
              {/* Resumen de montos */}
              <div className="grid grid-cols-3 gap-4 text-sm">
                <div className="bg-gray-50 rounded-lg p-3 border border-gray-200">
                  <p className="text-xs text-gray-500 font-bold uppercase">Total</p>
                  <p className="font-bold text-gray-800 text-base mt-0.5">S/ {parseFloat(modal.valor_pagar || 0).toFixed(2)}</p>
                </div>
                <div className="bg-green-50 rounded-lg p-3 border border-green-200">
                  <p className="text-xs text-green-600 font-bold uppercase">Pagado</p>
                  <p className="font-bold text-green-700 text-base mt-0.5">S/ {parseFloat(modal.pagado || 0).toFixed(2)}</p>
                </div>
                <div className={`rounded-lg p-3 border ${parseFloat(modal.a_cuenta) > 0 ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200'}`}>
                  <p className={`text-xs font-bold uppercase ${parseFloat(modal.a_cuenta) > 0 ? 'text-red-500' : 'text-gray-500'}`}>Adeuda</p>
                  <p className={`font-bold text-base mt-0.5 ${parseFloat(modal.a_cuenta) > 0 ? 'text-red-600' : 'text-gray-800'}`}>S/ {parseFloat(modal.a_cuenta || 0).toFixed(2)}</p>
                </div>
              </div>

              {/* Formulario de pago */}
              {parseFloat(modal.a_cuenta) > 0 && (
                <div className="bg-blue-50 border border-blue-200 rounded-xl p-4">
                  <h3 className="text-sm font-bold text-blue-800 mb-3">Registrar Pago</h3>
                  <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div>
                      <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Fecha</label>
                      <input type="date" className="w-full p-2 border border-gray-300 rounded-md text-sm focus:border-blue-500" value={payForm.fecha} onChange={e => handlePayChange('fecha', e.target.value)} />
                    </div>
                    <div>
                      <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Monto (S/)</label>
                      <input type="number" step="0.01" min="0.01" className="w-full p-2 border border-gray-300 rounded-md text-sm focus:border-blue-500" placeholder="0.00" value={payForm.monto_pagado} onChange={e => handlePayChange('monto_pagado', e.target.value)} />
                    </div>
                    <div>
                      <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Banco</label>
                      <select className="w-full p-2 border border-gray-300 rounded-md text-sm focus:border-blue-500" value={payForm.banco} onChange={e => handlePayChange('banco', e.target.value)}>
                        <option value="">--Selecciona--</option>
                        {BANCOS.map(b => <option key={b} value={b}>{b}</option>)}
                      </select>
                    </div>
                    <div>
                      <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Concepto</label>
                      <input type="text" className="w-full p-2 border border-gray-300 rounded-md text-sm focus:border-blue-500" value={payForm.concepto} onChange={e => handlePayChange('concepto', e.target.value)} />
                    </div>
                  </div>
                  <div className="mt-3 flex items-center justify-between">
                    <p className="text-sm text-gray-600">Nueva deuda: <span className="font-bold text-red-600">S/ {adeudaLocal.toFixed(2)}</span></p>
                    <button onClick={handlePay} disabled={saving} className="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 font-medium text-sm transition-colors disabled:opacity-60">
                      {saving ? 'Guardando...' : 'Actualizar Pago'}
                    </button>
                  </div>
                </div>
              )}

              {/* Historial de pagos */}
              <div>
                <h3 className="text-sm font-bold text-gray-700 uppercase tracking-wider mb-3">Historial de Pagos</h3>
                <table className="w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                  <thead className="bg-gray-50 text-gray-600 uppercase text-xs border-b">
                    <tr>
                      <th className="px-3 py-2 text-left">Fecha</th>
                      <th className="px-3 py-2 text-right">Monto</th>
                      <th className="px-3 py-2 text-left">Concepto</th>
                      <th className="px-3 py-2 text-left">Banco</th>
                      <th className="px-3 py-2 text-right">Deuda Resultante</th>
                      <th className="px-3 py-2 text-center">Quitar</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-gray-100">
                    {historial.length === 0 && (
                      <tr><td colSpan="6" className="px-3 py-4 text-center text-gray-400 text-xs">Sin pagos registrados</td></tr>
                    )}
                    {historial.map(p => (
                      <tr key={p.id} className="hover:bg-gray-50">
                        <td className="px-3 py-2 text-gray-700">{p.fecha_creacion}</td>
                        <td className="px-3 py-2 text-right font-semibold text-green-700">S/ {parseFloat(p.pago || 0).toFixed(2)}</td>
                        <td className="px-3 py-2 text-gray-600">{p.concepto || '-'}</td>
                        <td className="px-3 py-2 text-gray-600">{p.banco || '-'}</td>
                        <td className="px-3 py-2 text-right text-red-600 font-medium">S/ {parseFloat(p.deuda || 0).toFixed(2)}</td>
                        <td className="px-3 py-2 text-center">
                          <button onClick={() => handleDeletePayment(p.id)} title="Eliminar" className="text-gray-400 hover:text-red-600 hover:bg-red-50 rounded p-1 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                          </button>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
