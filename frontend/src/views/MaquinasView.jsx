import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../services/api';
import {
  getMaquinaImageUrl,
  handleMaquinaImageError
} from '../utils/image';
import {
  PencilSquareIcon,
  TrashIcon,
  CpuChipIcon,
  PlusIcon,
  MagnifyingGlassIcon,
  XMarkIcon,
  ArrowPathIcon,
  EyeIcon,
  DocumentArrowUpIcon,
  WrenchScrewdriverIcon
} from '@heroicons/react/24/outline';

const EMPTY = {
  maquina_ubicacion: 'Makitex',
  maquina_tipo: 'CR1',
  maquina_codigo: '',
  maquina_descripcion: '',
  maquina_marca: '',
  maquina_modelo: '',
  maquina_serie: '',
  maquina_marca_motor: '',
  maquina_serie_motor: '',
  maquina_exigencias: '',
  maquina_voltaje: '',
  maquina_tipo_corriente: '',
  maquina_anio_compra: '',
  maquina_vida_util: '',
  maquina_estado: '1',
  precio_compra: '',
  proveedor: ''
};

const UBICACIONES = ['Makitex', 'Jerusalen', 'Línea 1', 'Línea 2', 'Otros'];
const TIPOS = ['CR1', 'RC3', 'RM4', 'RM5', 'BAL', 'BOT', 'CC1', 'COP', 'CRT', 'DC1', 'EM1', 'ETP', 'FUS', 'MTG', 'OJAL', 'PLV', 'PLD', 'TPT'];

