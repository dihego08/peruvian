import { useState, useEffect } from 'react';
import api from '../services/api';
import { PencilSquareIcon, TrashIcon, BriefcaseIcon } from '@heroicons/react/24/outline';

const EMPTY = { cargo: '', id_referencia: '0' };

export default function CargosView() {
  const [cargos, setCargos] = useState([]);
  const [clients, setClients] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [editingId, setEditingId] = useState(null);
  const [formData, setFormData] = useState(EMPTY);
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    fetchCargos();
    fetchClients();
  }, []);

  const fetchCargos = async () => {
    try {
      const r = await api.get('/cargos');
      setCargos(r.data);
    } catch (e) { console.error(e); }
    finally { setLoading(false); }
  };

  const fetchClients = async () => {
    try {
      const r = await api.get('/cargos/clients');
      setClients(r.data);
    } catch (e) { console.error(e); }
  };

  const openCreate = () => { setEditingId(null); setFormData(EMPTY); setShowModal(true); };

  const openEdit = (item) => {
    setEditingId(item.id);
    setFormData({ cargo: item.cargo, id_referencia: item.id_referencia || '0' });
    setShowModal(true);
  };

  const closeModal = () => { setShowModal(false); setEditingId(null); setFormData(EMPTY); };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      if (editingId) {
        await api.put(`/cargos/${editingId}`, formData);
      } else {
        await api.post('/cargos', formData);
      }
      closeModal();
      fetchCargos();
    } catch (err) {
      alert(err.response?.data?.message || 'Error al guardar');
    } finally { setSaving(false); }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('¿Eliminar este cargo?')) return;
    try {
      await api.delete(`/cargos/${id}`);
      fetchCargos();
    } catch (e) { alert('Error al eliminar'); }
  };

  return (
    <div className="flex flex-col gap-6 animate-in fade-in duration-700">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <BriefcaseIcon className="h-8 w-8 text-blue-600" />
            Cargos
          </h1>
          <p className="text-sm text-gray-500 mt-0.5">Gestión de puestos y roles del sistema</p>
        </div>
        <button onClick={openCreate} className="bg-gray-800 text-white px-5 py-2.5 rounded-md hover:bg-gray-700 shadow-sm font-medium transition-colors flex items-center gap-2 text-sm">
          <PlusIcon className="h-4 w-4" />
          Nuevo Cargo
        </button>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-sm">
            <thead className="bg-gray-50 text-gray-600 uppercase text-xs border-b border-gray-200">
              <tr>
                <th className="px-4 py-3">Código</th>
                <th className="px-4 py-3">Cargo / Puesto</th>
                <th className="px-4 py-3">Cliente Referencia</th>
                <th className="px-4 py-3 text-center">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-100">
              {loading && <tr><td colSpan="4" className="px-4 py-8 text-center text-gray-400">Cargando...</td></tr>}
              {!loading && cargos.length === 0 && <tr><td colSpan="4" className="px-4 py-8 text-center text-gray-400">Sin cargos registrados.</td></tr>}
              {cargos.map(item => (
                <tr key={item.id} className="hover:bg-gray-50 transition-colors">
                  <td className="px-4 py-3 text-gray-500 font-mono">#{item.id}</td>
                  <td className="px-4 py-3 font-bold text-gray-800 uppercase">{item.cargo}</td>
                  <td className="px-4 py-3 text-gray-600">{item.client?.name || 'SIN REFERENCIA'}</td>
                  <td className="px-4 py-3">
                    <div className="flex items-center justify-center gap-2">
                      <button onClick={() => openEdit(item)} className="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                        <PencilSquareIcon className="h-5 w-5" />
                      </button>
                      <button onClick={() => handleDelete(item.id)} className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <TrashIcon className="h-5 w-5" />
                      </button>
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
          <div className="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden animate-in zoom-in duration-200">
            <div className="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
              <h2 className="text-lg font-bold text-gray-900">{editingId ? 'Editar Cargo' : 'Nuevo Cargo'}</h2>
              <button onClick={closeModal} className="text-gray-400 hover:text-gray-700 p-1.5 rounded-lg hover:bg-gray-200 transition-colors">
                <XMarkIcon className="h-5 w-5" />
              </button>
            </div>
            <form onSubmit={handleSubmit} className="p-6 space-y-4">
              <div>
                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nombre del Cargo *</label>
                <input
                  required
                  type="text"
                  className="w-full p-2.5 border border-gray-300 rounded-md focus:border-blue-500 text-sm outline-none"
                  value={formData.cargo}
                  onChange={e => setFormData({ ...formData, cargo: e.target.value })}
                  placeholder="Ej: Administrador, Vendedor..."
                />
              </div>
              <div>
                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Cliente Referencia</label>
                <select
                  className="w-full p-2.5 border border-gray-300 rounded-md focus:border-blue-500 text-sm outline-none"
                  value={formData.id_referencia}
                  onChange={e => setFormData({ ...formData, id_referencia: e.target.value })}
                >
                  <option value="0">SELECCIONA ...</option>
                  {clients.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                </select>
              </div>
              <div className="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onClick={closeModal} className="px-5 py-2 text-gray-700 font-bold text-sm hover:bg-gray-100 rounded-md transition-colors">Cancelar</button>
                <button type="submit" disabled={saving} className="px-8 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 font-bold text-sm transition-all shadow-sm disabled:opacity-50">
                  {saving ? 'Guardando...' : 'Guardar Cargo'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}

function PlusIcon({ className }) {
  return (
    <svg xmlns="http://www.w3.org/2000/svg" className={className} fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
    </svg>
  );
}

function XMarkIcon({ className }) {
  return (
    <svg xmlns="http://www.w3.org/2000/svg" className={className} fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
    </svg>
  );
}
