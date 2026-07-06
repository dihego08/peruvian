import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import api from '../../services/api';
import { getProductImageUrl, handleProductImageError } from '../../utils/image';
import {
    EyeIcon,
    TrashIcon,
    PlusIcon,
    DocumentTextIcon,
    MagnifyingGlassIcon,
    XMarkIcon
} from '@heroicons/react/24/outline';

//const IMAGE_BASE_URL = 'https://peruvian.peruviandress.com/storage/products';
//const LOCAL_IMAGE_BASE_URL = 'http://localhost/peruvian/storage/products';

const CotizationsView = () => {
    const [cotizations, setCotizations] = useState([]);
    const [loading, setLoading] = useState(true);
    const [showDetailModal, setShowDetailModal] = useState(false);
    const [selectedCotization, setSelectedCotization] = useState(null);
    const [detailLoading, setDetailLoading] = useState(false);
    // Modal Image State
    const [expandedImage, setExpandedImage] = useState(null);
    useEffect(() => {
        fetchCotizations();
    }, []);

    const fetchCotizations = async () => {
        try {
            setLoading(true);
            const response = await api.get('/cotizations');
            setCotizations(response.data.Records || []);
        } catch (error) {
            console.error('Error fetching cotizations:', error);
        } finally {
            setLoading(false);
        }
    };

    const handleViewDetail = async (codigo) => {
        try {
            setDetailLoading(true);
            setShowDetailModal(true);
            const response = await api.get(`/cotizations/${codigo}`);
            setSelectedCotization(response.data);
        } catch (error) {
            console.error('Error fetching detail:', error);
            alert('Error al cargar el detalle');
        } finally {
            setDetailLoading(false);
        }
    };

    const handleDelete = async (codigo) => {
        if (!window.confirm(`¿Seguro de eliminar la cotización ${codigo}?`)) return;

        try {
            await api.delete(`/cotizations/${codigo}`);
            fetchCotizations();
        } catch (error) {
            console.error('Error deleting cotization:', error);
            alert('Error al eliminar');
        }
    };

    return (
        <div className="flex flex-col gap-6">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-2xl font-bold text-gray-900">Cotizaciones</h1>
                    <p className="text-sm text-gray-500 mt-0.5">Gestión de presupuestos y propuestas comerciales</p>
                </div>
                <Link
                    to="/cotizations/new"
                    className="bg-gray-800 text-white px-5 py-2.5 rounded-md hover:bg-gray-700 shadow-sm font-medium transition-colors flex items-center gap-2"
                >
                    <PlusIcon className="h-4 w-4" />
                    Nueva Cotización
                </Link>
            </div>

            {/* Table */}
            <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead className="bg-gray-50">
                            <tr>
                                <th className="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider"></th>
                                <th className="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Código</th>
                                <th className="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th className="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Cliente</th>
                                <th className="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Subtotal</th>
                                <th className="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">IGV</th>
                                <th className="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                                <th className="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {loading ? (
                                <tr>
                                    <td colSpan="8" className="px-6 py-10 text-center text-gray-400">Cargando cotizaciones...</td>
                                </tr>
                            ) : cotizations.length === 0 ? (
                                <tr>
                                    <td colSpan="8" className="px-6 py-10 text-center text-gray-400">No se encontraron cotizaciones</td>
                                </tr>
                            ) : (
                                cotizations.map((cot) => (
                                    <tr key={cot.codigo} className="hover:bg-gray-50 transition-colors">
                                        <td className="px-6 py-4">
                                            {cot.imagen ? (
                                                <img
                                                    src={getProductImageUrl(cot.imagen)}
                                                    alt="Thumbnail"
                                                    onClick={() => setExpandedImage(getProductImageUrl(cot.imagen))}
                                                    className="w-15 h-15 object-cover rounded border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity"
                                                />
                                            ) : (
                                                <div className="w-12 h-12 bg-gray-100 rounded-md flex items-center justify-center text-gray-400">
                                                    <DocumentTextIcon className="w-6 h-6" />
                                                </div>
                                            )}
                                        </td>
                                        <td className="px-6 py-4 font-medium text-gray-700">{cot.codigo}</td>
                                        <td className="px-6 py-4 text-gray-600">{cot.fecha_creacion}</td>
                                        <td className="px-6 py-4 text-gray-600">{cot.name}</td>
                                        <td className="px-6 py-4 text-gray-600">S/. {parseFloat(cot.sub_total).toFixed(2)}</td>
                                        <td className="px-6 py-4 text-gray-600">S/. {parseFloat(cot.igv).toFixed(2)}</td>
                                        <td className="px-6 py-4 font-bold text-blue-600">S/. {parseFloat(cot.total).toFixed(2)}</td>
                                        <td className="px-6 py-4 text-right space-x-2">
                                            <button
                                                onClick={() => handleViewDetail(cot.codigo)}
                                                className="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors cursor-pointer"
                                                title="Ver Detalle"
                                            >
                                                <EyeIcon className="w-5 h-5" />
                                            </button>
                                            <a
                                                href={`${import.meta.env.BASE_URL}pdf-cotizacion.php?codigo=${cot.codigo}`}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="inline-block p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors cursor-pointer"
                                                title="Generar PDF"
                                            >
                                                <DocumentTextIcon className="w-5 h-5" />
                                            </a>
                                            <button
                                                onClick={() => handleDelete(cot.codigo)}
                                                className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer"
                                                title="Eliminar"
                                            >
                                                <TrashIcon className="w-5 h-5" />
                                            </button>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Detail Modal */}
            {showDetailModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                    <div className="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] flex flex-col">
                        <div className="p-6 border-b border-gray-100 flex justify-between items-center">
                            <div>
                                <h3 className="text-xl font-bold text-gray-800">
                                    Detalle de Cotización #{selectedCotization?.cabecera?.codigo}
                                </h3>
                                <p className="text-sm text-gray-500">
                                    {selectedCotization?.cabecera?.client_name || selectedCotization?.cabecera?.cliente}
                                </p>
                            </div>
                            <button
                                onClick={() => setShowDetailModal(false)}
                                className="p-2 hover:bg-gray-100 rounded-full transition-colors"
                            >
                                <XMarkIcon className="w-6 h-6 text-gray-400" />
                            </button>
                        </div>

                        <div className="flex-1 overflow-y-auto p-6 space-y-6">
                            {detailLoading ? (
                                <div className="text-center py-10 text-gray-400">Cargando detalles...</div>
                            ) : (
                                <>
                                    {/* Header Info */}
                                    <div className="grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-xl">
                                        <div>
                                            <label className="text-xs font-bold text-gray-400 uppercase">Fecha</label>
                                            <p className="font-medium">{selectedCotization?.cabecera?.fecha_creacion}</p>
                                        </div>
                                        <div>
                                            <label className="text-xs font-bold text-gray-400 uppercase">Validez</label>
                                            <p className="font-medium">{selectedCotization?.cabecera?.validez || '-'}</p>
                                        </div>
                                        <div>
                                            <label className="text-xs font-bold text-gray-400 uppercase">Forma de Pago</label>
                                            <p className="font-medium">{selectedCotization?.cabecera?.forma_pago || '-'}</p>
                                        </div>
                                        <div>
                                            <label className="text-xs font-bold text-gray-400 uppercase">Entrega</label>
                                            <p className="font-medium">{selectedCotization?.cabecera?.tiempo_entrega || '-'}</p>
                                        </div>
                                    </div>

                                    {/* Details Table */}
                                    <div className="border border-gray-100 rounded-xl overflow-hidden">
                                        <table className="w-full text-left">
                                            <thead className="bg-gray-50">
                                                <tr>
                                                    <th className="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Producto</th>
                                                    <th className="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Costo</th>
                                                    <th className="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Cant.</th>
                                                    <th className="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Total</th>
                                                    <th className="px-4 py-3 text-xs font-bold text-gray-500 uppercase">Imágenes</th>
                                                </tr>
                                            </thead>
                                            <tbody className="divide-y divide-gray-100">
                                                {selectedCotization?.detalle?.map((item, idx) => (
                                                    <tr key={idx} className="hover:bg-gray-50">
                                                        <td className="px-4 py-4">
                                                            <div className="font-medium text-gray-800">{item.nombre_producto}</div>
                                                            <div className="text-xs text-gray-500 italic" dangerouslySetInnerHTML={{ __html: item.descripcion }}></div>
                                                        </td>
                                                        <td className="px-4 py-4">S/. {parseFloat(item.costo).toFixed(2)}</td>
                                                        <td className="px-4 py-4">{item.cantidad}</td>
                                                        <td className="px-4 py-4 font-bold text-blue-600">
                                                            S/. {(item.cantidad * parseFloat(item.costo).toFixed(2)).toFixed(2)}
                                                        </td>
                                                        <td className="px-4 py-4">
                                                            <div className="flex space-x-2">
                                                                {item.imagen && (
                                                                    <img
                                                                        src={getProductImageUrl(item.imagen)}
                                                                        className="w-16 h-16 object-cover rounded border"
                                                                        alt="Modelo"
                                                                    />
                                                                )}
                                                                {item.imagen_2 && (
                                                                    <img
                                                                        src={getProductImageUrl(item.imagen_2)}
                                                                        className="w-16 h-16 object-cover rounded border"
                                                                        alt="Bordado"
                                                                    />
                                                                )}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>

                                    {/* Additional Info */}
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div className="space-y-4">
                                            <div className="bg-blue-50 p-4 rounded-xl border border-blue-100">
                                                <h4 className="text-sm font-bold text-blue-800 uppercase mb-2">Servicios</h4>
                                                <p className="text-gray-700 whitespace-pre-line" dangerouslySetInnerHTML={{ __html: selectedCotization?.cabecera?.servicios }}></p>
                                            </div>
                                            <div className="bg-amber-50 p-4 rounded-xl border border-amber-100">
                                                <h4 className="text-sm font-bold text-amber-800 uppercase mb-2">Observaciones</h4>
                                                <p className="text-gray-700 whitespace-pre-line" dangerouslySetInnerHTML={{ __html: selectedCotization?.cabecera?.obervacion }}></p>
                                            </div>
                                        </div>
                                        <div className="bg-gray-50 p-6 rounded-xl border border-gray-100 flex flex-col justify-between">
                                            <div className="space-y-2">
                                                <div className="flex justify-between text-gray-600">
                                                    <span>Subtotal</span>
                                                    <span>S/. {parseFloat(selectedCotization?.cabecera?.sub_total).toFixed(2)}</span>
                                                </div>
                                                <div className="flex justify-between text-gray-600">
                                                    <span>IGV (18%)</span>
                                                    <span>S/. {parseFloat(selectedCotization?.cabecera?.igv).toFixed(2)}</span>
                                                </div>
                                                <div className="border-t border-gray-200 pt-2 flex justify-between font-bold text-xl text-blue-600">
                                                    <span>TOTAL</span>
                                                    <span>S/. {parseFloat(selectedCotization?.cabecera?.total).toFixed(2)}</span>
                                                </div>
                                            </div>
                                            <div className="mt-6 pt-6 border-t border-gray-200">
                                                <p className="text-xs font-bold text-gray-400 uppercase mb-2">Asesor Comercial</p>
                                                <p className="font-medium text-gray-800">{selectedCotization?.cabecera?.asesor_comercial || '-'}</p>
                                                <p className="text-gray-500">{selectedCotization?.cabecera?.asesor_celular}</p>
                                            </div>
                                        </div>
                                    </div>
                                </>
                            )}
                        </div>

                        <div className="p-6 border-t border-gray-100 bg-gray-50 flex justify-end">
                            <button
                                onClick={() => setShowDetailModal(false)}
                                className="px-6 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors"
                            >
                                Cerrar
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
};

export default CotizationsView;
