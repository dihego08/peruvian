import { useState } from "react";
import { reportByDias } from "../services/reportesService";
import alertify from 'alertifyjs';
import { saveAs } from "file-saver";
import * as XLSX from "xlsx";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";
import { MagnifyingGlassIcon } from "@heroicons/react/24/outline";

export default function ReportesDia() {
    const [reportes, setReportes] = useState([]);
    const [mes, setMes] = useState(new Date().getMonth() + 1);
    const [anio, setAnio] = useState(new Date().getFullYear());
    const [filterText, setFilterText] = useState("");
    const [loading, setLoading] = useState(false);

    const meses = [
        { value: 1, label: "Enero" },
        { value: 2, label: "Febrero" },
        { value: 3, label: "Marzo" },
        { value: 4, label: "Abril" },
        { value: 5, label: "Mayo" },
        { value: 6, label: "Junio" },
        { value: 7, label: "Julio" },
        { value: 8, label: "Agosto" },
        { value: 9, label: "Septiembre" },
        { value: 10, label: "Octubre" },
        { value: 11, label: "Noviembre" },
        { value: 12, label: "Diciembre" },
    ];

    const generarAnios = () => {
        const anioActual = new Date().getFullYear();
        const anios = [];
        for (let i = anioActual - 5; i <= anioActual + 2; i++) {
            anios.push(i);
        }
        return anios;
    };

    // Obtener número de días del mes
    const getDiasDelMes = () => {
        return new Date(anio, mes, 0).getDate();
    };

    // Obtener nombre del día de la semana (abreviado)
    const getDiaSemana = (dia) => {
        const fecha = new Date(anio, mes - 1, dia);
        const dias = ['D', 'L', 'M', 'M', 'J', 'V', 'S'];
        return dias[fecha.getDay()];
    };

    // Determinar color de celda según valor
    const getCellClass = (valor) => {
        if (valor === '0.00') return 'bg-red-50 text-red-700 font-medium border-red-200'; // Falta
        if (valor === '-') return 'bg-gray-100 text-gray-500'; // No laborable
        if (valor === null || valor === undefined) return 'bg-gray-50'; // Sin datos
        return 'bg-white font-medium text-gray-800'; // Día trabajado
    };

    // Convertir formato "HH:MM:SS" a horas decimales
    const convertirHorasADecimal = (tiempoStr) => {
        if (!tiempoStr || tiempoStr === null) return 0;
        const partes = tiempoStr.split(':');
        const horas = parseInt(partes[0]) || 0;
        const minutos = parseInt(partes[1]) || 0;
        const segundos = parseInt(partes[2]) || 0;
        return horas + (minutos / 60) + (segundos / 3600);
    };

    // Calcular diferencia de horas
    const calcularDiferenciaHoras = (horasTeoricas, totalHoras) => {
        const teoricasDecimal = convertirHorasADecimal(horasTeoricas);
        const trabajadasDecimal = parseFloat(totalHoras) || 0;
        const diferencia = trabajadasDecimal - teoricasDecimal;
        return diferencia.toFixed(2);
    };

    const filteredData = reportes.filter(
        (item) =>
            item.nombre?.toLowerCase().includes(filterText.toLowerCase()) ||
            item.tra_codigo?.toLowerCase().includes(filterText.toLowerCase())
    );

    const listar = async () => {
        setLoading(true);
        try {
            const data = await reportByDias({
                mes: mes,
                anio: anio,
            });
            setReportes(data);
            alertify.success(`Reporte generado para ${meses.find(m => m.value === parseInt(mes))?.label} ${anio}`);
        } catch (error) {
            alertify.error("Error al generar el reporte");
            console.error(error);
        } finally {
            setLoading(false);
        }
    };

    const exportToExcel = () => {
        const mesNombre = meses.find(m => m.value === parseInt(mes))?.label || mes;

        // Preparar datos para Excel
        const excelData = filteredData.map(row => {
            const rowData = {
                'DNI': row.tra_codigo,
                'Nombre': row.nombre,
            };

            // Agregar días del mes como valores numéricos
            for (let i = 1; i <= getDiasDelMes(); i++) {
                const diaKey = `dia_${String(i).padStart(2, '0')}`;
                const valor = row[diaKey];

                // Convertir a número o dejar vacío/guión
                if (valor === '-' || valor === null || valor === undefined) {
                    rowData[`Día ${i}`] = valor || '';
                } else if (valor === '0.00') {
                    rowData[`Día ${i}`] = 0;
                } else {
                    // Convertir el string a número decimal
                    const numValue = parseFloat(valor);
                    rowData[`Día ${i}`] = isNaN(numValue) ? valor : numValue;
                }
            }

            // Agregar totales como números
            rowData['TOTAL'] = parseFloat(row.total_horas) || 0;
            rowData['HRS. TEÓR.'] = parseFloat(row.horas_teoricas) || 0;

            const diferenciaHoras = calcularDiferenciaHoras(row.horas_teoricas, row.total_horas);
            rowData['HRS. EXT.'] = row.horas_extras;

            rowData['HRS. TAR.'] = parseFloat(row.total_tardanza) || '';
            rowData['HRS. PER.'] = parseFloat(row.total_horas_permiso) || '';
            rowData['FALTAS'] = parseInt(row.total_faltas) || 0;
            rowData['ASIST.'] = parseInt(row.dias_asistidos) || 0;

            return rowData;
        });

        const ws = XLSX.utils.json_to_sheet(excelData);

        // Opcional: Configurar formato de columnas numéricas
        const range = XLSX.utils.decode_range(ws['!ref']);
        for (let C = 2; C <= range.e.c; C++) { // Desde la columna de días
            const address = XLSX.utils.encode_col(C) + "1";
            if (!ws[address]) continue;

            // Aplicar formato numérico a las columnas de horas
            for (let R = 2; R <= range.e.r + 1; R++) {
                const cellAddress = XLSX.utils.encode_cell({ r: R, c: C });
                if (ws[cellAddress] && typeof ws[cellAddress].v === 'number') {
                    ws[cellAddress].z = '0.00'; // Formato con 2 decimales
                }
            }
        }

        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Reporte");

        const wbout = XLSX.write(wb, { bookType: "xlsx", type: "array" });
        const blob = new Blob([wbout], { type: "application/octet-stream" });
        saveAs(blob, `reporte_asistencias_${mesNombre}_${anio}.xlsx`);
    };

    const exportToPDF = () => {
        const mesNombre = meses.find(m => m.value === parseInt(mes))?.label || mes;
        const doc = new jsPDF({
            orientation: "landscape",
            unit: "mm",
            format: "a3", // Usar A3 para más espacio
        });

        doc.text(`Reporte de Asistencias - ${mesNombre} ${anio}`, 14, 15);

        // Preparar columnas
        const tableColumn = ['DNI', 'Nombre'];
        for (let i = 1; i <= getDiasDelMes(); i++) {
            tableColumn.push(i.toString());
        }
        tableColumn.push('TOTAL', 'HRS. TEÓR.', 'HRS. EXT.', 'HRS. TAR.', 'HRS. PER.', 'FALTAS', 'ASIST.');

        // Preparar filas
        const tableRows = filteredData.map((row) => {
            const rowData = [row.tra_codigo, row.nombre];

            for (let i = 1; i <= getDiasDelMes(); i++) {
                const diaKey = `dia_${String(i).padStart(2, '0')}`;
                rowData.push(row[diaKey] || '');
            }

            rowData.push(row.total_horas, row.horas_teoricas, calcularDiferenciaHoras(row.horas_teoricas, row.total_horas) < 0 ? '' : calcularDiferenciaHoras(row.horas_teoricas, row.total_horas), row.total_tardanza == "00:00:00" ? "" : row.total_tardanza, row.total_horas_permiso == "00:00:00" ? "" : row.total_horas_permiso, row.total_faltas, row.dias_asistidos);
            return rowData;
        });

        autoTable(doc, {
            head: [tableColumn],
            body: tableRows,
            startY: 25,
            styles: { fontSize: 6, cellPadding: 1 },
            headStyles: { fillColor: [41, 128, 185] },
        });

        doc.save(`reporte_asistencias_${mesNombre}_${anio}.pdf`);
    };

    const [dropdownOpen, setDropdownOpen] = useState(false);

    return (
        <div className="p-6">
            <h1 className="text-2xl font-bold text-gray-800 mb-6">Reporte de Asistencias por Mes</h1>

            {/* Controles */}
            <div className="bg-white p-5 rounded-xl shadow-sm border border-gray-200 mb-6">
                <div className="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div className="md:col-span-2">
                        <label className="block text-sm font-medium text-gray-700 mb-1">Mes</label>
                        <select
                            value={mes}
                            onChange={(e) => setMes(parseInt(e.target.value))}
                            className="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm"
                        >
                            {meses.map((m) => (
                                <option key={m.value} value={m.value}>
                                    {m.label}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div className="md:col-span-2">
                        <label className="block text-sm font-medium text-gray-700 mb-1">Año</label>
                        <select
                            value={anio}
                            onChange={(e) => setAnio(parseInt(e.target.value))}
                            className="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm"
                        >
                            {generarAnios().map((a) => (
                                <option key={a} value={a}>
                                    {a}
                                </option>
                            ))}
                        </select>
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
                                    <MagnifyingGlassIcon className="w-4 h-4" /> Generar
                                </>
                            )}
                        </button>
                    </div>

                    <div className="md:col-span-2 relative">
                        <button
                            onClick={() => setDropdownOpen(!dropdownOpen)}
                            disabled={reportes.length === 0}
                            className="w-full bg-emerald-600 text-white p-2.5 rounded-lg font-medium hover:bg-emerald-700 transition flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Exportar <i className="fa fa-chevron-down text-xs"></i>
                        </button>
                        {dropdownOpen && (
                            <div className="absolute right-0 mt-2 w-full bg-white border border-gray-100 rounded-lg shadow-lg z-10 overflow-hidden animate-in fade-in slide-in-from-top-2">
                                <button
                                    onClick={() => { exportToExcel(); setDropdownOpen(false); }}
                                    className="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 flex items-center gap-2 text-gray-700"
                                >
                                    <i className="fas fa-file-excel text-green-600 w-4"></i> Exportar a Excel
                                </button>
                                <button
                                    onClick={() => { exportToPDF(); setDropdownOpen(false); }}
                                    className="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 flex items-center gap-2 text-gray-700"
                                >
                                    <i className="fas fa-file-pdf text-red-600 w-4"></i> Exportar a PDF
                                </button>
                            </div>
                        )}
                    </div>

                    <div className="md:col-span-4">
                        <input
                            type="text"
                            className="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm"
                            placeholder="🔍 Buscar por nombre o DNI..."
                            value={filterText}
                            onChange={(e) => setFilterText(e.target.value)}
                        />
                    </div>
                </div>
            </div>

            {/* Tabla de reporte */}
            <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div className="p-4 border-b border-gray-100 bg-gray-50">
                    <h2 className="text-lg font-bold text-gray-800">
                        Reporte de Asistencias - {meses.find(m => m.value === parseInt(mes))?.label} {anio}
                    </h2>
                </div>
                <div>
                    {reportes.length === 0 ? (
                        <div className="flex flex-col items-center justify-center py-16 px-4">
                            <div className="bg-gray-50 text-gray-300 p-4 rounded-full mb-4">
                                <i className="fas fa-inbox text-4xl"></i>
                            </div>
                            <h3 className="text-gray-600 font-medium text-lg mb-1">Sin datos para mostrar</h3>
                            <p className="text-gray-400 text-sm text-center max-w-sm">
                                Selecciona un mes y año, luego presiona "Generar" para ver el reporte de asistencias.
                            </p>
                        </div>
                    ) : (
                        <div className="overflow-x-auto">
                            <table className="w-full text-left text-xs whitespace-nowrap border-collapse">
                                <thead className="bg-gray-800 text-white sticky top-0 z-10">
                                    <tr>
                                        <th rowSpan="2" className="px-3 py-2 text-center align-middle font-medium border border-gray-700 min-w-[100px]">DNI</th>
                                        <th rowSpan="2" className="px-3 py-2 text-center align-middle font-medium border border-gray-700 min-w-[200px]">NOMBRE</th>
                                        <th colSpan={getDiasDelMes()} className="px-3 py-2 text-center font-medium border border-gray-700">DÍAS DEL MES</th>
                                        <th rowSpan="2" className="px-3 py-2 text-center align-middle font-medium border border-gray-700 min-w-[80px]">TOTAL</th>
                                        <th rowSpan="2" className="px-3 py-2 text-center align-middle font-medium border border-gray-700 min-w-[80px]">HRS. TEÓR.</th>
                                        <th rowSpan="2" className="px-3 py-2 text-center align-middle font-medium border border-gray-700">HRS. EXT.</th>
                                        <th rowSpan="2" className="px-3 py-2 text-center align-middle font-medium border border-gray-700">HRS. TAR.</th>
                                        <th rowSpan="2" className="px-3 py-2 text-center align-middle font-medium border border-gray-700">HRS. PER.</th>
                                        <th rowSpan="2" className="px-3 py-2 text-center align-middle font-medium border border-gray-700 min-w-[70px]">FALTAS</th>
                                        <th rowSpan="2" className="px-3 py-2 text-center align-middle font-medium border border-gray-700 min-w-[70px]">ASIST.</th>
                                    </tr>
                                    <tr>
                                        {Array.from({ length: getDiasDelMes() }, (_, i) => i + 1).map(dia => (
                                            <th key={dia} className="px-1 py-1 text-center font-normal border border-gray-700 bg-gray-700 min-w-[40px]">
                                                <div className="font-semibold">{dia}</div>
                                                <div className="text-[10px] text-gray-300">{getDiaSemana(dia)}</div>
                                            </th>
                                        ))}
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-200">
                                    {filteredData.map((row, index) => (
                                        <tr key={index} className="hover:bg-gray-50 transition-colors">
                                            <td className="px-3 py-2 text-center font-mono border-r border-gray-200 bg-gray-50">{row.tra_codigo}</td>
                                            <td className="px-3 py-2 font-medium text-gray-800 border-r border-gray-200 truncate max-w-[250px]" title={row.nombre}>{row.nombre}</td>

                                            {Array.from({ length: getDiasDelMes() }, (_, i) => i + 1).map(dia => {
                                                const diaKey = `dia_${String(dia).padStart(2, '0')}`;
                                                const valor = row[diaKey];
                                                return (
                                                    <td
                                                        key={dia}
                                                        className={`text-center border-r border-gray-200 px-1 py-2 ${getCellClass(valor)}`}
                                                    >
                                                        {valor}
                                                    </td>
                                                );
                                            })}

                                            <td className="px-3 py-2 text-center font-bold text-gray-800 border-r border-gray-200 bg-gray-50">{row.total_horas}</td>
                                            <td className="px-3 py-2 text-center text-gray-500 border-r border-gray-200">{row.horas_teoricas}</td>
                                            <td className="px-3 py-2 text-center font-bold text-blue-600 border-r border-gray-200">{row.horas_extras}</td>
                                            <td className="px-3 py-2 text-center font-bold text-amber-500 border-r border-gray-200">{row.total_tardanza == "00:00:00" ? "" : row.total_tardanza}</td>
                                            <td className="px-3 py-2 text-center font-bold text-purple-600 border-r border-gray-200">{row.total_horas_permiso == "00:00:00" ? "" : row.total_horas_permiso}</td>
                                            <td className="px-3 py-2 text-center font-bold text-red-600 border-r border-gray-200">{row.total_faltas}</td>
                                            <td className="px-3 py-2 text-center font-bold text-emerald-600">{row.dias_asistidos}</td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>
            </div>

            {/* Leyenda */}
            {reportes.length > 0 && (
                <div className="mt-4 bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                    <h6 className="text-sm font-bold text-gray-800 mb-3">Leyenda:</h6>
                    <div className="flex flex-wrap gap-4">
                        <div className="flex items-center gap-2">
                            <div className="bg-red-50 text-red-700 font-medium px-2 py-1 border border-red-200 rounded text-xs">0.00</div>
                            <span className="text-sm text-gray-600">Falta (día laborable sin asistencia)</span>
                        </div>
                        <div className="flex items-center gap-2">
                            <div className="bg-gray-100 text-gray-500 px-2 py-1 rounded text-xs border border-gray-200">-</div>
                            <span className="text-sm text-gray-600">Día no laborable (descanso)</span>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}