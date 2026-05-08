import { useState, useEffect } from 'react';
import api from '../services/api';
import { PencilSquareIcon, TrashIcon } from '@heroicons/react/24/outline';

const EMPTY = { name: '' };

export default function BrandsView() {
  const [brands, setBrands] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [editingId, setEditingId] = useState(null);
  const [formData, setFormData] = useState(EMPTY);
  const [saving, setSaving] = useState(false);

  useEffect(() => { fetchBrands(); }, []);

  const fetchBrands = async () => {
    try { const r = await api.get('/brands'); setBrands(r.data); }
    catch (e) { console.error(e); }
    finally { setLoading(false); }
  };

  const openCreate = () => { setEditingId(null); setFormData(EMPTY); setShowModal(true); };
  const openEdit = (b) => { setEditingId(b.id); setFormData({ name: b.name || '' }); setShowModal(true); };
  const closeModal = () => { setShowModal(false); setEditingId(null); setFormData(EMPTY); };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      if (editingId) await api.put(`/brands/${editingId}`, formData);
      else await api.post('/brands', formData);
      closeModal(); fetchBrands();
    } catch (err) { alert(err.response?.data?.message || 'Error al guardar'); }
    finally { setSaving(false); }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('¿Eliminar esta marca?')) return;
    try { await api.delete(`/brands/${id}`); fetchBrands(); }
    catch (e) { alert('Error al eliminar'); }
  };

  return (
    <div className="flex flex-col gap-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Marcas</h1>
          <p className="text-sm text-gray-500 mt-0.5">Catálogo de marcas de productos</p>
        </div>
        <button onClick={openCreate} className="bg-gray-800 text-white px-5 py-2.5 rounded-md hover:bg-gray-700 shadow-sm font-medium transition-colors flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" /></svg>
          Nueva Marca
        </button>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table className="w-full text-left text-sm">
          <thead className="bg-gray-50 text-gray-600 uppercase text-xs border-b border-gray-200">
            <tr>
              <th className="px-4 py-3">ID</th>
              <th className="px-4 py-3">Nombre</th>
              <th className="px-4 py-3 text-center">Acciones</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-100">
            {loading && <tr><td colSpan="3" className="px-4 py-8 text-center text-gray-400">Cargando...</td></tr>}
            {!loading && brands.length === 0 && <tr><td colSpan="3" className="px-4 py-8 text-center text-gray-400">Sin marcas registradas.</td></tr>}
            {brands.map(b => (
              <tr key={b.id} className="hover:bg-gray-50 transition-colors">
                <td className="px-4 py-3 text-gray-500 font-mono">#{b.id}</td>
                <td className="px-4 py-3 font-medium text-gray-800">{b.name}</td>
                <td className="px-4 py-3">
                  <div className="flex items-center justify-center gap-2">
                    <button
                      onClick={() => openEdit(b)}
                      title="Editar"
                      className="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                    >
                      <PencilSquareIcon className="h-5 w-5" />
                    </button>
                    <button
                      onClick={() => handleDelete(b.id)}
                      title="Eliminar"
                      className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                    >
                      <TrashIcon className="h-5 w-5" />
                    </button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {showModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div className="absolute inset-0 bg-black/50 backdrop-blur-sm" onClick={closeModal}></div>
          <div className="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">
            <div className="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
              <h2 className="text-lg font-bold text-gray-900">{editingId ? 'Editar Marca' : 'Nueva Marca'}</h2>
              <button onClick={closeModal} className="text-gray-400 hover:text-gray-700 p-1.5 rounded-lg hover:bg-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" /></svg>
              </button>
            </div>
            <form onSubmit={handleSubmit} className="p-6 space-y-4">
              <div>
                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nombre *</label>
                <input required type="text" className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" value={formData.name} onChange={e => setFormData({ name: e.target.value })} />
              </div>
              <div className="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onClick={closeModal} className="px-5 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium text-sm">Cancelar</button>
                <button type="submit" disabled={saving} className="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium text-sm disabled:opacity-60">
                  {saving ? 'Guardando...' : editingId ? 'Actualizar' : 'Guardar'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
