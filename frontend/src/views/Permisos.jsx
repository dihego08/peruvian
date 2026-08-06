import { useEffect, useState } from "react";
import {
    createPermiso, deletePermiso, getPermisos, updatePermiso, actualizarEstadoPermiso
} from "../services/permisosService";
import alertify from 'alertifyjs';
import FechaPicker from "../components/FechaPicker"; // el componente anterior
import SelectColaborador from "../components/SelectColaborador";
import SelectTipoPermiso from "../components/SelectTipoPermiso";
import Switch from "react-switch";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";
import { saveAs } from "file-saver";
import * as XLSX from "xlsx";
import DataTable from "react-data-table-component";
import { PencilSquareIcon, TrashIcon, PlusIcon, ArrowDownTrayIcon, XMarkIcon } from '@heroicons/react/24/outline';

export default function Permisos() {
    const [permisos, setPermisos] = useState([]);
    const [loadingId, setLoadingId] = useState(null);
    const [id_colaborador, setIdColaborador] = useState("");
    const [fecha_inicio, setFechaInicio] = useState("");
    const [fecha_fin, setFechaFin] = useState("");
    const [motivo, setMotivo] = useState("");
    const [id_tipo, setIdTipo] = useState("");
    const [estado, setEstado] = useState("");
    const [filterText, setFilterText] = useState("");
    const [show, setShow] = useState(false);
    const [hora_inicio, setHoraInicio] = useState("");
    const [hora_fin, setHoraFin] = useState("");
    const [idEdit, setIdEdit] = useState(null);

    const handleEstadoChange = async (id, nuevoEstado) => {
        setLoadingId(id);
        try {
            const res = await actualizarEstadoPermiso(id, nuevoEstado ? 1 : 0);
            if (res.status === "success") {
                // ✅ Actualizamos visualmente el estado del horario en el frontend
                setPermisos((prevPermisos) =>
                    prevPermisos.map((h) =>
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
    useEffect(() => {
        listar();
    }, []);
    const exportToPDF = () => {
        const doc = new jsPDF({
            orientation: "landscape",
            unit: "mm",
            format: "a4",
        });
        doc.text(`Permisos`, 14, 15);
        const tableColumn = [
            "ID",
            "Colaborador",
            "Fecha Inicio",
            "Fecha Fin",
            "Hora Inicio",
            "Hora Fin",
            "Motivo",
            "Tipo",
            "Estado",
        ];
        const tableRows = filteredData.map((row) => [
            row.id,
            row.colaborador.nombres + " " + row.colaborador.apellido_paterno + " " + row.colaborador.apellido_materno,
            row.fecha_inicio,
            row.fecha_fin,
            row.hora_inicio,
            row.hora_fin,
            row.motivo,
            row.tipo.tipo,
            row.estado === 1 ? "Activo" : "Inactivo",
        ]);
        autoTable(doc, {
            head: [tableColumn],
            body: tableRows,
            startY: 25,
        });
        doc.save(`permisos.pdf`);
    };
    // Filtrado simple por fecha o estado
    const filteredData = permisos.filter(
        (item) =>
            item.colaborador.nombres?.toLowerCase().includes(filterText.toLowerCase()) ||
            item.tipo.tipo?.toLowerCase().includes(filterText.toLowerCase())
    );
    const subHeaderComponent = (
        <div className="w-full max-w-md">
            <input
                type="text"
                className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm outline-none transition-all"
                placeholder="Buscar por fecha o estado..."
                value={filterText}
                onChange={(e) => setFilterText(e.target.value)}
            />
        </div>
    );
    const exportToExcel = () => {
        // Crear hoja Excel
        const ws = XLSX.utils.json_to_sheet(filteredData);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Reporte");

        // Generar archivo Excel y descargarlo
        const wbout = XLSX.write(wb, { bookType: "xlsx", type: "array" });
        const blob = new Blob([wbout], { type: "application/octet-stream" });
        saveAs(blob, `permisos.xlsx`);
    };
    const columns = [
        {
            name: "ID",
            selector: (row) => row.id,
            sortable: true,
        },
        {
            name: "Colaborador",
            selector: (row) => row.colaborador.nombres + " " + row.colaborador.apellido_paterno + " " + row.colaborador.apellido_materno,
            sortable: true,
        },
        {
            name: "Fecha Inicio",
            selector: (row) => row.fecha_inicio,
            sortable: true,
        },
        {
            name: "Fecha Fin",
            selector: (row) => row.fecha_fin,
            sortable: true,
        },
        {
            name: "Hora Inicio",
            selector: (row) => row.hora_inicio,
            sortable: true,
        },
        {
            name: "Hora Fin",
            selector: (row) => row.hora_fin,
            sortable: true,
        },
        {
            name: "Motivo",
            selector: (row) => row.motivo,
            sortable: true,
        },
        {
            name: "Tipo",
            selector: (row) => row.tipo.tipo,
            sortable: true,
        },
        {
            name: "Estado",
            cell: (row) => (
                <Switch
                    checked={row.estado === 1}
                    onChange={(checked) => handleEstadoChange(row.id, checked)}
                    onColor="#22c55e"
                    offColor="#d1d5db"
                    uncheckedIcon={false}
                    checkedIcon={false}
                    disabled={loadingId === row.id}
                />
            ),
            ignoreRowClick: true,
            allowOverflow: true,
            center: true,
        },
        {
            name: "Acciones",
            cell: (row) => (
                <div className="flex items-center justify-center gap-2">
                    <button
                        onClick={() => editar(row)}
                        title="Editar"
                        className="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                    >
                        <PencilSquareIcon className="h-5 w-5" />
                    </button>
                    <button
                        onClick={() => eliminar(row)}
                        title="Eliminar"
                        className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                    >
                        <TrashIcon className="h-5 w-5" />
                    </button>
                </div>
            ),
            ignoreRowClick: true,
            allowOverflow: true,
            button: true,
            width: "100px",
        },
    ];
    const listar = async () => {
        const data = await getPermisos();
        setPermisos(data);
    };

    const guardar = async () => {

        const fechaInicioFormateada = fecha_inicio instanceof Date
            ? fecha_inicio.toISOString().split("T")[0] // → '2025-11-22'
            : fecha_inicio.split("T")[0]; // por si viene como string ISO
        const fechaFinFormateada = fecha_fin instanceof Date
            ? fecha_fin.toISOString().split("T")[0] // → '2025-11-22'
            : fecha_fin.split("T")[0]; // por si viene como string ISO
        try {
            const payload = {
                id_colaborador: id_colaborador.id,
                fecha_inicio: fechaInicioFormateada || null,
                fecha_fin: fechaFinFormateada || null,
                motivo: motivo,
                id_tipo: id_tipo,
                hora_inicio: hora_inicio || null,
                hora_fin: hora_fin || null,
                estado: estado
            };
            let res = null;
            if (idEdit) {
                res = await updatePermiso(idEdit, payload);
            } else {
                res = await createPermiso(payload);
            }
            if (res.status === "success") {
                alertify.success(res.message);
                listar();
                cerrarModal();
            } else {
                alertify.error(res.message);
            }
        } catch (err) {
            alertify.error("Error al intentar guardar el permiso " + err);
        }
    };
    const cerrarModal = () => {
        setIdEdit(null);
        setIdColaborador({ id: "" });
        setFechaInicio("");
        setFechaFin("");
        setMotivo("");
        setIdTipo("");
        setHoraInicio("");
        setHoraFin("");
        setEstado("");
        setShow(false);
    };
    const editar = (permiso) => {
        setIdEdit(permiso.id);
        setIdColaborador({ id: permiso.id_colaborador });
        setFechaInicio(permiso.fecha_inicio ? new Date(`${permiso.fecha_inicio}T00:00:00`) : null);
        setFechaFin(permiso.fecha_fin ? new Date(`${permiso.fecha_fin}T00:00:00`) : null);
        setMotivo(permiso.motivo);
        setHoraInicio(permiso.hora_inicio || "");
        setHoraFin(permiso.hora_fin || "");
        setIdTipo(permiso.id_tipo);
        setEstado(permiso.estado);
        setShow(true); // control del modal
    };
    const eliminar = async (permiso) => {
        alertify.confirm(
            "Confirmar eliminación",
            "¿Seguro que deseas eliminar este permiso <b>(" + permiso.motivo + ")</b>?",
            async function () {
                try {
                    const res = await deletePermiso(permiso.id);
                    if (res.status === "success") {
                        alertify.success(res.message);
                        listar(); // refresca la lista
                    } else {
                        alertify.error(res.message);
                    }
                } catch (err) {
                    alertify.error("Error al intentar eliminar el permiso");
                }
            },
            function () {
                alertify.message("Acción cancelada");
            }
        );
    };
    const [dropdownOpen, setDropdownOpen] = useState(false);

    return (
        <div className="flex flex-col gap-6">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-2xl font-bold text-gray-900">Permisos</h1>
                    <p className="text-sm text-gray-500 mt-0.5">Gestión de permisos de colaboradores</p>
                </div>
                <div className="flex gap-2">
                    <div className="relative">
                        <button
                            onClick={() => setDropdownOpen(!dropdownOpen)}
                            className="bg-white text-gray-700 px-4 py-2.5 rounded-md font-medium border border-gray-300 hover:bg-gray-50 transition-colors flex items-center gap-2 shadow-sm"
                        >
                            <ArrowDownTrayIcon className="h-4 w-4" /> Exportar
                        </button>
                        {dropdownOpen && (
                            <div className="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-10 overflow-hidden">
                                <button
                                    onClick={() => { exportToExcel(); setDropdownOpen(false); }}
                                    className="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 flex items-center gap-2 text-gray-700"
                                >
                                    Excel
                                </button>
                                <button
                                    onClick={() => { exportToPDF(); setDropdownOpen(false); }}
                                    className="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 flex items-center gap-2 text-gray-700"
                                >
                                    PDF
                                </button>
                            </div>
                        )}
                    </div>
                    <button
                        onClick={() => setShow(true)}
                        className="bg-gray-800 text-white px-5 py-2.5 rounded-md hover:bg-gray-700 shadow-sm font-medium transition-colors flex items-center gap-2"
                    >
                        <PlusIcon className="h-4 w-4" /> Nuevo Permiso
                    </button>
                </div>
            </div>

            <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div className="p-4 border-b border-gray-100 bg-gray-50">
                    {subHeaderComponent}
                </div>
                <div className="w-full">
                    <DataTable
                        columns={columns}
                        data={filteredData}
                        pagination
                        highlightOnHover
                        striped
                        responsive
                        customStyles={{
                            headRow: {
                                style: {
                                    backgroundColor: '#f9fafb',
                                    color: '#4b5563',
                                    fontWeight: '600',
                                    textTransform: 'uppercase',
                                    fontSize: '0.75rem',
                                    borderBottomWidth: '1px',
                                    borderBottomColor: '#e5e7eb',
                                }
                            }
                        }}
                    />
                </div>
            </div>

            {/* Modal Custom (Tailwind) */}
            {show && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div className="absolute inset-0 bg-black/50 backdrop-blur-sm" onClick={cerrarModal}></div>
                    <div className="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden">
                        <div className="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 className="text-lg font-bold text-gray-900">{idEdit ? "Editar Permiso" : "Nuevo Permiso"}</h2>
                            <button onClick={cerrarModal} className="text-gray-400 hover:text-gray-700 p-1.5 rounded-lg hover:bg-gray-200 transition-colors">
                                <XMarkIcon className="h-5 w-5" />
                            </button>
                        </div>
                        <div className="p-6 max-h-[70vh] overflow-y-auto">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div className="md:col-span-2">
                                    <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Colaborador</label>
                                    <SelectColaborador value={id_colaborador.id} onChange={setIdColaborador} />
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Fecha Inicio</label>
                                    <FechaPicker
                                        initialDate={fecha_inicio}
                                        onChange={({ ymd }) => setFechaInicio(ymd)}
                                    />
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Fecha Fin</label>
                                    <FechaPicker
                                        initialDate={fecha_fin}
                                        onChange={({ ymd }) => setFechaFin(ymd)}
                                    />
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Hora Inicio</label>
                                    <input
                                        type="time"
                                        className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm"
                                        value={hora_inicio}
                                        onChange={(e) => setHoraInicio(e.target.value)}
                                    />
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Hora Fin</label>
                                    <input
                                        type="time"
                                        className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm"
                                        value={hora_fin}
                                        onChange={(e) => setHoraFin(e.target.value)}
                                    />
                                </div>

                                <div className="md:col-span-2">
                                    <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tipo Permiso</label>
                                    <SelectTipoPermiso value={id_tipo} onChange={setIdTipo} />
                                </div>

                                <div className="md:col-span-2">
                                    <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Motivo</label>
                                    <input
                                        type="text"
                                        className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm"
                                        value={motivo}
                                        onChange={(e) => setMotivo(e.target.value)}
                                    />
                                </div>

                                <div className="md:col-span-2">
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