import { useState, useEffect } from 'react';
import api from '../services/api';
import { PencilSquareIcon, TrashIcon } from '@heroicons/react/24/outline';

const EMPTY = {
  id_insumo: '', no: '', name: '', address1: '', banco: 'SIN_BANCO',
  nro_cuenta: '', tipo_cuenta: '', tipo_moneda: '', forma_envio: '',
  email1: '', phone1: '', wsp: ''
};

export default function ProvidersView() {
  const [providers, setProviders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [editingId, setEditingId] = useState(null);
  const [formData, setFormData] = useState(EMPTY);
  const [saving, setSaving] = useState(false);

  useEffect(() => { fetchProviders(); }, []);

  const fetchProviders = async () => {
    try { const r = await api.get('/providers'); setProviders(r.data); }
    catch (e) { console.error(e); }
    finally { setLoading(false); }
  };

  const openCreate = () => { setEditingId(null); setFormData(EMPTY); setShowModal(true); };

  const openEdit = (p) => {
    setEditingId(p.id);
    setFormData({
      id_insumo: p.id_insumo || '', no: p.no || '', name: p.name || '',
      address1: p.address1 || '', banco: p.banco || 'SIN_BANCO',
      nro_cuenta: p.nro_cuenta || '', tipo_cuenta: p.tipo_cuenta || '',
      tipo_moneda: p.tipo_moneda || '', forma_envio: p.forma_envio || '',
      email1: p.email1 || '', phone1: p.phone1 || '', wsp: p.wsp || ''
    });
    setShowModal(true);
  };

  const closeModal = () => { setShowModal(false); setEditingId(null); setFormData(EMPTY); };

  const handleDelete = async (id) => {
    if (!window.confirm('¿Eliminar este proveedor?')) return;
    try { await api.delete(`/providers/${id}`); fetchProviders(); }
    catch (e) { alert('Error al eliminar'); }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      if (editingId) await api.put(`/providers/${editingId}`, formData);
      else await api.post('/providers', formData);
      closeModal(); fetchProviders();
    } catch (err) { alert(err.response?.data?.message || 'Error al guardar'); }
    finally { setSaving(false); }
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  return (
    <div className="flex flex-col gap-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Directorio de Proveedores</h1>
          <p className="text-sm text-gray-500 mt-0.5">Gestión de proveedores y cuentas bancarias</p>
        </div>
        <button onClick={openCreate} className="bg-gray-800 text-white px-5 py-2.5 rounded-md hover:bg-gray-700 shadow-sm font-medium transition-colors flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" /></svg>
          Nuevo Proveedor
        </button>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        {loading ? (
          <div className="p-8 text-center text-gray-500">Cargando proveedores...</div>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                  <th className="p-4 border-b">RUC/DNI</th>
                  <th className="p-4 border-b">Razón Social / Nombre</th>
                  <th className="p-4 border-b">Teléfono / WSP</th>
                  <th className="p-4 border-b">Banco</th>
                  <th className="p-4 border-b">Acciones</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-gray-100">
                {providers.map((provider) => (
                  <tr key={provider.id} className="hover:bg-gray-50 transition-colors">
                    <td className="p-4 text-gray-500 font-mono text-sm">{provider.no || '-'}</td>
                    <td className="p-4 font-medium text-gray-800">{provider.name} {provider.lastname}</td>
                    <td className="p-4 text-gray-600">{provider.phone1 || provider.wsp || '-'}</td>
                    <td className="p-4 text-gray-600">
                      {provider.banco !== 'SIN_BANCO' ? (
                        <span className="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-md border border-blue-200">{provider.banco}</span>
                      ) : '-'}
                    </td>
                    <td className="p-4">
                      <div className="flex items-center gap-2">
                        <button
                          title="Editar"
                          onClick={() => openEdit(provider)}
                          className="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                        >
                          <PencilSquareIcon className="h-5 w-5" />
                        </button>
                        <button
                          title="Eliminar"
                          onClick={() => handleDelete(provider.id)}
                          className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                        >
                          <TrashIcon className="h-5 w-5" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
                {providers.length === 0 && (
                  <tr>
                    <td colSpan="5" className="p-8 text-center text-gray-500">No se encontraron proveedores.</td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        )}

        {/* Expanded CRUD Modal */}
        {showModal && (
          <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div className="bg-white p-6 rounded-xl shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
              <h3 className="text-xl font-bold mb-6 border-b pb-2">{editingId ? 'Editar Proveedor' : 'Añadir Proveedor'}</h3>
              <form onSubmit={handleSubmit}>
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                  <div>
                    <label className="block text-sm font-medium text-gray-700">DNI / RUC *</label>
                    <div className="flex mt-1">
                      <input required name="no" type="text" className="block w-full rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.no} onChange={handleInputChange} />
                      <button type="button" className="bg-gray-100 border border-l-0 border-gray-300 px-3 rounded-r-md hover:bg-gray-200">🔍</button>
                    </div>
                  </div>
                  <div className="col-span-2">
                    <label className="block text-sm font-medium text-gray-700">Nombre / Razón social *</label>
                    <input required name="name" type="text" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.name} onChange={handleInputChange} />
                  </div>
                  <div className="col-span-3">
                    <label className="block text-sm font-medium text-gray-700">Dirección *</label>
                    <input required name="address1" type="text" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.address1} onChange={handleInputChange} />
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-700">Banco</label>
                    <select name="banco" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.banco} onChange={handleInputChange}>
                      <option value="SIN_BANCO">SIN BANCO</option>
                      <option value="BCP">BCP</option>
                      <option value="INTERBANK">INTERBANK</option>
                      <option value="SCOTIABANK">SCOTIABANK</option>
                      <option value="BBVA_CONTINENTAL">BBVA CONTINENTAL</option>
                      <option value="BANCO_DE_CREDITO">BANCO DE CREDITO</option>
                      <option value="MiBanco">MiBanco</option>
                    </select>
                  </div>

                  {formData.banco !== 'SIN_BANCO' && (
                    <>
                      <div>
                        <label className="block text-sm font-medium text-gray-700">Nro de Cuenta</label>
                        <input name="nro_cuenta" type="text" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.nro_cuenta} onChange={handleInputChange} />
                      </div>
                      <div>
                        <label className="block text-sm font-medium text-gray-700">Tipo de Cuenta</label>
                        <select name="tipo_cuenta" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.tipo_cuenta} onChange={handleInputChange}>
                          <option value="">- Elegir opción -</option>
                          <option value="corriente">Cuenta Corriente</option>
                          <option value="ahorros">Cuenta de Ahorros</option>
                        </select>
                      </div>
                      <div>
                        <label className="block text-sm font-medium text-gray-700">Tipo de Moneda</label>
                        <select name="tipo_moneda" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.tipo_moneda} onChange={handleInputChange}>
                          <option value="">- Elegir opción -</option>
                          <option value="SOL">Soles</option>
                          <option value="DOL">Dólares</option>
                        </select>
                      </div>
                    </>
                  )}

                  <div>
                    <label className="block text-sm font-medium text-gray-700">Forma de Envío</label>
                    <input name="forma_envio" type="text" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.forma_envio} onChange={handleInputChange} />
                  </div>
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
                </div>

                <div className="mt-4 p-3 bg-blue-50 text-blue-800 rounded-lg text-sm border border-blue-100">
                  * Campos obligatorios
                </div>

                <div className="flex justify-end gap-3 mt-6 pt-4 border-t">
                  <button type="button" onClick={closeModal} className="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium transition-colors">Cancelar</button>
                  <button type="submit" disabled={saving} className="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium transition-colors disabled:opacity-60">
                    {saving ? 'Guardando...' : editingId ? 'Actualizar Proveedor' : 'Guardar Proveedor'}
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
