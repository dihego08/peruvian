import { useEffect, useState } from "react";
import {
    reportByColaborador
} from "../services/reportesService";
import alertify from 'alertifyjs';
import FechaPicker from "../components/FechaPicker"; // el componente anterior
import SelectColaborador from "../components/SelectColaborador";
import { insertarMarcacion } from "../services/marcacionesService";
import { saveAs } from "file-saver";
import * as XLSX from "xlsx";
import DataTable from "react-data-table-component";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";
import { PencilSquareIcon, TrashIcon, PlusIcon, XMarkIcon, ChevronDownIcon, DocumentArrowDownIcon,  MagnifyingGlassCircleIcon, MagnifyingGlassIcon } from '@heroicons/react/24/outline';

export default function Reportes() {
    const [reportes, setReportes] = useState([]);
    const [id_colaborador, setIdColaborador] = useState("");
    const [colaborador, setColaborador] = useState("");
    const [fecha_inicio, setFechaInicio] = useState("");
    const [fecha_fin, setFechaFin] = useState("");
    const [filterText, setFilterText] = useState("");
    const [loading, setLoading] = useState(false);

    // Modal state
    const [showModal, setShowModal] = useState(false);
    const [savingMarcacion, setSavingMarcacion] = useState(false);
    const [modalColaboradorId, setModalColaboradorId] = useState("");
    const [marcacionData, setMarcacionData] = useState({ dni: "", fecha_hora: "" });

    const handleCloseModal = () => {
        setShowModal(false);
        setModalColaboradorId("");
        setMarcacionData({ dni: "", fecha_hora: "" });
    };

    const handleSaveMarcacion = async () => {
        if (!marcacionData.dni || !marcacionData.fecha_hora) {
            alertify.error("Por favor, seleccione un colaborador y la fecha/hora.");
            return;
        }

        // El input datetime-local devuelve YYYY-MM-DDTHH:mm, lo formateamos para la BD (YYYY-MM-DD HH:mm:00)
        const formattedFechaHora = marcacionData.fecha_hora.replace('T', ' ') + ':00';

        setSavingMarcacion(true);
        try {
            await insertarMarcacion({
                dni: marcacionData.dni,
                fecha_hora: formattedFechaHora,
                estado: 1,
                reloj_ip: 'MANUAL'
            });
            alertify.success("Marcación registrada correctamente");
            handleCloseModal();
            // Actualizar tabla si ya se había generado el reporte
            if (id_colaborador && fecha_inicio && fecha_fin) {
                listar();
            }
        } catch (error) {
            console.error(error);
            alertify.error("Error al registrar la marcación manual");
        } finally {
            setSavingMarcacion(false);
        }
    };


    const exportToPDF = () => {
        const doc = new jsPDF({
            orientation: "landscape",
            unit: "mm",
            format: "a4",
        });
        doc.text(`Reporte de Asistencias de ${colaborador}`, 14, 15);
        const tableColumn = [
            "Fecha",
            "Hora Entrada",
            "Hora Salida",
            "Hora Inicio Refrigerio",
            "Hora Fin Refrigerio",
            "Hora Entrada Real",
            "Hora Salida Real",
            "Hora Inicio Refrigerio Real",
            "Hora Fin Refrigerio Real",
            "Hora Entrada Extra",
            "Hora Salida Extra",
            "Estado Badge",
            "Minutos Tardanza",
            "Minutos Salida Anticipada",
            "Horas Efectivas",
            "Horas Extras",
            "Num. Marcaciones",
        ];
        const tableRows = filteredData.map((row) => [
            row.fecha,
            row.hora_entrada_esperada,
            row.hora_salida_esperada,
            row.hora_inicio_refrigerio,
            row.hora_fin_refrigerio,
            row.hora_entrada,
            row.hora_salida,
            row.hora_inicio_refrigerio_real,
            row.hora_fin_refrigerio_real,
            row.hora_entrada_extra,
            row.hora_salida_extra,
            row.estado_asistencia,
            row.minutos_tardanza,
            row.minutos_salida_anticipada,
            row.horas_efectivas,
            row.horas_extras,
            row.num_marcaciones,
        ]);
        autoTable(doc, {
            head: [tableColumn],
            body: tableRows,
            startY: 25,
        });
        doc.save(`reporte_asistencias_${colaborador}.pdf`);
    };
    // Filtrado simple por fecha o estado
    const filteredData = reportes.filter(
        (item) =>
            item.fecha?.toLowerCase().includes(filterText.toLowerCase()) ||
            item.estado_asistencia?.toLowerCase().includes(filterText.toLowerCase())
    );
    const subHeaderComponent = (
        <div className="flex items-center justify-between w-full mb-4">
            <input
                type="text"
                className="w-full max-w-md p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                placeholder="🔍 Buscar por fecha o estado..."
                value={filterText}
                onChange={(e) => setFilterText(e.target.value)}
            />
        </div>
    );
    const listar = async () => {
        setLoading(true); // 🔹 activa el loading
        /*const data = await reportByColaborador({
            id_colaborador,
            fecha_inicio,
            fecha_fin
        });
        setReportes(data);*/
        try {
            const data = await reportByColaborador({
                id_colaborador,
                fecha_inicio,
                fecha_fin
            });
            setReportes(data);
        } catch (error) {
            alertify.error("Error al generar el reporte");
            console.error(error);
        } finally {
            setLoading(false); // 🔹 desactiva el loading siempre
        }
    };
    const exportToExcel = () => {
        // Crear hoja Excel
        const ws = XLSX.utils.json_to_sheet(filteredData);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Reporte");

        // Generar archivo Excel y descargarlo
        const wbout = XLSX.write(wb, { bookType: "xlsx", type: "array" });
        const blob = new Blob([wbout], { type: "application/octet-stream" });
        saveAs(blob, `reporte_asistencias_${colaborador}.xlsx`);
    };
    const getBadgeClass = (estado) => {
        switch (estado) {
            case "OK":
                return "px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800"; // verde
            case "TARDANZA":
                return "px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800"; // amarillo
            case "FALTA":
                return "px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800"; // rojo
            default:
                return "px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800"; // gris por defecto
        }
    };
    const columns = [
        {
            name: "Fecha",
            selector: (row) => row.fecha,
            sortable: true,
        },
        {
            name: "Hora Entrada",
            selector: (row) => row.hora_entrada,
            sortable: true,
        },
        {
            name: "Hora Salida",
            selector: (row) => row.hora_salida,
            sortable: true,
        },
        {
            name: "Hora Inicio Refrigerio",
            selector: (row) => row.hora_inicio_refrigerio_real,
            sortable: true,
        },
        {
            name: "Hora Fin Refrigerio",
            selector: (row) => row.hora_fin_refrigerio_real,
            sortable: true,
        },
        {
            name: "Hora Entrada Ext.",
            selector: (row) => row.hora_entrada_extra,
            sortable: true,
        },
        {
            name: "Hora Salida Ext.",
            selector: (row) => row.hora_salida_extra,
            sortable: true,
        },
        {
            name: "Estado Badge",
            cell: (row) => (
                <span className={getBadgeClass(row.estado_base)}>
                    {row.estado_base}
                </span>
            ),
            ignoreRowClick: true,
            allowOverflow: true,
            width: "100px",
            className: "text-center",
        },
        {
            name: "Minutos Tardanza",
            selector: (row) => row.minutos_tardanza,
            sortable: true,
        },
        {
            name: "Minutos Salida Anticipada",
            selector: (row) => row.minutos_salida_anticipada,
            sortable: true,
        },
        {
            name: "Horas Efectivas",
            selector: (row) => row.horas_efectivas,
            sortable: true,
        },
        {
            name: "Horas Extras",
            selector: (row) => row.horas_extras,
            sortable: true,
        },
        {
            name: "Num. Marcaciones",
            selector: (row) => row.num_marcaciones,
            sortable: true,
        },
    ];
    const [dropdownOpen, setDropdownOpen] = useState(false);

    return (
        <div className="p-6">
            <h1 className="text-2xl font-bold text-gray-800 mb-6">Reportes</h1>

            <div className="flex justify-end mb-6">
                <button
                    onClick={() => setShowModal(true)}
                    className="bg-emerald-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-emerald-700 transition flex items-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" /></svg> Registrar Marcación Manual
                </button>
            </div>

            <div className="bg-white p-5 rounded-xl shadow-sm border border-gray-200 mb-6">
                <div className="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div className="md:col-span-4">
                        <label className="block text-sm font-medium text-gray-700 mb-1">Colaborador</label>
                        <SelectColaborador label="Colaborador" value={id_colaborador} onChange={({ id, nombre }) => {
                            setIdColaborador(id);
                            setColaborador(nombre);
                        }} />
                    </div>
                    <div className="md:col-span-2">
                        <label className="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                        <FechaPicker
                            label="Fecha Inicio"
                            value={fecha_inicio}
                            onChange={({ ymd }) => setFechaInicio(ymd)}
                        />
                    </div>
                    <div className="md:col-span-2">
                        <label className="block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                        <FechaPicker
                            label="Fecha Fin"
                            value={fecha_fin}
                            onChange={({ ymd }) => setFechaFin(ymd)}
                        />
                    </div>
                    <div className="md:col-span-2">
                        <button
                            className="w-full bg-blue-600 text-white p-2.5 rounded-lg font-medium hover:bg-blue-700 transition flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            onClick={listar}
                            disabled={loading}
                        >
                            {loading ? (
                                <>
                                    <div className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                    Generando...
                                </>
                            ) : (
                                <>
                                    <MagnifyingGlassIcon className="h-4 w-4" /> Generar Reporte
                                </>
                            )}
                        </button>
                    </div>
                    <div className="md:col-span-2 relative">
                        <button
                            onClick={() => setDropdownOpen(!dropdownOpen)}
                            className="w-full bg-gray-100 text-gray-700 p-2.5 rounded-lg font-medium border border-gray-300 hover:bg-gray-200 transition flex items-center justify-center gap-2"
                        >
                            <DocumentArrowDownIcon className="h-4 w-4" /> Exportar  <ChevronDownIcon className="h-3 w-3 ml-1" />
                        </button>
                        {dropdownOpen && (
                            <div className="absolute right-0 mt-2 w-full bg-white border border-gray-100 rounded-lg shadow-lg z-10 overflow-hidden animate-in fade-in slide-in-from-top-2">
                                <button
                                    onClick={() => { exportToExcel(); setDropdownOpen(false); }}
                                    className="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 flex items-center gap-2 text-gray-700"
                                >
                                    <span className="text-green-600 font-bold">X</span> Exportar a Excel
                                </button>
                                <button
                                    onClick={() => { exportToPDF(); setDropdownOpen(false); }}
                                    className="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 flex items-center gap-2 text-gray-700"
                                >
                                    <span className="text-red-600 font-bold">P</span> Exportar a PDF
                                </button>
                            </div>
                        )}
                    </div>
                </div>
            </div>

            <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div className="p-4 border-b border-gray-100 bg-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h2 className="text-lg font-bold text-gray-800">
                        {colaborador ? `Reporte de Asistencias de ${colaborador}` : "Reporte de Asistencias"}
                    </h2>
                    <div className="w-full md:w-auto">
                        {subHeaderComponent}
                    </div>
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
            {showModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-in fade-in duration-200">
                    <div className="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden animate-in zoom-in-95 duration-200">
                        <div className="flex justify-between items-center p-4 border-b border-gray-100">
                            <h3 className="text-lg font-bold text-gray-900">Registrar Marcación Manual</h3>
                            <button onClick={handleCloseModal} className="text-gray-400 hover:text-gray-600 transition">
                                <i className="fa fa-times text-xl"></i>
                            </button>
                        </div>
                        <div className="p-5 space-y-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Colaborador</label>
                                <SelectColaborador
                                    value={modalColaboradorId}
                                    onChange={({ id, dni }) => {
                                        setModalColaboradorId(id);
                                        setMarcacionData({ ...marcacionData, dni: dni });
                                    }}
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Fecha y Hora</label>
                                <input
                                    type="datetime-local"
                                    className="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm"
                                    value={marcacionData.fecha_hora}
                                    onChange={(e) => setMarcacionData({ ...marcacionData, fecha_hora: e.target.value })}
                                />
                            </div>
                        </div>
                        <div className="p-4 border-t border-gray-100 flex justify-end gap-2 bg-gray-50">
                            <button
                                onClick={handleCloseModal}
                                className="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition"
                            >
                                Cancelar
                            </button>
                            <button
                                onClick={handleSaveMarcacion}
                                disabled={savingMarcacion}
                                className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                            >
                                {savingMarcacion && <div className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>}
                                {savingMarcacion ? "Guardando..." : "Guardar Marcación"}
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}