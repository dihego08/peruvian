import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../../services/api';
import { getProductImageUrl, handleProductImageError } from '../../utils/image';
import { EyeIcon, TrashIcon, XMarkIcon, PencilIcon, PlusIcon } from '@heroicons/react/24/outline';
import * as XLSX from 'xlsx';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';
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

  // Modal Image State
  const [expandedImage, setExpandedImage] = useState(null);
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

  let pedidosTiempo = 0;
  let pedidosFuera = 0;

  filtered.forEach(o => {
    if (o.fecha_entrega_real) {
      if (o.fecha_entrega_real <= o.fecha_entrega) pedidosTiempo++;
      else pedidosFuera++;
    } else {
      if (parseInt(o.dias_restantes) >= 0) pedidosTiempo++;
      else pedidosFuera++;
    }
  });

  const exportToExcel = () => {
    const dataToExport = filtered.map(order => ({
      'Pedido': order.codigo,
      'F. Creación': order.fecha_creacion,
      'Cliente': order.name,
      'Descripción': order.nombre_modelo || order.producto,
      'Cod. Modelo': order.codigo_unitario || order.codigo_modelo,
      'Modelo': order.nombre_modelo,
      'N° Contrato': order.num_contrato,
      'Cant. Pedido': order.total,
      'Cant. Producción': order.totalp,
      'Guía Remisión': order.guia_remision,
      'Documento': order.codigo_venta,
      'Fec. Est. Entrega': order.fecha_entrega,
      'Fec. Entrega': order.fecha_entrega_real
    }));

    const worksheet = XLSX.utils.json_to_sheet(dataToExport);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Pedidos");
    XLSX.writeFile(workbook, "pedidos_export.xlsx");
  };

  const exportToPDF = () => {
    const doc = new jsPDF('landscape');
    doc.text("Reporte de Pedidos", 14, 15);

    const tableColumn = ["Código", "Fecha", "Cliente", "Modelo", "Contrato", "Cant", "Prod", "F. Estimada", "F. Real", "Guías"];
    const tableRows = [];

    filtered.forEach(order => {
      const rowData = [
        order.codigo,
        order.fecha_creacion ? new Date(order.fecha_creacion).toLocaleDateString('es-PE') : '-',
        order.name,
        order.nombre_modelo || order.producto || '-',
        order.num_contrato || '-',
        order.total || 0,
        order.totalp || 0,
        order.fecha_entrega ? new Date(order.fecha_entrega).toLocaleDateString('es-PE') : '-',
        order.fecha_entrega_real ? new Date(order.fecha_entrega_real).toLocaleDateString('es-PE') : '-',
        (order.guia_remision || '').split(' - ').join(', ')
      ];
      tableRows.push(rowData);
    });

    autoTable(doc, {
      head: [tableColumn],
      body: tableRows,
      startY: 20,
      styles: { fontSize: 8 },
      headStyles: { fillColor: [31, 41, 55] }
    });

    doc.save("pedidos_export.pdf");
  };

  const SIZE_COLS = ['_2', '_4', '_6', '_8', '_10', '_12', '_14', '_16', 's', 'm', 'l', 'xl', 'xxl'];
  const PROD_COLS = ['p2', 'p4', 'p6', 'p8', 'p10', 'p12', 'p14', 'p16', 'ps', 'pm', 'pl', 'pxl', 'pxxl'];

  return (
    <div className="flex flex-col gap-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Órdenes de Pedido</h1>
          <p className="text-sm text-gray-500 mt-0.5">Gestión de producción de prendas</p>
        </div>
        <div className="flex items-center gap-3">
          <button
            onClick={exportToExcel}
            title="Exportar a Excel"
            className="bg-green-600 text-white px-4 py-2.5 rounded-md hover:bg-green-700 shadow-sm font-medium transition-colors flex items-center gap-2"
          >
            <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} strokeLinecap="round" strokeLinejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" /><polyline points="14 2 14 8 20 8" /><line x1="8" y1="13" x2="16" y2="17" /><line x1="16" y1="13" x2="8" y2="17" /></svg>
            Excel
          </button>
          <button
            onClick={exportToPDF}
            title="Exportar a PDF"
            className="bg-red-600 text-white px-4 py-2.5 rounded-md hover:bg-red-700 shadow-sm font-medium transition-colors flex items-center gap-2"
          >
            <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} strokeLinecap="round" strokeLinejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" /><polyline points="14 2 14 8 20 8" /><path d="M16 13H8" /><path d="M16 17H8" /><path d="M10 9H8" /></svg>
            PDF
          </button>
          <button
            onClick={() => navigate('/orders/new')}
            className="bg-gray-800 text-white px-5 py-2.5 rounded-md hover:bg-gray-700 shadow-sm font-medium transition-colors flex items-center gap-2"
          >
            <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" /></svg>
            Nuevo Pedido
          </button>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div className="bg-blue-50 border border-blue-100 rounded-xl p-4 flex flex-col justify-center items-center shadow-sm hover:scale-[1.02] transition-transform duration-300">
          <span className="text-xs font-bold text-blue-800 uppercase tracking-wider mb-1">Total Pedidos</span>
          <span className="text-3xl font-black text-blue-600 drop-shadow-sm">{filtered.length}</span>
        </div>
        <div className="bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex flex-col justify-center items-center shadow-sm hover:scale-[1.02] transition-transform duration-300">
          <span className="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-1">Entregados / En Tiempo</span>
          <span className="text-3xl font-black text-emerald-600 drop-shadow-sm">{pedidosTiempo}</span>
        </div>
        <div className="bg-rose-50 border border-rose-100 rounded-xl p-4 flex flex-col justify-center items-center shadow-sm hover:scale-[1.02] transition-transform duration-300">
          <span className="text-xs font-bold text-rose-800 uppercase tracking-wider mb-1">Fuera de Tiempo</span>
          <span className="text-3xl font-black text-rose-600 drop-shadow-sm">{pedidosFuera}</span>
        </div>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <input
          type="text"
          className="w-full p-2.5 border border-gray-300 rounded-md focus:border-blue-500 text-sm focus:ring-1 focus:ring-blue-500 transition-all outline-none"
          placeholder="Buscar por código, cliente, modelo o contrato..."
          value={search}
          onChange={e => setSearch(e.target.value)}
        />
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col" style={{ maxHeight: 'calc(100vh - 290px)' }}>
        <div className="overflow-auto relative">
          <table className="w-full text-left text-sm whitespace-nowrap">
            <thead className="bg-gray-50 text-gray-600 uppercase text-xs border-b border-gray-200 sticky top-0 z-10 shadow-sm">
              <tr>
                <th className="px-4 py-3">Pedido</th>
                <th className="px-4 py-3">F. Creación</th>
                <th className="px-4 py-3">Cliente</th>
                <th className="px-4 py-3">Descripción</th>
                <th className="px-4 py-3">Cod. Modelo</th>
                <th className="px-4 py-3">Modelo</th>
                <th className="px-4 py-3">N° Contrato</th>
                <th className="px-4 py-3">Cant. Pedido</th>
                <th className="px-4 py-3">Cant. Producción</th>
                <th className="px-4 py-3">Guía Remisión</th>
                <th className="px-4 py-3">Documento</th>
                <th className="px-4 py-3">Días para Entrega</th>
                <th className="px-4 py-3">Fec. Entrega</th>
                <th className="px-4 py-3 text-center sticky right-0 bg-gray-50 z-20 border-l border-gray-200 shadow-[-1px_0_0_#f3f4f6]">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-100">
              {loading && (
                <tr><td colSpan="15" className="px-4 py-8 text-center text-gray-400">Cargando pedidos...</td></tr>
              )}
              {!loading && filtered.length === 0 && (
                <tr><td colSpan="15" className="px-4 py-8 text-center text-gray-400">No hay órdenes registradas</td></tr>
              )}
              {filtered.map(order => {
                const estado = ESTADO_MAP[order.estado] || ESTADO_MAP[0];
                const diasRest = parseInt(order.dias_restantes);
                const diasColor = diasRest < 0 ? 'text-red-600 font-bold' : diasRest <= 3 ? 'text-orange-500 font-semibold' : 'text-gray-700';

                const isDanger = !order.codigo_venta ||
                  order.codigo_venta === 'null' ||
                  order.codigo_venta === 'NULL' ||
                  parseFloat(order.total || 0) > parseFloat(order.totalp || 0);

                const rowClass = isDanger ? 'bg-red-50 hover:bg-red-100 transition-colors' : 'bg-white hover:bg-gray-50 transition-colors';

                return (
                  <tr key={order.codigo} className={rowClass}>
                    <td className="px-4 py-3 font-mono font-bold text-gray-800">{order.codigo}</td>
                    <td className="px-4 py-3 text-gray-600">{order.fecha_creacion ? new Date(order.fecha_creacion).toLocaleDateString('es-PE') : '-'}</td>
                    <td className="px-4 py-3 font-medium">{order.name}</td>
                    <td className="px-4 py-3 text-gray-700 truncate max-w-xs">{order.nombre_modelo || order.producto || '-'}</td>
                    <td className="px-4 py-3 text-gray-700">{order.codigo_unitario || order.codigo_modelo || '-'}</td>
                    <td className="px-4 py-3">
                      <div className="flex items-center gap-2">
                        {(order.imagen_alt || order.imagen) ? (
                          <img
                            src={getProductImageUrl(order.imagen_alt || order.imagen)}
                            alt={order.producto}
                            className="w-8 h-8 object-cover rounded border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity"
                            onClick={(e) => setExpandedImage(e.target.src)}
                            onError={e => handleProductImageError(e, order.imagen_alt || order.imagen)}
                          />
                        ) : (
                          <div className="w-8 h-8 bg-gray-100 rounded flex items-center justify-center text-gray-400 text-xs border border-gray-200">
                            -
                          </div>
                        )}
                      </div>
                    </td>
                    <td className="px-4 py-3 text-gray-600">{order.num_contrato || '-'}</td>
                    <td className="px-4 py-3 text-gray-600">{order.total || '-'}</td>
                    <td className="px-4 py-3 text-gray-600">{order.totalp || '-'}</td>
                    <td className="px-4 py-3 text-gray-600" dangerouslySetInnerHTML={{ __html: order.guia_remision ? order.guia_remision.split(' - ').join('<br>') : '-' }} />
                    <td className="px-4 py-3 text-gray-600" dangerouslySetInnerHTML={{ __html: order.codigo_venta ? order.codigo_venta.split(' - ').join('<br>') : '-' }} />
                    <td className={`px-4 py-3 text-center ${diasColor}`}>
                      {parseFloat(order.total || 0) > parseFloat(order.totalp || 0) ? isNaN(diasRest) ? '-' : diasRest < 0 ? `${Math.abs(diasRest)}d tarde` : `${diasRest}d` : ''}
                    </td>
                    <td className="px-4 py-3 text-gray-600">{order.fecha_entrega_real ? new Date(order.fecha_entrega_real).toLocaleDateString('es-PE') : '-'}</td>
                    <td className="px-4 py-3 sticky right-0 bg-inherit z-10 border-l border-gray-100 shadow-[-1px_0_0_#f3f4f6]">
                      <div className="flex items-center justify-center gap-1">
                        <button
                          onClick={() => handleViewDetail(order)}
                          title="Ver Detalle"
                          className="p-1 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                        >
                          <EyeIcon className="h-4 w-4 cursor-pointer" />
                        </button>
                        <button
                          onClick={() => navigate(`/orders/${order.codigo}/production`)}
                          title="Completar pedido / Avance de producción"
                          className="p-1 text-gray-800 hover:bg-gray-100 rounded-lg transition-colors border border-gray-200"
                        >
                          <PlusIcon className="h-4 w-4 cursor-pointer" />
                        </button>
                        <button
                          onClick={() => navigate(`/orders/${order.codigo}/edit`)}
                          title="Editar pedido"
                          className="p-1 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                        >
                          <PencilIcon className="h-4 w-4 cursor-pointer" />
                        </button>
                        <button
                          onClick={() => handleDelete(order.codigo)}
                          title="Eliminar"
                          className="p-1 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                        >
                          <TrashIcon className="h-4 w-4 cursor-pointer" />
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
      {/* Modal Imagen */}
      {expandedImage && (
        <div
          className="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/80 backdrop-blur-sm cursor-pointer animate-in fade-in zoom-in duration-200"
          onClick={() => setExpandedImage(null)}
        >
          <div className="relative max-w-4xl max-h-[90vh] flex flex-col">
            <button
              className="absolute -top-10 right-0 text-white hover:text-gray-300 transition-colors"
              onClick={() => setExpandedImage(null)}
            >
              <XMarkIcon className="h-8 w-8" />
            </button>
            <img
              src={expandedImage}
              alt="Vista ampliada"
              className="w-full h-full object-contain rounded-lg shadow-2xl border-4 border-white"
              onClick={(e) => e.stopPropagation()}
            />
          </div>
        </div>
      )}
    </div>
  );
}
