import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../services/api';
import {
   PlusIcon,
   TrashIcon,
   ArrowLeftIcon,
   MagnifyingGlassIcon,
   XMarkIcon,
   PlusCircleIcon,
   CalculatorIcon,
   DocumentTextIcon
} from '@heroicons/react/24/outline';

export default function NewPurchaseView() {
   const navigate = useNavigate();
   const [loading, setLoading] = useState(false);
   const [providers, setProviders] = useState([]);
   const [docTypes, setDocTypes] = useState([]);
   const [paymentMethods, setPaymentMethods] = useState([]);
   const [unidades, setUnidades] = useState([]);

   // Header state
   const [formData, setFormData] = useState({
      id_proveedor: '',
      fecha_creacion: new Date().toISOString().split('T')[0],
      fproceso: new Date().toISOString().split('T')[0],
      tipo_documento: '2', // Default to factura
      id_forma_pago: '1', // Default to contado
      serie: '',
      numeracion: '',
      codigo_compra: '',
      total: 0,
      igv: 0,
      gravado: 0,
      exonerado: 0,
      otros_no_gravado: 0,
      fecha_detraccion: '',
      numero_detraccion: '',
      tipo_cambio: '',
      fecha_comprobante: '',
      serie_comprobante: '',
      documento_comprobante: ''
   });

   // Items state
   const [items, setItems] = useState([]);
   const [searchTerm, setSearchTerm] = useState('');
   const [searchResults, setSearchResults] = useState([]);
   const [searching, setSearching] = useState(false);

   useEffect(() => {
      fetchInitialData();
   }, []);

   const fetchInitialData = async () => {
      try {
         const [provRes, docRes, payRes, uniRes] = await Promise.all([
            api.get('/insumos/providers'),
            api.get('/purchases/tipos-documento'),
            api.get('/forma-pago'),
            api.get('/insumos/unidades')
         ]);
         setProviders(provRes.data.Records || []);
         setDocTypes(docRes.data || []);
         setPaymentMethods(payRes.data || []);
         setUnidades(uniRes.data.Records || []);
      } catch (e) { console.error(e); }
   };

   const handleSearch = async (term) => {
      setSearchTerm(term);
      if (term.length < 2) {
         setSearchResults([]);
         return;
      }
      setSearching(true);
      try {
         const r = await api.get(`/insumos?q=${term}`);
         setSearchResults(r.data.Records || []);
      } catch (e) { console.error(e); }
      finally { setSearching(false); }
   };

   const addItem = (insumo) => {
      const exists = items.find(i => i.id_insumo === insumo.id);
      if (exists) return;

      setItems([...items, {
         id_insumo: insumo.id,
         nombre: insumo.insumo,
         codigo: insumo.codigo,
         precio: 0,
         cantidad: 1,
         total: 0,
         unidad: insumo.unidad || 'UND'
      }]);
      setSearchTerm('');
      setSearchResults([]);
   };

   const removeItem = (id) => {
      setItems(items.filter(i => i.id_insumo !== id));
   };

   const updateItem = (id, field, value) => {
      const updated = items.map(item => {
         if (item.id_insumo === id) {
            const newItem = { ...item, [field]: value };
            if (field === 'precio' || field === 'cantidad') {
               newItem.total = (parseFloat(newItem.precio) || 0) * (parseFloat(newItem.cantidad) || 0);
            }
            return newItem;
         }
         return item;
      });
      setItems(updated);
   };

   const calculateTotals = () => {
      const totalItems = items.reduce((acc, i) => acc + (parseFloat(i.total) || 0), 0);
      const total = totalItems + (parseFloat(formData.otros_no_gravado) || 0);
      const base = total / 1.18;
      const igv = total - base;

      setFormData({
         ...formData,
         total: total.toFixed(2),
         igv: igv.toFixed(2),
         gravado: base.toFixed(2)
      });
   };

   const handleSubmit = async (e) => {
      e.preventDefault();
      if (!formData.id_proveedor) return alert('Selecciona un proveedor');
      if (items.length === 0) return alert('Agrega al menos un producto');

      setLoading(true);
      try {
         const payload = {
            ...formData,
            items,
            codigo: `${formData.id_proveedor}-${formData.codigo_compra || Date.now()}`
         };
         await api.post('/purchases', payload);
         navigate('/purchases');
      } catch (e) {
         alert(e.response?.data?.Message || 'Error al guardar la compra');
      } finally { setLoading(false); }
   };

   return (
      <div className="flex flex-col gap-6 animate-in fade-in duration-700 h-full pb-20">
         <div className="flex items-center gap-4">
            <button onClick={() => navigate('/purchases')} className="p-2 hover:bg-gray-100 rounded-lg transition-all text-gray-500">
               <ArrowLeftIcon className="h-6 w-6" />
            </button>
            <div>
               <h1 className="text-2xl font-black text-gray-900 uppercase tracking-tighter">Nueva Compra</h1>
               <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Formulario de adquisición de insumos</p>
            </div>
         </div>

         <form onSubmit={handleSubmit} className="grid grid-cols-12 gap-6">
            {/* Left Column: Header Info */}
            <div className="col-span-12 lg:col-span-8 space-y-6">
               <div className="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm space-y-6">
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                     <div className="flex flex-col gap-2">
                        <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Proveedor</label>
                        <select
                           required
                           className="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-blue-500 outline-none transition-all font-bold"
                           value={formData.id_proveedor}
                           onChange={e => setFormData({ ...formData, id_proveedor: e.target.value })}
                        >
                           <option value="">Selecciona un proveedor</option>
                           {providers.map(p => <option key={p.id} value={p.id}>{p.name}</option>)}
                        </select>
                     </div>
                     <div className="flex flex-col gap-2">
                        <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Fecha de Compra</label>
                        <input
                           type="date"
                           className="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-blue-500 outline-none font-mono"
                           value={formData.fecha_creacion}
                           onChange={e => setFormData({ ...formData, fecha_creacion: e.target.value })}
                        />
                     </div>
                  </div>

                  {/* Product Search */}
                  <div className="relative">
                     <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Agregar Insumos</label>
                     <div className="relative">
                        <MagnifyingGlassIcon className="h-5 w-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" />
                        <input
                           type="text"
                           className="w-full pl-12 pr-4 py-4 bg-gray-900 text-white rounded-2xl text-sm outline-none placeholder:text-gray-500 focus:ring-4 focus:ring-blue-500/20 transition-all font-bold"
                           placeholder="Buscar por nombre o código..."
                           value={searchTerm}
                           onChange={e => handleSearch(e.target.value)}
                        />
                        {searching && <div className="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 border-2 border-white/20 border-t-white rounded-full animate-spin"></div>}
                     </div>

                     {searchResults.length > 0 && (
                        <div className="absolute z-10 w-full mt-2 bg-white border border-gray-200 rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                           {searchResults.map(item => (
                              <button
                                 key={item.id}
                                 type="button"
                                 onClick={() => addItem(item)}
                                 className="w-full flex items-center justify-between px-6 py-4 hover:bg-blue-50 transition-colors border-b border-gray-50 last:border-0"
                              >
                                 <div className="text-left">
                                    <p className="text-sm font-bold text-gray-900 uppercase">{item.insumo}</p>
                                    <p className="text-[10px] text-gray-400 font-black">{item.codigo}</p>
                                 </div>
                                 <PlusCircleIcon className="h-6 w-6 text-blue-500" />
                              </button>
                           ))}
                        </div>
                     )}
                  </div>

                  {/* Items Table */}
                  <div className="border border-gray-100 rounded-2xl overflow-hidden mt-6">
                     <table className="w-full text-left">
                        <thead className="bg-gray-50 border-b border-gray-100">
                           <tr className="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                              <th className="px-6 py-4">Insumo</th>
                              <th className="px-4 py-4 text-center">Unidad</th>
                              <th className="px-4 py-4 text-center">Cant.</th>
                              <th className="px-4 py-4 text-right">Precio</th>
                              <th className="px-4 py-4 text-right">Total</th>
                              <th className="px-4 py-4 text-center"></th>
                           </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100 font-bold">
                           {items.length === 0 ? (
                              <tr>
                                 <td colSpan="6" className="px-6 py-20 text-center text-gray-300 italic uppercase font-black text-[10px]">No hay items agregados</td>
                              </tr>
                           ) : items.map(item => (
                              <tr key={item.id_insumo}>
                                 <td className="px-6 py-4">
                                    <div className="flex flex-col">
                                       <span className="text-sm text-gray-900">{item.nombre}</span>
                                       <span className="text-[10px] text-gray-400 font-mono">{item.codigo}</span>
                                    </div>
                                 </td>
                                 <td className="px-4 py-4">
                                    <select
                                       className="w-24 p-2 bg-gray-50 border border-gray-200 rounded-lg text-xs outline-none"
                                       value={item.unidad}
                                       onChange={e => updateItem(item.id_insumo, 'unidad', e.target.value)}
                                    >
                                       {unidades.map(u => <option key={u.codigo} value={u.codigo}>{u.unidad}</option>)}
                                    </select>
                                 </td>
                                 <td className="px-4 py-4">
                                    <input
                                       type="number" step="0.01"
                                       className="w-20 p-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-center outline-none focus:border-blue-500"
                                       value={item.cantidad}
                                       onChange={e => updateItem(item.id_insumo, 'cantidad', e.target.value)}
                                    />
                                 </td>
                                 <td className="px-4 py-4">
                                    <input
                                       type="number" step="0.01"
                                       className="w-24 p-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-right outline-none focus:border-blue-500 font-mono"
                                       value={item.precio}
                                       onChange={e => updateItem(item.id_insumo, 'precio', e.target.value)}
                                    />
                                 </td>
                                 <td className="px-4 py-4 text-right text-sm text-blue-600 font-black font-mono">
                                    S/ {parseFloat(item.total).toFixed(2)}
                                 </td>
                                 <td className="px-4 py-4 text-center">
                                    <button onClick={() => removeItem(item.id_insumo)} className="p-2 text-gray-400 hover:text-red-500 transition-colors">
                                       <TrashIcon className="h-5 w-5" />
                                    </button>
                                 </td>
                              </tr>
                           ))}
                        </tbody>
                     </table>
                  </div>
               </div>

               {/* Detraction & Ref Section */}
               <div className="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm">
                  <h3 className="text-xs font-black text-gray-900 uppercase tracking-widest mb-6 flex items-center gap-2 border-b border-gray-100 pb-4">
                     <DocumentTextIcon className="h-5 w-5 text-amber-500" />
                     Información Adicional (Detracción y Referencia)
                  </h3>
                  <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                     <div className="flex flex-col gap-2">
                        <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Constancia Fecha</label>
                        <input type="date" className="p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none font-mono" value={formData.fecha_detraccion} onChange={e => setFormData({ ...formData, fecha_detraccion: e.target.value })} />
                     </div>
                     <div className="flex flex-col gap-2">
                        <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Detracción Número</label>
                        <input type="text" className="p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none font-bold" placeholder="Nº ..." value={formData.numero_detraccion} onChange={e => setFormData({ ...formData, numero_detraccion: e.target.value })} />
                     </div>
                     <div className="flex flex-col gap-2">
                        <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tipo de Cambio</label>
                        <input type="number" step="0.001" className="p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none font-mono" placeholder="0.000" value={formData.tipo_cambio} onChange={e => setFormData({ ...formData, tipo_cambio: e.target.value })} />
                     </div>
                  </div>
                  <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                     <div className="flex flex-col gap-2">
                        <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Ref. Fecha</label>
                        <input type="date" className="p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none font-mono" value={formData.fecha_comprobante} onChange={e => setFormData({ ...formData, fecha_comprobante: e.target.value })} />
                     </div>
                     <div className="flex flex-col gap-2">
                        <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Ref. Serie</label>
                        <input type="text" className="p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none font-bold" placeholder="F001" value={formData.serie_comprobante} onChange={e => setFormData({ ...formData, serie_comprobante: e.target.value })} />
                     </div>
                     <div className="flex flex-col gap-2">
                        <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Ref. Documento</label>
                        <input type="text" className="p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none font-bold" placeholder="000123" value={formData.documento_comprobante} onChange={e => setFormData({ ...formData, documento_comprobante: e.target.value })} />
                     </div>
                  </div>
               </div>
            </div>

            {/* Right Column: Totals and Save */}
            <div className="col-span-12 lg:col-span-4 space-y-6">
               <div className="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm sticky top-6">
                  <h3 className="text-xs font-black text-gray-900 uppercase tracking-widest mb-6 border-b border-gray-100 pb-4 flex items-center gap-2">
                     <CalculatorIcon className="h-5 w-5 text-blue-500" />
                     Resumen de Documento
                  </h3>

                  <div className="space-y-4 mb-8">
                     <div className="flex flex-col gap-2">
                        <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tipo de Documento</label>
                        <select
                           className="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none font-bold"
                           value={formData.tipo_documento}
                           onChange={e => setFormData({ ...formData, tipo_documento: e.target.value })}
                        >
                           {docTypes.map(d => <option key={d.id} value={d.id}>{d.tipo_documento}</option>)}
                        </select>
                     </div>
                     <div className="flex flex-col gap-2">
                        <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Forma de Pago</label>
                        <select
                           className="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none font-bold"
                           value={formData.id_forma_pago}
                           onChange={e => setFormData({ ...formData, id_forma_pago: e.target.value })}
                        >
                           {paymentMethods.map(p => <option key={p.id} value={p.id}>{p.name}</option>)}
                        </select>
                     </div>
                     <div className="grid grid-cols-2 gap-4">
                        <div className="flex flex-col gap-2">
                           <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Serie</label>
                           <input type="text" className="p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none font-bold" placeholder="F001" value={formData.serie} onChange={e => setFormData({ ...formData, serie: e.target.value })} />
                        </div>
                        <div className="flex flex-col gap-2">
                           <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Número</label>
                           <input type="text" className="p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none font-bold" placeholder="00123" value={formData.numeracion} onChange={e => setFormData({ ...formData, numeracion: e.target.value })} />
                        </div>
                     </div>
                     <div className="flex flex-col gap-2">
                        <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Otros no Gravados</label>
                        <input type="number" step="0.01" className="p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none font-mono font-bold" value={formData.otros_no_gravado} onChange={e => setFormData({ ...formData, otros_no_gravado: e.target.value })} />
                     </div>
                  </div>

                  <div className="bg-gray-900 rounded-2xl p-6 text-white space-y-4 shadow-xl">
                     <div className="flex justify-between items-center text-xs opacity-60 font-black uppercase tracking-widest">
                        <span>Subtotal Base</span>
                        <span>S/ {formData.gravado}</span>
                     </div>
                     <div className="flex justify-between items-center text-xs opacity-60 font-black uppercase tracking-widest">
                        <span>IGV (18%)</span>
                        <span>S/ {formData.igv}</span>
                     </div>
                     <div className="h-px bg-white/10 my-2"></div>
                     <div className="flex justify-between items-end">
                        <span className="text-[10px] font-black uppercase tracking-widest opacity-80">Total Documento</span>
                        <span className="text-3xl font-black text-blue-400 tracking-tighter">S/ {formData.total}</span>
                     </div>
                     <button
                        type="button"
                        onClick={calculateTotals}
                        className="w-full mt-4 bg-white/10 hover:bg-white/20 text-white py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all"
                     >
                        Recalcular Totales
                     </button>
                  </div>

                  <button
                     type="submit"
                     disabled={loading}
                     className="w-full mt-6 bg-blue-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg hover:bg-blue-700 transition-all disabled:opacity-50"
                  >
                     {loading ? 'Procesando...' : 'Guardar Compra'}
                  </button>
               </div>
            </div>
         </form>
      </div>
   );
}
