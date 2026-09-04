import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../services/api';
import {
  getDispositivoImageUrl,
  handleDispositivoImageError
} from '../utils/image';
import {
  PlusIcon,
  PencilSquareIcon,
  TrashIcon,
  ArrowLeftIcon,
  PhotoIcon,
  XMarkIcon
} from '@heroicons/react/24/outline';

const EMPTY = {
  codigo: '',
  descripcion: '',
  cantidad: '',
  imagen: '',
  observaciones: '',
  responsable: '',
  fecha: new Date().toISOString().slice(0, 10)
};

export default function DispositivosView() {
  const navigate = useNavigate();
  const [dispositivos, setDispositivos] = useState([]);
  const [loading, setLoading] = useState(true);

  const [showModal, setShowModal] = useState(false);

  const [formData, setFormData] = useState(EMPTY);
  const [editingId, setEditingId] = useState(null);
  const [saving, setSaving] = useState(false);
  const [expandedImage, setExpandedImage] = useState(null);

  useEffect(() => {
    fetchDispositivos();
  }, []);

  const fetchDispositivos = async () => {
    setLoading(true);
    try {
      const res = await api.get('/dispositivos');
      setDispositivos(res.data);
    } catch (e) {
      console.error(e);
    } finally {
      setLoading(false);
    }
  };

  const openCreate = () => {
    setFormData(EMPTY);
    setEditingId(null);
    setShowModal(true);
  };

  const openEdit = (item) => {
    setFormData({
      codigo: item.codigo || '',
      descripcion: item.descripcion || '',
      cantidad: item.cantidad || '',
      imagen: item.imagen || '',
      observaciones: item.observaciones || '',
      responsable: item.responsable || '',
      fecha: item.fecha || ''
    });
    setEditingId(item.id);
    setShowModal(true);
  };

  const closeModal = () => {
    setShowModal(false);
    setEditingId(null);
  };

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
        await api.post(`/dispositivos/${editingId}`, data, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
      } else {
        await api.post('/dispositivos', data, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
      }
      closeModal();
      fetchDispositivos();
    } catch (e) {
      alert('Error al guardar el dispositivo');
    } finally {
      setSaving(false);
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('¿Seguro de Eliminar este registro? Esta acción es irreversible.')) return;
    try {
      await api.delete(`/dispositivos/${id}`);
      fetchDispositivos();
    } catch (e) {
      alert('Error al eliminar');
    }
  };

  return (
    <div className="flex flex-col gap-6 animate-in fade-in duration-700">
      <div className="flex items-center justify-between">
        <div className="flex items-center gap-4">
          <button onClick={() => navigate('/machines')} className="p-2 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 text-gray-600 transition-colors">
            <ArrowLeftIcon className="h-5 w-5" />
          </button>
          <div>
            <h1 className="text-2xl font-bold text-gray-900 flex items-center gap-2">
              Dispositivos y Accesorios
            </h1>
            <p className="text-sm text-gray-500 mt-0.5">Control de inventario de accesorios de maquinarias</p>
          </div>
        </div>

        <div>
          <button onClick={openCreate} className="bg-emerald-600 text-white px-5 py-2.5 rounded-lg hover:bg-emerald-700 shadow-sm font-medium transition-colors flex items-center gap-2 text-sm">
            <PlusIcon className="h-4 w-4" />
            Agregar Dispositivo/Accesorio
          </button>
        </div>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-sm whitespace-nowrap">
            <thead className="bg-gray-50 text-gray-600 uppercase text-[10px] font-black tracking-widest border-b border-gray-200">
              <tr>
                <th className="px-4 py-4">Código</th>
                <th className="px-4 py-4">Descripción</th>
                <th className="px-4 py-4 text-center">Cantidad</th>
                <th className="px-4 py-4">Responsable</th>
                <th className="px-4 py-4">Fecha Entrega</th>
                <th className="px-4 py-4 text-center">Imagen</th>
                <th className="px-4 py-4">Observaciones</th>
                <th className="px-4 py-4 text-center">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-100">
              {loading ? (
                <tr><td colSpan="8" className="px-4 py-10 text-center text-gray-400">Cargando dispositivos...</td></tr>
              ) : dispositivos.length === 0 ? (
                <tr><td colSpan="8" className="px-4 py-10 text-center text-gray-400">No hay dispositivos registrados.</td></tr>
              ) : dispositivos.map(item => (
                <tr key={item.id} className="hover:bg-gray-50 transition-colors">
                  <td className="px-4 py-4 font-mono font-bold text-gray-800">{item.codigo}</td>
                  <td className="px-4 py-4 font-medium">{item.descripcion}</td>
                  <td className="px-4 py-4 text-center font-bold text-blue-600">{item.cantidad_actual || item.cantidad || 0}</td>
                  <td className="px-4 py-4 text-gray-600">{item.recibido_por || item.responsable || '-'}</td>
                  <td className="px-4 py-4 text-gray-500">{item.fecha_entrega || item.fecha || '-'}</td>
                  <td className="px-4 py-4 text-center">
                    {item.imagen ? (
                      <img
                        src={getDispositivoImageUrl(item.imagen)}
                        alt={item.descripcion}
                        className="w-12 h-12 object-cover rounded-md border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity"
                        onClick={(e) => setExpandedImage(e.target.src)}
                        onError={(e) => handleDispositivoImageError(e, item.imagen)}
                      />
                    ) : (
                      <span className="text-gray-300">-</span>
                    )}
                  </td>
                  <td className="px-4 py-4 text-gray-500 text-xs truncate max-w-[200px]">{item.observaciones}</td>
                  <td className="px-4 py-4">
                    <div className="flex items-center justify-center gap-2">
                      <button onClick={() => openEdit(item)} className="p-1.5 bg-amber-50 text-amber-600 rounded hover:bg-amber-100 transition-colors" title="Editar">
                        <PencilSquareIcon className="h-4 w-4" />
                      </button>
                      <button onClick={() => handleDelete(item.id)} className="p-1.5 bg-red-50 text-red-600 rounded hover:bg-red-100 transition-colors" title="Eliminar">
                        <TrashIcon className="h-4 w-4" />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* Form Modal */}
      {showModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
          <div className="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden animate-in zoom-in duration-200">
            <div className="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
              <h2 className="text-lg font-bold text-gray-900">{editingId ? 'Editar Dispositivo' : 'Agregar Dispositivo'}</h2>
              <button onClick={closeModal} className="text-gray-400 hover:text-gray-700 p-1.5 rounded-lg hover:bg-gray-200 transition-colors">
                <XMarkIcon className="h-5 w-5" />
              </button>
            </div>

            <form onSubmit={handleSubmit} className="p-6">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Código *</label>
                  <input required type="text" className="w-full p-2.5 border border-gray-300 rounded-md text-sm outline-none focus:border-blue-500" value={formData.codigo} onChange={e => setFormData({ ...formData, codigo: e.target.value })} />
                </div>
                <div>
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Cantidad *</label>
                  <input required type="number" className="w-full p-2.5 border border-gray-300 rounded-md text-sm outline-none focus:border-blue-500" value={formData.cantidad} onChange={e => setFormData({ ...formData, cantidad: e.target.value })} />
                </div>
                <div className="md:col-span-2">
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Descripción *</label>
                  <input required type="text" className="w-full p-2.5 border border-gray-300 rounded-md text-sm outline-none focus:border-blue-500" value={formData.descripcion} onChange={e => setFormData({ ...formData, descripcion: e.target.value })} />
                </div>
                <div>
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Responsable</label>
                  <input type="text" className="w-full p-2.5 border border-gray-300 rounded-md text-sm outline-none focus:border-blue-500" value={formData.responsable} onChange={e => setFormData({ ...formData, responsable: e.target.value })} />
                </div>
                <div>
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Fecha Inicial</label>
                  <input type="date" className="w-full p-2.5 border border-gray-300 rounded-md text-sm outline-none focus:border-blue-500" value={formData.fecha} onChange={e => setFormData({ ...formData, fecha: e.target.value })} />
                </div>
                <div className="md:col-span-2">
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Imagen</label>
                  <input type="file" accept="image/*" className="w-full p-2 border border-gray-300 rounded-md text-sm outline-none focus:border-blue-500 bg-white" onChange={e => setFormData({ ...formData, imagen: e.target.files[0] })} />
                  {editingId && typeof formData.imagen === 'string' && formData.imagen !== '' && (
                    <p className="text-[10px] text-gray-500 mt-1">Archivo actual: {formData.imagen}</p>
                  )}
                </div>
                <div className="md:col-span-2">
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Observaciones</label>
                  <textarea rows="3" className="w-full p-2.5 border border-gray-300 rounded-md text-sm outline-none focus:border-blue-500 resize-none" value={formData.observaciones} onChange={e => setFormData({ ...formData, observaciones: e.target.value })} />
                </div>
              </div>

              <div className="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-100">
                <button type="button" onClick={closeModal} className="px-6 py-2.5 text-gray-700 font-bold text-sm hover:bg-gray-100 rounded-md transition-colors">Cancelar</button>
                <button type="submit" disabled={saving} className="px-10 py-2.5 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 font-bold text-sm transition-all shadow-sm disabled:opacity-50">
                  {saving ? 'Guardando...' : 'Guardar Dispositivo'}
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
