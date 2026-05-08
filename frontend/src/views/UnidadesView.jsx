import React, { useState, useEffect } from 'react';
import api from '../services/api';
import { 
    PencilSquareIcon, 
    TrashIcon, 
    PlusIcon, 
    XMarkIcon,
    ScaleIcon
} from '@heroicons/react/24/outline';

const UnidadesView = () => {
    const [unidades, setUnidades] = useState([]);
    const [loading, setLoading] = useState(true);
    const [showModal, setShowModal] = useState(false);
    const [editingUnidad, setEditingUnidad] = useState(null);
    const [form, setForm] = useState({ codigo: '', unidad: '' });

    useEffect(() => {
        fetchUnidades();
    }, []);

    const fetchUnidades = async () => {
        try {
            const response = await api.get('/insumos/unidades');
            setUnidades(response.data.Records);
            setLoading(false);
        } catch (error) {
            console.error("Error fetching unidades:", error);
            setLoading(false);
        }
    };

    const handleOpenModal = (unidad = null) => {
        if (unidad) {
            setEditingUnidad(unidad);
            setForm({ codigo: unidad.codigo, unidad: unidad.unidad });
        } else {
            setEditingUnidad(null);
            setForm({ codigo: '', unidad: '' });
        }
        setShowModal(true);
    };

    const handleSave = async (e) => {
        e.preventDefault();
        try {
            if (editingUnidad) {
                await api.put(`/insumos/unidades/${editingUnidad.codigo}`, form);
            } else {
                await api.post('/insumos/unidades', form);
            }
            setShowModal(false);
            fetchUnidades();
        } catch (error) {
            alert("Error al guardar la unidad");
        }
    };

    const handleDelete = async (codigo) => {
        if (window.confirm("¿Seguro de eliminar esta unidad?")) {
            try {
                await api.delete(`/insumos/unidades/${codigo}`);
                fetchUnidades();
            } catch (error) {
                alert("Error al eliminar");
            }
        }
    };

    return (
        <div className="flex flex-col gap-6">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-2xl font-bold text-gray-900">Unidades de Medida</h1>
                    <p className="text-sm text-gray-500 mt-0.5">Gestión de unidades para insumos y productos</p>
                </div>
                <button
                    onClick={() => handleOpenModal()}
                    className="bg-gray-800 text-white px-5 py-2.5 rounded-md hover:bg-gray-700 shadow-sm font-medium transition-colors flex items-center gap-2"
                >
                    <PlusIcon className="h-4 w-4" />
                    Nueva Unidad
                </button>
            </div>

            <div className="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                <table className="w-full text-left">
                    <thead className="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th className="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Código</th>
                            <th className="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Unidad</th>
                            <th className="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-50 text-sm">
                        {loading ? (
                            <tr><td colSpan="3" className="px-6 py-10 text-center text-gray-400 font-medium">Cargando...</td></tr>
                        ) : unidades.length === 0 ? (
                            <tr><td colSpan="3" className="px-6 py-10 text-center text-gray-400 font-medium">No hay unidades registradas.</td></tr>
                        ) : (
                            unidades.map((u) => (
                                <tr key={u.id} className="hover:bg-blue-50/30 transition-colors group">
                                    <td className="px-6 py-4 font-mono font-bold text-blue-600">{u.codigo}</td>
                                    <td className="px-6 py-4 font-medium text-gray-700">{u.unidad}</td>
                                    <td className="px-6 py-4 text-center">
                                        <div className="flex justify-center gap-2">
                                            <button 
                                                onClick={() => handleOpenModal(u)}
                                                className="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                                title="Editar"
                                            >
                                                <PencilSquareIcon className="h-5 w-5" />
                                            </button>
                                            <button 
                                                onClick={() => handleDelete(u.codigo)}
                                                className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Eliminar"
                                            >
                                                <TrashIcon className="h-5 w-5" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))
                        )}
                    </tbody>
                </table>
            </div>

            {/* Modal */}
            {showModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
                    <div className="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in duration-200">
                        <div className="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                            <h2 className="text-xl font-extrabold text-gray-800 tracking-tight">
                                {editingUnidad ? 'Editar Unidad' : 'Nueva Unidad'}
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
                                    placeholder="ej: UND"
                                    required
                                />
                            </div>
                            <div>
                                <label className="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Descripción</label>
                                <input 
                                    type="text"
                                    className="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none text-sm font-medium transition-all"
                                    value={form.unidad}
                                    onChange={(e) => setForm({ ...form, unidad: e.target.value })}
                                    placeholder="ej: UNIDADES"
                                    required
                                />
                            </div>

                            <div className="flex justify-end gap-3 pt-4">
                                <button 
                                    type="button"
                                    onClick={() => setShowModal(false)}
                                    className="px-6 py-2.5 text-gray-500 font-bold text-sm hover:text-gray-700 transition-colors"
                                >
                                    Cancelar
                                </button>
                                <button 
                                    type="submit"
                                    className="px-10 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 text-sm font-bold"
                                >
                                    {editingUnidad ? 'Actualizar' : 'Guardar'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
};

export default UnidadesView;
