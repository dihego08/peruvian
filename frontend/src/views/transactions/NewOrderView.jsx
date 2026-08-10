import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../../services/api';
import { handleProductImageError } from '../../utils/image';

const SIZE_COLS = ['_2', '_4', '_6', '_8', '_10', '_12', '_14', '_16', 's', 'm', 'l', 'xl', 'xxl'];
const DEFAULT_HEADERS = ['2', '4', '6', '8', '10', '12', '14', '16', 'S', 'M', 'L', 'XL', 'XXL'];

// Fila en blanco para la tabla de captura de color/cantidades
const emptyRow = () => ({ color: '', sizes: Array(13).fill('') });

export default function NewOrderView() {
  const navigate = useNavigate();
  const [clients, setClients] = useState([]);
  const [searchQuery, setSearchQuery] = useState('');
  const [searchResults, setSearchResults] = useState([]);
  const [selectedModel, setSelectedModel] = useState(null);

  // Headers editables (N1-N13) - se comparten entre buscador y resumen
  const [headers, setHeaders] = useState([...DEFAULT_HEADERS]);

  // Fila de entrada actual (editable)
  const [inputRow, setInputRow] = useState(emptyRow());

  // Listado acumulado de filas del pedido
  const [rows, setRows] = useState([]);

  const [imageFile, setImageFile] = useState(null);
  const [imagePreview, setImagePreview] = useState(null);

  const [formData, setFormData] = useState({
    person_id: '',
    fecha_desde: new Date().toISOString().slice(0, 10),
    tiempo_entrega: 15,
    num_contrato: '',
    nombre_producto: '',
    comentario: '',
    imagen_alt: '',
  });

  const [saving, setSaving] = useState(false);

  useEffect(() => { fetchClients(); }, []);

  const fetchClients = async () => {
    try {
      const res = await api.get('/clients');
      setClients(res.data);
    } catch (e) { console.error(e); }
  };

  const handleSearch = async (e) => {
    e.preventDefault();
    if (!searchQuery) return;
    try {
      const res = await api.get(`/products-search?q=${searchQuery}`);
      setSearchResults(res.data);
    } catch (e) { console.error(e); }
  };

  const selectModel = (product) => {
    setSelectedModel(product);
    setFormData(f => ({ ...f, nombre_producto: product.name, imagen_alt: product.image || '' }));
    setImagePreview(product.image ? `https://omcar.peruviandress.com/storage/products/${product.image}` : null);
    setImageFile(null);
    setSearchResults([]);
    setSearchQuery('');
  };

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setImageFile(file);
      setImagePreview(URL.createObjectURL(file));
    }
  };

  const handleRemoveImage = () => {
    setImageFile(null);
    setImagePreview(null);
    setFormData(f => ({ ...f, imagen_alt: '' }));
  };

  const handleHeaderChange = (idx, val) => {
    const h = [...headers];
    h[idx] = val;
    setHeaders(h);
  };

  const handleSizeChange = (idx, val) => {
    const s = [...inputRow.sizes];
    s[idx] = val;
    setInputRow({ ...inputRow, sizes: s });
  };

  const rowTotal = (sizes) => sizes.reduce((acc, v) => acc + (parseInt(v) || 0), 0);

  const addRow = () => {
    if (!inputRow.color && rowTotal(inputRow.sizes) === 0) {
      alert('Ingresa el color y/o al menos una cantidad.');
      return;
    }
    setRows([...rows, { ...inputRow, headers: [...headers] }]);
    setInputRow(emptyRow());
  };

  const removeRow = (idx) => {
    setRows(rows.filter((_, i) => i !== idx));
  };

  const grandTotal = rows.reduce((acc, r) => acc + rowTotal(r.sizes), 0);

  const handleSubmit = async () => {
    if (!formData.person_id) { alert('Selecciona un cliente.'); return; }
    if (!selectedModel) { alert('Busca y selecciona un modelo.'); return; }
    if (rows.length === 0) { alert('Agrega al menos una fila de colores/tallas.'); return; }

    setSaving(true);
    try {
      let uploadedFilename = formData.imagen_alt;
      if (imageFile) {
        const uploadData = new FormData();
        uploadData.append('image', imageFile);
        const uploadRes = await api.post('/transactions/orders/upload', uploadData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
        if (uploadRes.data && uploadRes.data.filename) {
          uploadedFilename = uploadRes.data.filename;
        }
      }

      const payload = {
        ...formData,
        imagen_alt: uploadedFilename,
        rows: rows.map(r => ({
          modelo: selectedModel.code || selectedModel.name,
          color: r.color,
          sizes: r.sizes,
          headers: r.headers,
        })),
      };
      await api.post('/transactions/orders', payload);
      alert('Pedido registrado correctamente');
      navigate('/orders');
    } catch (e) {
      console.error(e);
      alert('Error al guardar el pedido.');
    } finally {
      setSaving(false);
    }
  };

  return (
    <div className="flex flex-col gap-6">
      {/* Page Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Nueva Orden de Pedido</h1>
          <p className="text-sm text-gray-500 mt-0.5">Ingresa el modelo, tallas y cantidades por color</p>
        </div>
        <button onClick={() => navigate('/orders')} className="text-sm text-gray-500 hover:text-gray-800 flex items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" /></svg>
          Volver
        </button>
      </div>

      {/* Info General */}
      <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 className="text-base font-bold text-gray-800 mb-4">Información General</h2>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div>
            <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Cliente *</label>
            <select className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" value={formData.person_id} onChange={e => setFormData({ ...formData, person_id: e.target.value })}>
              <option value="">Seleccione...</option>
              {clients.map(c => <option key={c.id} value={c.id}>{c.name} {c.lastname}</option>)}
            </select>
          </div>
          <div>
            <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nº Contrato</label>
            <input type="text" className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" placeholder="Número de contrato" value={formData.num_contrato} onChange={e => setFormData({ ...formData, num_contrato: e.target.value })} />
          </div>
          <div>
            <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Fecha de Inicio *</label>
            <input type="date" className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" value={formData.fecha_desde} onChange={e => setFormData({ ...formData, fecha_desde: e.target.value })} />
          </div>
          <div>
            <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tiempo de Entrega (días hábiles) *</label>
            <input type="number" min="1" className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" value={formData.tiempo_entrega} onChange={e => setFormData({ ...formData, tiempo_entrega: e.target.value })} />
          </div>
          <div className="md:col-span-2">
            <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Comentario</label>
            <input type="text" className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" placeholder="Observaciones..." value={formData.comentario} onChange={e => setFormData({ ...formData, comentario: e.target.value })} />
          </div>
        </div>
      </div>

      {/* Buscar Modelo */}
      <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 className="text-base font-bold text-gray-800 mb-4">Modelo a Producir</h2>
        <form onSubmit={handleSearch} className="flex gap-3 mb-4">
          <input type="text" className="flex-1 p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" placeholder="Buscar modelo por nombre o código..." value={searchQuery} onChange={e => setSearchQuery(e.target.value)} />
          <button type="submit" className="bg-gray-800 text-white px-5 py-2 rounded-md hover:bg-gray-700 text-sm font-medium transition-colors">Buscar</button>
        </form>

        {searchResults.length > 0 && (
          <div className="border border-gray-200 rounded-lg overflow-hidden mb-4">
            <table className="w-full text-sm">
              <thead className="bg-gray-50 text-gray-600 uppercase text-xs border-b">
                <tr>
                  <th className="px-4 py-2">Código</th>
                  <th className="px-4 py-2">Nombre</th>
                  <th className="px-4 py-2 text-center">Acción</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-gray-100">
                {searchResults.map(p => (
                  <tr key={p.id} className="hover:bg-blue-50 transition-colors">
                    <td className="px-4 py-2 font-mono">{p.code}</td>
                    <td className="px-4 py-2">{p.name}</td>
                    <td className="px-4 py-2 text-center">
                      <button onClick={() => selectModel(p)} className="bg-blue-600 text-white text-xs px-3 py-1 rounded-md hover:bg-blue-700 transition-colors font-medium">Seleccionar</button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}

        {selectedModel && (
          <div className="p-4 bg-gray-50 border border-gray-200 rounded-xl flex flex-col md:flex-row gap-4 items-center">
            <div className="relative w-32 h-32 bg-white rounded-lg border border-gray-200 overflow-hidden flex items-center justify-center group shadow-sm shrink-0">
              {imagePreview ? (
                <img
                  src={imagePreview}
                  alt="Modelo preview"
                  className="w-full h-full object-cover transition-transform duration-200 group-hover:scale-105"
                  onError={(e) => {
                    if (formData.imagen_alt) {
                      handleProductImageError(e, formData.imagen_alt);
                    } else {
                      e.target.style.display = 'none';
                    }
                  }}
                />
              ) : (
                <div className="text-gray-400 text-xs flex flex-col items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" className="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  Sin Imagen
                </div>
              )}
            </div>

            <div className="flex-1 flex flex-col gap-2 w-full text-left">
              <div>
                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                  Modelo Seleccionado
                </span>
                {imageFile && (
                  <span className="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 animate-pulse">
                    Nueva imagen cargada
                  </span>
                )}
                <h3 className="text-base font-semibold text-gray-900 mt-1">
                  {selectedModel.code} — {selectedModel.name}
                </h3>
                <p className="text-xs text-gray-500 mt-0.5">
                  La imagen asociada a esta orden se puede modificar de forma independiente del catálogo.
                </p>
              </div>

              <div className="flex flex-wrap gap-2 mt-2">
                <label className="cursor-pointer inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-sm transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" className="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                  </svg>
                  Seleccionar otra
                  <input
                    type="file"
                    accept="image/*"
                    className="hidden"
                    onChange={handleImageChange}
                  />
                </label>

                {imagePreview && (
                  <button
                    type="button"
                    onClick={handleRemoveImage}
                    className="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold rounded-md transition-colors"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" className="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Quitar
                  </button>
                )}

                <button
                  type="button"
                  onClick={() => {
                    setSelectedModel(null);
                    setImagePreview(null);
                    setImageFile(null);
                  }}
                  className="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold rounded-md transition-colors"
                >
                  Cambiar Modelo
                </button>
              </div>
            </div>
          </div>
        )}
      </div>

      {/* Matriz dinámica de tallas */}
      <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 className="text-base font-bold text-gray-800 mb-1">Matriz de Tallas</h2>
        <p className="text-xs text-gray-500 mb-4">Edita los encabezados (azul) según tus tallas. Luego ingresa el color y las cantidades y presiona <strong>Agregar</strong>.</p>

        <div className="overflow-x-auto border border-gray-200 rounded-lg">
          <table className="w-full text-sm whitespace-nowrap">
            <thead>
              {/* Fila de cabeceras editables (N1-N13) */}
              <tr className="bg-sky-50 border-b border-sky-200">
                <th className="px-3 py-2 text-left text-xs font-bold text-gray-600 uppercase w-36">Color</th>
                {headers.map((h, i) => (
                  <th key={i} className="px-1 py-2 min-w-[56px]">
                    <input
                      type="text"
                      className="w-full text-center p-1 border border-sky-300 rounded bg-sky-100 text-xs font-bold text-sky-800 focus:outline-none focus:border-blue-500"
                      value={h}
                      onChange={e => handleHeaderChange(i, e.target.value)}
                    />
                  </th>
                ))}
                <th className="px-3 py-2 text-center text-xs font-bold text-gray-600 uppercase w-16">Total</th>
                <th className="w-10"></th>
              </tr>
              {/* Fila de entrada actual */}
              <tr className="bg-gray-50 border-b border-gray-200">
                <td className="px-2 py-2">
                  <input
                    type="text"
                    className="w-full p-1.5 border border-gray-300 rounded-md text-sm focus:border-blue-500"
                    placeholder="Color..."
                    value={inputRow.color}
                    onChange={e => setInputRow({ ...inputRow, color: e.target.value })}
                  />
                </td>
                {inputRow.sizes.map((s, i) => (
                  <td key={i} className="px-1 py-2">
                    <input
                      type="number"
                      min="0"
                      className="w-full p-1.5 border border-gray-300 rounded-md text-center text-sm focus:border-blue-500"
                      value={s}
                      onChange={e => handleSizeChange(i, e.target.value)}
                    />
                  </td>
                ))}
                <td className="px-3 py-2 text-center font-bold text-gray-800">
                  {rowTotal(inputRow.sizes)}
                </td>
                <td className="px-2 py-2 text-center">
                  <button
                    type="button"
                    onClick={addRow}
                    className="bg-green-600 text-white rounded-md p-1.5 hover:bg-green-700 transition-colors shadow-sm"
                    title="Agregar fila"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M12 4v16m8-8H4" /></svg>
                  </button>
                </td>
              </tr>
            </thead>
            {/* Filas confirmadas */}
            <tbody className="divide-y divide-gray-100">
              {rows.length === 0 && (
                <tr>
                  <td colSpan={16} className="px-4 py-6 text-center text-gray-400 text-sm">
                    Aún no hay filas. Completa el formulario de arriba y presiona el botón verde +
                  </td>
                </tr>
              )}
              {rows.map((row, ri) => (
                <tr key={ri} className="hover:bg-gray-50 transition-colors">
                  <td className="px-3 py-2 font-medium text-gray-800">{row.color || <span className="text-gray-400 italic">Sin color</span>}</td>
                  {row.sizes.map((s, si) => (
                    <td key={si} className="px-3 py-2 text-center text-gray-700">{s || '-'}</td>
                  ))}
                  <td className="px-3 py-2 text-center font-bold text-gray-800">{rowTotal(row.sizes)}</td>
                  <td className="px-2 py-2 text-center">
                    <button onClick={() => removeRow(ri)} className="text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md p-1.5 transition-colors" title="Eliminar fila">
                      <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
            {rows.length > 0 && (
              <tfoot>
                <tr className="bg-gray-100 font-bold">
                  <td className="px-3 py-2 text-right text-gray-700 uppercase text-xs tracking-wider" colSpan={14}>Total General:</td>
                  <td className="px-3 py-2 text-center text-gray-900 text-base">{grandTotal}</td>
                  <td></td>
                </tr>
              </tfoot>
            )}
          </table>
        </div>
      </div>

      {/* Actions */}
      <div className="flex gap-3 justify-end">
        <button onClick={() => navigate('/orders')} className="bg-white border border-gray-300 text-gray-700 font-medium px-6 py-2.5 rounded-md hover:bg-gray-50 transition-colors text-sm">
          Cancelar
        </button>
        <button
          onClick={handleSubmit}
          disabled={saving}
          className="bg-blue-600 text-white font-medium px-8 py-2.5 rounded-md hover:bg-blue-700 shadow-sm transition-colors text-sm disabled:opacity-60"
        >
          {saving ? 'Guardando...' : 'Guardar Pedido'}
        </button>
      </div>
    </div>
  );
}
