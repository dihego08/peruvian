import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../services/api';
import {
  MagnifyingGlassIcon,
  EyeIcon,
  TrashIcon,
  DocumentTextIcon,
  TruckIcon,
  XMarkIcon,
  ArrowDownTrayIcon,
} from '@heroicons/react/24/outline';

export default function GuiasView() {
  const navigate = useNavigate();
  const [guias, setGuias] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [desde, setDesde] = useState('');
  const [hasta, setHasta] = useState('');
  const [sendingSunatId, setSendingSunatId] = useState(null);

  // Detail modal
  const [showDetail, setShowDetail] = useState(false);
  const [detailLoading, setDetailLoading] = useState(false);
  const [detailData, setDetailData] = useState(null);

  useEffect(() => {
    fetchGuias();
  }, []);

  const fetchGuias = async (params = {}) => {
    setLoading(true);
    try {
      const r = await api.get('/guias', {
        params: { search, desde, hasta, ...params },
      });
      setGuias(r.data);
    } catch (e) {
      console.error(e);
    } finally {
      setLoading(false);
    }
  };

  const handleSearch = (e) => {
    e.preventDefault();
    fetchGuias();
  };

  const handleViewDetail = async (id, numGuia) => {
    setShowDetail(true);
    setDetailLoading(true);
    setDetailData(null);
    try {
      const r = await api.get(`/guias/${id}/detalle`);
      setDetailData({ ...r.data, numGuia });
    } catch (e) {
      console.error(e);
    } finally {
      setDetailLoading(false);
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('¿Seguro de eliminar esta guía de remisión?')) return;
    try {
      await api.delete(`/guias/${id}`);
      fetchGuias();
    } catch (e) {
      alert('Error al eliminar la guía');
    }
  };

  const handleSendSunat = async (id) => {
    if (!window.confirm('¿Enviar esta guía a SUNAT?')) return;
    setSendingSunatId(id);
    try {
      const response = await api.post(`/guias/${id}/send-sunat`);
      alert(response.data.message || 'Guía enviada a SUNAT.');
      fetchGuias();
      if (detailData?.cabecera?.id === id) {
        handleViewDetail(id, detailData.numGuia);
      }
    } catch (error) {
      console.error(error);
      alert(error.response?.data?.message || 'Error enviando la guía a SUNAT.');
    } finally {
      setSendingSunatId(null);
    }
  };

  const estadoBadge = (estado) => {
    if (estado == 1) {
      return (
        <span className="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-[10px] font-bold uppercase tracking-wide">
          Emitida
        </span>
      );
    }
    return (
      <span className="px-2 py-0.5 bg-amber-100 text-amber-700 rounded-full text-[10px] font-bold uppercase tracking-wide">
        Pendiente
      </span>
    );
  };

  return (
    <div className="flex flex-col gap-6 animate-in fade-in duration-500">
      {/* Header */}
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Guías de Remisión</h1>
          <p className="text-sm text-gray-500 mt-0.5">Lista general de guías de remisión emitidas</p>
        </div>
        <button
          onClick={() => navigate('/guias/new')}
          className="bg-gray-800 text-white px-5 py-2.5 rounded-lg hover:bg-gray-700 shadow-sm font-medium transition-colors flex items-center gap-2 text-sm"
        >
          <ArrowDownTrayIcon className="h-4 w-4 rotate-180" />
          Nueva Guía
        </button>
      </div>

      {/* Filters */}
      <div className="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
        <form onSubmit={handleSearch} className="flex flex-wrap gap-3 items-end">
          <div className="flex-1 min-w-[200px] space-y-1">
            <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Buscar</label>
            <div className="relative">
              <MagnifyingGlassIcon className="h-4 w-4 absolute left-3 top-2.5 text-gray-400" />
              <input
                type="text"
                placeholder="Num. Guía, destinatario, transportista..."
                className="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50 outline-none focus:border-blue-500"
                value={search}
                onChange={(e) => setSearch(e.target.value)}
              />
            </div>
          </div>
          <div className="space-y-1">
            <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Desde</label>
            <input
              type="date"
              className="p-2 border border-gray-300 rounded-lg text-sm bg-gray-50 outline-none focus:border-blue-500"
              value={desde}
              onChange={(e) => setDesde(e.target.value)}
            />
          </div>
          <div className="space-y-1">
            <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Hasta</label>
            <input
              type="date"
              className="p-2 border border-gray-300 rounded-lg text-sm bg-gray-50 outline-none focus:border-blue-500"
              value={hasta}
              onChange={(e) => setHasta(e.target.value)}
            />
          </div>
          <button
            type="submit"
            className="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 font-bold text-sm transition-colors flex items-center gap-2"
          >
            <MagnifyingGlassIcon className="h-4 w-4" />
            Buscar
          </button>
        </form>
      </div>

      {/* Table */}
      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
        <table className="w-full text-left text-sm min-w-[1000px]">
          <thead className="bg-gray-50 text-gray-600 uppercase text-xs border-b border-gray-200">
            <tr>
              <th className="px-4 py-4 font-bold">#</th>
              <th className="px-4 py-4 font-bold">Num. Guía</th>
              <th className="px-4 py-4 font-bold">F. Emisión</th>
              <th className="px-4 py-4 font-bold">F. Traslado</th>
              <th className="px-4 py-4 font-bold">Origen</th>
              <th className="px-4 py-4 font-bold">Razón Social</th>
              <th className="px-4 py-4 font-bold">Destinatario</th>
              <th className="px-4 py-4 font-bold">Destino</th>
              <th className="px-4 py-4 font-bold">Transportista</th>
              <th className="px-4 py-4 font-bold">Placa</th>
              <th className="px-4 py-4 font-bold">Conductor</th>
              <th className="px-4 py-4 font-bold text-right">T. Bruto</th>
              <th className="px-4 py-4 font-bold text-right">T. Neto</th>
              <th className="px-4 py-4 font-bold">Estado</th>
              <th className="px-4 py-4 font-bold text-center">Acciones</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-100">
            {loading && (
              <tr>
                <td colSpan="15" className="px-6 py-10 text-center text-gray-400">
                  Cargando guías...
                </td>
              </tr>
            )}
            {!loading && guias.length === 0 && (
              <tr>
                <td colSpan="15" className="px-6 py-10 text-center text-gray-400">
                  No se encontraron guías de remisión.
                </td>
              </tr>
            )}
            {guias.map((g, idx) => (
              <tr key={g.id} className="hover:bg-gray-50 transition-colors">
                <td className="px-4 py-3 text-gray-400 text-xs">{idx + 1}</td>
                <td className="px-4 py-3 font-mono font-bold text-blue-700 text-xs">{g.num_guia}</td>
                <td className="px-4 py-3 text-gray-600 text-xs">{g.fecha_emision}</td>
                <td className="px-4 py-3 text-gray-600 text-xs">{g.fecha_traslado}</td>
                <td className="px-4 py-3 text-gray-700 text-xs max-w-[120px] truncate">{g.origen}</td>
                <td className="px-4 py-3 font-semibold text-gray-800 text-xs max-w-[140px] truncate">{g.name}</td>
                <td className="px-4 py-3 text-gray-600 text-xs">{g.ruc_destinatario}</td>
                <td className="px-4 py-3 text-gray-700 text-xs max-w-[120px] truncate">{g.destino}</td>
                <td className="px-4 py-3 text-gray-600 text-xs">{g.ruc_transportista}</td>
                <td className="px-4 py-3 font-mono text-xs text-gray-700">{g.placa}</td>
                <td className="px-4 py-3 text-gray-600 text-xs">{g.ruc_conductor}</td>
                <td className="px-4 py-3 text-right font-semibold text-gray-800 text-xs">{g.total_bruto}</td>
                <td className="px-4 py-3 text-right font-semibold text-gray-800 text-xs">{g.total_neto}</td>
                <td className="px-4 py-3">{estadoBadge(g.estado)}</td>
                <td className="px-4 py-3">
                  <div className="flex items-center justify-center gap-1">
                    <button
                      onClick={() => handleViewDetail(g.id, g.num_guia)}
                      className="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                      title="Ver Detalle"
                    >
                      <EyeIcon className="h-4 w-4" />
                    </button>
                    {g.estado == 1 ? (
                      <a
                        href={`${import.meta.env.VITE_LEGACY_URL || 'https://peruvian.peruviandress.com'}/core/app/view/pdf-guia.php?id=${g.id}`}
                        target="_blank"
                        rel="noreferrer"
                        className="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                        title="Ver PDF"
                      >
                        <DocumentTextIcon className="h-4 w-4" />
                      </a>
                    ) : null}
                    {g.estado != 1 && (
                      <button
                        onClick={() => handleSendSunat(g.id)}
                        disabled={sendingSunatId === g.id}
                        className="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
                        title="Enviar a SUNAT"
                      >
                        <ArrowDownTrayIcon className="h-4 w-4 rotate-180" />
                      </button>
                    )}
                    <button
                      onClick={() => handleDelete(g.id)}
                      className="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                      title="Eliminar"
                    >
                      <TrashIcon className="h-4 w-4" />
                    </button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {/* Count */}
      {!loading && guias.length > 0 && (
        <p className="text-xs text-gray-400 text-right">
          {guias.length} guía{guias.length !== 1 ? 's' : ''} encontrada{guias.length !== 1 ? 's' : ''}
        </p>
      )}

      {/* Detail Modal */}
      {showDetail && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-in fade-in duration-300">
          <div className="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden animate-in zoom-in-95 duration-300">
            {/* Modal Header */}
            <div className="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
              <div className="flex items-center gap-3">
                <div className="p-2 bg-blue-100 text-blue-600 rounded-lg">
                  <TruckIcon className="h-5 w-5" />
                </div>
                <div>
                  <h2 className="text-lg font-bold text-gray-900">
                    Detalle de Guía de Remisión
                  </h2>
                  {detailData?.numGuia && (
                    <p className="text-xs text-blue-600 font-mono font-bold">{detailData.numGuia}</p>
                  )}
                </div>
              </div>
              <button
                onClick={() => setShowDetail(false)}
                className="text-gray-400 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-200 transition-colors"
              >
                <XMarkIcon className="h-5 w-5" />
              </button>
            </div>

            <div className="p-6 overflow-y-auto max-h-[75vh]">
              {detailLoading && (
                <div className="text-center py-10 text-gray-400">Cargando detalle...</div>
              )}

              {!detailLoading && detailData && (
                <>
                  {/* Cabecera info cards */}
                  {detailData.cabecera && (
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                      {[
                        { label: 'Origen', value: detailData.cabecera.origen },
                        { label: 'Destino', value: detailData.cabecera.destino },
                        { label: 'Transportista', value: detailData.cabecera.ruc_transportista },
                        { label: 'Conductor', value: detailData.cabecera.ruc_conductor },
                        { label: 'Placa', value: detailData.cabecera.placa },
                        { label: 'F. Emisión', value: detailData.cabecera.fecha_emision },
                        { label: 'F. Traslado', value: detailData.cabecera.fecha_traslado },
                        { label: 'Destinatario', value: detailData.cabecera.ruc_destinatario },
                      ].map((item) => (
                        <div key={item.label} className="bg-gray-50 rounded-xl p-3 border border-gray-100">
                          <div className="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">
                            {item.label}
                          </div>
                          <div className="text-sm font-semibold text-gray-800">{item.value || '—'}</div>
                        </div>
                      ))}
                    </div>
                  )}

                  {/* Detail table */}
                  <div className="rounded-xl border border-gray-200 overflow-hidden">
                    <table className="w-full text-sm">
                      <thead className="bg-gray-50 border-b border-gray-200">
                        <tr>
                          <th className="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Producto</th>
                          <th className="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Pedido</th>
                          <th className="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Cantidad</th>
                          <th className="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Unidad</th>
                          <th className="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Peso Neto</th>
                          <th className="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Peso Bruto</th>
                        </tr>
                      </thead>
                      <tbody className="divide-y divide-gray-100">
                        {detailData.detalle?.length === 0 && (
                          <tr>
                            <td colSpan="6" className="px-4 py-8 text-center text-gray-400 text-sm">
                              Sin ítems en esta guía.
                            </td>
                          </tr>
                        )}
                        {detailData.detalle?.map((item, idx) => (
                          <tr key={idx} className="hover:bg-gray-50">
                            <td className="px-4 py-3 font-semibold text-gray-800">{item.descripcion_producto || item.name}</td>
                            <td className="px-4 py-3 text-gray-600 text-xs">{item.pedido}</td>
                            <td className="px-4 py-3 text-right font-bold text-gray-800">{item.cantidad}</td>
                            <td className="px-4 py-3 text-center text-gray-600 text-xs">{item.unidad}</td>
                            <td className="px-4 py-3 text-right text-gray-700">{item.t_neto}</td>
                            <td className="px-4 py-3 text-right text-gray-700">{item.t_bruto}</td>
                          </tr>
                        ))}
                      </tbody>
                      {detailData.detalle?.length > 0 && (
                        <tfoot className="bg-gray-50 border-t border-gray-200">
                          <tr>
                            <td colSpan="4" className="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Totales</td>
                            <td className="px-4 py-3 text-right font-bold text-gray-900">
                              {detailData.detalle.reduce((s, i) => s + (parseFloat(i.t_neto) || 0), 0).toFixed(2)}
                            </td>
                            <td className="px-4 py-3 text-right font-bold text-gray-900">
                              {detailData.detalle.reduce((s, i) => s + (parseFloat(i.t_bruto) || 0), 0).toFixed(2)}
                            </td>
                          </tr>
                        </tfoot>
                      )}
                    </table>
                  </div>
                </>
              )}
            </div>

            <div className="flex justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50">
              {detailData?.cabecera?.estado != 1 && (
                <button
                  onClick={() => handleSendSunat(detailData.cabecera.id)}
                  disabled={sendingSunatId === detailData.cabecera.id}
                  className="px-5 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-bold text-sm transition-colors flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <ArrowDownTrayIcon className="h-4 w-4 rotate-180" />
                  {sendingSunatId === detailData.cabecera.id ? 'Enviando...' : 'Enviar SUNAT'}
                </button>
              )}
              {detailData?.cabecera?.estado == 1 && (
                <a
                  href={`${import.meta.env.VITE_LEGACY_URL || 'https://peruvian.peruviandress.com'}/core/app/view/pdf-guia.php?id=${detailData.cabecera.id}`}
                  target="_blank"
                  rel="noreferrer"
                  className="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-bold text-sm transition-colors flex items-center gap-2"
                >
                  <DocumentTextIcon className="h-4 w-4" />
                  Ver PDF
                </a>
              )}
              <button
                onClick={() => setShowDetail(false)}
                className="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-sm transition-colors"
              >
                Cerrar
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