export default function MaquinasView() {
  const navigate = useNavigate();
  const [machines, setMachines] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [status, setStatus] = useState('1'); // 1 = Active, 0 = Inactive
  const [showModal, setShowModal] = useState(false);
  const [editingId, setEditingId] = useState(null);
  const [formData, setFormData] = useState(EMPTY);
  const [saving, setSaving] = useState(false);
  const [expandedImage, setExpandedImage] = useState(null);

  useEffect(() => {
    fetchMachines();
  }, [status]);

  const fetchMachines = async () => {
    setLoading(true);
    try {
      const r = await api.get(`/machines?status=${status}`);
      setMachines(r.data);
    } catch (e) { console.error(e); }
    finally { setLoading(false); }
  };

  const openCreate = () => { setEditingId(null); setFormData(EMPTY); setShowModal(true); };
  const openEdit = (item) => { setEditingId(item.maquina_id); setFormData(item); setShowModal(true); };
  const closeModal = () => { setShowModal(false); setEditingId(null); setFormData(EMPTY); };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      const data = new FormData();
      Object.keys(formData).forEach(key => {
        if (formData[key] !== null && formData[key] !== undefined) {
          data.append(key, formData[key]);
        }
      });

      if (editingId) {
        data.append('_method', 'PUT');
        await api.post(`/machines/${editingId}`, data, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
      } else {
        await api.post('/machines', data, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
      }
      closeModal();
      fetchMachines();
    } catch (err) {
      alert('Error al guardar maquinaria');
    } finally { setSaving(false); }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('¿Desea dar de baja esta máquina?')) return;
    try {
      await api.delete(`/machines/${id}`);
      fetchMachines();
    } catch (e) { alert('Error al procesar baja'); }
  };

  const handleRestore = async (id) => {
    try {
      await api.post(`/machines/${id}/restore`);
      fetchMachines();
    } catch (e) { alert('Error al restaurar'); }
  };

  const filtered = machines.filter(m =>
    (m.maquina_descripcion || '').toLowerCase().includes(search.toLowerCase()) ||
    (m.maquina_codigo || '').toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div className="flex flex-col gap-6 animate-in fade-in duration-700">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900 flex items-center gap-2">
            Maquinaria
          </h1>
          <p className="text-sm text-gray-500 mt-0.5">Control de activos y equipos de producción</p>
        </div>
        <div className="flex items-center gap-3">
          <select
            value={status}
            onChange={e => setStatus(e.target.value)}
            className="p-2.5 border border-gray-300 rounded-md text-sm font-bold text-gray-700 outline-none focus:border-blue-500"
          >
            <option value="1">Ver Activos</option>
            <option value="0">Ver Bajas</option>
          </select>
          <button onClick={() => navigate('/machines/dispositivos')} className="bg-emerald-600 text-white px-5 py-2.5 rounded-md hover:bg-emerald-700 shadow-sm font-medium transition-colors flex items-center gap-2 text-sm">
            <CpuChipIcon className="h-4 w-4" />
            Dispositivos
          </button>
          <button onClick={() => navigate('/machines/maintenance-program')} className="bg-blue-600 text-white px-5 py-2.5 rounded-md hover:bg-blue-700 shadow-sm font-medium transition-colors flex items-center gap-2 text-sm">
            <DocumentArrowUpIcon className="h-4 w-4" />
            Programa de Mantenimiento
          </button>
          <button onClick={openCreate} className="bg-gray-800 text-white px-5 py-2.5 rounded-md hover:bg-gray-700 shadow-sm font-medium transition-colors flex items-center gap-2 text-sm">
            <PlusIcon className="h-4 w-4" />
            Agregar Máquina
          </button>
        </div>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div className="relative">
          <MagnifyingGlassIcon className="h-5 w-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
          <input
            type="text"
            className="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-md focus:border-blue-500 text-sm outline-none"
            placeholder="Buscar por descripción o código..."
            value={search}
            onChange={e => setSearch(e.target.value)}
          />
        </div>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-sm">
            <thead className="bg-gray-50 text-gray-600 uppercase text-[10px] border-b border-gray-200 font-black tracking-widest">
              <tr>
                <th className="px-4 py-4">Código / Tipo</th>
                <th className="px-4 py-4">Imagen</th>
                <th className="px-4 py-4">Descripción</th>
                <th className="px-4 py-4">Ubicación</th>
                <th className="px-4 py-4">Cabezal (Marca/Serie)</th>
                <th className="px-4 py-4">Motor (Marca/Serie)</th>
                <th className="px-4 py-4 text-center">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-100">
              {loading ? (
                <tr><td colSpan="6" className="px-4 py-10 text-center text-gray-400">Cargando maquinaria...</td></tr>
              ) : filtered.length === 0 ? (
                <tr><td colSpan="6" className="px-4 py-10 text-center text-gray-400">No se encontraron máquinas.</td></tr>
              ) : filtered.map(item => (
                <tr key={item.maquina_id} className="hover:bg-gray-50 transition-colors">
                  <td className="px-4 py-4">
                    <div className="flex flex-col">
                      <span className="font-mono font-bold text-blue-600">{item.maquina_tipo}-{item.maquina_codigo}</span>
                      <span className="text-[10px] text-gray-400 uppercase font-black">{item.maquina_modelo}</span>
                    </div>
                  </td>
                  <td className="px-4 py-4">
                    {item.maquina_imagen ? (
                      <img
                        src={getMaquinaImageUrl(item.maquina_imagen)}
                        alt={item.maquina_descripcion}
                        className="w-12 h-12 object-cover rounded-md border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity"
                        onClick={(e) => setExpandedImage(e.target.src)}
                        onError={(e) => handleMaquinaImageError(e, item.maquina_imagen)}
                      />
                    ) : (
                      <span className="text-gray-300">-</span>
                    )}
                  </td>
                  <td className="px-4 py-4">
                    <p className="font-bold text-gray-800">{item.maquina_descripcion}</p>
                    <p className="text-[10px] text-gray-500 uppercase italic">Marca: {item.maquina_marca}</p>
                  </td>
                  <td className="px-4 py-4">
                    <span className="px-2 py-1 bg-gray-100 rounded text-[10px] font-bold text-gray-600 uppercase">
                      {item.maquina_ubicacion || 'Sin Ubicación'}
                    </span>
                  </td>
                  <td className="px-4 py-4">
                    <div className="text-xs">
                      <p className="text-gray-900 font-medium">{item.maquina_marca || '-'}</p>
                      <p className="text-gray-400 font-mono">S/N: {item.maquina_serie || '-'}</p>
                    </div>
                  </td>

                  <td className="px-4 py-4">
                    <div className="text-xs">
                      <p className="text-gray-900 font-medium">{item.maquina_marca_motor || '-'}</p>
                      <p className="text-gray-400 font-mono">S/N: {item.maquina_serie_motor || '-'}</p>
                    </div>
                  </td>
                  <td className="px-4 py-4">
                    <div className="flex items-center justify-center gap-2">
                      <button
                        onClick={() => navigate(`/machines/${item.maquina_id}/maintenance`)}
                        className="flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-all text-[10px] font-black uppercase tracking-tight border border-blue-100 shadow-sm"
                      >
                        <WrenchScrewdriverIcon className="h-3.5 w-3.5" />
                        Mantenimiento
                      </button>
                      {status === '1' ? (
                        <>
                          <button onClick={() => openEdit(item)} className="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                            <PencilSquareIcon className="h-5 w-5" />
                          </button>
                          <button onClick={() => handleDelete(item.maquina_id)} className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <TrashIcon className="h-5 w-5" />
                          </button>
                        </>
                      ) : (
                        <button onClick={() => handleRestore(item.maquina_id)} className="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors flex items-center gap-1 text-xs font-bold">
                          <ArrowPathIcon className="h-5 w-5" />
                          RESTAURAR
                        </button>
                      )}
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {showModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
          <div className="bg-white rounded-2xl shadow-2xl w-full max-w-5xl overflow-hidden animate-in zoom-in duration-200 flex flex-col max-h-[95vh]">
            <div className="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
              <h2 className="text-lg font-bold text-gray-900">{editingId ? 'Editar Máquina' : 'Nueva Máquina'}</h2>
              <button onClick={closeModal} className="text-gray-400 hover:text-gray-700 p-1.5 rounded-lg hover:bg-gray-200 transition-colors">
                <XMarkIcon className="h-5 w-5" />
              </button>
            </div>
            <form onSubmit={handleSubmit} className="p-6 overflow-y-auto space-y-8">
              {/* Seccion 1: Identificación y Ubicación */}
              <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Ubicación</label>
                  <select className="w-full p-2.5 border border-gray-300 rounded-md text-sm outline-none focus:border-blue-500" value={formData.maquina_ubicacion} onChange={e => setFormData({ ...formData, maquina_ubicacion: e.target.value })}>
                    {UBICACIONES.map(u => <option key={u} value={u}>{u}</option>)}
                  </select>
                </div>
                <div>
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Tipo de Máquina</label>
                  <select className="w-full p-2.5 border border-gray-300 rounded-md text-sm outline-none focus:border-blue-500" value={formData.maquina_tipo} onChange={e => setFormData({ ...formData, maquina_tipo: e.target.value })}>
                    {TIPOS.map(t => <option key={t} value={t}>{t}</option>)}
                  </select>
                </div>
                <div>
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Código *</label>
                  <input required type="text" className="w-full p-2.5 border border-gray-300 rounded-md text-sm focus:border-blue-500 outline-none" value={formData.maquina_codigo} onChange={e => setFormData({ ...formData, maquina_codigo: e.target.value })} />
                </div>
                <div>
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Estado</label>
                  <select className="w-full p-2.5 border border-gray-300 rounded-md text-sm outline-none focus:border-blue-500" value={formData.maquina_estado} onChange={e => setFormData({ ...formData, maquina_estado: e.target.value })}>
                    <option value="1">Activo</option>
                    <option value="0">Baja</option>
                  </select>
                </div>
              </div>

              <div>
                <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Descripción Detallada *</label>
                <input required type="text" className="w-full p-2.5 border border-gray-300 rounded-md text-sm focus:border-blue-500 outline-none" value={formData.maquina_descripcion} onChange={e => setFormData({ ...formData, maquina_descripcion: e.target.value })} />
              </div>

              {/* Seccion 2: Datos Técnicos (Cabezal y Motor) */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div className="space-y-4 p-4 bg-blue-50/30 rounded-xl border border-blue-100">
                  <h3 className="text-xs font-black text-blue-600 uppercase tracking-widest border-b border-blue-100 pb-1">Especificaciones del Cabezal</h3>
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[10px] font-bold text-gray-400 uppercase mb-1">Marca *</label>
                      <input required type="text" className="w-full p-2 border border-gray-300 rounded-md text-sm" value={formData.maquina_marca} onChange={e => setFormData({ ...formData, maquina_marca: e.target.value })} />
                    </div>
                    <div>
                      <label className="block text-[10px] font-bold text-gray-400 uppercase mb-1">Modelo</label>
                      <input type="text" className="w-full p-2 border border-gray-300 rounded-md text-sm" value={formData.maquina_modelo} onChange={e => setFormData({ ...formData, maquina_modelo: e.target.value })} />
                    </div>
                  </div>
                  <div>
                    <label className="block text-[10px] font-bold text-gray-400 uppercase mb-1">Número de Serie</label>
                    <input type="text" className="w-full p-2 border border-gray-300 rounded-md text-sm" value={formData.maquina_serie} onChange={e => setFormData({ ...formData, maquina_serie: e.target.value })} />
                  </div>
                </div>

                <div className="space-y-4 p-4 bg-amber-50/30 rounded-xl border border-amber-100">
                  <h3 className="text-xs font-black text-amber-600 uppercase tracking-widest border-b border-amber-100 pb-1">Especificaciones del Motor</h3>
                  <div>
                    <label className="block text-[10px] font-bold text-gray-400 uppercase mb-1">Marca Motor</label>
                    <input type="text" className="w-full p-2 border border-gray-300 rounded-md text-sm" value={formData.maquina_marca_motor} onChange={e => setFormData({ ...formData, maquina_marca_motor: e.target.value })} />
                  </div>
                  <div>
                    <label className="block text-[10px] font-bold text-gray-400 uppercase mb-1">Serie Motor</label>
                    <input type="text" className="w-full p-2 border border-gray-300 rounded-md text-sm" value={formData.maquina_serie_motor} onChange={e => setFormData({ ...formData, maquina_serie_motor: e.target.value })} />
                  </div>
                </div>
              </div>

              {/* Seccion 3: Requerimientos y Suministros */}
              <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div className="col-span-1">
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Medidas para Espacio</label>
                  <input type="text" className="w-full p-2.5 border border-gray-300 rounded-md text-sm outline-none" value={formData.maquina_exigencias} onChange={e => setFormData({ ...formData, maquina_exigencias: e.target.value })} placeholder="Ej: 1.20 x 0.80m" />
                </div>
                <div>
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Voltaje</label>
                  <input type="text" className="w-full p-2.5 border border-gray-300 rounded-md text-sm outline-none" value={formData.maquina_voltaje} onChange={e => setFormData({ ...formData, maquina_voltaje: e.target.value })} placeholder="Ej: 220V" />
                </div>
                <div>
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Tipo de Corriente</label>
                  <input type="text" className="w-full p-2.5 border border-gray-300 rounded-md text-sm outline-none" value={formData.maquina_tipo_corriente} onChange={e => setFormData({ ...formData, maquina_tipo_corriente: e.target.value })} placeholder="Ej: Monofásica / Trifásica" />
                </div>
              </div>

              {/* Seccion 4: Información de Compra */}
              <div className="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                <div>
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Año de Compra</label>
                  <input type="text" className="w-full p-2.5 border border-gray-300 rounded-md text-sm outline-none" value={formData.maquina_anio_compra} onChange={e => setFormData({ ...formData, maquina_anio_compra: e.target.value })} />
                </div>
                <div>
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Vida Útil (Años)</label>
                  <input type="text" className="w-full p-2.5 border border-gray-300 rounded-md text-sm outline-none" value={formData.maquina_vida_util} onChange={e => setFormData({ ...formData, maquina_vida_util: e.target.value })} />
                </div>
                <div>
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Precio de Compra</label>
                  <input type="text" className="w-full p-2.5 border border-gray-300 rounded-md text-sm outline-none font-mono" value={formData.precio_compra} onChange={e => setFormData({ ...formData, precio_compra: e.target.value })} placeholder="0.00" />
                </div>
                <div>
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Proveedor</label>
                  <input type="text" className="w-full p-2.5 border border-gray-300 rounded-md text-sm outline-none" value={formData.proveedor} onChange={e => setFormData({ ...formData, proveedor: e.target.value })} />
                </div>
                <div>
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Imagen de Máquina</label>
                  <input type="file" accept="image/*" className="w-full p-2 border border-gray-300 rounded-md text-sm outline-none focus:border-blue-500 bg-white" onChange={e => setFormData({ ...formData, maquina_imagen: e.target.files[0] })} />
                  {editingId && typeof formData.maquina_imagen === 'string' && formData.maquina_imagen !== '' && (
                    <p className="text-[10px] text-gray-500 mt-1">Archivo actual: {formData.maquina_imagen}</p>
                  )}
                </div>
                <div>
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Factura de Compra</label>
                  <input type="file" accept="image/*,application/pdf" className="w-full p-2 border border-gray-300 rounded-md text-sm outline-none focus:border-blue-500 bg-white" onChange={e => setFormData({ ...formData, factura_compra: e.target.files[0] })} />
                  {editingId && typeof formData.factura_compra === 'string' && formData.factura_compra !== '' && (
                    <p className="text-[10px] text-gray-500 mt-1">Archivo actual: {formData.factura_compra}</p>
                  )}
                </div>
              </div>

              <div className="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-100">
                <button type="button" onClick={closeModal} className="px-6 py-2.5 text-gray-700 font-bold text-sm hover:bg-gray-100 rounded-md transition-colors">Cancelar</button>
                <button type="submit" disabled={saving} className="px-10 py-2.5 bg-gray-800 text-white rounded-md hover:bg-gray-700 font-bold text-sm transition-all shadow-sm disabled:opacity-50">
                  {saving ? 'Guardando...' : 'Guardar Maquinaria'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
      {/* Modal Imagen */}
      {expandedImage && (
        <div
          className="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/80 backdrop-blur-sm cursor-pointer animate-in fade-in zoom-in duration-200"
          onClick={() => setExpandedImage(null)}
        >
          <div className="relative max-w-4xl max-h-[90vh] flex flex-col">
            <button
              className="absolute -top-10 right-0 text-white hover:text-gray-300 transition-colors"
              onClick={() => setExpandedImage(null)}
            >
              <XMarkIcon className="h-8 w-8" />
            </button>
            <img
              src={expandedImage}
              alt="Vista ampliada"
              className="w-full h-full object-contain rounded-lg shadow-2xl border-4 border-white"
              onClick={(e) => e.stopPropagation()}
            />
          </div>
        </div>
      )}
    </div>
  );
}
