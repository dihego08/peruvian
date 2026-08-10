import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../../services/api';
import {
    MagnifyingGlassIcon,
    PlusIcon,
    TrashIcon,
    ArrowLeftIcon,
    PhotoIcon,
    ChevronDownIcon,
    ChevronUpIcon
} from '@heroicons/react/24/outline';

const IMAGE_BASE_URL = 'https://omcar.peruviandress.com/storage/products';
const LOCAL_IMAGE_BASE_URL = 'http://localhost/peruvian/storage/products';

const NewCotizationView = () => {
    const navigate = useNavigate();
    const [clients, setClients] = useState([]);
    const [searchQuery, setSearchQuery] = useState('');
    const [searchResults, setSearchResults] = useState([]);
    const [loading, setLoading] = useState(false);
    const [saving, setSaving] = useState(false);

    const [selectedProducts, setSelectedProducts] = useState([]);
    const [header, setHeader] = useState({
        person_id: '0',
        cliente_extra: '',
        tiempo_entrega: '',
        validez: '15 días',
        forma_pago: '50% Adelanto - 50% Contraentrega',
        tallas_especiales: '',
        asesor_comercial: '',
        asesor_celular: '',
        observacion: '',
        servicios: '',
        aplica_igv: 'yes'
    });

    useEffect(() => {
        fetchClients();
    }, []);

    const fetchClients = async () => {
        try {
            const response = await api.get('/clients');
            setClients(response.data || []);
        } catch (error) {
            console.error('Error fetching clients:', error);
        }
    };

    const handleSearch = async (e) => {
        if (e) e.preventDefault();
        if (!searchQuery.trim()) return;
        try {
            setLoading(true);
            const response = await api.get(`/products-search?q=${searchQuery}`);
            setSearchResults(response.data || []);
        } catch (error) {
            console.error('Error searching products:', error);
        } finally {
            setLoading(false);
        }
    };

    const addProduct = (product) => {
        setSelectedProducts([...selectedProducts, {
            ...product,
            product_id: product.id,
            nombre_producto: product.name,
            cantidad: 1,
            costo: product.price_out || product.price_in || 0,
            descripcion: product.description || '',
            img_m: product.image,
            img_b: product.imgbordado,
            file_m: null,
            file_b: null,
            preview_m: product.image ? `${IMAGE_BASE_URL}/${product.image}` : null,
            preview_b: product.imgbordado ? `${IMAGE_BASE_URL}/${product.imgbordado}` : null
        }]);
        setSearchResults([]);
        setSearchQuery('');
    };

    const updateProduct = (index, field, value) => {
        const newProducts = [...selectedProducts];
        newProducts[index][field] = value;
        setSelectedProducts(newProducts);
    };

    const handleFileChange = (index, field, file) => {
        const newProducts = [...selectedProducts];
        newProducts[index][field === 'm' ? 'file_m' : 'file_b'] = file;
        newProducts[index][field === 'm' ? 'preview_m' : 'preview_b'] = URL.createObjectURL(file);
        setSelectedProducts(newProducts);
    };

    const removeProduct = (index) => {
        setSelectedProducts(selectedProducts.filter((_, i) => i !== index));
    };

    const calculateSubtotal = () => {
        return selectedProducts.reduce((acc, p) => acc + (p.cantidad * p.costo), 0);
    };

    const handleSubmit = async () => {
        if (selectedProducts.length === 0) {
            alert('Debe agregar al menos un producto');
            return;
        }
        if (header.person_id === '0' && !header.cliente_extra) {
            alert('Debe seleccionar un cliente o ingresar un nombre');
            return;
        }

        try {
            setSaving(true);
            const formData = new FormData();

            // Append header data
            Object.keys(header).forEach(key => {
                formData.append(key, header[key]);
            });

            // Append items as JSON
            formData.append('items', JSON.stringify(selectedProducts.map(p => ({
                product_id: p.product_id,
                nombre_producto: p.nombre_producto,
                cantidad: p.cantidad,
                costo: p.costo,
                descripcion: p.descripcion,
                img_m: p.img_m,
                img_b: p.img_b
            }))));

            // Append files
            selectedProducts.forEach(p => {
                if (p.file_m) formData.append(`imagen_${p.product_id}`, p.file_m);
                if (p.file_b) formData.append(`imagen_b_${p.product_id}`, p.file_b);
            });

            const response = await api.post('/cotizations', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });

            if (response.data.Result === 'OK') {
                alert('Cotización guardada correctamente');
                navigate('/cotizations');
            } else {
                alert('Error: ' + response.data.message);
            }
        } catch (error) {
            console.error('Error saving cotization:', error);
            alert('Error al guardar la cotización');
        } finally {
            setSaving(false);
        }
    };

    const subtotal = calculateSubtotal();
    const igv = header.aplica_igv === 'yes' ? subtotal * 0.18 : 0;
    const total = subtotal + igv;

    return (
        <div className="flex flex-col gap-6 pb-20">
            {/* Header */}
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-2xl font-bold text-gray-900">Nueva Cotización</h1>
                    <p className="text-sm text-gray-500 mt-0.5">Crea una propuesta comercial detallada</p>
                </div>
                <button
                    onClick={() => navigate('/cotizations')}
                    className="text-sm text-gray-500 hover:text-gray-800 flex items-center gap-1 transition-colors"
                >
                    <ArrowLeftIcon className="h-4 w-4" />
                    Volver
                </button>
            </div>

            {/* 1. Buscar Productos */}
            <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 className="text-base font-bold text-gray-800 mb-4">1. Selección de Productos</h2>
                <form onSubmit={handleSearch} className="flex gap-3 mb-4">
                    <div className="relative flex-1">
                        <MagnifyingGlassIcon className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                        <input
                            type="text"
                            className="w-full pl-10 p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm"
                            placeholder="Buscar producto por nombre o código..."
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                        />
                    </div>
                    <button
                        type="submit"
                        disabled={loading}
                        className="bg-gray-800 text-white px-6 py-2 rounded-md hover:bg-gray-700 text-sm font-medium transition-colors disabled:opacity-50"
                    >
                        {loading ? 'Buscando...' : 'Buscar'}
                    </button>
                </form>

                {searchResults.length > 0 && (
                    <div className="border border-gray-200 rounded-lg overflow-hidden animate-in fade-in slide-in-from-top-2 duration-300">
                        <table className="w-full text-sm">
                            <thead className="bg-gray-50 text-gray-600 uppercase text-xs border-b">
                                <tr>
                                    <th className="px-4 py-3 text-left">Imagen</th>
                                    <th className="px-4 py-3 text-left">Código</th>
                                    <th className="px-4 py-3 text-left">Producto</th>
                                    <th className="px-4 py-3 text-right">Precio Ref.</th>
                                    <th className="px-4 py-3 text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                                {searchResults.map(product => (
                                    <tr key={product.id} className="hover:bg-blue-50 transition-colors">
                                        <td className="px-4 py-2">
                                            <div className="w-10 h-10 bg-gray-100 rounded flex items-center justify-center overflow-hidden">
                                                {product.image ? (
                                                    <img src={`${IMAGE_BASE_URL}/${product.image}`} className="w-full h-full object-cover" />
                                                ) : (
                                                    <PhotoIcon className="w-5 h-5 text-gray-400" />
                                                )}
                                            </div>
                                        </td>
                                        <td className="px-4 py-2 font-mono text-gray-600">{product.code}</td>
                                        <td className="px-4 py-2 font-medium text-gray-800">{product.name}</td>
                                        <td className="px-4 py-2 text-right text-gray-600">S/ {product.price_in || '0.00'}</td>
                                        <td className="px-4 py-2 text-center">
                                            <button
                                                onClick={() => addProduct(product)}
                                                className="bg-blue-600 text-white text-xs px-3 py-1.5 rounded hover:bg-blue-700 transition-colors font-bold uppercase tracking-wider"
                                            >
                                                Añadir
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                )}
            </div>

            {/* 2. Lista de Items Seleccionados */}
            {selectedProducts.length > 0 && (
                <div className="space-y-4">
                    <h2 className="text-base font-bold text-gray-800 px-1">2. Detalle de Productos</h2>
                    {selectedProducts.map((product, idx) => (
                        <div key={idx} className="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col md:flex-row gap-6 animate-in zoom-in-95 duration-200">
                            <div className="flex-1 space-y-4">
                                <div className="flex justify-between items-start">
                                    <input
                                        className="text-lg font-bold text-gray-800 bg-transparent border-b border-dashed border-gray-300 focus:border-blue-500 outline-none w-full mr-4"
                                        value={product.nombre_producto}
                                        onChange={(e) => updateProduct(idx, 'nombre_producto', e.target.value)}
                                    />
                                    <button
                                        onClick={() => removeProduct(idx)}
                                        className="text-gray-400 hover:text-red-500 p-1 rounded-full hover:bg-red-50 transition-all"
                                    >
                                        <TrashIcon className="h-5 w-5" />
                                    </button>
                                </div>

                                <div className="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    <div>
                                        <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Cantidad</label>
                                        <input
                                            type="number"
                                            className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm"
                                            value={product.cantidad}
                                            onChange={(e) => updateProduct(idx, 'cantidad', parseFloat(e.target.value) || 0)}
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Precio Unit. (S/)</label>
                                        <input
                                            type="number"
                                            className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm"
                                            value={product.price_in}
                                            onChange={(e) => updateProduct(idx, 'price_in', parseFloat(e.target.value) || 0)}
                                        />
                                    </div>
                                    <div className="col-span-2 md:col-span-1">
                                        <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Total Item</label>
                                        <div className="p-2 bg-gray-50 rounded-md text-sm font-bold text-blue-600 border border-gray-200">
                                            S/ {(product.cantidad * product.price_in).toFixed(2)}
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Descripción / Especificaciones</label>
                                    <textarea
                                        className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm h-20"
                                        value={product.descripcion}
                                        onChange={(e) => updateProduct(idx, 'descripcion', e.target.value)}
                                        placeholder="Ej: Bordado en pecho, tela 100% algodón..."
                                    ></textarea>
                                </div>
                            </div>

                            <div className="w-full md:w-64 flex flex-col gap-4 border-l border-gray-100 pl-0 md:pl-6">
                                <div className="space-y-2">
                                    <label className="block text-xs font-bold text-gray-500 uppercase">Imagen Modelo</label>
                                    <div className="relative h-32 rounded-lg border-2 border-dashed border-gray-200 hover:border-blue-300 transition-colors group">
                                        {product.preview_m ? (
                                            <img src={product.preview_m} className="w-full h-full object-cover rounded-lg" />
                                        ) : (
                                            <div className="flex flex-col items-center justify-center h-full">
                                                <PhotoIcon className="h-8 w-8 text-gray-300" />
                                                <span className="text-[10px] text-gray-400 mt-1 uppercase font-bold">Sin foto</span>
                                            </div>
                                        )}
                                        <input
                                            type="file"
                                            className="absolute inset-0 opacity-0 cursor-pointer"
                                            onChange={(e) => handleFileChange(idx, 'm', e.target.files[0])}
                                        />
                                    </div>
                                </div>
                                <div className="space-y-2">
                                    <label className="block text-xs font-bold text-gray-500 uppercase">Imagen Bordado</label>
                                    <div className="relative h-32 rounded-lg border-2 border-dashed border-gray-200 hover:border-blue-300 transition-colors group">
                                        {product.preview_b ? (
                                            <img src={product.preview_b} className="w-full h-full object-cover rounded-lg" />
                                        ) : (
                                            <div className="flex flex-col items-center justify-center h-full">
                                                <PhotoIcon className="h-8 w-8 text-gray-300" />
                                                <span className="text-[10px] text-gray-400 mt-1 uppercase font-bold">Sin bordado</span>
                                            </div>
                                        )}
                                        <input
                                            type="file"
                                            className="absolute inset-0 opacity-0 cursor-pointer"
                                            onChange={(e) => handleFileChange(idx, 'b', e.target.files[0])}
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            )}

            {/* 3. Datos de la Cotización */}
            <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 className="text-base font-bold text-gray-800 mb-4">3. Información General y Condiciones</h2>

                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Cliente *</label>
                        <select
                            className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm"
                            value={header.person_id}
                            onChange={(e) => setHeader({ ...header, person_id: e.target.value })}
                        >
                            <option value="0">SELECCIONE CLIENTE...</option>
                            {clients.map(c => (
                                <option key={c.id} value={c.id}>{c.name} {c.lastname}</option>
                            ))}
                        </select>
                    </div>
                    <div>
                        <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Cliente Externo (Nombre)</label>
                        <input
                            type="text"
                            className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm"
                            placeholder="Nombre si no está registrado"
                            value={header.cliente_extra}
                            onChange={(e) => setHeader({ ...header, cliente_extra: e.target.value })}
                        />
                    </div>
                    <div>
                        <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tiempo de Entrega</label>
                        <input
                            type="text"
                            className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm"
                            placeholder="Ej: 15 días hábiles"
                            value={header.tiempo_entrega}
                            onChange={(e) => setHeader({ ...header, tiempo_entrega: e.target.value })}
                        />
                    </div>
                    <div>
                        <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Asesor Comercial</label>
                        <input
                            type="text"
                            className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm"
                            value={header.asesor_comercial}
                            onChange={(e) => setHeader({ ...header, asesor_comercial: e.target.value })}
                        />
                    </div>
                    <div>
                        <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Celular Asesor</label>
                        <input
                            type="text"
                            className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm"
                            value={header.asesor_celular}
                            onChange={(e) => setHeader({ ...header, asesor_celular: e.target.value })}
                        />
                    </div>
                    <div>
                        <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Validez de Oferta</label>
                        <input
                            type="text"
                            className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm"
                            value={header.validez}
                            onChange={(e) => setHeader({ ...header, validez: e.target.value })}
                        />
                    </div>
                    <div>
                        <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Forma de Pago</label>
                        <input
                            type="text"
                            className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm"
                            value={header.forma_pago}
                            onChange={(e) => setHeader({ ...header, forma_pago: e.target.value })}
                        />
                    </div>
                    <div className="lg:col-span-2">
                        <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Observaciones / Tallas Especiales</label>
                        <input
                            type="text"
                            className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm"
                            value={header.observacion}
                            onChange={(e) => setHeader({ ...header, observacion: e.target.value })}
                        />
                    </div>
                </div>

                <div className="mt-8 pt-6 border-t border-gray-100 grid grid-cols-1 lg:grid-cols-2 gap-8 items-end">
                    <div>
                        <label className="flex items-center space-x-3 cursor-pointer group">
                            <input
                                type="checkbox"
                                className="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                checked={header.aplica_igv === 'yes'}
                                onChange={(e) => setHeader({ ...header, aplica_igv: e.target.checked ? 'yes' : 'no' })}
                            />
                            <span className="text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">Aplicar IGV (18%)</span>
                        </label>
                    </div>

                    <div className="space-y-2">
                        <div className="flex justify-between text-sm text-gray-500">
                            <span>Subtotal:</span>
                            <span>S/ {subtotal.toFixed(2)}</span>
                        </div>
                        {header.aplica_igv === 'yes' && (
                            <div className="flex justify-between text-sm text-gray-500">
                                <span>IGV (18%):</span>
                                <span>S/ {igv.toFixed(2)}</span>
                            </div>
                        )}
                        <div className="flex justify-between text-xl font-bold text-gray-900 border-t border-gray-200 pt-2">
                            <span>Total General:</span>
                            <span className="text-blue-600">S/ {total.toFixed(2)}</span>
                        </div>
                    </div>
                </div>
            </div>

            {/* Acciones Finales */}
            <div className="flex justify-end gap-4 mt-4">
                <button
                    onClick={() => navigate('/cotizations')}
                    className="bg-white border border-gray-300 text-gray-700 font-medium px-8 py-2.5 rounded-lg hover:bg-gray-50 transition-all text-sm"
                >
                    Cancelar
                </button>
                <button
                    onClick={handleSubmit}
                    disabled={saving}
                    className="bg-blue-600 text-white font-bold px-12 py-2.5 rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all text-sm disabled:opacity-50"
                >
                    {saving ? 'Guardando...' : 'Generar Cotización'}
                </button>
            </div>
        </div>
    );
};

export default NewCotizationView;
