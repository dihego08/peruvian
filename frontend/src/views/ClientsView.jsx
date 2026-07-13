import { useState, useEffect } from 'react';
import api from '../services/api';
import { PencilSquareIcon, TrashIcon } from '@heroicons/react/24/outline';

const EMPTY = {
  name: '', no: '', address1: '', tipo_pago: '0', banco: 'BCP',
  nro_cuenta: '', email1: '', phone1: '', wsp: '',
  has_credit: false, credit_limit: '', is_active_access: false, password: ''
};

export default function ClientsView() {
  const [clients, setClients] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [editingId, setEditingId] = useState(null);
  const [formData, setFormData] = useState(EMPTY);
  const [saving, setSaving] = useState(false);

  useEffect(() => { fetchClients(); }, []);

  const fetchClients = async () => {
    try { const r = await api.get('/clients'); setClients(r.data); }
    catch (e) { console.error(e); }
    finally { setLoading(false); }
  };

  const openCreate = () => { setEditingId(null); setFormData(EMPTY); setShowModal(true); };

  const openEdit = (c) => {
    setEditingId(c.id);
    setFormData({
      name: c.name || '', no: c.no || '', address1: c.address1 || '',
      tipo_pago: c.tipo_pago || '0', banco: c.banco || 'BCP',
      nro_cuenta: c.nro_cuenta || '', email1: c.email1 || '',
      phone1: c.phone1 || '', wsp: c.wsp || '',
      has_credit: !!c.has_credit, credit_limit: c.credit_limit || '',
      is_active_access: !!c.is_active_access, password: ''
    });
    setShowModal(true);
  };

  const closeModal = () => { setShowModal(false); setEditingId(null); setFormData(EMPTY); };

  const handleDelete = async (id) => {
    if (!window.confirm('¿Eliminar este cliente?')) return;
    try { await api.delete(`/clients/${id}`); fetchClients(); }
    catch (e) { alert('Error al eliminar'); }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      if (editingId) await api.put(`/clients/${editingId}`, formData);
      else await api.post('/clients', formData);
      closeModal(); fetchClients();
    } catch (err) { alert(err.response?.data?.message || 'Error al guardar'); }
    finally { setSaving(false); }
  };

  const handleInputChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData({ ...formData, [name]: type === 'checkbox' ? checked : value });
  };

  return (
    <div className="flex flex-col gap-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Directorio de Clientes</h1>
          <p className="text-sm text-gray-500 mt-0.5">Gestión de base de datos de clientes</p>
        </div>
        <button onClick={openCreate} className="bg-gray-800 text-white px-5 py-2.5 rounded-md hover:bg-gray-700 shadow-sm font-medium transition-colors flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" /></svg>
          Nuevo Cliente
        </button>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        {loading ? (
          <div className="p-8 text-center text-gray-500">Cargando clientes...</div>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                  <th className="p-4 border-b">Doc/DNI</th>
                  <th className="p-4 border-b">Nombre / Empresa</th>
                  <th className="p-4 border-b">Teléfono</th>
                  <th className="p-4 border-b">Email</th>
                  <th className="p-4 border-b">Crédito</th>
                  <th className="p-4 border-b">Acciones</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-gray-100">
                {clients.map((client) => (
                  <tr key={client.id} className="hover:bg-gray-50 transition-colors">
                    <td className="p-4 text-gray-500 font-mono text-sm">{client.no || '-'}</td>
                    <td className="p-4 font-medium text-gray-800">
                      {client.name} {client.lastname}
                      {client.company && <div className="text-xs text-gray-500 font-normal">{client.company}</div>}
                    </td>
                    <td className="p-4 text-gray-600">{client.phone1 || client.wsp || '-'}</td>
                    <td className="p-4 text-gray-600">{client.email1 || '-'}</td>
                    <td className="p-4">
                      {client.has_credit === 1 ? (
                        <span className="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                          Sí (Límite: {client.credit_limit || 0})
                        </span>
                      ) : (
                        <span className="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                          No
                        </span>
                      )}
                    </td>
                    <td className="p-4">
                      <div className="flex items-center gap-2">
                        <button
                          title="Editar"
                          onClick={() => openEdit(client)}
                          className="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                        >
                          <PencilSquareIcon className="h-5 w-5" />
                        </button>
                        <button
                          title="Eliminar"
                          onClick={() => handleDelete(client.id)}
                          className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                        >
                          <TrashIcon className="h-5 w-5" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
                {clients.length === 0 && (
                  <tr>
                    <td colSpan="6" className="p-8 text-center text-gray-500">
                      No se encontraron clientes.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        )}

        {/* Expanded CRUD Modal */}
        {showModal && (
          <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div className="bg-white p-6 rounded-xl shadow-xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
              <h3 className="text-xl font-bold mb-6 border-b pb-2">{editingId ? 'Editar Cliente' : 'Añadir Cliente'}</h3>
              <form onSubmit={handleSubmit}>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div className="col-span-2">
                    <label className="block text-sm font-medium text-gray-700">Nombre / Razón social *</label>
                    <input required name="name" type="text" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.name} onChange={handleInputChange} />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">DNI / RUC</label>
                    <div className="flex mt-1">
                      <input name="no" type="text" className="block w-full rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.no} onChange={handleInputChange} />
                      <button type="button" className="bg-gray-100 border border-l-0 border-gray-300 px-3 rounded-r-md hover:bg-gray-200">🔍</button>
                    </div>
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">Dirección</label>
                    <input name="address1" type="text" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.address1} onChange={handleInputChange} />
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-700">Tipo de Pago</label>
                    <select name="tipo_pago" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.tipo_pago} onChange={handleInputChange}>
                      <option value="0">Efectivo</option>
                      <option value="1">Bancarizado</option>
                    </select>
                  </div>

                  {formData.tipo_pago === '1' && (
                    <>
                      <div>
                        <label className="block text-sm font-medium text-gray-700">Banco *</label>
                        <select name="banco" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.banco} onChange={handleInputChange}>
                          <option value="BCP">BCP</option>
                          <option value="INTERBANK">INTERBANK</option>
                          <option value="SCOTIABANK">SCOTIABANK</option>
                          <option value="BBVA_CONTINENTAL">BBVA CONTINENTAL</option>
                          <option value="BANCO_DE_CREDITO">BANCO DE CREDITO</option>
                          <option value="MiBanco">MiBanco</option>
                        </select>
                      </div>
                      <div>
                        <label className="block text-sm font-medium text-gray-700">Nro de Cuenta</label>
                        <input name="nro_cuenta" type="text" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.nro_cuenta} onChange={handleInputChange} />
                      </div>
                    </>
                  )}

                  <div>
                    <label className="block text-sm font-medium text-gray-700">Email</label>
                    <input name="email1" type="email" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.email1} onChange={handleInputChange} />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input name="phone1" type="text" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.phone1} onChange={handleInputChange} />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">WhatsApp</label>
                    <input name="wsp" type="text" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.wsp} onChange={handleInputChange} />
                  </div>

                  <div className="col-span-2 grid grid-cols-2 gap-6 bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <div>
                      <label className="flex items-center space-x-2">
                        <input name="has_credit" type="checkbox" className="rounded border-gray-300 text-blue-600 focus:ring-blue-500" checked={formData.has_credit} onChange={handleInputChange} />
                        <span className="text-sm font-medium text-gray-700">Activar Crédito</span>
                      </label>
                      {formData.has_credit && (
                        <div className="mt-2">
                          <label className="block text-xs font-medium text-gray-500">Límite de crédito</label>
                          <input name="credit_limit" type="number" step="0.01" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.credit_limit} onChange={handleInputChange} />
                        </div>
                      )}
                    </div>

                    <div>
                      <label className="flex items-center space-x-2">
                        <input name="is_active_access" type="checkbox" className="rounded border-gray-300 text-blue-600 focus:ring-blue-500" checked={formData.is_active_access} onChange={handleInputChange} />
                        <span className="text-sm font-medium text-gray-700">Activar Acceso al Sistema</span>
                      </label>
                      {formData.is_active_access && (
                        <div className="mt-2">
                          <label className="block text-xs font-medium text-gray-500">Contraseña temporal</label>
                          <input name="password" type="password" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.password} onChange={handleInputChange} />
                        </div>
                      )}
                    </div>
                  </div>
                </div>

                <div className="mt-4 p-3 bg-blue-50 text-blue-800 rounded-lg text-sm border border-blue-100">
                  * Campos obligatorios
                </div>

                <div className="flex justify-end gap-3 mt-6 pt-4 border-t">
                  <button type="button" onClick={closeModal} className="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium transition-colors">Cancelar</button>
                  <button type="submit" disabled={saving} className="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors disabled:opacity-60">
                    {saving ? 'Guardando...' : editingId ? 'Actualizar Cliente' : 'Guardar Cliente'}
                  </button>
                </div>
              </form>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
