import { useState, useEffect } from 'react';
import api from '../services/api';
import { getProductImageUrl, handleProductImageError } from '../utils/image';
import { PencilSquareIcon, TrashIcon } from '@heroicons/react/24/outline';

const EMPTY_PRODUCT = {
  kind: '1', cliente_id: '', code: '', barcode: '', name: '', brand_id: '',
  description: '', price_in: '', price_in_2: '', price_out: '', unit: '',
  presentation: '', large: '', width: '', height: '', weight: '',
  inventary_min: '', q: '', pre_bor_in: '', pre_bor_out: '', fecact: '', image: null,
  imgbordado: null, secuencia: null
};

export default function ProductsView() {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [editingId, setEditingId] = useState(null);
  const [formData, setFormData] = useState(EMPTY_PRODUCT);
  const [saving, setSaving] = useState(false);
  const [brands, setBrands] = useState([]);
  const [clients, setClients] = useState([]);

  useEffect(() => {
    fetchProducts();
    fetchBrands();
    fetchClients();
  }, []);

  const fetchBrands = async () => {
    try { const r = await api.get('/brands'); setBrands(r.data); } catch (e) { console.error(e); }
  };

  const fetchClients = async () => {
    try { const r = await api.get('/clients'); setClients(r.data); } catch (e) { console.error(e); }
  };

  const fetchProducts = async () => {
    try { const r = await api.get('/products'); setProducts(r.data); }
    catch (e) { console.error(e); }
    finally { setLoading(false); }
  };

  const openCreate = () => { setEditingId(null); setFormData(EMPTY_PRODUCT); setShowModal(true); };

  const openEdit = (p) => {
    setEditingId(p.id);
    setFormData({
      kind: p.kind || '1', cliente_id: p.cliente_id || '', code: p.code || '',
      barcode: p.barcode || '', name: p.name || '', brand_id: p.brand_id || '',
      description: p.description || '', price_in: p.price_in || '', price_in_2: p.price_in_2 || '',
      price_out: p.price_out || '', unit: p.unit || '', presentation: p.presentation || '',
      large: p.large || '', width: p.width || '', height: p.height || '',
      weight: p.weight || '', inventary_min: p.inventary_min || '',
      q: p.q || '', pre_bor_in: p.pre_bor_in || '', pre_bor_out: p.pre_bor_out || '',
      fecact: p.fecact || '', image: null, imgbordado: null, secuencia: null
    });
    setShowModal(true);
  };

  const closeModal = () => { setShowModal(false); setEditingId(null); setFormData(EMPTY_PRODUCT); };

  const handleDelete = async (id) => {
    if (!window.confirm('¿Eliminar este producto?')) return;
    try { await api.delete(`/products/${id}`); fetchProducts(); }
    catch (e) { alert('Error al eliminar'); }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    const data = new FormData();
    Object.keys(formData).forEach(key => {
      if (formData[key] !== null && formData[key] !== undefined) data.append(key, formData[key]);
    });
    // Laravel no acepta PUT con multipart, usamos POST + _method spoofing
    if (editingId) data.append('_method', 'PUT');
    try {
      const url = editingId ? `/products/${editingId}` : '/products';
      await api.post(url, data, { headers: { 'Content-Type': 'multipart/form-data' } });
      closeModal(); fetchProducts();
    } catch (err) {
      alert(err.response?.data?.message || 'Error al guardar');
    } finally { setSaving(false); }
  };

  const handleInputChange = (e) => {
    const { name, value, type, files } = e.target;
    if (type === 'file') setFormData({ ...formData, [name]: files[0] });
    else setFormData({ ...formData, [name]: value });
  };

  return (
    <div className="flex flex-col gap-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Catálogo de Productos</h1>
          <p className="text-sm text-gray-500 mt-0.5">Administración de inventario y precios</p>
        </div>
        <button onClick={openCreate} className="bg-gray-800 text-white px-5 py-2.5 rounded-md hover:bg-gray-700 shadow-sm font-medium transition-colors flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" /></svg>
          Nuevo Producto
        </button>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col" style={{ maxHeight: 'calc(100vh - 290px)' }}>

        {loading ? (
          <div className="p-8 text-center text-gray-500">Cargando productos...</div>
        ) : (
          <div className="overflow-auto relative">
            <table className="w-full text-left border-collapse whitespace-nowrap">
              <thead className="bg-gray-50 sticky top-0 z-10 shadow-sm">
                <tr className="text-gray-600 text-sm uppercase tracking-wider">
                  <th className="p-4 border-b">Imagen</th>
                  <th className="p-4 border-b">Código</th>
                  <th className="p-4 border-b">Descripción</th>
                  <th className="p-4 border-b">Precio Min.</th>
                  <th className="p-4 border-b">Precio Max.</th>
                  <th className="p-4 border-b">Precio Bordado Salida</th>
                  <th className="p-4 border-b">Bordado</th>
                  <th className="p-4 border-b">Cliente</th>
                  <th className="p-4 border-b">Acciones</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-gray-100">
                {products.map((product) => (
                  <tr key={product.id} className="hover:bg-gray-50 transition-colors">
                    <td className="p-4">
                      {product.image ? (
                        <img
                          src={getProductImageUrl(product.image)}
                          alt={product.name}
                          className="w-12 h-12 object-cover rounded-md border border-gray-200"
                          onError={(e) => handleProductImageError(e, product.image)}
                        />
                      ) : (
                        <div className="w-12 h-12 bg-gray-100 rounded-md flex items-center justify-center text-gray-400 text-xs">Sin img</div>
                      )}
                    </td>
                    <td className="p-4 text-gray-500 font-mono">{product.code || '-'}</td>
                    <td className="p-4 font-medium text-gray-800">{product.name}</td>
                    <td className="p-4 font-medium text-gray-800">S/ {product.price_in || '0.00'}</td>
                    <td className="p-4 font-medium text-gray-800">S/ {product.price_in_2 || '0.00'}</td>
                    <td className="p-4 text-gray-600 font-bold text-green-600">S/ {product.prebor_out || '0.00'}</td>
                    <td className="p-4 text-gray-600 font-bold text-green-600">
                      {product.imgbordado ? (
                        <img
                          src={getProductImageUrl(product.imgbordado)}
                          alt={product.name}
                          className="w-12 h-12 object-cover rounded-md border border-gray-200"
                          onError={(e) => handleProductImageError(e, product.imgbordado)}
                        />
                      ) : (
                        <div className="w-12 h-12 bg-gray-100 rounded-md flex items-center justify-center text-gray-400 text-xs">Sin img</div>
                      )}
                    </td>
                    <td className="p-4 text-gray-500 text-sm">{product.client ? product.client.name : '-'}</td>
                    <td className="p-4">
                      <div className="flex items-center gap-2">
                        <button
                          title="Editar"
                          onClick={() => openEdit(product)}
                          className="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                        >
                          <PencilSquareIcon className="h-5 w-5" />
                        </button>
                        <button
                          title="Eliminar"
                          onClick={() => handleDelete(product.id)}
                          className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                        >
                          <TrashIcon className="h-5 w-5" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
                {products.length === 0 && (
                  <tr>
                    <td colSpan="6" className="p-8 text-center text-gray-500">No se encontraron productos.</td>
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
              <h3 className="text-xl font-bold mb-6 border-b pb-2">{editingId ? 'Editar Producto' : 'Añadir Producto'}</h3>
              <form onSubmit={handleSubmit}>
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                  <div>
                    <label className="block text-sm font-medium text-gray-700">Tipo</label>
                    <select name="kind" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.kind} onChange={handleInputChange}>
                      <option value="1">Producto</option>
                      <option value="2">Servicio</option>
                    </select>
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">Imagen Principal</label>
                    <input name="image" type="file" accept="image/*" className="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" onChange={handleInputChange} />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">Imagen Bordado</label>
                    <input name="imgbordado" type="file" accept="image/*" className="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" onChange={handleInputChange} />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">Hoja de Secuencia</label>
                    <input name="secuencia" type="file" accept="image/*,application/pdf" className="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" onChange={handleInputChange} />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">Nombre *</label>
                    <input required name="name" type="text" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.name} onChange={handleInputChange} />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">Modelo *</label>
                    <input required name="code" type="text" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.code} onChange={handleInputChange} />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">Cliente</label>
                    <select name="cliente_id" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.cliente_id} onChange={handleInputChange}>
                      <option value="">-- NINGUNA --</option>
                      {clients.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                    </select>
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">Marca</label>
                    <select name="brand_id" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.brand_id} onChange={handleInputChange}>
                      <option value="">-- NINGUNA --</option>
                      {brands.map(b => <option key={b.id} value={b.id}>{b.name}</option>)}
                    </select>
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">Código de Barras</label>
                    <input name="barcode" type="text" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.barcode} onChange={handleInputChange} />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">Precio Confección Mínimo *</label>
                    <input required name="price_in" type="number" step="0.01" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.price_in} onChange={handleInputChange} />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">Precio Confección Máximo</label>
                    <input name="price_in_2" type="number" step="0.01" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.price_in_2} onChange={handleInputChange} />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">Precio Venta (Salida)</label>
                    <input name="price_out" type="number" step="0.01" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.price_out} onChange={handleInputChange} />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">Unidad *</label>
                    <input name="unit" type="text" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.unit} onChange={handleInputChange} />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">Presentación</label>
                    <input name="presentation" type="text" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.presentation} onChange={handleInputChange} />
                  </div>

                  {/* Dimensions */}
                  <div><label className="block text-sm font-medium text-gray-700">Largo *</label><input name="large" type="text" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.large} onChange={handleInputChange} /></div>
                  <div><label className="block text-sm font-medium text-gray-700">Ancho *</label><input name="width" type="text" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.width} onChange={handleInputChange} /></div>
                  <div><label className="block text-sm font-medium text-gray-700">Alto *</label><input name="height" type="text" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.height} onChange={handleInputChange} /></div>
                  <div><label className="block text-sm font-medium text-gray-700">Peso *</label><input name="weight" type="text" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.weight} onChange={handleInputChange} /></div>

                  {formData.kind === '1' && (
                    <>
                      <div><label className="block text-sm font-medium text-gray-700">Stock Mínimo</label><input name="inventary_min" type="number" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.inventary_min} onChange={handleInputChange} /></div>
                      <div><label className="block text-sm font-medium text-gray-700">Inventario Inicial</label><input name="q" type="number" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.q} onChange={handleInputChange} /></div>
                    </>
                  )}

                  <div>
                    <label className="block text-sm font-medium text-gray-700">Precio Bordado</label>
                    <input name="pre_bor_in" type="number" step="0.01" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.pre_bor_in} onChange={handleInputChange} />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">Precio Bordado Salida</label>
                    <input name="pre_bor_out" type="number" step="0.01" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.pre_bor_out} onChange={handleInputChange} />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700">Fecha Actualización</label>
                    <input required name="fecact" type="date" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.fecact} onChange={handleInputChange} />
                  </div>
                  <div className="col-span-full">
                    <label className="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea name="description" rows="3" className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 p-2 border" value={formData.description} onChange={handleInputChange}></textarea>
                  </div>
                </div>

                <div className="flex justify-end gap-3 mt-8 pt-4 border-t">
                  <button type="button" onClick={closeModal} className="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium transition-colors">Cancelar</button>
                  <button type="submit" disabled={saving} className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors disabled:opacity-60">
                    {saving ? 'Guardando...' : editingId ? 'Actualizar Producto' : 'Guardar Producto'}
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
