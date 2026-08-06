import { useEffect, useState } from "react";
import Switch from "react-switch";
import {
    getTiposPermisos,
    createTipoPermiso,
    updateTipoPermiso,
    deleteTipoPermiso
} from "../services/tiposPermisosService";
import alertify from 'alertifyjs';
import { PencilSquareIcon, TrashIcon, PlusIcon, XMarkIcon } from '@heroicons/react/24/outline';

export default function TiposPermisos() {
    const [tipos_permisos, setTiposPermisos] = useState([]);
    const [tipo, setTipo] = useState("");
    const [show, setShow] = useState(false);
    const [idEdit, setIdEdit] = useState(null);

    useEffect(() => {
        listar();
    }, []);

    const listar = async () => {
        const data = await getTiposPermisos();
        setTiposPermisos(data);
    };

    const guardar = async () => {
        try {
            let res = null;
            if (idEdit) {
                res = await updateTipoPermiso(idEdit, { tipo });
            } else {
                res = await createTipoPermiso({ tipo });
            }
            if (res.status === "success") {
                alertify.success(res.message);
                listar();
                cerrarModal();
            } else {
                alertify.error(res.message);
            }
        } catch (err) {
            alertify.error("Error al intentar guardar el reloj " + err);
        }
    };
    const cerrarModal = () => {
        setIdEdit(null);
        setTipo("");
        setShow(false);
    };
    const editar = (marca) => {
        setIdEdit(marca.id);
        setTipo(marca.tipo);
        setShow(true); // control del modal
    };
    const eliminar = async (tipo_permiso) => {
        alertify.confirm(
            "Confirmar eliminación",
            "¿Seguro que deseas eliminar este tipo de permiso <b>(" + tipo_permiso.tipo + ")</b>?",
            async function () {
                try {
                    const res = await deleteTipoPermiso(tipo_permiso.id);

                    if (res.status === "success") {
                        alertify.success(res.message);
                        listar(); // refresca la lista
                    } else {
                        alertify.error(res.message);
                    }
                } catch (err) {
                    alertify.error("Error al intentar eliminar el tipo de permiso");
                }
            },
            function () {
                alertify.message("Acción cancelada");
            }
        );
    };

    return (
        <div className="flex flex-col gap-6">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-2xl font-bold text-gray-900">Tipos de Permiso</h1>
                    <p className="text-sm text-gray-500 mt-0.5">Gestión de tipos de permisos</p>
                </div>
                <div className="flex gap-2">
                    <button
                        onClick={() => setShow(true)}
                        className="bg-gray-800 text-white px-5 py-2.5 rounded-md hover:bg-gray-700 shadow-sm font-medium transition-colors flex items-center gap-2"
                    >
                        <PlusIcon className="h-4 w-4" /> Nuevo Tipo de Permiso
                    </button>
                </div>
            </div>

            <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left text-sm">
                        <thead className="bg-gray-50 text-gray-600 uppercase text-xs border-b border-gray-200">
                            <tr>
                                <th className="px-4 py-3">Id</th>
                                <th className="px-4 py-3">Tipo de Permiso</th>
                                <th className="px-4 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {tipos_permisos.map((c) => (
                                <tr key={c.id} className="hover:bg-gray-50 transition-colors">
                                    <td className="px-4 py-3 font-mono">{c.id}</td>
                                    <td className="px-4 py-3 font-medium text-gray-800">{c.tipo}</td>
                                    <td className="px-4 py-3 text-center">
                                        <div className="flex items-center justify-center gap-2">
                                            <button
                                                onClick={() => editar(c)}
                                                title="Editar"
                                                className="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                            >
                                                <PencilSquareIcon className="h-5 w-5" />
                                            </button>
                                            <button
                                                onClick={() => eliminar(c)}
                                                title="Eliminar"
                                                className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            >
                                                <TrashIcon className="h-5 w-5" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Modal Custom (Tailwind) */}
            {show && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div className="absolute inset-0 bg-black/50 backdrop-blur-sm" onClick={cerrarModal}></div>
                    <div className="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
                        <div className="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 className="text-lg font-bold text-gray-900">{idEdit ? "Editar Tipo de Permiso" : "Nuevo Tipo de Permiso"}</h2>
                            <button onClick={cerrarModal} className="text-gray-400 hover:text-gray-700 p-1.5 rounded-lg hover:bg-gray-200 transition-colors">
                                <XMarkIcon className="h-5 w-5" />
                            </button>
                        </div>
                        <div className="p-6 space-y-4">
                            <div>
                                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tipo</label>
                                <input
                                    type="text"
                                    className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm outline-none transition-all"
                                    value={tipo}
                                    onChange={(e) => setTipo(e.target.value)}
                                />
                            </div>
                        </div>
                        <div className="flex justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50">
                            <button
                                onClick={cerrarModal}
                                className="px-5 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium text-sm transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                onClick={guardar}
                                className="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium text-sm transition-colors"
                            >
                                Guardar
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}