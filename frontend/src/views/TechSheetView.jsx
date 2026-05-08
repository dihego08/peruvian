import { useState, useEffect } from 'react';
import api from '../services/api';
import TechSheetModal from '../components/TechSheetModal';
import {
  MagnifyingGlassIcon,
  FunnelIcon,
  IdentificationIcon,
  EyeIcon,
  ChevronRightIcon
} from '@heroicons/react/24/outline';

export default function TechSheetView() {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [selectedProduct, setSelectedProduct] = useState(null);
  const [isModalOpen, setIsModalOpen] = useState(false);

  useEffect(() => {
    fetchProducts();
  }, []);

  const fetchProducts = async () => {
    setLoading(true);
    try {
      // Reusing products index for now
      const res = await api.get('/products');
      setProducts(res.data);
    } catch (error) {
      console.error("Error fetching products", error);
    } finally {
      setLoading(false);
    }
  };

  const filteredProducts = products.filter(p =>
    p.name?.toLowerCase().includes(search.toLowerCase()) ||
    p.code?.toLowerCase().includes(search.toLowerCase())
  );

  const openFicha = (code) => {
    setSelectedProduct(code);
    setIsModalOpen(true);
  };

  return (
    <div className="flex flex-col gap-6 animate-in fade-in duration-700">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Fichas Técnicas</h1>
          <p className="text-sm text-gray-500 mt-0.5">Gestión de especificaciones y procesos de producción</p>
        </div>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex gap-4">
        <input
          type="text"
          className="flex-1 p-2.5 border border-gray-300 rounded-md focus:border-blue-500 text-sm"
          placeholder="Buscar por modelo o nombre..."
          value={search}
          onChange={e => setSearch(e.target.value)}
        />
        <button className="p-2.5 bg-gray-100 text-gray-600 rounded-md hover:bg-gray-200 transition-all border border-gray-200">
          <FunnelIcon className="h-5 w-5" />
        </button>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-sm">
            <thead className="bg-gray-50 text-gray-600 uppercase text-xs border-b border-gray-200">
              <tr>
                <th className="px-4 py-3">Modelo / Código</th>
                <th className="px-4 py-3">Producto / Cliente</th>
                <th className="px-4 py-3 text-center">Imagen</th>
                <th className="px-4 py-3 text-center">Estado</th>
                <th className="px-4 py-3 text-center">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-100">
              {loading ? (
                <tr><td colSpan="5" className="px-4 py-8 text-center text-gray-400">Cargando productos...</td></tr>
              ) : filteredProducts.length > 0 ? (
                filteredProducts.map((product) => (
                  <tr key={product.id} className="hover:bg-gray-50 transition-colors">
                    <td className="px-4 py-3 font-mono font-bold text-gray-800">{product.code}</td>
                    <td className="px-4 py-3">
                      <p className="font-medium text-gray-900">{product.name}</p>
                      <p className="text-[11px] text-gray-500 uppercase mt-0.5">{product.client?.name || 'Sin Cliente'}</p>
                    </td>
                    <td className="px-4 py-3">
                      <div className="w-10 h-10 bg-gray-50 rounded-lg overflow-hidden mx-auto border border-gray-200">
                        {product.image ? (
                          <img src={`https://peruvian.peruviandress.com/storage/products/${product.image}`} className="w-full h-full object-cover" alt="" />
                        ) : (
                          <div className="w-full h-full flex items-center justify-center text-gray-300"><EyeIcon className="h-5 w-5" /></div>
                        )}
                      </div>
                    </td>
                    <td className="px-4 py-3 text-center">
                      <span className={`px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ${product.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`}>
                        {product.is_active ? 'Activo' : 'Inactivo'}
                      </span>
                    </td>
                    <td className="px-4 py-3">
                      <div className="flex items-center justify-center">
                        <button
                          onClick={() => openFicha(product.code)}
                          className="flex items-center gap-2 px-4 py-2 bg-gray-800 text-white rounded-md text-xs font-bold hover:bg-gray-700 transition-all shadow-sm"
                        >
                          Ver Ficha
                          <ChevronRightIcon className="h-3 w-3" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan="5" className="px-4 py-20 text-center">
                    <div className="flex flex-col items-center gap-3">
                      <IdentificationIcon className="h-12 w-12 text-gray-200" />
                      <p className="text-gray-400 font-bold">No se encontraron productos</p>
                    </div>
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>

      {/* Tech Sheet Modal */}
      <TechSheetModal
        isOpen={isModalOpen}
        onClose={() => setIsModalOpen(false)}
        productCode={selectedProduct}
      />
    </div>
  );
}
