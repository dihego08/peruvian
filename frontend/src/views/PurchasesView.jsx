import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../services/api';
import { 
  PlusIcon, 
  MagnifyingGlassIcon, 
  EyeIcon, 
  TrashIcon, 
  FunnelIcon,
  XMarkIcon,
  DocumentTextIcon,
  ArrowDownTrayIcon
} from '@heroicons/react/24/outline';

export default function PurchasesView() {
  const navigate = useNavigate();
  const [purchases, setPurchases] = useState([]);
  const [loading, setLoading] = useState(true);
  const [providers, setProviders] = useState([]);
  const [docTypes, setDocTypes] = useState([]);
  const [paymentMethods, setPaymentMethods] = useState([]);
  const [showFilters, setShowFilters] = useState(false);
  
  // Filters
  const [filters, setFilters] = useState({
    desde: '',
    hasta: '',
    id_proveedor: '0',
    tipo_documento: '-1',
    tipo_pago: '-1'
  });

  const [selectedPurchase, setSelectedPurchase] = useState(null);
  const [showDetailModal, setShowDetailModal] = useState(false);

  useEffect(() => {
    fetchPurchases();
    fetchInitialData();
  }, []);

  const fetchInitialData = async () => {
    try {
      const [provRes, docRes, payRes] = await Promise.all([
        api.get('/insumos/providers'),
        api.get('/purchases/tipos-documento'),
        api.get('/forma-pago')
      ]);
      setProviders(provRes.data.Records || []);
      setDocTypes(docRes.data || []);
      setPaymentMethods(payRes.data || []);
    } catch (e) { console.error(e); }
  };

  const fetchPurchases = async () => {
    setLoading(true);
    try {
      const queryParams = new URLSearchParams(filters).toString();
      const r = await api.get(`/purchases?${queryParams}`);
      setPurchases(r.data.Records || []);
    } catch (e) { console.error(e); }
    finally { setLoading(false); }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('¿Está seguro de eliminar esta compra? Esta acción revertirá el stock.')) return;
    try {
      await api.delete(`/purchases/${id}`);
      fetchPurchases();
    } catch (e) { alert('Error al eliminar'); }
  };

  const openDetail = async (id) => {
    try {
      const r = await api.get(`/purchases/${id}`);
      setSelectedPurchase(r.data.Record);
      setShowDetailModal(true);
    } catch (e) { console.error(e); }
  };

  return (
    <div className="flex flex-col gap-6 animate-in fade-in duration-700">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-bold text-gray-900 flex items-center gap-2">
            Registros de Compra
          </h1>
          <p className="text-sm text-gray-500 font-medium italic">Historial de adquisiciones e inventario</p>
        </div>
        <div className="flex items-center gap-3">
          <button 
            onClick={() => setShowFilters(!showFilters)}
            className={`p-2 rounded-lg border transition-all ${showFilters ? 'bg-blue-50 border-blue-200 text-blue-600' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'}`}
          >
            <FunnelIcon className="h-5 w-5" />
          </button>
          <button 
            onClick={() => navigate('/purchases/new')}
            className="flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-all shadow-sm font-bold text-sm"
          >
            <PlusIcon className="h-5 w-5" />
            NUEVA COMPRA
          </button>
        </div>
      </div>

      {showFilters && (
        <div className="bg-white p-6 rounded-xl border border-gray-200 shadow-sm animate-in slide-in-from-top duration-300">
           <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
              <div className="flex flex-col gap-1">
                 <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Desde</label>
                 <input type="date" className="p-2 border border-gray-200 rounded-lg text-sm focus:border-blue-500 outline-none" value={filters.desde} onChange={e => setFilters({...filters, desde: e.target.value})} />
              </div>
              <div className="flex flex-col gap-1">
                 <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Hasta</label>
                 <input type="date" className="p-2 border border-gray-200 rounded-lg text-sm focus:border-blue-500 outline-none" value={filters.hasta} onChange={e => setFilters({...filters, hasta: e.target.value})} />
              </div>
              <div className="flex flex-col gap-1">
                 <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Proveedor</label>
                 <select className="p-2 border border-gray-200 rounded-lg text-sm outline-none" value={filters.id_proveedor} onChange={e => setFilters({...filters, id_proveedor: e.target.value})}>
                    <option value="0">TODOS</option>
                    {providers.map(p => <option key={p.id} value={p.id}>{p.name}</option>)}
                 </select>
              </div>
              <div className="flex flex-col gap-1">
                 <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Doc.</label>
                 <select className="p-2 border border-gray-200 rounded-lg text-sm outline-none" value={filters.tipo_documento} onChange={e => setFilters({...filters, tipo_documento: e.target.value})}>
                    <option value="-1">TODOS</option>
                    {docTypes.map(d => <option key={d.id} value={d.id}>{d.tipo_documento}</option>)}
                 </select>
              </div>
              <div className="flex items-end gap-2">
                 <button onClick={fetchPurchases} className="flex-1 bg-blue-600 text-white py-2 rounded-lg font-bold text-sm hover:bg-blue-700 transition-all">Filtrar</button>
                 <button onClick={() => { setFilters({desde:'', hasta:'', id_proveedor:'0', tipo_documento:'-1', tipo_pago:'-1'}); fetchPurchases(); }} className="p-2 text-gray-400 hover:text-red-500"><XMarkIcon className="h-5 w-5" /></button>
              </div>
           </div>
        </div>
      )}

      <div className="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-gray-50 border-b border-gray-200">
                <th className="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Cod/Fec</th>
                <th className="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Proveedor</th>
                <th className="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Documento</th>
                <th className="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Total</th>
                <th className="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-100">
              {loading ? (
                <tr>
                  <td colSpan="5" className="px-6 py-10 text-center text-gray-400 font-bold text-xs uppercase italic">Cargando registros...</td>
                </tr>
              ) : purchases.length === 0 ? (
                <tr>
                  <td colSpan="5" className="px-6 py-10 text-center text-gray-400 font-bold text-xs uppercase italic">No se encontraron compras</td>
                </tr>
              ) : purchases.map((purchase) => (
                <tr key={purchase.id} className="hover:bg-gray-50/50 transition-colors group">
                  <td className="px-6 py-4">
                    <div className="flex flex-col">
                      <span className="text-sm font-bold text-gray-900">#{purchase.id}</span>
                      <span className="text-[10px] text-gray-500 font-mono">{purchase.fecha_creacion}</span>
                    </div>
                  </td>
                  <td className="px-6 py-4">
                    <div className="flex flex-col">
                      <span className="text-sm font-bold text-gray-800">{purchase.provider?.name || purchase.proveedor || 'Sin Proveedor'}</span>
                      <span className="text-[10px] text-gray-400 font-mono">{purchase.provider?.no || 'N/A'}</span>
                    </div>
                  </td>
                  <td className="px-6 py-4 text-center">
                    <span className="px-2 py-1 bg-gray-100 text-gray-600 rounded-md text-[10px] font-black uppercase">
                      {purchase.serie}-{purchase.numeracion}
                    </span>
                  </td>
                  <td className="px-6 py-4 text-right">
                    <span className="text-sm font-black text-gray-900">S/ {parseFloat(purchase.total).toFixed(2)}</span>
                  </td>
                  <td className="px-6 py-4">
                    <div className="flex justify-center items-center gap-2">
                      <button 
                        onClick={() => openDetail(purchase.id)}
                        className="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all shadow-sm border border-transparent hover:border-blue-100"
                      >
                        <EyeIcon className="h-5 w-5" />
                      </button>
                      <button 
                        onClick={() => handleDelete(purchase.id)}
                        className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all shadow-sm border border-transparent hover:border-red-100"
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

      {/* Detail Modal */}
      {showDetailModal && selectedPurchase && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm p-4">
          <div className="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden animate-in zoom-in duration-200">
             <div className="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <div>
                   <h3 className="font-black text-gray-900 uppercase tracking-tighter text-xl flex items-center gap-2">
                      Detalle de Compra <span className="text-blue-600 font-mono">#{selectedPurchase.id}</span>
                   </h3>
                   <p className="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Documento: {selectedPurchase.serie}-{selectedPurchase.numeracion}</p>
                </div>
                <button onClick={() => setShowDetailModal(false)} className="text-gray-400 hover:text-gray-600 transition-colors">
                  <XMarkIcon className="h-6 w-6" />
                </button>
             </div>
             
             <div className="p-8">
                <div className="grid grid-cols-2 gap-8 mb-8">
                   <div className="space-y-4">
                      <div>
                         <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Proveedor</label>
                         <p className="text-sm font-bold text-gray-800">{selectedPurchase.provider?.name || selectedPurchase.proveedor}</p>
                         <p className="text-xs text-gray-500">{selectedPurchase.provider?.no}</p>
                         <p className="text-xs text-gray-500">{selectedPurchase.provider?.address1}</p>
                      </div>
                      <div className="grid grid-cols-2 gap-4">
                         <div>
                            <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Fec. Emisión</label>
                            <p className="text-sm font-bold text-gray-800 italic font-mono">{selectedPurchase.fecha_creacion}</p>
                         </div>
                         <div>
                            <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Fec. Registro</label>
                            <p className="text-sm font-bold text-gray-800 italic font-mono">{selectedPurchase.fproceso || 'N/A'}</p>
                         </div>
                      </div>
                   </div>
                   <div className="bg-gray-50 p-6 rounded-2xl border border-gray-100 flex flex-col justify-center">
                      <div className="flex justify-between items-center mb-2">
                         <span className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Subtotal Gravado</span>
                         <span className="text-sm font-bold text-gray-600">S/ {parseFloat(selectedPurchase.gravado).toFixed(2)}</span>
                      </div>
                      <div className="flex justify-between items-center mb-2">
                         <span className="text-[10px] font-black text-gray-400 uppercase tracking-widest">IGV (18%)</span>
                         <span className="text-sm font-bold text-gray-600">S/ {parseFloat(selectedPurchase.igv).toFixed(2)}</span>
                      </div>
                      {parseFloat(selectedPurchase.exonerado) > 0 && (
                        <div className="flex justify-between items-center mb-2">
                          <span className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Exonerado</span>
                          <span className="text-sm font-bold text-gray-600">S/ {parseFloat(selectedPurchase.exonerado).toFixed(2)}</span>
                        </div>
                      )}
                      <div className="h-px bg-gray-200 my-2"></div>
                      <div className="flex justify-between items-center">
                         <span className="text-xs font-black text-gray-900 uppercase">Total Compra</span>
                         <span className="text-2xl font-black text-blue-600">S/ {parseFloat(selectedPurchase.total).toFixed(2)}</span>
                      </div>
                   </div>
                </div>

                <div className="border border-gray-100 rounded-xl overflow-hidden">
                   <table className="w-full text-left text-sm">
                      <thead className="bg-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                         <tr>
                            <th className="px-4 py-3">Item / Insumo</th>
                            <th className="px-4 py-3 text-center">Unidad</th>
                            <th className="px-4 py-3 text-center">Cantidad</th>
                            <th className="px-4 py-3 text-right">Precio Unit.</th>
                            <th className="px-4 py-3 text-right">Total</th>
                         </tr>
                      </thead>
                      <tbody className="divide-y divide-gray-50">
                         {selectedPurchase.details?.map((item) => (
                           <tr key={item.id} className="hover:bg-gray-50/50 transition-colors">
                              <td className="px-4 py-3">
                                 <div className="flex flex-col">
                                    <span className="font-bold text-gray-800">{item.insumo?.insumo}</span>
                                    <span className="text-[10px] text-gray-400 font-mono">{item.insumo?.codigo}</span>
                                 </div>
                              </td>
                              <td className="px-4 py-3 text-center text-gray-500 font-bold">{item.unidad}</td>
                              <td className="px-4 py-3 text-center font-bold text-gray-700">{item.cantidad}</td>
                              <td className="px-4 py-3 text-right font-mono">S/ {parseFloat(item.precio).toFixed(2)}</td>
                              <td className="px-4 py-3 text-right font-black text-gray-900">S/ {parseFloat(item.total).toFixed(2)}</td>
                           </tr>
                         ))}
                      </tbody>
                   </table>
                </div>

                {selectedPurchase.numero_detraccion && (
                   <div className="mt-6 p-4 bg-amber-50 border border-amber-100 rounded-xl">
                      <h4 className="text-[10px] font-black text-amber-600 uppercase mb-2">Información de Detracción</h4>
                      <div className="grid grid-cols-3 gap-4">
                         <div>
                            <span className="text-[10px] text-amber-500 uppercase">Nº Constancia</span>
                            <p className="text-sm font-bold text-amber-900">{selectedPurchase.numero_detraccion}</p>
                         </div>
                         <div>
                            <span className="text-[10px] text-amber-500 uppercase">Fecha</span>
                            <p className="text-sm font-bold text-amber-900 font-mono">{selectedPurchase.fecha_detraccion}</p>
                         </div>
                         <div>
                            <span className="text-[10px] text-amber-500 uppercase">T. Cambio</span>
                            <p className="text-sm font-bold text-amber-900">{selectedPurchase.tipo_cambio}</p>
                         </div>
                      </div>
                   </div>
                )}
             </div>
             
             <div className="px-8 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <button onClick={() => setShowDetailModal(false)} className="px-6 py-2 text-gray-500 font-bold text-sm">Cerrar</button>
                <button className="px-6 py-2 bg-gray-900 text-white rounded-lg font-bold text-sm hover:bg-gray-800 shadow-md flex items-center gap-2">
                   <ArrowDownTrayIcon className="h-4 w-4" />
                   Descargar PDF
                </button>
             </div>
          </div>
        </div>
      )}
    </div>
  );
}
