import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../../services/api';
import { EyeIcon, TrashIcon, XMarkIcon } from '@heroicons/react/24/outline';

const ESTADO_MAP = {
  0: { label: 'En Proceso', color: 'bg-yellow-100 text-yellow-800' },
  1: { label: 'Completado', color: 'bg-green-100 text-green-800' },
  2: { label: 'Cancelado', color: 'bg-red-100 text-red-800' },
};

export default function OrdersView() {
  const navigate = useNavigate();
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');

  // Modal Detail State
  const [showModalDetail, setShowModalDetail] = useState(false);
  const [selectedOrder, setSelectedOrder] = useState(null);
  const [orderDetail, setOrderDetail] = useState([]);
  const [loadingDetail, setLoadingDetail] = useState(false);

  useEffect(() => { fetchOrders(); }, []);

  const fetchOrders = async () => {
    try {
      setLoading(true);
      const res = await api.get('/transactions/orders');
      setOrders(res.data.Records || []);
    } catch (e) {
      console.error(e);
    } finally {
      setLoading(false);
    }
  };

  const handleViewDetail = async (order) => {
    setSelectedOrder(order);
    setShowModalDetail(true);
    setLoadingDetail(true);
    try {
      const res = await api.get(`/transactions/orders/${order.codigo}`);
      setOrderDetail(res.data.detalles || []);
    } catch (e) {
      console.error(e);
      alert('Error al cargar detalle');
    } finally {
      setLoadingDetail(false);
    }
  };

  const handleDelete = async (codigo) => {
    if (!window.confirm(`¿Eliminar la orden ${codigo}?`)) return;
    try {
      await api.delete(`/transactions/orders/${codigo}`);
      fetchOrders();
    } catch (e) {
      alert('Error al eliminar');
    }
  };

  const handleStatusChange = async (codigo, estado) => {
    try {
      await api.patch(`/transactions/orders/${codigo}/status`, { estado });
      fetchOrders();
    } catch (e) {
      alert('Error al actualizar estado');
    }
  };

  const filtered = orders.filter(o =>
    (o.codigo || '').toLowerCase().includes(search.toLowerCase()) ||
    (o.name || '').toLowerCase().includes(search.toLowerCase()) ||
    (o.nombre_modelo || '').toLowerCase().includes(search.toLowerCase()) ||
    (o.num_contrato || '').toLowerCase().includes(search.toLowerCase())
  );

  const SIZE_COLS = ['_2', '_4', '_6', '_8', '_10', '_12', '_14', '_16', 's', 'm', 'l', 'xl', 'xxl'];
  const PROD_COLS = ['p2', 'p4', 'p6', 'p8', 'p10', 'p12', 'p14', 'p16', 'ps', 'pm', 'pl', 'pxl', 'pxxl'];

  return (
    <div className="flex flex-col gap-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Órdenes de Pedido</h1>
          <p className="text-sm text-gray-500 mt-0.5">Gestión de producción de prendas</p>
        </div>
        <button
          onClick={() => navigate('/orders/new')}
          className="bg-gray-800 text-white px-5 py-2.5 rounded-md hover:bg-gray-700 shadow-sm font-medium transition-colors flex items-center gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" /></svg>
          Nuevo Pedido
        </button>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <input
          type="text"
          className="w-full p-2.5 border border-gray-300 rounded-md focus:border-blue-500 text-sm"
          placeholder="Buscar por código, cliente, modelo o contrato..."
          value={search}
          onChange={e => setSearch(e.target.value)}
        />
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-sm">
            <thead className="bg-gray-50 text-gray-600 uppercase text-xs border-b border-gray-200">
              <tr>
                <th className="px-4 py-3">Código</th>
                <th className="px-4 py-3">Contrato</th>
                <th className="px-4 py-3">Cliente</th>
                <th className="px-4 py-3">Modelo</th>
                <th className="px-4 py-3 text-center">Total</th>
                <th className="px-4 py-3">F. Creación</th>
                <th className="px-4 py-3">F. Entrega</th>
                <th className="px-4 py-3 text-center">Días Rest.</th>
                <th className="px-4 py-3 text-center">Estado</th>
                <th className="px-4 py-3 text-center">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-100">
              {loading && (
                <tr><td colSpan="10" className="px-4 py-8 text-center text-gray-400">Cargando pedidos...</td></tr>
              )}
              {!loading && filtered.length === 0 && (
                <tr><td colSpan="10" className="px-4 py-8 text-center text-gray-400">No hay órdenes registradas</td></tr>
              )}
              {filtered.map(order => {
                const estado = ESTADO_MAP[order.estado] || ESTADO_MAP[0];
                const diasRest = parseInt(order.dias_restantes);
                const diasColor = diasRest < 0 ? 'text-red-600 font-bold' : diasRest <= 3 ? 'text-orange-500 font-semibold' : 'text-gray-700';
                return (
                  <tr key={order.codigo} className="hover:bg-gray-50 transition-colors">
                    <td className="px-4 py-3 font-mono font-bold text-gray-800">{order.codigo}</td>
                    <td className="px-4 py-3 text-gray-600">{order.num_contrato || '-'}</td>
                    <td className="px-4 py-3 font-medium">{order.name}</td>
                    <td className="px-4 py-3">
                      <div className="flex items-center gap-2">
                        {order.imagen && (
                          <img
                            src={`http://localhost:8000/storage/products/${order.imagen}`}
                            alt={order.producto}
                            className="w-8 h-8 object-cover rounded border border-gray-200"
                            onError={e => { e.target.style.display = 'none'; }}
                          />
                        )}
                        <span className="text-gray-700 truncate max-w-xs">{order.codigo_modelo || order.nombre_modelo || '-'}</span>
                      </div>
                    </td>
                    <td className="px-4 py-3 text-center font-semibold">{order.totalp || order.total || 0}</td>
                    <td className="px-4 py-3 text-gray-600">{order.fecha_creacion ? new Date(order.fecha_creacion).toLocaleDateString('es-PE') : '-'}</td>
                    <td className="px-4 py-3 text-gray-600">{order.fecha_entrega ? new Date(order.fecha_entrega).toLocaleDateString('es-PE') : '-'}</td>
                    <td className={`px-4 py-3 text-center ${diasColor}`}>
                      {isNaN(diasRest) ? '-' : diasRest < 0 ? `${Math.abs(diasRest)}d tarde` : `${diasRest}d`}
                    </td>
                    <td className="px-4 py-3 text-center">
                      <select
                        value={order.estado}
                        onChange={e => handleStatusChange(order.codigo, parseInt(e.target.value))}
                        className={`text-xs px-2 py-1 rounded-full font-semibold cursor-pointer border-0 ${estado.color}`}
                      >
                        <option value={0}>En Proceso</option>
                        <option value={1}>Completado</option>
                        <option value={2}>Cancelado</option>
                      </select>
                    </td>
                    <td className="px-4 py-3">
                      <div className="flex items-center justify-center gap-2">
                        <button 
                          onClick={() => handleViewDetail(order)} 
                          title="Ver Detalle" 
                          className="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                        >
                          <EyeIcon className="h-5 w-5" />
                        </button>
                        <button 
                          onClick={() => handleDelete(order.codigo)} 
                          title="Eliminar" 
                          className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                        >
                          <TrashIcon className="h-5 w-5" />
                        </button>
                      </div>
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
      </div>

      {/* Modal Detalle Orden */}
      {showModalDetail && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
          <div className="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-[90vh] overflow-hidden flex flex-col animate-in fade-in zoom-in duration-200">
            <div className="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
              <div>
                <h2 className="text-xl font-extrabold text-gray-800 tracking-tight">
                  Detalle de Orden: <span className="text-blue-600 font-mono">{selectedOrder?.codigo}</span>
                </h2>
                <p className="text-sm text-gray-500 font-medium">{selectedOrder?.name}</p>
              </div>
              <button onClick={() => setShowModalDetail(false)} className="text-gray-400 hover:text-gray-600 transition-colors">
                <XMarkIcon className="h-6 w-6" />
              </button>
            </div>

            <div className="flex-1 overflow-auto p-6">
              {loadingDetail ? (
                <div className="flex flex-col items-center justify-center py-20 text-gray-400">
                  <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
                  <p className="font-medium tracking-wide">Cargando matriz de tallas...</p>
                </div>
              ) : (
                <div className="space-y-6">
                  {orderDetail.length > 0 ? (
                    <div className="overflow-x-auto rounded-xl border border-gray-100 shadow-sm">
                      <table className="w-full text-left text-sm border-collapse">
                        <thead className="bg-gray-50">
                          <tr>
                            <th rowSpan={2} className="px-4 py-3 font-bold text-gray-600 border-b border-r border-gray-200 text-center align-middle">Modelo / Color</th>
                            <th colSpan={13} className="px-4 py-2 font-bold text-gray-600 border-b border-gray-200 text-center">Cantidades por Talla</th>
                            <th rowSpan={2} className="px-4 py-3 font-bold text-gray-600 border-b border-gray-200 text-center align-middle bg-blue-50/50">Total</th>
                          </tr>
                          <tr className="bg-gray-50/50 text-[10px]">
                            {orderDetail[0] && Array.from({ length: 13 }).map((_, i) => (
                              <th key={i} className="px-2 py-2 font-extrabold text-blue-600 border-b border-gray-200 text-center uppercase tracking-tighter">
                                {orderDetail[0][`n${i + 1}`] || '-'}
                              </th>
                            ))}
                          </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                          {orderDetail.map((det, idx) => (
                            <React.Fragment key={idx}>
                              <tr className="hover:bg-gray-50/50">
                                <td className="px-4 py-3 border-r border-gray-100">
                                  <div className="font-bold text-gray-800">{det.modelo}</div>
                                  <div className="text-xs text-gray-500 uppercase">{det.color || 'Sin color'}</div>
                                </td>
                                {SIZE_COLS.map(col => (
                                  <td key={col} className="px-2 py-3 text-center font-medium text-gray-700">
                                    {det[col] || 0}
                                  </td>
                                ))}
                                <td className="px-4 py-3 text-center font-extrabold text-blue-600 bg-blue-50/30">
                                  {det.total || 0}
                                </td>
                              </tr>
                              <tr className="bg-gray-50/30 text-[11px]">
                                <td className="px-4 py-2 border-r border-gray-100 font-bold text-green-600 italic">PRODUCIDOS</td>
                                {PROD_COLS.map(col => (
                                  <td key={col} className="px-2 py-2 text-center font-bold text-green-600">
                                    {det[col] || 0}
                                  </td>
                                ))}
                                <td className="px-4 py-2 text-center font-extrabold text-green-600 bg-green-50/30">
                                  {det.ptotal || 0}
                                </td>
                              </tr>
                            </React.Fragment>
                          ))}
                        </tbody>
                      </table>
                    </div>
                  ) : (
                    <div className="text-center py-10 text-gray-400 font-medium">No hay detalles disponibles para esta orden.</div>
                  )}

                  {selectedOrder?.comentario && (
                    <div className="bg-amber-50 border border-amber-100 rounded-xl p-4">
                      <h4 className="text-xs font-bold text-amber-800 uppercase tracking-wider mb-2">Comentario / Observaciones:</h4>
                      <p className="text-amber-900 text-sm italic">{selectedOrder.comentario}</p>
                    </div>
                  )}
                </div>
              )}
            </div>

            <div className="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end">
              <button 
                onClick={() => setShowModalDetail(false)}
                className="bg-white border border-gray-200 text-gray-700 px-6 py-2 rounded-xl hover:bg-gray-100 transition-all font-bold text-sm shadow-sm"
              >
                Cerrar Detalle
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
