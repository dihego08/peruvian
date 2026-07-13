import React, { useState, useEffect, useCallback } from 'react';
import api from '../services/api';
import { 
    PencilSquareIcon, 
    TrashIcon, 
    PlusIcon, 
    XMarkIcon,
    TagIcon,
    MagnifyingGlassIcon
} from '@heroicons/react/24/outline';

const FamClassView = () => {
    const [familias, setFamilias] = useState([]);
    const [clases, setClases] = useState([]);
    const [subclases, setSubclases] = useState([]);
    const [loading, setLoading] = useState(true);
    
    // Search states
    const [searchFam, setSearchFam] = useState('');
    const [searchCla, setSearchCla] = useState('');
    const [searchSub, setSearchSub] = useState('');

    // Modal state
    const [showModal, setShowModal] = useState(false);
    const [modalType, setModalType] = useState(''); // 'familia', 'clase', 'subclase'
    const [editingItem, setEditingItem] = useState(null);
    const [form, setForm] = useState({ codigo: '', descripcion: '' });

    const fetchData = useCallback(async () => {
        try {
            setLoading(true);
            const [famRes, claRes, subRes] = await Promise.all([
                api.get('/insumos/familias'),
                api.get('/insumos/clases'),
                api.get('/insumos/subclases')
            ]);
            setFamilias(famRes.data.Records);
            setClases(claRes.data.Records);
            setSubclases(subRes.data.Records);
        } catch (error) {
            console.error("Error fetching data:", error);
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => {
        fetchData();
    }, [fetchData]);

    const handleOpenModal = (type, item = null) => {
        setModalType(type);
        if (item) {
            setEditingItem(item);
            setForm({ codigo: item.codigo, descripcion: item.descripcion });
        } else {
            setEditingItem(null);
            setForm({ codigo: '', descripcion: '' });
        }
        setShowModal(true);
    };

    const handleSave = async (e) => {
        e.preventDefault();
        try {
            let endpoint = `/insumos/${modalType}s`;
            if (editingItem) {
                const id = modalType === 'familia' ? editingItem.codigo : editingItem.id;
                await api.put(`${endpoint}/${id}`, form);
            } else {
                await api.post(endpoint, form);
            }
            setShowModal(false);
            fetchData();
        } catch (error) {
            alert(error.response?.data?.message || "Error al guardar");
        }
    };

    const handleDelete = async (type, item) => {
        if (window.confirm(`¿Seguro de eliminar esta ${type}?`)) {
            try {
                const id = type === 'familia' ? item.codigo : item.id;
                await api.delete(`/insumos/${type}s/${id}`);
                fetchData();
            } catch (error) {
                alert(error.response?.data?.message || "Error al eliminar. Posiblemente esté siendo usada.");
            }
        }
    };

    const renderTable = (title, data, type, search, setSearch) => (
        <div className="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden flex flex-col h-full">
            <div className="p-5 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h2 className="text-lg font-extrabold text-gray-800 tracking-tight">{title}</h2>
                <div className="flex items-center gap-2 w-full sm:w-auto">
                    <div className="relative flex-1">
                        <MagnifyingGlassIcon className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                        <input 
                            type="text"
                            placeholder="Filtrar..."
                            className="pl-9 pr-3 py-1.5 border border-gray-200 rounded-lg text-xs outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                        />
                    </div>
                    <button 
                        onClick={() => handleOpenModal(type)}
                        className="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 shadow-md shadow-blue-100 transition-all"
                    >
                        <PlusIcon className="h-4 w-4" />
                    </button>
                </div>
            </div>
            <div className="overflow-y-auto max-h-[400px]">
                <table className="w-full text-left">
                    <thead className="bg-gray-50 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th className="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Cod</th>
                            <th className="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Descripción</th>
                            <th className="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-50 text-xs font-medium">
                        {data.filter(i => i.descripcion.toLowerCase().includes(search.toLowerCase()) || i.codigo.toLowerCase().includes(search.toLowerCase())).map((item) => (
                            <tr key={item.id || item.codigo} className="hover:bg-blue-50/30 transition-colors group">
                                <td className="px-4 py-3 font-mono font-bold text-blue-600">{item.codigo}</td>
                                <td className="px-4 py-3 text-gray-700">{item.descripcion}</td>
                                <td className="px-4 py-3 text-right">
                                    <div className="flex justify-end gap-2">
                                        <button onClick={() => handleOpenModal(type, item)} className="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"><PencilSquareIcon className="h-5 w-5" /></button>
                                        <button onClick={() => handleDelete(type, item)} className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"><TrashIcon className="h-5 w-5" /></button>
                                    </div>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );

    return (
        <div className="flex flex-col gap-6">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-2xl font-bold text-gray-900">Familias y Clases</h1>
                    <p className="text-sm text-gray-500 mt-0.5">Configuración de categorías para el catálogo de insumos</p>
                </div>
            </div>

            <div className="grid grid-cols-1 xl:grid-cols-3 gap-6">
                {renderTable("Familias de Artículos", familias, 'familia', searchFam, setSearchFam)}
                {renderTable("Clases de Artículos", clases, 'clase', searchCla, setSearchCla)}
                {renderTable("Subclases de Artículos", subclases, 'subclase', searchSub, setSearchSub)}
            </div>

            {/* Modal */}
            {showModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
                    <div className="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in duration-200">
                        <div className="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                            <h2 className="text-xl font-extrabold text-gray-800 tracking-tight">
                                {editingItem ? 'Editar' : 'Nueva'} {modalType.charAt(0).toUpperCase() + modalType.slice(1)}
                            </h2>
                            <button onClick={() => setShowModal(false)} className="text-gray-400 hover:text-gray-600 transition-colors">
                                <XMarkIcon className="h-6 w-6" />
                            </button>
                        </div>
                        
                        <form onSubmit={handleSave} className="p-6 space-y-5">
                            <div>
                                <label className="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Código</label>
                                <input 
                                    type="text"
                                    className="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none text-sm font-mono transition-all"
                                    value={form.codigo}
                                    onChange={(e) => setForm({ ...form, codigo: e.target.value })}
                                    required
                                />
                            </div>
                            <div>
                                <label className="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Descripción</label>
                                <input 
                                    type="text"
                                    className="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none text-sm font-medium transition-all"
                                    value={form.descripcion}
                                    onChange={(e) => setForm({ ...form, descripcion: e.target.value })}
                                    required
                                />
                            </div>

                            <div className="flex justify-end gap-3 pt-4">
                                <button type="button" onClick={() => setShowModal(false)} className="px-6 py-2.5 text-gray-500 font-bold text-sm hover:text-gray-700 transition-colors">Cancelar</button>
                                <button type="submit" className="px-10 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 text-sm font-bold">
                                    {editingItem ? 'Actualizar' : 'Guardar'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
};

export default FamClassView;
