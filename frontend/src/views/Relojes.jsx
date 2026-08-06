import { useEffect, useState } from "react";
import Switch from "react-switch";
import {
    createReloj, deleteReloj, getRelojes, updateReloj, actualizarEstadoReloj
} from "../services/relojesService";
import alertify from 'alertifyjs';
import { PencilSquareIcon, TrashIcon, PlusIcon, XMarkIcon } from '@heroicons/react/24/outline';

export default function Relojes() {
    const [relojes, setRelojes] = useState([]);
    const [nombre, setNombre] = useState("");
    const [loadingId, setLoadingId] = useState(null);
    const [ip, setIp] = useState("");
    const [puerto, setPuerto] = useState("");
    const [ubicacion, setUbicacion] = useState("");
    const [estado, setEstado] = useState("");
    const [show, setShow] = useState(false);
    const [idEdit, setIdEdit] = useState(null);

    useEffect(() => {
        listar();
    }, []);

    const listar = async () => {
        const data = await getRelojes();
        setRelojes(data);
    };

    const guardar = async () => {
        try {
            let res = null;
            if (idEdit) {
                res = await updateReloj(idEdit, { nombre, ip, puerto, ubicacion, estado });
            } else {
                res = await createReloj({ nombre, ip, puerto, ubicacion, estado });
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
        setNombre("");
        setIp("");
        setPuerto("");
        setUbicacion("");
        setEstado("");
        setShow(false);
    };
    const editar = (marca) => {
        setIdEdit(marca.id);
        setNombre(marca.nombre);
        setIp(marca.ip);
        setPuerto(marca.puerto);
        setUbicacion(marca.ubicacion);
        setEstado(marca.estado);
        setShow(true); // control del modal
    };
    const eliminar = async (reloj) => {
        alertify.confirm(
            "Confirmar eliminación",
            "¿Seguro que deseas eliminar este reloj <b>(" + reloj.nombre + ")</b>?",
            async function () {
                try {
                    const res = await deleteReloj(reloj.id);

                    if (res.status === "success") {
                        alertify.success(res.message);
                        listar(); // refresca la lista
                    } else {
                        alertify.error(res.message);
                    }
                } catch (err) {
                    alertify.error("Error al intentar eliminar el reloj");
                }
            },
            function () {
                alertify.message("Acción cancelada");
            }
        );
    };
    const handleEstadoChange = async (id, nuevoEstado) => {
        setLoadingId(id);
        try {
            const res = await actualizarEstadoReloj(id, nuevoEstado ? 1 : 0);
            if (res.status === "success") {
                // ✅ Actualizamos visualmente el estado del horario en el frontend
                setRelojes((prevRelojes) =>
                    prevRelojes.map((h) =>
                        h.id === id ? { ...h, estado: nuevoEstado ? 1 : 0 } : h
                    )
                );
                alertify.success(res.message);
            } else {
                alertify.error(res.message);
            }
        } catch (err) {
            console.error("Error al actualizar el estado:", err);
            alertify.error("Error al actualizar el estado");
        } finally {
            setLoadingId(null);
        }
    };

    return (
        <div className="flex flex-col gap-6">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-2xl font-bold text-gray-900">Relojes</h1>
                    <p className="text-sm text-gray-500 mt-0.5">Gestión de dispositivos de marcación</p>
                </div>
                <div className="flex gap-2">
                    <button
                        onClick={() => setShow(true)}
                        className="bg-gray-800 text-white px-5 py-2.5 rounded-md hover:bg-gray-700 shadow-sm font-medium transition-colors flex items-center gap-2"
                    >
                        <PlusIcon className="h-4 w-4" /> Nuevo Reloj
                    </button>
                </div>
            </div>

            <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left text-sm">
                        <thead className="bg-gray-50 text-gray-600 uppercase text-xs border-b border-gray-200">
                            <tr>
                                <th className="px-4 py-3">Id</th>
                                <th className="px-4 py-3">Nombre</th>
                                <th className="px-4 py-3">IP</th>
                                <th className="px-4 py-3">Puerto</th>
                                <th className="px-4 py-3">Ubicación</th>
                                <th className="px-4 py-3">Estado</th>
                                <th className="px-4 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {relojes.map((c) => (
                                <tr key={c.id} className="hover:bg-gray-50 transition-colors">
                                    <td className="px-4 py-3 font-mono">{c.id}</td>
                                    <td className="px-4 py-3 font-medium text-gray-800">{c.nombre}</td>
                                    <td className="px-4 py-3">{c.ip}</td>
                                    <td className="px-4 py-3 font-mono">{c.puerto}</td>
                                    <td className="px-4 py-3">{c.ubicacion}</td>
                                    <td className="px-4 py-3">
                                        <Switch
                                            checked={c.estado === 1}
                                            onChange={(checked) => handleEstadoChange(c.id, checked)}
                                            onColor="#28a745"
                                            offColor="#ccc"
                                            uncheckedIcon={false}
                                            checkedIcon={false}
                                            disabled={loadingId === c.id}
                                        />
                                    </td>
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
                            <h2 className="text-lg font-bold text-gray-900">{idEdit ? "Editar Reloj" : "Nuevo Reloj"}</h2>
                            <button onClick={cerrarModal} className="text-gray-400 hover:text-gray-700 p-1.5 rounded-lg hover:bg-gray-200 transition-colors">
                                <XMarkIcon className="h-5 w-5" />
                            </button>
                        </div>
                        <div className="p-6 space-y-4">
                            <div>
                                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nombre</label>
                                <input
                                    type="text"
                                    className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm outline-none transition-all"
                                    value={nombre}
                                    onChange={(e) => setNombre(e.target.value)}
                                />
                            </div>
                            <div>
                                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">IP</label>
                                <input
                                    type="text"
                                    className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm font-mono outline-none transition-all"
                                    value={ip}
                                    onChange={(e) => setIp(e.target.value)}
                                />
                            </div>
                            <div>
                                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Puerto</label>
                                <input
                                    type="text"
                                    className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm font-mono outline-none transition-all"
                                    value={puerto}
                                    onChange={(e) => setPuerto(e.target.value)}
                                />
                            </div>
                            <div>
                                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Ubicación</label>
                                <input
                                    type="text"
                                    className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm outline-none transition-all"
                                    value={ubicacion}
                                    onChange={(e) => setUbicacion(e.target.value)}
                                />
                            </div>
                            <div>
                                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Estado</label>
                                <div className="flex items-center gap-3 mt-1">
                                    <Switch
                                        checked={estado === 1}
                                        onChange={(checked) => setEstado(checked ? 1 : 0)}
                                        onColor="#22c55e"
                                        offColor="#d1d5db"
                                        checkedIcon={false}
                                        uncheckedIcon={false}
                                        height={20}
                                        width={40}
                                    />
                                    <span className={`text-sm font-medium ${estado === 1 ? "text-green-600" : "text-gray-500"}`}>
                                        {estado === 1 ? "Activo" : "Inactivo"}
                                    </span>
                                </div>
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