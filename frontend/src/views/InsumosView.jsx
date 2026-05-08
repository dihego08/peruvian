import React, { useState, useEffect } from 'react';
import api from '../services/api';
import {
    PencilSquareIcon,
    TrashIcon,
    PlusIcon,
    CubeIcon,
    MagnifyingGlassIcon,
    CircleStackIcon,
    XMarkIcon,
    CheckIcon
} from '@heroicons/react/24/outline';

const EMPTY_INSUMO = {
    insumo: '',
    familia: '0',
    clase: '0',
    subclase: '0',
    codigo: ''
};

const EMPTY_STOCK = {
    id_insumo: '',
    id_proveedor: '',
    fecha: new Date().toISOString().split('T')[0],
    descripcion: '',
    codigo_unidad: '',
    stock: '',
    precio: ''
};

const InsumosView = () => {
    const [insumos, setInsumos] = useState([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');

    // Dropdowns
    const [familias, setFamilias] = useState([]);
    const [clases, setClases] = useState([]);
    const [subclases, setSubclases] = useState([]);
    const [unidades, setUnidades] = useState([]);
    const [providers, setProviders] = useState([]);

    // Modals
    const [showInsumoModal, setShowInsumoModal] = useState(false);
    const [showStockModal, setShowStockModal] = useState(false);
    const [editingInsumoId, setEditingInsumoId] = useState(null);
    const [editingStockId, setEditingStockId] = useState(null);

    // Forms
    const [insumoForm, setInsumoForm] = useState(EMPTY_INSUMO);
    const [stockForm, setStockForm] = useState(EMPTY_STOCK);
    const [selectedInsumoForStock, setSelectedInsumoForStock] = useState(null);
    const [stockList, setStockList] = useState([]);
    const [stockLoading, setStockLoading] = useState(false);

    useEffect(() => {
        fetchInsumos();
        fetchDropdowns();
    }, []);

    const fetchInsumos = async () => {
        try {
            setLoading(true);
            const res = await api.get('/insumos');
            setInsumos(res.data.Records || []);
        } catch (e) {
            console.error(e);
        } finally {
            setLoading(false);
        }
    };

    const fetchDropdowns = async () => {
        try {
            const [f, c, s, u, p] = await Promise.all([
                api.get('/insumos/familias'),
                api.get('/insumos/clases'),
                api.get('/insumos/subclases'),
                api.get('/insumos/unidades'),
                api.get('/insumos/providers')
            ]);
            setFamilias(f.data.Records || []);
            setClases(c.data.Records || []);
            setSubclases(s.data.Records || []);
            setUnidades(u.data.Records || []);
            setProviders(p.data.Records || []);
        } catch (e) {
            console.error(e);
        }
    };

    const handleOpenInsumoModal = (insumo = null) => {
        if (insumo) {
            setEditingInsumoId(insumo.id);
            setInsumoForm({
                insumo: insumo.insumo,
                familia: insumo.familia,
                clase: insumo.clase,
                subclase: insumo.subclase,
                codigo: insumo.codigo
            });
        } else {
            setEditingInsumoId(null);
            setInsumoForm(EMPTY_INSUMO);
        }
        setShowInsumoModal(true);
    };

    const handleInsumoFormChange = (field, value) => {
        const updatedForm = { ...insumoForm, [field]: value };

        // Auto-concatenate name only if we are creating a new insumo AND a dropdown changed
        if (!editingInsumoId && ['familia', 'clase', 'subclase'].includes(field)) {
            const f = familias.find(i => i.codigo === (field === 'familia' ? value : updatedForm.familia))?.descripcion || '';
            const c = clases.find(i => i.codigo === (field === 'clase' ? value : updatedForm.clase))?.descripcion || '';
            const s = subclases.find(i => i.codigo === (field === 'subclase' ? value : updatedForm.subclase))?.descripcion || '';

            updatedForm.insumo = `${f} ${c} ${s}`.trim();
        }

        setInsumoForm(updatedForm);
    };

    const getGeneratedCode = () => {
        const f = insumoForm.familia !== '0' ? insumoForm.familia : '';
        const c = insumoForm.clase !== '0' ? insumoForm.clase : '';
        const s = insumoForm.subclase !== '0' ? insumoForm.subclase : '';
        const code = insumoForm.codigo || '';
        return f + c + s + code;
    };

    const handleSaveInsumo = async (e) => {
        e.preventDefault();
        try {
            if (editingInsumoId) {
                await api.put(`/insumos/${editingInsumoId}`, insumoForm);
            } else {
                await api.post('/insumos', insumoForm);
            }
            setShowInsumoModal(false);
            fetchInsumos();
        } catch (e) {
            alert('Error al guardar el insumo');
        }
    };

    const handleDeleteInsumo = async (id) => {
        if (!window.confirm('¿Eliminar este insumo?')) return;
        try {
            await api.delete(`/insumos/${id}`);
            fetchInsumos();
        } catch (e) {
            alert('Error al eliminar');
        }
    };

    // Stock Methods
    const handleOpenStockModal = async (insumo) => {
        setSelectedInsumoForStock(insumo);
        setStockForm({ ...EMPTY_STOCK, id_insumo: insumo.id });
        setShowStockModal(true);
        fetchStockList(insumo.id);
    };

    const fetchStockList = async (id_insumo) => {
        try {
            setStockLoading(true);
            const res = await api.get(`/insumos/${id_insumo}/stock`);
            setStockList(res.data.stock || []);
        } catch (e) {
            console.error(e);
        } finally {
            setStockLoading(false);
        }
    };

    const handleSaveStock = async (e) => {
        e.preventDefault();
        try {
            if (editingStockId) {
                await api.put(`/insumos/stock/${editingStockId}`, stockForm);
            } else {
                await api.post('/insumos/stock', stockForm);
            }
            setStockForm({ ...EMPTY_STOCK, id_insumo: selectedInsumoForStock.id });
            setEditingStockId(null);
            fetchStockList(selectedInsumoForStock.id);
            fetchInsumos(); // Update main list too
        } catch (e) {
            alert('Error al guardar el stock');
        }
    };

    const handleEditStock = (s) => {
        setEditingStockId(s.id);
        setStockForm({
            id_insumo: selectedInsumoForStock.id,
            id_proveedor: s.id_proveedor || '',
            fecha: s.fecha || new Date().toISOString().split('T')[0],
            descripcion: s.descripcion || '',
            codigo_unidad: s.codigo_unidad || '',
            stock: s.stock || '',
            precio: s.precio || ''
        });
    };

    const handleDeleteStock = async (id) => {
        if (!window.confirm('¿Eliminar este registro de stock?')) return;
        try {
            await api.delete(`/insumos/stock/${id}`);
            fetchStockList(selectedInsumoForStock.id);
            fetchInsumos();
        } catch (e) {
            alert('Error al eliminar stock');
        }
    };

    const filteredInsumos = insumos.filter(i =>
        i.insumo.toLowerCase().includes(search.toLowerCase()) ||
        i.codigo.toLowerCase().includes(search.toLowerCase())
    );

    const totalGeneral = filteredInsumos.reduce((acc, curr) => acc + (parseFloat(curr.total_to) || 0), 0);

    return (
        <div className="flex flex-col gap-6">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-2xl font-bold text-gray-900">Gestión de Insumos</h1>
                    <p className="text-sm text-gray-500 mt-0.5">Control de materiales, stock y precios por proveedor</p>
                </div>
                <button
                    onClick={() => handleOpenInsumoModal()}
                    className="bg-gray-800 text-white px-5 py-2.5 rounded-md hover:bg-gray-700 shadow-sm font-medium transition-colors flex items-center gap-2"
                >
                    <PlusIcon className="h-4 w-4" />
                    Nuevo Insumo
                </button>
            </div>

            <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex gap-4">
                <div className="relative flex-1">
                    <MagnifyingGlassIcon className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                    <input
                        type="text"
                        className="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm w-full"
                        placeholder="Buscar por nombre o código de insumo..."
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                    />
                </div>
                <div className="bg-blue-50 px-4 py-2 rounded-lg border border-blue-100 flex flex-col justify-center">
                    <span className="text-[10px] uppercase font-bold text-blue-600 leading-none">Inversión Total</span>
                    <span className="text-lg font-black text-blue-800 leading-none mt-1">S/ {totalGeneral.toFixed(2)}</span>
                </div>
            </div>

            <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-sm text-left">
                        <thead className="bg-gray-50 text-gray-600 uppercase text-xs font-bold border-b border-gray-200">
                            <tr>
                                <th className="px-6 py-4">Código</th>
                                <th className="px-6 py-4">Insumo</th>
                                <th className="px-6 py-4">Precio Prom.</th>
                                <th className="px-6 py-4">Stock Total</th>
                                <th className="px-6 py-4">Total Valorizado</th>
                                <th className="px-6 py-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {loading ? (
                                <tr><td colSpan="6" className="px-6 py-10 text-center text-gray-400">Cargando datos...</td></tr>
                            ) : filteredInsumos.length === 0 ? (
                                <tr><td colSpan="6" className="px-6 py-10 text-center text-gray-400">No se encontraron insumos.</td></tr>
                            ) : (
                                filteredInsumos.map((insumo) => (
                                    <tr key={insumo.id} className="hover:bg-gray-50 transition-colors">
                                        <td className="px-6 py-4 font-mono text-gray-600">
                                            {insumo.familia}{insumo.clase}{insumo.subclase}{insumo.codigo}
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="font-medium text-gray-900">{insumo.insumo}</div>
                                        </td>
                                        <td className="px-6 py-4 text-gray-600">
                                            S/ {(insumo.precio_total).toFixed(2)}
                                        </td>
                                        <td className="px-6 py-4">
                                            <span className={`px-2 py-1 rounded-full text-xs font-bold ${insumo.stock_total > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`}>
                                                {insumo.stock_total.toFixed(2)}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 font-bold text-gray-800">
                                            S/ {(insumo.total_to).toFixed(2)}
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex justify-center gap-2">
                                                <button
                                                    onClick={() => handleOpenStockModal(insumo)}
                                                    className="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                    title="Ver/Gestionar Stock"
                                                >
                                                    <CircleStackIcon className="h-5 w-5" />
                                                </button>
                                                <button
                                                    onClick={() => handleOpenInsumoModal(insumo)}
                                                    className="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                                    title="Editar Insumo"
                                                >
                                                    <PencilSquareIcon className="h-5 w-5" />
                                                </button>
                                                <button
                                                    onClick={() => handleDeleteInsumo(insumo.id)}
                                                    className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Eliminar Insumo"
                                                >
                                                    <TrashIcon className="h-5 w-5" />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                        {!loading && filteredInsumos.length > 0 && (
                            <tfoot className="bg-gray-50 font-bold border-t border-gray-200">
                                <tr>
                                    <td colSpan="4" className="px-6 py-4 text-right text-gray-600">TOTAL VALORIZADO GENERAL:</td>
                                    <td className="px-6 py-4 text-blue-600 text-lg">S/ {totalGeneral.toFixed(2)}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        )}
                    </table>
                </div>
            </div>

            {/* Insumo Modal (Add/Edit) */}
            {showInsumoModal && (
                <div className="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div className="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden animate-in zoom-in-95 duration-200">
                        <div className="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h3 className="text-xl font-bold text-gray-800">
                                {editingInsumoId ? 'Editar Insumo' : 'Nuevo Insumo'}
                            </h3>
                            <button onClick={() => setShowInsumoModal(false)} className="text-gray-400 hover:text-gray-600 transition-colors">
                                <XMarkIcon className="h-6 w-6" />
                            </button>
                        </div>
                        <form onSubmit={handleSaveInsumo} className="p-8 space-y-6">
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Familia</label>
                                    <select
                                        className="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm"
                                        value={insumoForm.familia}
                                        onChange={(e) => handleInsumoFormChange('familia', e.target.value)}
                                    >
                                        <option value="0">Seleccionar...</option>
                                        {familias.map(f => <option key={f.id} value={f.codigo}>{f.descripcion}</option>)}
                                    </select>
                                </div>
                                <div>
                                    <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Clase</label>
                                    <select
                                        className="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm"
                                        value={insumoForm.clase}
                                        onChange={(e) => handleInsumoFormChange('clase', e.target.value)}
                                    >
                                        <option value="0">Seleccionar...</option>
                                        {clases.map(c => <option key={c.id} value={c.codigo}>{c.descripcion}</option>)}
                                    </select>
                                </div>
                                <div>
                                    <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Subclase</label>
                                    <select
                                        className="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm"
                                        value={insumoForm.subclase}
                                        onChange={(e) => handleInsumoFormChange('subclase', e.target.value)}
                                    >
                                        <option value="0">Seleccionar...</option>
                                        {subclases.map(s => <option key={s.id} value={s.codigo}>{s.descripcion}</option>)}
                                    </select>
                                </div>
                            </div>

                            <div className="bg-blue-50 p-4 rounded-xl border border-blue-100 flex items-center justify-between">
                                <div>
                                    <span className="text-[10px] font-bold text-blue-400 uppercase tracking-widest block mb-1">Previsualización del Código</span>
                                    <span className="text-xl font-mono font-bold text-blue-700 tracking-tighter">
                                        {getGeneratedCode() || '---'}
                                    </span>
                                </div>
                                <div className="text-right">
                                    <span className="text-[10px] font-bold text-blue-400 uppercase tracking-widest block mb-1">Código Base</span>
                                    <input
                                        type="text"
                                        className="w-24 p-1.5 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm font-mono text-center bg-white"
                                        value={insumoForm.codigo}
                                        onChange={(e) => handleInsumoFormChange('codigo', e.target.value)}
                                        placeholder="001"
                                    />
                                </div>
                            </div>

                            <div>
                                <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Descripción del Insumo</label>
                                <input
                                    type="text"
                                    className="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm"
                                    value={insumoForm.insumo}
                                    onChange={(e) => handleInsumoFormChange('insumo', e.target.value)}
                                    placeholder="Nombre del material o insumo"
                                />
                            </div>

                            <div className="flex justify-end gap-3 pt-4">
                                <button
                                    type="button"
                                    onClick={() => setShowInsumoModal(false)}
                                    className="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium"
                                >
                                    Cancelar
                                </button>
                                <button
                                    type="submit"
                                    className="px-10 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 text-sm font-bold"
                                >
                                    {editingInsumoId ? 'Actualizar' : 'Guardar'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

            {/* Stock Modal */}
            {showStockModal && (
                <div className="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div className="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-hidden flex flex-col animate-in zoom-in-95 duration-200">
                        <div className="p-6 border-b border-gray-100 flex justify-between items-center bg-blue-600 text-white">
                            <div>
                                <h3 className="text-xl font-bold">Gestión de Stock: {selectedInsumoForStock?.insumo}</h3>
                                <p className="text-blue-100 text-xs mt-1">Registra entradas y precios por cada proveedor</p>
                            </div>
                            <button onClick={() => setShowStockModal(false)} className="text-white/80 hover:text-white transition-colors">
                                <XMarkIcon className="h-6 w-6" />
                            </button>
                        </div>

                        <div className="flex-1 overflow-y-auto p-8 space-y-8">
                            {/* Stock Form */}
                            <div className="bg-gray-50 p-6 rounded-xl border border-gray-200">
                                <h4 className="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider flex items-center gap-2">
                                    <PlusIcon className="h-5 w-5" />
                                    {editingStockId ? 'Editar Registro' : 'Nueva Entrada'}
                                </h4>
                                <form onSubmit={handleSaveStock} className="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div className="md:col-span-2">
                                        <label className="block text-[10px] font-bold text-gray-400 uppercase mb-1">Proveedor</label>
                                        <select
                                            className="w-full p-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none text-sm"
                                            value={stockForm.id_proveedor}
                                            onChange={(e) => setStockForm({ ...stockForm, id_proveedor: e.target.value })}
                                        >
                                            <option value="">Seleccionar Proveedor...</option>
                                            {providers.map(p => <option key={p.id} value={p.id}>{p.name}</option>)}
                                        </select>
                                    </div>
                                    <div>
                                        <label className="block text-[10px] font-bold text-gray-400 uppercase mb-1">Fecha</label>
                                        <input
                                            type="date"
                                            className="w-full p-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none text-sm"
                                            value={stockForm.fecha}
                                            onChange={(e) => setStockForm({ ...stockForm, fecha: e.target.value })}
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-[10px] font-bold text-gray-400 uppercase mb-1">Unidad</label>
                                        <select
                                            className="w-full p-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none text-sm"
                                            value={stockForm.codigo_unidad}
                                            onChange={(e) => setStockForm({ ...stockForm, codigo_unidad: e.target.value })}
                                        >
                                            <option value="">Und...</option>
                                            {unidades.map(u => <option key={u.id} value={u.codigo}>{u.unidad}</option>)}
                                        </select>
                                    </div>
                                    <div className="md:col-span-2">
                                        <label className="block text-[10px] font-bold text-gray-400 uppercase mb-1">Descripción / Notas</label>
                                        <input
                                            type="text"
                                            className="w-full p-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none text-sm"
                                            placeholder="Detalle de la compra..."
                                            value={stockForm.descripcion}
                                            onChange={(e) => setStockForm({ ...stockForm, descripcion: e.target.value })}
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-[10px] font-bold text-gray-400 uppercase mb-1">Stock / Cantidad</label>
                                        <input
                                            type="number"
                                            className="w-full p-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none text-sm"
                                            value={stockForm.stock}
                                            onChange={(e) => setStockForm({ ...stockForm, stock: e.target.value })}
                                        />
                                    </div>
                                    <div className="relative">
                                        <label className="block text-[10px] font-bold text-gray-400 uppercase mb-1">Precio Unit. (S/)</label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            className="w-full p-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none text-sm"
                                            value={stockForm.precio}
                                            onChange={(e) => setStockForm({ ...stockForm, precio: e.target.value })}
                                        />
                                        <button
                                            type="submit"
                                            className="absolute -right-2 top-1/2 bg-green-500 text-white p-2 rounded-lg hover:bg-green-600 shadow-md shadow-green-100 transition-all"
                                        >
                                            <CheckIcon className="h-5 w-5" />
                                        </button>
                                    </div>
                                </form>
                            </div>

                            {/* Stock Table */}
                            <div className="space-y-4">
                                <h4 className="text-sm font-bold text-gray-700 uppercase tracking-wider">Historial de Registros</h4>
                                <div className="border border-gray-200 rounded-xl overflow-hidden">
                                    <table className="w-full text-sm text-left">
                                        <thead className="bg-gray-50 text-gray-500 text-[10px] font-bold uppercase tracking-widest border-b">
                                            <tr>
                                                <th className="px-6 py-3">Proveedor</th>
                                                <th className="px-6 py-3">Descripción</th>
                                                <th className="px-6 py-3">Und.</th>
                                                <th className="px-6 py-3 text-right">Cant.</th>
                                                <th className="px-6 py-3 text-right">Precio</th>
                                                <th className="px-6 py-3 text-center">Fecha</th>
                                                <th className="px-6 py-3 text-center"></th>
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y divide-gray-100">
                                            {stockLoading ? (
                                                <tr><td colSpan="7" className="px-6 py-8 text-center text-gray-400">Cargando historial...</td></tr>
                                            ) : stockList.length === 0 ? (
                                                <tr><td colSpan="7" className="px-6 py-8 text-center text-gray-400 italic">No hay registros de stock para este insumo.</td></tr>
                                            ) : (
                                                stockList.map((s) => (
                                                    <tr key={s.id} className="hover:bg-gray-50 transition-colors">
                                                        <td className="px-6 py-3 font-medium">{s.proveedor}</td>
                                                        <td className="px-6 py-3 text-gray-500 italic">{s.descripcion}</td>
                                                        <td className="px-6 py-3 text-gray-500">{s.codigo_unidad}</td>
                                                        <td className="px-6 py-3 text-right font-bold text-blue-600">{s.stock}</td>
                                                        <td className="px-6 py-3 text-right text-gray-600">S/ {parseFloat(s.precio).toFixed(2)}</td>
                                                        <td className="px-6 py-3 text-center text-gray-400 text-xs">{s.fecha}</td>
                                                        <td className="px-6 py-3 text-center">
                                                            <div className="flex gap-1 justify-center">
                                                                <button onClick={() => handleEditStock(s)} className="p-1.5 text-amber-600 hover:bg-amber-50 rounded transition-colors">
                                                                    <PencilSquareIcon className="h-4 w-4" />
                                                                </button>
                                                                <button onClick={() => handleDeleteStock(s.id)} className="p-1.5 text-red-600 hover:bg-red-50 rounded transition-colors">
                                                                    <TrashIcon className="h-4 w-4" />
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                ))
                                            )}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div className="p-6 border-t border-gray-100 flex justify-end bg-gray-50">
                            <button
                                onClick={() => setShowStockModal(false)}
                                className="px-8 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors text-sm font-bold shadow-lg shadow-gray-200"
                            >
                                Cerrar Panel
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
};

export default InsumosView;
