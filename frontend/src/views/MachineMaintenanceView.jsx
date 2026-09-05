import { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import api from '../services/api';
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";
import {
  ArrowLeftIcon,
  WrenchScrewdriverIcon,
  PlusIcon,
  TrashIcon,
  PencilSquareIcon,
  CheckCircleIcon,
  CalendarIcon,
  UserIcon,
  CurrencyDollarIcon,
  ChatBubbleLeftEllipsisIcon
} from '@heroicons/react/24/outline';

const MTTO_EMPTY = {
  maq_mtto_tipo: '',
  maq_mtto_fecha: new Date().toISOString().split('T')[0],
  maq_mtto_reponsable: '',
  maq_mtto_observacion: '',
  maq_mtto_costo: '',
  tipo_mantenimiento: '1' // 1: Preventivo, 2: Correctivo
};

export default function MachineMaintenanceView() {
  const { mid } = useParams();
  const navigate = useNavigate();
  const [machine, setMachine] = useState(null);
  const [maintenance, setMaintenance] = useState([]);
  const [loading, setLoading] = useState(true);
  const [formData, setFormData] = useState({ ...MTTO_EMPTY, maquina_id: mid });
  const [editingId, setEditingId] = useState(null);
  const [saving, setSaving] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [dateFrom, setDateFrom] = useState('');
  const [dateTo, setDateTo] = useState('');

  useEffect(() => {
    fetchMachine();
    fetchMaintenance();
  }, [mid]);

  const fetchMachine = async () => {
    try {
      const r = await api.get(`/machines/${mid}`);
      setMachine(r.data);
    } catch (e) { console.error(e); }
  };

  const fetchMaintenance = async () => {
    setLoading(true);
    try {
      const r = await api.get(`/machine-maintenance?maquina_id=${mid}`);
      setMaintenance(r.data);
    } catch (e) { console.error(e); }
    finally { setLoading(false); }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      if (editingId) {
        await api.put(`/machine-maintenance/${editingId}`, formData);
      } else {
        await api.post('/machine-maintenance', formData);
      }
      setFormData({ ...MTTO_EMPTY, maquina_id: mid });
      setEditingId(null);
      fetchMaintenance();
    } catch (e) {
      alert('Error al guardar mantenimiento');
    } finally { setSaving(false); }
  };

  const handleEdit = (item) => {
    setEditingId(item.maq_mtto_id);
    setFormData(item);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const handleDelete = async (id) => {
    if (!window.confirm('¿Eliminar este registro de mantenimiento?')) return;
    try {
      await api.delete(`/machine-maintenance/${id}`);
      fetchMaintenance();
    } catch (e) { alert('Error al eliminar'); }
  };

  if (!machine) return <div className="p-8 text-center text-gray-500">Cargando datos de la máquina...</div>;

  const filteredMaintenance = maintenance.filter(p => {
    const matchQuery = (p.maq_mtto_fecha || '').toLowerCase().includes(searchQuery.toLowerCase()) ||
      (p.maq_mtto_reponsable || '').toLowerCase().includes(searchQuery.toLowerCase()) ||
      (p.maq_mtto_observacion || '').toLowerCase().includes(searchQuery.toLowerCase()) ||
      (p.maq_mtto_tipo || '').toLowerCase().includes(searchQuery.toLowerCase()) ||
      (p.maq_mtto_costo || '').toString().toLowerCase().includes(searchQuery.toLowerCase());
    let matchFrom = true;
    let matchTo = true;
    if (dateFrom) matchFrom = p.maq_mtto_fecha >= dateFrom;
    if (dateTo) matchTo = p.maq_mtto_fecha <= dateTo;
    return matchQuery && matchFrom && matchTo;
  });

  const totalCost = filteredMaintenance.reduce((sum, item) => sum + parseFloat(item.maq_mtto_costo || 0), 0);

  const exportToPDF = () => {
    const doc = new jsPDF({
      orientation: "portrait",
      unit: "mm",
      format: "a4"
    });

    doc.text(`Historial de Mantenimientos`, 14, 15);
    doc.setFontSize(10);
    doc.text(`Máquina: ${machine.maquina_tipo}-${machine.maquina_codigo} | ${machine.maquina_descripcion}`, 14, 22);
    doc.text(`Inversión Total Mostrada: S/ ${totalCost.toFixed(2)}`, 14, 28);

    const tableColumn = ["Tipo", "Fecha", "Responsable", "Mtto. Realizado", "Observaciones", "Costo (S/)"];
    const tableRows = [];

    filteredMaintenance.forEach(item => {
      const row = [
        item.tipo_mantenimiento == '1' ? 'Preventivo' : 'Correctivo',
        item.maq_mtto_fecha,
        item.maq_mtto_reponsable,
        item.maq_mtto_tipo,
        item.maq_mtto_observacion,
        parseFloat(item.maq_mtto_costo || 0).toFixed(2)
      ];
      tableRows.push(row);
    });

    autoTable(doc, {
      head: [tableColumn],
      body: tableRows,
      startY: 34,
      styles: { fontSize: 8 },
      headStyles: { fillColor: [59, 130, 246] }
    });

    doc.save(`mantenimientos_${machine.maquina_codigo}.pdf`);
  };

  return (
    <div className="flex flex-col gap-8 animate-in fade-in duration-700 pb-20">
      {/* Header & Machine Info Summary */}
      <div className="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div className="px-8 py-6 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
          <div className="flex items-center gap-4">
            <button onClick={() => navigate('/machines')} className="p-2 hover:bg-gray-200 rounded-lg transition-colors">
              <ArrowLeftIcon className="h-5 w-5 text-gray-600" />
            </button>
            <div>
              <h1 className="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <WrenchScrewdriverIcon className="h-7 w-7 text-blue-600" />
                Mantenimiento de Máquina
              </h1>
              <p className="text-sm text-gray-500 font-medium">{machine.maquina_tipo}-{machine.maquina_codigo} | {machine.maquina_descripcion}</p>
            </div>
          </div>
          <div className="flex items-center gap-3">
            <span className="px-4 py-1.5 bg-blue-100 text-blue-700 rounded-full text-xs font-black uppercase tracking-widest border border-blue-200">
              {machine.maquina_ubicacion}
            </span>
            {machine.factura_compra && (
              <a href={`https://peruvian.peruviandress.com/storage/maquinas/${machine.factura_compra}`} target="_blank" rel="noreferrer" className="text-xs font-bold text-gray-500 hover:text-blue-600 underline">
                Ver Factura
              </a>
            )}
            <a href={`${import.meta.env.VITE_API_BASE_URL || 'https://apiperuvian.dbusinessaqp.com/api'}/machines/${machine.maquina_id}/pdf`} target="_blank" rel="noreferrer" className="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-bold border border-red-100 hover:bg-red-100 transition-colors flex items-center gap-1 shadow-sm">
              <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fillRule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clipRule="evenodd" />
              </svg>
              Ficha/PDF
            </a>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-4 gap-8 p-8">
          <div className="md:col-span-1">
            <div className="aspect-video bg-gray-100 rounded-xl border border-gray-200 flex items-center justify-center overflow-hidden">
              {machine.maquina_imagen ? (
                <img src={`https://peruvian.peruviandress.com/storage/maquinas/${machine.maquina_imagen}`} alt="Maquina" className="w-full h-full object-cover" />
              ) : (
                <WrenchScrewdriverIcon className="h-12 w-12 text-gray-300" />
              )}
            </div>
          </div>
          <div className="md:col-span-3 grid grid-cols-2 md:grid-cols-3 gap-6">
            <InfoItem label="Marca / Modelo" value={`${machine.maquina_marca} - ${machine.maquina_modelo}`} />
            <InfoItem label="Nro Serie Cabezal" value={machine.maquina_serie} />
            <InfoItem label="Motor (Marca/Serie)" value={`${machine.maquina_marca_motor} / ${machine.maquina_serie_motor}`} />
            <InfoItem label="Voltaje / Corriente" value={`${machine.maquina_voltaje} / ${machine.maquina_tipo_corriente}`} />
            <InfoItem label="Año Compra" value={machine.maquina_anio_compra} />
            <InfoItem label="Vida Útil" value={machine.maquina_vida_util} />
            <InfoItem label="Proveedor" value={`${machine.proveedor}`} />
            <InfoItem label="Precio de Compra" value={`S/ ${machine.precio_compra}`} />
          </div>
        </div>
      </div>

      {/* Formulario de Mantenimiento */}
      <div className="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        <h2 className="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
          <PlusIcon className="h-5 w-5 text-green-600" />
          {editingId ? 'Editar Mantenimiento' : 'Registrar Mantenimiento Realizado'}
        </h2>
        <form onSubmit={handleSubmit} className="grid grid-cols-1 md:grid-cols-4 gap-6">
          <div className="md:col-span-1">
            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Tipo de Mtto</label>
            <div className="flex gap-4">
              <label className="flex items-center gap-2 cursor-pointer group">
                <input type="radio" name="tipo" value="1" checked={formData.tipo_mantenimiento == '1'} onChange={e => setFormData({ ...formData, tipo_mantenimiento: e.target.value })} className="w-4 h-4 text-blue-600" />
                <span className="text-sm font-bold text-gray-700 group-hover:text-blue-600 transition-colors">Preventivo</span>
              </label>
              <label className="flex items-center gap-2 cursor-pointer group">
                <input type="radio" name="tipo" value="2" checked={formData.tipo_mantenimiento == '2'} onChange={e => setFormData({ ...formData, tipo_mantenimiento: e.target.value })} className="w-4 h-4 text-red-600" />
                <span className="text-sm font-bold text-gray-700 group-hover:text-red-600 transition-colors">Correctivo</span>
              </label>
            </div>
          </div>
          <div className="md:col-span-1">
            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 flex items-center gap-1">
              <CalendarIcon className="h-3 w-3" /> Fecha
            </label>
            <input required type="date" className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={formData.maq_mtto_fecha} onChange={e => setFormData({ ...formData, maq_mtto_fecha: e.target.value })} />
          </div>
          <div className="md:col-span-1">
            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 flex items-center gap-1">
              <UserIcon className="h-3 w-3" /> Responsable
            </label>
            <input required type="text" className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={formData.maq_mtto_reponsable} onChange={e => setFormData({ ...formData, maq_mtto_reponsable: e.target.value })} placeholder="Ej: Juan Perez" />
          </div>
          <div className="md:col-span-1">
            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 flex items-center gap-1">
              <CurrencyDollarIcon className="h-3 w-3" /> Costo (S/)
            </label>
            <input required type="number" step="0.01" className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none font-mono" value={formData.maq_mtto_costo} onChange={e => setFormData({ ...formData, maq_mtto_costo: e.target.value })} placeholder="0.00" />
          </div>
          <div className="md:col-span-1">
            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 flex items-center gap-1">
              <ChatBubbleLeftEllipsisIcon className="h-3 w-3" /> Mantenimiento Realizado
            </label>
            <input required type="text" className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={formData.maq_mtto_tipo} onChange={e => setFormData({ ...formData, maq_mtto_tipo: e.target.value })} placeholder="Describa el mantenimiento realizado..." />
          </div>
          <div className="md:col-span-2">
            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 flex items-center gap-1">
              <ChatBubbleLeftEllipsisIcon className="h-3 w-3" /> Observaciones
            </label>
            <input type="text" className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={formData.maq_mtto_observacion} onChange={e => setFormData({ ...formData, maq_mtto_observacion: e.target.value })} placeholder="Observaciones..." />
          </div>
          <div className="md:col-span-1 flex items-end">
            <button type="submit" disabled={saving} className="w-full py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-700 font-bold text-sm transition-all shadow-md flex items-center justify-center gap-2 disabled:opacity-50">
              <CheckCircleIcon className="h-5 w-5" />
              {saving ? 'Guardando...' : (editingId ? 'Actualizar' : 'Agregar Registro')}
            </button>
          </div>
        </form>
      </div>

      {/* Historial de Mantenimientos */}
      <div className="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div className="px-8 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
          <h3 className="font-black text-gray-900 uppercase tracking-tighter">Historial de Mantenimientos</h3>
          <div className="flex items-center gap-6">
            <button
              onClick={exportToPDF}
              className="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-bold border border-red-100 hover:bg-red-100 transition-colors flex items-center gap-1 shadow-sm"
              title="Generar PDF del listado actual"
            >
              <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fillRule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clipRule="evenodd" />
              </svg>
              Imprimir Lista (PDF)
            </button>
            <div className="text-right">
              <p className="text-[10px] text-gray-400 font-black uppercase tracking-widest">Inversión Total</p>
              <p className="text-lg font-mono font-bold text-blue-600">S/ {totalCost.toFixed(2)}</p>
            </div>
          </div>
        </div>
        <div className="overflow-x-auto">
          <div className="flex flex-col md:flex-row gap-4 p-4 items-center">
            <input
              type="text"
              placeholder="Buscar..."
              className="flex-1 p-2.5 border border-gray-300 rounded-md focus:border-blue-500 text-sm focus:ring-1 focus:ring-blue-500 transition-all outline-none"
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
            />
            <div className="flex items-center gap-2">
              <label className="text-xs text-gray-500 font-bold uppercase">Desde:</label>
              <input
                type="date"
                className="p-2 border border-gray-300 rounded-md text-sm outline-none focus:border-blue-500"
                value={dateFrom}
                onChange={e => setDateFrom(e.target.value)}
              />
            </div>
            <div className="flex items-center gap-2">
              <label className="text-xs text-gray-500 font-bold uppercase">Hasta:</label>
              <input
                type="date"
                className="p-2 border border-gray-300 rounded-md text-sm outline-none focus:border-blue-500"
                value={dateTo}
                onChange={e => setDateTo(e.target.value)}
              />
            </div>
            {(dateFrom || dateTo || searchQuery) && (
              <button
                onClick={() => { setDateFrom(''); setDateTo(''); setSearchQuery(''); }}
                className="text-xs font-bold text-red-500 hover:text-red-700 transition-colors"
              >
                Limpiar
              </button>
            )}
          </div>
          <table className="w-full text-left text-sm">
            <thead className="bg-gray-50 text-gray-500 uppercase text-[10px] font-black border-b border-gray-200 tracking-widest">
              <tr>
                <th className="px-8 py-4">Tipo</th>
                <th className="px-4 py-4">Fecha</th>
                <th className="px-4 py-4">Responsable</th>
                <th className="px-4 py-4">Mantenimiento Realizado</th>
                <th className="px-4 py-4">Observaciones</th>
                <th className="px-4 py-4 text-right">Costo</th>
                <th className="px-8 py-4 text-center">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-100">
              {loading ? (
                <tr><td colSpan="6" className="px-8 py-12 text-center text-gray-400">Cargando historial...</td></tr>
              ) : filteredMaintenance.length === 0 ? (
                <tr><td colSpan="6" className="px-8 py-12 text-center text-gray-400 italic">No hay mantenimientos registrados aún para esta búsqueda.</td></tr>
              ) : filteredMaintenance.map(item => (
                <tr key={item.maq_mtto_id} className="hover:bg-gray-50/50 transition-colors">
                  <td className="px-8 py-4">
                    <span className={`px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter ${item.tipo_mantenimiento == '1' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700'}`}>
                      {item.tipo_mantenimiento == '1' ? 'Preventivo' : 'Correctivo'}
                    </span>
                  </td>
                  <td className="px-4 py-4 text-gray-600 font-medium">{item.maq_mtto_fecha}</td>
                  <td className="px-4 py-4 font-bold text-gray-800">{item.maq_mtto_reponsable}</td>
                  <td className="px-4 py-4 text-gray-500 text-xs italic leading-relaxed">{item.maq_mtto_tipo}</td>
                  <td className="px-4 py-4 text-gray-500 text-xs italic leading-relaxed">{item.maq_mtto_observacion}</td>
                  <td className="px-4 py-4 text-right font-mono font-bold text-gray-900">S/ {parseFloat(item.maq_mtto_costo || 0).toFixed(2)}</td>
                  <td className="px-8 py-4">
                    <div className="flex items-center justify-center gap-2">
                      <button onClick={() => handleEdit(item)} className="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                        <PencilSquareIcon className="h-5 w-5" />
                      </button>
                      <button onClick={() => handleDelete(item.maq_mtto_id)} className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
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
    </div>
  );
}

function InfoItem({ label, value }) {
  return (
    <div className="space-y-1">
      <p className="text-[10px] font-black text-gray-400 uppercase tracking-widest">{label}</p>
      <p className="text-sm font-bold text-gray-800 truncate">{value || '-'}</p>
    </div>
  );
}
