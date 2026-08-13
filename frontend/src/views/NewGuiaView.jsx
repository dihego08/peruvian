import { useState, useEffect, useCallback, useRef } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../services/api';
import { PlusIcon, TrashIcon, MagnifyingGlassIcon, ArrowLeftIcon, CheckIcon, XCircleIcon, PencilIcon } from '@heroicons/react/24/outline';

// ─── moved OUTSIDE to prevent remount on every render ────────────────────────
function Field({ label, children }) {
  return (
    <div className="space-y-1">
      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">{label}</label>
      {children}
    </div>
  );
}

function UbigeoSelects({ prefix, value, onChange }) {
  const [departamentos, setDepartamentos] = useState([]);
  const [provincias, setProvincias] = useState([]);
  const [distritos, setDistritos] = useState([]);

  useEffect(() => {
    api.get('/guias/departamentos').then(r => setDepartamentos(r.data)).catch(() => { });
  }, []);

  const handleDpto = async (e) => {
    const v = e.target.value;
    onChange({ departamento: v, provincia: '', distrito: '' });
    setProvincias([]);
    setDistritos([]);
    if (v) {
      const r = await api.get('/guias/provincias', { params: { departamento: v } });
      setProvincias(r.data);
    }
  };

  const handleProv = async (e) => {
    const v = e.target.value;
    onChange({ ...value, provincia: v, distrito: '' });
    setDistritos([]);
    if (v) {
      const r = await api.get('/guias/distritos', { params: { provincia: v } });
      setDistritos(r.data);
    }
  };

  const sel = 'w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none bg-white';
  return (
    <div className="grid grid-cols-3 gap-3">
      <div className="space-y-1">
        <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Departamento</label>
        <select className={sel} value={value?.departamento || ''} onChange={handleDpto}>
          <option value="">-- Departamento --</option>
          {departamentos.map(d => <option key={d.codigo} value={d.codigo}>{d.departamento}</option>)}
        </select>
      </div>
      <div className="space-y-1">
        <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Provincia</label>
        <select className={sel} value={value?.provincia || ''} onChange={handleProv}>
          <option value="">-- Provincia --</option>
          {provincias.map(p => <option key={p.codigo} value={p.codigo}>{p.provincia}</option>)}
        </select>
      </div>
      <div className="space-y-1">
        <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Distrito</label>
        <select className={sel} value={value?.distrito || ''} onChange={e => onChange({ ...value, distrito: e.target.value })}>
          <option value="">-- Distrito --</option>
          {distritos.map(d => <option key={d.codigo} value={d.codigo}>{d.distrito}</option>)}
        </select>
      </div>
    </div>
  );
}

function RucSearch({ label, value, onChange, onFound }) {
  const [searching, setSearching] = useState(false);
  const [result, setResult] = useState(null);
  const [error, setError] = useState('');

  const doSearch = async () => {
    if (!value || value.length < 8) return;
    setSearching(true);
    setResult(null);
    setError('');
    try {
      const param = value.length > 8 ? 'ruc' : 'dni';
      const r = await fetch(`https://dbusinessaqp.com/api_ruc/api.php?${param}=${value}`);
      const obj = await r.json();
      if (obj.error) {
        setError(obj.error);
      } else {
        setResult(obj);
        if (onFound) onFound(obj);
      }
    } catch {
      setError('Error al consultar');
    } finally {
      setSearching(false);
    }
  };

  const sel = 'flex-1 p-2.5 border border-gray-300 rounded-l-lg text-sm focus:border-blue-500 outline-none bg-white';
  return (
    <div className="space-y-1">
      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">{label}</label>
      <div className="flex">
        <input
          className={sel}
          value={value}
          placeholder="RUC (11 dígitos) o DNI (8 dígitos)"
          onChange={e => onChange(e.target.value)}
          onKeyDown={e => e.key === 'Enter' && (e.preventDefault(), doSearch())}
        />
        <button
          type="button"
          onClick={doSearch}
          disabled={searching}
          className="px-3 py-2 bg-gray-800 text-white rounded-r-lg hover:bg-gray-700 transition-colors border border-l-0 border-gray-800 disabled:opacity-60"
        >
          <MagnifyingGlassIcon className="h-4 w-4" />
        </button>
      </div>
      {searching && <p className="text-xs text-amber-600 font-bold">Buscando...</p>}
      {result && <p className="text-xs text-green-700 font-bold bg-green-50 px-2 py-1 rounded">{value} — {result.nombre}</p>}
      {error && <p className="text-xs text-red-600 font-bold bg-red-50 px-2 py-1 rounded">{value} — {error}</p>}
    </div>
  );
}
// ─────────────────────────────────────────────────────────────────────────────

