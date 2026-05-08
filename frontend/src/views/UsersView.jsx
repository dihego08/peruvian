import { useState, useEffect } from 'react';
import api from '../services/api';
import { PencilSquareIcon, TrashIcon } from '@heroicons/react/24/outline';

const EMPTY = { kind: '0', name: '', lastname: '', username: '', email: '', celular: '', password: '' };

export default function UsersView() {
  const [users, setUsers]       = useState([]);
  const [cargos, setCargos]     = useState([]);
  const [loading, setLoading]   = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [editingId, setEditingId] = useState(null);
  const [formData, setFormData] = useState(EMPTY);
  const [saving, setSaving]     = useState(false);

  useEffect(() => { 
    fetchUsers(); 
    fetchCargos();
  }, []);

  const fetchUsers = async () => {
    try { const r = await api.get('/users'); setUsers(r.data); }
    catch (e) { console.error(e); }
    finally { setLoading(false); }
  };

  const fetchCargos = async () => {
    try { const r = await api.get('/cargos'); setCargos(r.data); }
    catch (e) { console.error(e); }
  };

  const openCreate = () => { setEditingId(null); setFormData(EMPTY); setShowModal(true); };

  const openEdit = (user) => {
    setEditingId(user.id);
    setFormData({ kind: user.kind || '0', name: user.name || '', lastname: user.lastname || '',
      username: user.username || '', email: user.email || '', celular: user.celular || '', password: '' });
    setShowModal(true);
  };

  const closeModal = () => { setShowModal(false); setEditingId(null); setFormData(EMPTY); };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      const payload = { ...formData };
      if (editingId && !payload.password) delete payload.password; // no cambiar si está vacío
      if (editingId) {
        await api.put(`/users/${editingId}`, payload);
      } else {
        await api.post('/users', payload);
      }
      closeModal();
      fetchUsers();
    } catch (err) {
      alert(err.response?.data?.message || 'Error al guardar');
    } finally { setSaving(false); }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('¿Eliminar este usuario?')) return;
    try { await api.delete(`/users/${id}`); fetchUsers(); }
    catch (e) { alert('Error al eliminar'); }
  };

  return (
    <div className="flex flex-col gap-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Usuarios</h1>
          <p className="text-sm text-gray-500 mt-0.5">Administración de cuentas del sistema</p>
        </div>
        <button onClick={openCreate} className="bg-gray-800 text-white px-5 py-2.5 rounded-md hover:bg-gray-700 shadow-sm font-medium transition-colors flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" /></svg>
          Nuevo Usuario
        </button>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-sm">
            <thead className="bg-gray-50 text-gray-600 uppercase text-xs border-b border-gray-200">
              <tr>
                <th className="px-4 py-3">ID</th>
                <th className="px-4 py-3">Nombre</th>
                <th className="px-4 py-3">Usuario</th>
                <th className="px-4 py-3">Email</th>
                <th className="px-4 py-3 text-center">Rol</th>
                <th className="px-4 py-3 text-center">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-100">
              {loading && <tr><td colSpan="6" className="px-4 py-8 text-center text-gray-400">Cargando...</td></tr>}
              {!loading && users.length === 0 && <tr><td colSpan="6" className="px-4 py-8 text-center text-gray-400">Sin usuarios registrados.</td></tr>}
              {users.map(u => (
                <tr key={u.id} className="hover:bg-gray-50 transition-colors">
                  <td className="px-4 py-3 text-gray-500 font-mono">#{u.id}</td>
                  <td className="px-4 py-3 font-medium text-gray-800">{u.name} {u.lastname}</td>
                  <td className="px-4 py-3 text-gray-600">{u.username}</td>
                  <td className="px-4 py-3 text-gray-600">{u.email}</td>
                  <td className="px-4 py-3 text-center">
                    <span className="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800 font-semibold">
                      {cargos.find(c => c.id == u.kind)?.cargo || `Rol ${u.kind}`}
                    </span>
                  </td>
                  <td className="px-4 py-3">
                    <div className="flex items-center justify-center gap-2">
                      <button 
                        onClick={() => openEdit(u)} 
                        title="Editar" 
                        className="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                      >
                        <PencilSquareIcon className="h-5 w-5" />
                      </button>
                      <button 
                        onClick={() => handleDelete(u.id)} 
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
      </div>

      {/* Modal Crear / Editar */}
      {showModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div className="absolute inset-0 bg-black/50 backdrop-blur-sm" onClick={closeModal}></div>
          <div className="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
            <div className="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
              <h2 className="text-lg font-bold text-gray-900">{editingId ? 'Editar Usuario' : 'Nuevo Usuario'}</h2>
              <button onClick={closeModal} className="text-gray-400 hover:text-gray-700 p-1.5 rounded-lg hover:bg-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" /></svg>
              </button>
            </div>
            <form onSubmit={handleSubmit} className="p-6 space-y-4">
              <div>
                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tipo de Usuario *</label>
                <select name="kind" required className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" value={formData.kind} onChange={e => setFormData({...formData, kind: e.target.value})}>
                  <option value="0">Selecciona...</option>
                  {cargos.map(c => <option key={c.id} value={c.id}>{c.cargo}</option>)}
                </select>
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nombre *</label>
                  <input required name="name" type="text" className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" value={formData.name} onChange={e => setFormData({...formData, name: e.target.value})} />
                </div>
                <div>
                  <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Apellido</label>
                  <input name="lastname" type="text" className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" value={formData.lastname} onChange={e => setFormData({...formData, lastname: e.target.value})} />
                </div>
              </div>
              <div>
                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Usuario (login) *</label>
                <input required name="username" type="text" className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" value={formData.username} onChange={e => setFormData({...formData, username: e.target.value})} />
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Email *</label>
                  <input required name="email" type="email" className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" value={formData.email} onChange={e => setFormData({...formData, email: e.target.value})} />
                </div>
                <div>
                  <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Celular</label>
                  <input name="celular" type="text" className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" value={formData.celular} onChange={e => setFormData({...formData, celular: e.target.value})} />
                </div>
              </div>
              <div>
                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">
                  Contraseña {editingId ? '(dejar vacío para no cambiar)' : '*'}
                </label>
                <input name="password" type="password" required={!editingId} className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm" value={formData.password} onChange={e => setFormData({...formData, password: e.target.value})} />
              </div>
              <div className="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onClick={closeModal} className="px-5 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium text-sm transition-colors">Cancelar</button>
                <button type="submit" disabled={saving} className="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium text-sm transition-colors disabled:opacity-60">
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
