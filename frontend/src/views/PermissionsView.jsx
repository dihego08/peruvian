import { useState, useEffect } from 'react';
import api from '../services/api';
import { ShieldCheckIcon, CheckCircleIcon } from '@heroicons/react/24/outline';

export default function PermissionsView() {
  const [users, setUsers] = useState([]);
  const [selectedUser, setSelectedUser] = useState('');
  const [menus, setMenus] = useState([]);
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    fetchUsers();
  }, []);

  useEffect(() => {
    if (selectedUser) {
      fetchPermissions();
    } else {
      setMenus([]);
    }
  }, [selectedUser]);

  const fetchUsers = async () => {
    try {
      const r = await api.get('/users');
      setUsers(r.data);
    } catch (e) { console.error(e); }
  };

  const fetchPermissions = async () => {
    setLoading(true);
    try {
      const r = await api.get(`/permissions/menus?idUsuario=${selectedUser}`);
      setMenus(r.data);
    } catch (e) { console.error(e); }
    finally { setLoading(false); }
  };

  const handleToggle = (id) => {
    setMenus(prev => prev.map(m => {
      if (m.id === id) return { ...m, checked: !m.checked };
      return m;
    }));
  };

  const handleSave = async () => {
    if (!selectedUser) return;
    setSaving(true);
    try {
      const menuIds = menus.filter(m => m.checked).map(m => m.id);
      await api.post('/permissions/save', { idUsuario: selectedUser, menuIds });
      alert('Permisos guardados correctamente');
    } catch (e) {
      alert('Error al guardar permisos');
    } finally { setSaving(false); }
  };

  const renderMenus = (parentId = 0) => {
    const filtered = menus.filter(m => m.parent_id == parentId);
    if (filtered.length === 0) return null;

    return (
      <ul className={`space-y-2 ${parentId !== 0 ? 'ml-8 mt-2' : ''}`}>
        {filtered.map(m => (
          <li key={m.id} className="group">
            <div className="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors">
              <input
                type="checkbox"
                id={`menu-${m.id}`}
                checked={m.checked}
                onChange={() => handleToggle(m.id)}
                className="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer"
              />
              <label htmlFor={`menu-${m.id}`} className={`text-sm cursor-pointer select-none ${parentId === 0 ? 'font-bold text-gray-900 uppercase tracking-tight' : 'text-gray-700'}`}>
                {m.text}
              </label>
            </div>
            {renderMenus(m.id)}
          </li>
        ))}
      </ul>
    );
  };

  return (
    <div className="flex flex-col gap-6 animate-in fade-in duration-700">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900 flex items-center gap-2">
            Control de Accesos
          </h1>
          <p className="text-sm text-gray-500 mt-0.5">Permisos del menú del nuevo sistema (app_menus)</p>
        </div>
        {selectedUser && (
          <button
            onClick={handleSave}
            disabled={saving}
            className="bg-gray-800 text-white px-8 py-2.5 rounded-md hover:bg-gray-700 shadow-sm font-bold transition-all flex items-center gap-2 disabled:opacity-50"
          >
            {saving ? 'Guardando...' : (
              <>
                <CheckCircleIcon className="h-5 w-5" />
                Guardar Cambios
              </>
            )}
          </button>
        )}
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div className="max-w-md">
          <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Seleccionar Usuario</label>
          <select
            className="w-full p-2.5 border border-gray-300 rounded-md focus:border-blue-500 text-sm outline-none"
            value={selectedUser}
            onChange={e => setSelectedUser(e.target.value)}
          >
            <option value="">-- Seleccione un usuario --</option>
            {users.map(u => <option key={u.id} value={u.id}>{u.name} {u.lastname} ({u.username})</option>)}
          </select>
        </div>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-8 min-h-[400px]">
        {loading ? (
          <div className="flex flex-col items-center justify-center py-20 text-gray-400">
            <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
            <p className="font-medium">Cargando árbol de permisos...</p>
          </div>
        ) : selectedUser ? (
          <div className="max-w-2xl mx-auto">
            <div className="mb-6 pb-4 border-b border-gray-100 flex items-center justify-between">
              <h3 className="font-bold text-gray-900">Módulos Disponibles</h3>
              <p className="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Activar/Desactivar acceso</p>
            </div>
            {renderMenus()}
          </div>
        ) : (
          <div className="flex flex-col items-center justify-center py-20 text-gray-400 opacity-50">
            <ShieldCheckIcon className="h-20 w-20 mb-4" />
            <p className="font-bold text-lg">Selecciona un usuario para gestionar sus accesos</p>
          </div>
        )}
      </div>
    </div>
  );
}