const today = new Date().toISOString().split('T')[0];

const MOTIVOS = [
  { value: '01', label: 'VTA | Venta' },
  { value: '02', label: 'CMP | Compra' },
  { value: '03', label: 'VET | Venta con entrega a terceros' },
  { value: '04', label: 'TEE | Traslado entre establecimientos' },
  { value: '05', label: 'CON | Consignación' },
  { value: '06', label: 'DEV | Devolución' },
  { value: '09', label: 'EXP | Exportación' },
  { value: '13', label: 'OTR | Otros' },
  { value: '17', label: 'TPT | Traslado para transformación' },
];

const TALLAS = ['2', '4', '6', '8', '10', '12', '14', 'XS', 'S', 'M', 'L', 'XL', 'XXL'];

const INIT = {
  num_guia: '', fecha_emision: today, fecha_traslado: today,
  motivo_traslado: '01', descripcion_motivo: '',
  origen: 'CAL.BELEN NRO. 319 URB. JERUSALEN AREQUIPA - AREQUIPA - MARIANO MELGAR',
  ruc_destinatario: '', destino: '',
  ubigeo_origen: { departamento: '', provincia: '', distrito: '' },
  ubigeo_destino: { departamento: '', provincia: '', distrito: '' },
  ruc_transportista: '', ruc_conductor: '',
  placa: '', licencia: '',
  modalidad_trasnporte: '01', comentario: '',
};

