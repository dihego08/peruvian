import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import api from '../../services/api';
import { EyeIcon, ArrowUpTrayIcon, DocumentTextIcon, ArrowDownTrayIcon } from '@heroicons/react/24/outline';

export default function SellsView() {
  const [sells, setSells] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [sendSunatLoading, setSendSunatLoading] = useState(false);
  const [sendingSunatId, setSendingSunatId] = useState(null);

  // Modal state
  const [modalSell, setModalSell] = useState(null);   // cabecera
  const [modalItems, setModalItems] = useState([]);   // detalle
  const [modalLoading, setModalLoading] = useState(false);

  useEffect(() => { fetchSells(); }, []);

  const fetchSells = async () => {
    try {
      const response = await api.get('/transactions/sells');
      setSells(response.data.Records || []);
    } catch (error) {
      console.error('Error fetching sells:', error);
    } finally {
      setLoading(false);
    }
  };

  const openModal = async (codigoVenta) => {
    setModalSell(null);
    setModalItems([]);
    setModalLoading(true);
    try {
      const res = await api.get(`/transactions/sells/${encodeURIComponent(codigoVenta)}`);
      setModalSell(res.data.cabecera);
      setModalItems(res.data.detalle || []);
    } catch (e) {
      console.error(e);
      alert('Error cargando el detalle de la venta.');
    } finally {
      setModalLoading(false);
    }
  };

  const closeModal = () => { setModalSell(null); setModalItems([]); };

  const handleSendSunat = async (codigo = null) => {
    const targetCodigo = typeof codigo === 'string' ? codigo : modalSell?.codigo_venta;
    if (!targetCodigo) return;
    if (!window.confirm('¿Enviar este comprobante a SUNAT?')) return;

    setSendSunatLoading(true);
    setSendingSunatId(targetCodigo);
    try {
      const response = await api.post(`/transactions/sells/${encodeURIComponent(targetCodigo)}/send-sunat`);
      if (response.data.Result === 'ERROR') {
        alert(`Error de SUNAT:\n${response.data.message || 'Error desconocido.'}`);
      } else {
        alert(response.data.message || 'Comprobante enviado a SUNAT exitosamente.');
        fetchSells();
        if (modalSell && modalSell.codigo_venta === targetCodigo) {
          openModal(targetCodigo);
        }
      }
    } catch (error) {
      console.error(error);
      alert(error.response?.data?.message || 'Error de red o servidor enviando el comprobante a SUNAT.');
    } finally {
      setSendSunatLoading(false);
      setSendingSunatId(null);
    }
  };

  const filtered = sells.filter(s =>
    (s.codigo_venta || '').toLowerCase().includes(search.toLowerCase()) ||
    (s.person || '').toLowerCase().includes(search.toLowerCase()) ||
    (s.tipo_doc_nombre || '').toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div className="flex flex-col gap-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Ventas</h1>
          <p className="text-sm text-gray-500 mt-0.5">Historial de ventas y comprobantes</p>
        </div>
        <Link
          to="/sells/new"
          className="bg-gray-800 text-white px-5 py-2.5 rounded-md hover:bg-gray-700 shadow-sm font-medium transition-colors flex items-center gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" /></svg>
          Nueva Venta
        </Link>
      </div>

      {/* Search */}
      <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <input
          type="text"
          className="w-full p-2.5 border border-gray-300 rounded-md focus:border-blue-500 text-sm"
          placeholder="Buscar por comprobante, cliente o tipo de documento..."
          value={search}
          onChange={e => setSearch(e.target.value)}
        />
      </div>

      {/* Table */}
      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-sm">
            <thead className="bg-gray-50 text-gray-600 uppercase text-xs border-b border-gray-200">
              <tr>
                <th className="px-4 py-3">Comprobante</th>
                <th className="px-4 py-3">Tipo</th>
                <th className="px-4 py-3">Fecha Emisión</th>
                <th className="px-4 py-3">Cliente</th>
                <th className="px-4 py-3 text-right">Subtotal</th>
                <th className="px-4 py-3 text-right">IGV</th>
                <th className="px-4 py-3 text-right">Total</th>
                <th className="px-4 py-3 text-center">Pago</th>
                <th className="px-4 py-3 text-center">Entrega</th>
                <th className="px-4 py-3 text-center">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-100">
              {loading && (
                <tr><td colSpan="10" className="px-4 py-8 text-center text-gray-400">Cargando ventas...</td></tr>
              )}
              {!loading && filtered.length === 0 && (
                <tr><td colSpan="10" className="px-4 py-8 text-center text-gray-400">No hay ventas registradas.</td></tr>
              )}
              {filtered.map((sell, idx) => (
                <tr key={sell.codigo_venta || idx} className="hover:bg-gray-50 transition-colors">
                  <td className="px-4 py-3 font-mono font-bold text-gray-800">{sell.codigo_venta || '-'}</td>
                  <td className="px-4 py-3">
                    <span className={`text-xs px-2 py-1 rounded-full font-semibold ${sell.tipo_documento == 2 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700'}`}>
                      {sell.tipo_doc_nombre || `Tipo ${sell.tipo_documento}`}
                    </span>
                  </td>
                  <td className="px-4 py-3 text-gray-600">{sell.fecha_creacion || '-'}</td>
                  <td className="px-4 py-3 font-medium text-gray-800 max-w-[200px] truncate">{sell.person || 'Público General'}</td>
                  <td className="px-4 py-3 text-right text-gray-700">S/ {parseFloat(sell.subtotal || 0).toFixed(2)}</td>
                  <td className="px-4 py-3 text-right text-gray-700">S/ {parseFloat(sell.igv || 0).toFixed(2)}</td>
                  <td className="px-4 py-3 text-right font-bold text-green-700">S/ {parseFloat(sell.total || 0).toFixed(2)}</td>
                  <td className="px-4 py-3 text-center">
                    <span className="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded-full">{sell.pago || '-'}</span>
                  </td>
                  <td className="px-4 py-3 text-center">
                    <span className="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded-full">{sell.entrega || '-'}</span>
                  </td>
                  <td className="px-4 py-3 text-center">
                    <div className="flex items-center justify-center gap-1">
                      <button
                        title="Ver Detalle"
                        onClick={() => openModal(sell.codigo_venta)}
                        className="p-1 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                      >
                        <EyeIcon className="h-5 w-5" />
                      </button>

                      {sell.envio_sunat != 1 && (
                        <button
                          onClick={() => handleSendSunat(sell.codigo_venta)}
                          disabled={sendingSunatId === sell.codigo_venta}
                          className="p-1 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
                          title="Enviar a SUNAT"
                        >
                          <ArrowDownTrayIcon className="h-4 w-4 rotate-180" />
                        </button>
                      )}

                      {sell.envio_sunat == 1 && (
                        <a
                          href={`${import.meta.env.VITE_API_BASE_URL || 'https://apiperuvian.dbusinessaqp.com/api'}/transactions/sells/${encodeURIComponent(sell.codigo_venta)}/sunat-files`}
                          className="p-1 text-orange-500 hover:bg-orange-50 rounded-lg transition-colors"
                          title="Descargar XML y CDR"
                        >
                          <ArrowDownTrayIcon className="h-4 w-4" />
                        </a>
                      )}

                      <a
                        href={`${import.meta.env.VITE_API_BASE_URL || 'https://apiperuvian.dbusinessaqp.com/api'}/transactions/sells/${encodeURIComponent(sell.codigo_venta)}/pdf`}
                        target="_blank"
                        rel="noreferrer"
                        className="p-1 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                        title="Ver PDF con backend"
                      >
                        <DocumentTextIcon className="h-4 w-4" />
                      </a>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
        {!loading && (
          <div className="px-4 py-3 border-t border-gray-100 bg-gray-50 text-xs text-gray-500 text-right">
            Mostrando {filtered.length} de {sells.length} registros
          </div>
        )}
      </div>

      {/* Sending Modal */}
      {sendingSunatId && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-in fade-in duration-300">
          <div className="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center animate-in zoom-in-95 duration-300">
            <div className="mx-auto w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mb-4"></div>
            <h3 className="text-lg font-bold text-gray-900 mb-2">Enviando a SUNAT</h3>
            <p className="text-sm text-gray-500">
              Por favor, espere. Este proceso demora aproximadamente 60 segundos debido a la generación del ticket y respuesta de SUNAT.
            </p>
          </div>
        </div>
      )}

      {/* ===== MODAL DETALLE ===== */}
      {(modalSell || modalLoading) && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
          {/* Overlay */}
          <div className="absolute inset-0 bg-black/50 backdrop-blur-sm" onClick={closeModal}></div>

          {/* Panel */}
          <div className="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden">
            {/* Modal Header */}
            <div className="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
              <div>
                <h2 className="text-lg font-bold text-gray-900">
                  Detalle de Venta — <span className="font-mono text-blue-600">{modalSell?.codigo_venta || '...'}</span>
                </h2>
                <p className="text-xs text-gray-500 mt-0.5">{modalSell?.tipo_doc_nombre} · {modalSell?.fecha_emision_fmt}</p>
              </div>
              <div className="flex items-center gap-2">
                <button onClick={closeModal} className="text-gray-400 hover:text-gray-700 transition-colors p-1.5 rounded-lg hover:bg-gray-200">
                  <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
              </div>
            </div>

            {modalLoading ? (
              <div className="flex-1 flex items-center justify-center py-16 text-gray-400">Cargando detalle...</div>
            ) : (
              <div className="flex-1 overflow-y-auto">
                {/* Info del cliente y fechas */}
                <div className="grid grid-cols-2 md:grid-cols-3 gap-4 p-6 border-b border-gray-100">
                  <div>
                    <p className="text-xs font-bold text-gray-500 uppercase tracking-wider">Cliente</p>
                    <p className="text-sm font-semibold text-gray-800 mt-0.5">{modalSell?.person || 'Público General'}</p>
                    {modalSell?.ruc && <p className="text-xs text-gray-500">RUC: {modalSell.ruc}</p>}
                    {modalSell?.direccion && <p className="text-xs text-gray-500 truncate">{modalSell.direccion}</p>}
                  </div>
                  <div>
                    <p className="text-xs font-bold text-gray-500 uppercase tracking-wider">Fechas</p>
                    <p className="text-sm text-gray-700 mt-0.5">Emisión: <span className="font-medium">{modalSell?.fecha_emision_fmt || '-'}</span></p>
                    <p className="text-sm text-gray-700">Vencim.: <span className="font-medium">{modalSell?.fecha_vencimiento_fmt || '-'}</span></p>
                  </div>
                  <div>
                    <p className="text-xs font-bold text-gray-500 uppercase tracking-wider">Condición</p>
                    <p className="text-sm text-gray-700 mt-0.5">Pago: <span className="font-medium">{modalSell?.pago || '-'}</span></p>
                    <p className="text-sm text-gray-700">Forma: <span className="font-medium">{modalSell?.tipo_pago || '-'}</span></p>
                    <p className="text-sm text-gray-700">Entrega: <span className="font-medium">{modalSell?.entrega || '-'}</span></p>
                  </div>
                </div>

                {/* Tabla de ítems */}
                <div className="p-6">
                  <table className="w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                    <thead className="bg-gray-50 text-gray-600 uppercase text-xs">
                      <tr>
                        <th className="px-3 py-2 text-left border-b">Producto</th>
                        <th className="px-3 py-2 text-center border-b">Tipo</th>
                        <th className="px-3 py-2 text-center border-b">Unidad</th>
                        <th className="px-3 py-2 text-center border-b">Pedido</th>
                        <th className="px-3 py-2 text-right border-b">Cant.</th>
                        <th className="px-3 py-2 text-right border-b">P. Unit.</th>
                        <th className="px-3 py-2 text-right border-b">P. Bord.</th>
                        <th className="px-3 py-2 text-right border-b">Subtotal</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-100">
                      {modalItems.map((item, i) => (
                        <tr key={i} className="hover:bg-gray-50">
                          <td className="px-3 py-2">
                            <div className="flex items-center gap-2">
                              {item.producto_imagen && (
                                <img
                                  src={`https://peruvian.peruviandress.com/storage/products/${item.producto_imagen}`}
                                  className="w-7 h-7 object-cover rounded border border-gray-200"
                                  onError={e => { e.target.style.display = 'none'; }}
                                  alt=""
                                />
                              )}
                              <div>
                                <p className="font-medium text-gray-800">{item.producto_nombre || `Prod. #${item.id_producto}`}</p>
                                <p className="text-xs text-gray-500 font-mono">{item.producto_codigo}</p>
                              </div>
                            </div>
                          </td>
                          <td className="px-3 py-2 text-center">
                            <span className={`text-xs px-2 py-0.5 rounded-full font-semibold ${item.tipo === 'Servicio' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600'}`}>
                              {item.tipo || 'Producto'}
                            </span>
                          </td>
                          <td className="px-3 py-2 text-center text-gray-600 text-xs">{item.unidad || item.codigo_unidad || '-'}</td>
                          <td className="px-3 py-2 text-center text-gray-600 text-xs">{item.pedido_cod || '-'}</td>
                          <td className="px-3 py-2 text-right font-medium">{item.cantidad}</td>
                          <td className="px-3 py-2 text-right text-gray-700">S/ {parseFloat(item.precio_unitario || 0).toFixed(4)}</td>
                          <td className="px-3 py-2 text-right text-gray-700">S/ {parseFloat(item.precio_bordado || 0).toFixed(2)}</td>
                          <td className="px-3 py-2 text-right font-semibold text-gray-800">S/ {parseFloat(item.subtotal_item || 0).toFixed(2)}</td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>

                {/* Totales */}
                <div className="flex justify-end px-6 pb-6">
                  <div className="w-64 bg-gray-50 border border-gray-200 rounded-xl p-4 space-y-2 text-sm">
                    <div className="flex justify-between text-gray-600">
                      <span>Subtotal:</span>
                      <span>S/ {parseFloat(modalSell?.subtotal || 0).toFixed(2)}</span>
                    </div>
                    <div className="flex justify-between text-gray-600">
                      <span>IGV (18%):</span>
                      <span>S/ {parseFloat(modalSell?.igv || 0).toFixed(2)}</span>
                    </div>
                    {parseFloat(modalSell?.descuento || 0) > 0 && (
                      <div className="flex justify-between text-red-500 font-medium">
                        <span>Descuento:</span>
                        <span>- S/ {parseFloat(modalSell?.descuento || 0).toFixed(2)}</span>
                      </div>
                    )}
                    <div className="flex justify-between font-bold text-gray-900 text-base border-t border-gray-300 pt-2 mt-2">
                      <span>Total:</span>
                      <span className="text-green-700">S/ {parseFloat(modalSell?.total || 0).toFixed(2)}</span>
                    </div>
                    {parseFloat(modalSell?.a_cuenta || 0) > 0 && (
                      <div className="flex justify-between text-orange-600 text-xs">
                        <span>Por cobrar:</span>
                        <span>S/ {parseFloat(modalSell?.a_cuenta || 0).toFixed(2)}</span>
                      </div>
                    )}
                  </div>
                </div>
              </div>
            )}
          </div>
        </div>
      )}
    </div>
  );
}