export default function NewGuiaView() {
  const navigate = useNavigate();
  const [head, setHead] = useState(INIT);
  const [items, setItems] = useState([]);
  const [saving, setSaving] = useState(false);

  const [searchNombre, setSearchNombre] = useState('');
  const [searchCodigo, setSearchCodigo] = useState('');
  const [searchResults, setSearchResults] = useState([]);
  const [searching, setSearching] = useState(false);
  const [selProd, setSelProd] = useState(null);
  const [unidadesSunat, setUnidadesSunat] = useState([]);
  const [tallas, setTallas] = useState(Array(13).fill(''));
  const [pesoBruto, setPesoBruto] = useState('');
  const [pesoNeto, setPesoNeto] = useState('');
  const [pedido, setPedido] = useState('');
  const [editDescripcion, setEditDescripcion] = useState('');
  const [editUnidad, setEditUnidad] = useState('');
  const [editingItemIndex, setEditingItemIndex] = useState(null);
  const [editingRow, setEditingRow] = useState(null);

  useEffect(() => {
    api.get('/codigos-sunat').then(r => setUnidadesSunat(r.data)).catch(() => { });
    api.get('/guias/next-num').then(r => setHead(h => ({ ...h, num_guia: r.data.num_guia }))).catch(() => { });
  }, []);

  const set = useCallback((field, value) => setHead(h => ({ ...h, [field]: value })), []);

  const handleSearch = async (e) => {
    if (e) e.preventDefault();
    if (!searchNombre && !searchCodigo) return;
    setSearching(true);
    try {
      const r = await api.get('/guias/search-products', { params: { nombre: searchNombre, codigo: searchCodigo } });
      setSearchResults(r.data);
      setSelProd(null);
    } catch { }
    finally { setSearching(false); }
  };

  const selectProd = (p) => {
    setSelProd(p);
    setTallas(Array(13).fill(''));
    setPesoBruto('');
    setPesoNeto(p.weight);
    setPedido('');
    setEditDescripcion(p.name);
    setEditUnidad(p.unit || '');
  };

  const addItem = () => {
    if (!selProd) return;
    const cant = tallas.reduce((s, v) => s + (parseFloat(v) || 0), 0) || 1;
    const neto = (cant * (parseFloat(pesoNeto) || 0)).toFixed(2);
    const bruto = parseFloat(pesoBruto || 0).toFixed(2);
    const tallaStr = TALLAS.map((t, i) => tallas[i] ? `${t}:${tallas[i]}` : null).filter(Boolean).join(', ');
    setItems(p => [...p, {
      id_producto: selProd.id,
      descripcion_producto: editDescripcion + (tallaStr ? ` [${tallaStr}]` : ''),
      cantidad: cant,
      unidad: editUnidad,
      pedido,
      t_neto: neto,
      t_bruto: bruto,
      code: selProd.code,
    }]);
    setSelProd(null);
    setSearchResults([]);
    setSearchNombre('');
    setSearchCodigo('');
  };

  const startEditItem = (item, index) => {
    setEditingItemIndex(index);
    setEditingRow({ ...item });
  };

  const cancelEditItem = () => {
    setEditingItemIndex(null);
    setEditingRow(null);
  };

  const saveEditedItem = () => {
    if (editingItemIndex === null || !editingRow) return;
    setItems(prev => prev.map((item, idx) => idx === editingItemIndex ? editingRow : item));
    cancelEditItem();
  };

  const updateEditingRow = (field, value) => {
    setEditingRow(prev => ({ ...prev, [field]: value }));
  };

  const totalBruto = items.reduce((s, i) => s + (parseFloat(i.t_bruto) || 0), 0).toFixed(2);
  const totalNeto = items.reduce((s, i) => s + (parseFloat(i.t_neto) || 0), 0).toFixed(2);

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!head.ruc_destinatario) return alert('Ingrese el RUC/DNI del destinatario');
    if (items.length === 0) return alert('Agregue al menos un producto');
    setSaving(true);
    try {
      const payload = {
        ...head,
        ubigeo: head.ubigeo_origen.distrito,
        ubigeo_destino: head.ubigeo_destino.distrito,
        total_bruto: totalBruto,
        total_neto: totalNeto,
        items,
      };
      const r = await api.post('/guias', payload);
      if (r.data.Result === 'OK') navigate('/guias');
      else alert(r.data.Message || 'Error al guardar');
    } catch (err) {
      alert(err.response?.data?.Message || 'Error al guardar');
    } finally { setSaving(false); }
  };

  const inp = 'w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none bg-white';

  return (
    <div className="flex flex-col gap-6 animate-in fade-in duration-500 max-w-5xl mx-auto">
      <div className="flex items-center gap-4">
        <button type="button" onClick={() => navigate('/guias')} className="p-2 text-gray-500 hover:bg-gray-100 rounded-lg transition-colors">
          <ArrowLeftIcon className="h-5 w-5" />
        </button>
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Nueva Guía de Remisión</h1>
          <p className="text-sm text-gray-500 mt-0.5">Complete los datos y agregue los productos</p>
        </div>
      </div>

      <form onSubmit={handleSubmit} className="flex flex-col gap-6">
        {/* Datos Generales */}
        <section className="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
          <h2 className="text-xs font-black text-gray-500 uppercase tracking-widest mb-4 pb-2 border-b border-gray-100">Datos Generales</h2>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
            <Field label="Núm. Guía *">
              <input required className={inp} value={head.num_guia} onChange={e => set('num_guia', e.target.value)} />
            </Field>
            <Field label="Fecha Emisión *">
              <input required type="date" className={inp} value={head.fecha_emision} onChange={e => set('fecha_emision', e.target.value)} />
            </Field>
            <Field label="Fecha Traslado *">
              <input required type="date" className={inp} value={head.fecha_traslado} onChange={e => set('fecha_traslado', e.target.value)} />
            </Field>
            <Field label="Modalidad">
              <select className={inp} value={head.modalidad_trasnporte} onChange={e => set('modalidad_trasnporte', e.target.value)}>
                <option value="01">Público</option>
                <option value="02">Privado</option>
              </select>
            </Field>
            <div className="col-span-2">
              <Field label="Motivo Traslado *">
                <select className={inp} value={head.motivo_traslado} onChange={e => set('motivo_traslado', e.target.value)}>
                  {MOTIVOS.map(m => <option key={m.value} value={m.value}>{m.label}</option>)}
                </select>
              </Field>
            </div>
            {head.motivo_traslado === '13' && (
              <div className="col-span-2">
                <Field label="Descripción Motivo">
                  <input className={inp} value={head.descripcion_motivo} onChange={e => set('descripcion_motivo', e.target.value)} />
                </Field>
              </div>
            )}
          </div>
        </section>

        {/* Origen */}
        <section className="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
          <h2 className="text-xs font-black text-gray-500 uppercase tracking-widest mb-4 pb-2 border-b border-gray-100">Origen</h2>
          <div className="space-y-4">
            <Field label="Dirección Origen *">
              <textarea required className={`${inp} resize-none`} rows={2} value={head.origen} onChange={e => set('origen', e.target.value)} />
            </Field>
            <UbigeoSelects prefix="origen" value={head.ubigeo_origen} onChange={v => set('ubigeo_origen', v)} />
          </div>
        </section>

        {/* Destino */}
        <section className="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
          <h2 className="text-xs font-black text-gray-500 uppercase tracking-widest mb-4 pb-2 border-b border-gray-100">Destino</h2>
          <div className="space-y-4">
            <RucSearch
              label="RUC / DNI Destinatario *"
              value={head.ruc_destinatario}
              onChange={v => set('ruc_destinatario', v)}
              onFound={obj => set('destino', (obj.direccion || '') + (obj.distrito ? ' - ' + obj.distrito : '') + (obj.provincia ? ' - ' + obj.provincia : '') + (obj.departamento ? ' - ' + obj.departamento : ''))}
            />
            <Field label="Dirección Destino">
              <textarea className={`${inp} resize-none`} rows={2} value={head.destino} onChange={e => set('destino', e.target.value)} />
            </Field>
            <UbigeoSelects prefix="destino" value={head.ubigeo_destino} onChange={v => set('ubigeo_destino', v)} />
          </div>
        </section>

        {/* Transporte */}
        <section className="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
          <h2 className="text-xs font-black text-gray-500 uppercase tracking-widest mb-4 pb-2 border-b border-gray-100">Datos de Transporte</h2>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <RucSearch label="RUC / DNI Transportista" value={head.ruc_transportista} onChange={v => set('ruc_transportista', v)} />
            <RucSearch label="RUC / DNI Conductor" value={head.ruc_conductor} onChange={v => set('ruc_conductor', v)} />
            <Field label="Placa Vehículo">
              <input className={inp} placeholder="Ej: F5Z200" value={head.placa} onChange={e => set('placa', e.target.value)} />
            </Field>
            <Field label="N° Licencia">
              <input className={inp} value={head.licencia} onChange={e => set('licencia', e.target.value)} />
            </Field>
            <div className="col-span-1 md:col-span-2">
              <Field label="Comentario">
                <input className={inp} value={head.comentario} onChange={e => set('comentario', e.target.value)} />
              </Field>
            </div>
          </div>
        </section>

        {/* Buscar Productos */}
        <section className="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
          <h2 className="text-xs font-black text-gray-500 uppercase tracking-widest mb-4 pb-2 border-b border-gray-100">Agregar Productos</h2>
          <div className="flex gap-3 flex-wrap items-end">
            <div className="flex-1 min-w-[180px] space-y-1">
              <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Nombre</label>
              <input className={inp} placeholder="Nombre del producto..." value={searchNombre} onChange={e => setSearchNombre(e.target.value)} onKeyDown={e => e.key === 'Enter' && (e.preventDefault(), handleSearch())} />
            </div>
            <div className="flex-1 min-w-[120px] space-y-1">
              <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Código</label>
              <input className={inp} placeholder="Código..." value={searchCodigo} onChange={e => setSearchCodigo(e.target.value)} onKeyDown={e => e.key === 'Enter' && (e.preventDefault(), handleSearch())} />
            </div>
            <button type="button" onClick={handleSearch} className="px-5 py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-700 text-sm font-bold flex items-center gap-2 transition-colors">
              <MagnifyingGlassIcon className="h-4 w-4" />
              {searching ? 'Buscando...' : 'Buscar'}
            </button>
          </div>

          {searchResults.length > 0 && (
            <div className="mt-4 rounded-xl border border-gray-200 overflow-hidden">
              <table className="w-full text-sm">
                <thead className="bg-gray-50 text-xs uppercase border-b text-gray-600">
                  <tr>
                    <th className="px-4 py-3 text-left font-bold">Producto</th>
                    <th className="px-4 py-3 text-left font-bold">Código</th>
                    <th className="px-4 py-3 text-left font-bold">Unidad</th>
                    <th className="px-4 py-3 text-center font-bold">Sel.</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-gray-100">
                  {searchResults.map(p => (
                    <tr key={p.id} className={`cursor-pointer transition-colors ${selProd?.id === p.id ? 'bg-blue-50' : 'hover:bg-gray-50'}`} onClick={() => selectProd(p)}>
                      <td className="px-4 py-2.5 font-semibold text-gray-800">{p.name}</td>
                      <td className="px-4 py-2.5 text-gray-500 font-mono text-xs">{p.code}</td>
                      <td className="px-4 py-2.5 text-gray-500">{p.unit}</td>
                      <td className="px-4 py-2.5 text-center">
                        <button type="button" onClick={(e) => { e.stopPropagation(); selectProd(p); }} className="text-white bg-green-600 hover:bg-green-700 transition-colors rounded-md p-1.5 flex items-center justify-center mx-auto shadow-sm">
                          <PlusIcon className="h-4 w-4" />
                        </button>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          )}

          {selProd && (
            <div className="mt-4 p-4 bg-blue-50 rounded-xl border border-blue-200 space-y-4">
              <div className="space-y-1">
                <label className="text-xs font-bold text-gray-500 uppercase">Descripción del Producto</label>
                <input className="w-full p-2 border border-gray-300 rounded-lg text-sm font-bold text-blue-800 focus:border-blue-500 outline-none" value={editDescripcion} onChange={e => setEditDescripcion(e.target.value)} />
              </div>
              <div className="space-y-1">
                <label className="text-xs font-bold text-gray-500 uppercase">Unidad (SUNAT)</label>
                <select className="w-full p-2 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none bg-white" value={editUnidad} onChange={e => setEditUnidad(e.target.value)}>
                  <option value="">Unidades</option>
                  {unidadesSunat.map(u => (
                    <option key={u.id} value={u.codigo}>{u.unidad}</option>
                  ))}
                </select>
              </div>
              <div className="space-y-2">
                <div className="flex items-center justify-between">
                  <p className="text-xs font-bold text-gray-600 uppercase">Cantidades por Talla</p>
                  <p className="text-xs font-bold text-blue-600 bg-blue-100 px-2 py-1 rounded">
                    Total: {tallas.reduce((acc, curr) => acc + (parseFloat(curr) || 0), 0) || 0}
                  </p>
                </div>
                <div className="flex flex-wrap gap-2">
                  {TALLAS.map((t, i) => (
                    <div key={t} className="flex flex-col items-center gap-1">
                      <span className="text-[10px] font-bold text-gray-500">{t}</span>
                      <input type="number" min="0" className="w-14 p-1.5 border border-gray-300 rounded-lg text-xs text-center focus:border-blue-500 outline-none" value={tallas[i]} onChange={e => { const n = [...tallas]; n[i] = e.target.value; setTallas(n); }} />
                    </div>
                  ))}
                </div>
              </div>
              <div className="grid grid-cols-3 gap-3">
                <div className="space-y-1">
                  <label className="text-xs font-bold text-gray-500 uppercase">Pedido</label>
                  <input className="w-full p-2 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={pedido} onChange={e => setPedido(e.target.value)} />
                </div>
                <div className="space-y-1">
                  <label className="text-xs font-bold text-gray-500 uppercase">KG. Neto (c/u)</label>
                  <input type="number" step="0.001" className="w-full p-2 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={pesoNeto} onChange={e => setPesoNeto(e.target.value)} />
                </div>
                <div className="space-y-1">
                  <label className="text-xs font-bold text-gray-500 uppercase">KG. Bruto total</label>
                  <input type="number" step="0.001" className="w-full p-2 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={pesoBruto} onChange={e => setPesoBruto(e.target.value)} />
                </div>
              </div>
              <button type="button" onClick={addItem} className="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-bold flex items-center gap-2 transition-colors">
                <PlusIcon className="h-4 w-4" />Agregar a la Guía
              </button>
            </div>
          )}
        </section>

        {/* Items */}
        {items.length > 0 && (
          <section className="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div className="px-6 py-4 border-b border-gray-100 bg-gray-50">
              <h2 className="text-xs font-black text-gray-500 uppercase tracking-widest">Contenido de la Guía</h2>
            </div>
            <table className="w-full text-sm">
              <thead className="bg-gray-50 text-gray-600 text-xs uppercase border-b">
                <tr>
                  <th className="px-4 py-3 text-left font-bold">Código</th>
                  <th className="px-4 py-3 text-left font-bold">Producto</th>
                  <th className="px-4 py-3 text-left font-bold">Pedido</th>
                  <th className="px-4 py-3 text-right font-bold">Cant.</th>
                  <th className="px-4 py-3 text-center font-bold">Unidad</th>
                  <th className="px-4 py-3 text-right font-bold">KG. Neto</th>
                  <th className="px-4 py-3 text-right font-bold">KG. Bruto</th>
                  <th className="px-4 py-3 text-center font-bold">Quitar</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-gray-100">
                {items.map((it, idx) => (
                  editingItemIndex === idx ? (
                    <tr key={idx} className="bg-blue-50">
                      <td className="px-4 py-3 text-gray-600 text-xs font-mono">
                        <input className="w-full p-2 border border-gray-300 rounded-lg text-xs" value={editingRow.code} disabled />
                      </td>
                      <td className="px-4 py-3">
                        <input className="w-full p-2 border border-gray-300 rounded-lg text-xs" value={editingRow.descripcion_producto} onChange={e => updateEditingRow('descripcion_producto', e.target.value)} />
                      </td>
                      <td className="px-4 py-3">
                        <input className="w-full p-2 border border-gray-300 rounded-lg text-xs" value={editingRow.pedido || ''} onChange={e => updateEditingRow('pedido', e.target.value)} />
                      </td>
                      <td className="px-4 py-3 text-right">
                        <input type="number" min="0" className="w-20 p-2 border border-gray-300 rounded-lg text-xs text-right" value={editingRow.cantidad} onChange={e => updateEditingRow('cantidad', e.target.value)} />
                      </td>
                      <td className="px-4 py-3 text-center">
                        <input className="w-full p-2 border border-gray-300 rounded-lg text-xs text-center" value={editingRow.unidad || ''} onChange={e => updateEditingRow('unidad', e.target.value)} />
                      </td>
                      <td className="px-4 py-3 text-right">
                        <input type="number" step="0.001" className="w-20 p-2 border border-gray-300 rounded-lg text-xs text-right" value={editingRow.t_neto} onChange={e => updateEditingRow('t_neto', e.target.value)} />
                      </td>
                      <td className="px-4 py-3 text-right">
                        <input type="number" step="0.001" className="w-20 p-2 border border-gray-300 rounded-lg text-xs text-right" value={editingRow.t_bruto} onChange={e => updateEditingRow('t_bruto', e.target.value)} />
                      </td>
                      <td className="px-4 py-3 text-center space-x-1">
                        <button type="button" onClick={saveEditedItem} className="px-2 py-1 bg-green-600 text-white rounded-lg text-[10px] font-semibold hover:bg-green-700 transition-colors">Guardar</button>
                        <button type="button" onClick={cancelEditItem} className="px-2 py-1 bg-gray-200 text-gray-700 rounded-lg text-[10px] font-semibold hover:bg-gray-300 transition-colors">Cancelar</button>
                      </td>
                    </tr>
                  ) : (
                    <tr key={idx} className="hover:bg-gray-50">
                      <td className="px-4 py-3 text-gray-600 text-xs font-mono">{it.code}</td>
                      <td className="px-4 py-3 font-semibold text-gray-800 text-xs max-w-[260px]">{it.descripcion_producto}</td>
                      <td className="px-4 py-3 text-gray-600 text-xs">{it.pedido}</td>
                      <td className="px-4 py-3 text-right font-bold">{it.cantidad}</td>
                      <td className="px-4 py-3 text-center text-gray-600 text-xs">{it.unidad}</td>
                      <td className="px-4 py-3 text-right text-gray-700">{it.t_neto}</td>
                      <td className="px-4 py-3 text-right text-gray-700">{it.t_bruto}</td>
                      <td className="px-4 py-3 text-center flex items-center justify-center gap-1">
                        <button type="button" onClick={() => startEditItem(it, idx)} className="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                          <PencilIcon className="h-4 w-4" />
                        </button>
                        <button type="button" onClick={() => setItems(p => p.filter((_, i) => i !== idx))} className="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                          <TrashIcon className="h-4 w-4" />
                        </button>
                      </td>
                    </tr>
                  )
                ))}
              </tbody>
              <tfoot className="bg-gray-50 border-t">
                <tr>
                  <td colSpan="5" className="px-4 py-3 text-xs font-black text-gray-600 uppercase">Totales</td>
                  <td className="px-4 py-3 text-right font-black text-gray-900">{totalNeto}</td>
                  <td className="px-4 py-3 text-right font-black text-gray-900">{totalBruto}</td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </section>
        )}

        <div className="flex justify-end gap-3 pb-8">
          <button type="button" onClick={() => navigate('/guias')} className="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-sm transition-colors">
            Cancelar
          </button>
          <button type="submit" disabled={saving || items.length === 0} className="px-10 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold text-sm disabled:opacity-50 transition-all shadow-lg shadow-blue-500/20">
            {saving ? 'Guardando...' : 'Guardar Guía de Remisión'}
          </button>
        </div>
      </form>
    </div>
  );
}
