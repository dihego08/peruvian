import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../services/api';
import {
  PlusIcon,
  PencilSquareIcon,
  TrashIcon,
  XMarkIcon,
  ArrowLeftIcon
} from '@heroicons/react/24/outline';

const MONTHS = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

const EMPTY_FORM = {
  curso: '',
  areas: '',
  responsable: '',
  id_tipo: '',
  eficacia: '',
  anio: new Date().getFullYear().toString(),
  meses_dias: [{ mes: '', dia: '' }]
};

export default function MaintenanceProgramView() {
  const navigate = useNavigate();
  const [cronogramas, setCronogramas] = useState([]);
  const [tipos, setTipos] = useState([]);
  const [maquinas, setMaquinas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [tipoSeleccionado, setTipoSeleccionado] = useState(2);
  const [anio, setAnio] = useState(new Date().getFullYear().toString());
  
  const [showModal, setShowModal] = useState(false);
  const [formData, setFormData] = useState(EMPTY_FORM);
  const [editingId, setEditingId] = useState(null);
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    fetchMaquinas();
    fetchTipos();
  }, []);

  useEffect(() => {
    if (tipos.length > 0) {
      fetchCronogramas();
    }
  }, [tipoSeleccionado, anio, tipos]);

  const fetchMaquinas = async () => {
    try {
      const res = await api.get('/machines?status=1');
      setMaquinas(res.data);
    } catch (e) {
      console.error(e);
    }
  };

  const fetchTipos = async () => {
    try {
      const res = await api.get('/cronogramas/tipos');
      setTipos(res.data);
      if (res.data.length > 0) {
        setTipoSeleccionado(res.data[0].id);
      }
    } catch (e) {
      console.error(e);
    }
  };

  const fetchCronogramas = async () => {
    setLoading(true);
    try {
      const res = await api.get(`/cronogramas?anio=${anio}&tipo=${tipoSeleccionado}`);
      setCronogramas(res.data);
    } catch (e) {
      console.error(e);
    } finally {
      setLoading(false);
    }
  };

  const handleAddMesDia = () => {
    setFormData({
      ...formData,
      meses_dias: [...formData.meses_dias, { mes: '', dia: '' }]
    });
  };

  const handleRemoveMesDia = (index) => {
    const newMesesDias = [...formData.meses_dias];
    newMesesDias.splice(index, 1);
    setFormData({ ...formData, meses_dias: newMesesDias });
  };

  const handleMesDiaChange = (index, field, value) => {
    const newMesesDias = [...formData.meses_dias];
    newMesesDias[index][field] = value;
    setFormData({ ...formData, meses_dias: newMesesDias });
  };

  const openCreate = () => {
    setFormData({ ...EMPTY_FORM, id_tipo: tipoSeleccionado, anio });
    setEditingId(null);
    setShowModal(true);
  };

  const openEdit = (item) => {
    const meses_dias = item.fechas && item.fechas.length > 0 
      ? item.fechas.map(f => ({ mes: f.mes.toString(), dia: f.dia.toString() }))
      : [{ mes: '', dia: '' }];
      
    setFormData({
      curso: item.curso,
      areas: item.areas,
      responsable: item.responsable,
      id_tipo: item.id_tipo,
      eficacia: item.eficacia,
      anio: item.anio.toString(),
      meses_dias
    });
    setEditingId(item.id);
    setShowModal(true);
  };

  const closeModal = () => {
    setShowModal(false);
    setEditingId(null);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    
    // Transform formData back to api format
    const apiData = {
      ...formData,
      mes: formData.meses_dias.map(md => md.mes !== '' ? md.mes : null),
      dia: formData.meses_dias.map(md => md.dia !== '' ? md.dia : null)
    };

    try {
      if (editingId) {
        await api.put(`/cronogramas/${editingId}`, apiData);
      } else {
        await api.post('/cronogramas', apiData);
      }
      closeModal();
      fetchCronogramas();
    } catch (e) {
      alert('Error al guardar');
      console.error(e);
    } finally {
      setSaving(false);
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('¿Eliminar este registro?')) return;
    try {
      await api.delete(`/cronogramas/${id}`);
      fetchCronogramas();
    } catch (e) {
      alert('Error al eliminar');
    }
  };

  const cambiarEstadoFecha = async (fechaId, nuevoEstado) => {
    try {
      await api.put(`/cronogramas/fechas/${fechaId}/estado`, { estado: nuevoEstado });
      fetchCronogramas();
    } catch (e) {
      alert('Error al cambiar estado');
    }
  };

  const calcularPorcentajeItem = (item) => {
    if (!item.fechas || item.fechas.length === 0) return 0;
    const realizados = item.fechas.filter(f => f.estado === 1).length;
    return Math.round((realizados / item.fechas.length) * 100);
  };

  // Calcular porcentajes por mes
  const porcentajesMes = Array(12).fill(0);
  const totalMes = Array(12).fill(0);
  const realizadosMes = Array(12).fill(0);

  cronogramas.forEach(item => {
    if (item.fechas) {
      item.fechas.forEach(f => {
        totalMes[f.mes] += 1;
        if (f.estado === 1) realizadosMes[f.mes] += 1;
      });
    }
  });

  for (let i = 0; i < 12; i++) {
    porcentajesMes[i] = totalMes[i] > 0 ? Math.round((realizadosMes[i] / totalMes[i]) * 100) : 0;
  }

  return (
    <div className="flex flex-col gap-6 animate-in fade-in duration-700">
      <div className="flex items-center gap-4">
        <button onClick={() => navigate('/machines')} className="p-2 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 text-gray-600 transition-colors">
          <ArrowLeftIcon className="h-5 w-5" />
        </button>
        <div>
          <h1 className="text-2xl font-bold text-gray-900 flex items-center gap-2">
            Programa General de Mantenimiento
          </h1>
          <p className="text-sm text-gray-500 mt-0.5">Gestión de cronogramas y seguimientos anuales</p>
        </div>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div className="flex flex-wrap items-center justify-between gap-4">
          <div className="flex gap-2">
            {tipos.map(t => (
              <button
                key={t.id}
                onClick={() => setTipoSeleccionado(t.id)}
                className={`px-4 py-2 text-sm font-bold rounded-lg transition-all ${
                  tipoSeleccionado === t.id 
                    ? 'bg-blue-600 text-white shadow-md' 
                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                }`}
              >
                {t.tipo_cronograma}
              </button>
            ))}
          </div>
          
          <div className="flex items-center gap-3">
            <input 
              type="number" 
              value={anio} 
              onChange={e => setAnio(e.target.value)}
              className="w-24 p-2 border border-gray-300 rounded-lg text-center font-bold text-sm outline-none focus:border-blue-500"
            />
            <button onClick={openCreate} className="bg-gray-800 text-white px-5 py-2.5 rounded-lg hover:bg-gray-700 shadow-sm font-medium transition-colors flex items-center gap-2 text-sm">
              <PlusIcon className="h-4 w-4" />
              Agregar Ítem
            </button>
          </div>
        </div>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-sm whitespace-nowrap">
            <thead className="bg-gray-50 text-gray-600 uppercase text-[10px] font-black tracking-widest">
              <tr>
                <th className="px-3 py-4 border-b border-gray-200 text-center sticky left-0 bg-gray-50 z-10 w-16">Acciones</th>
                <th className="px-4 py-4 border-b border-gray-200 sticky left-16 bg-gray-50 z-10 w-64 shadow-[10px_0_10px_-10px_rgba(0,0,0,0.1)]">Ítem</th>
                <th className="px-4 py-4 border-b border-gray-200">Áreas</th>
                <th className="px-4 py-4 border-b border-gray-200">Responsable</th>
                <th className="px-4 py-4 border-b border-gray-200">Eficacia</th>
                {MONTHS.map((m, i) => (
                  <th key={m} className="px-3 py-4 border-b border-gray-200 text-center min-w-[80px]">
                    {m}<br/>{anio}
                  </th>
                ))}
                <th className="px-4 py-4 border-b border-gray-200 text-center bg-blue-50 text-blue-800">Cumplimiento<br/>Anual</th>
              </tr>
              <tr className="bg-gray-100">
                <th colSpan="5" className="px-4 py-2 border-b border-gray-200 text-right">Avance Mensual:</th>
                {MONTHS.map((m, i) => (
                  <th key={`pct-${i}`} className={`px-3 py-2 border-b border-gray-200 text-center font-black ${porcentajesMes[i] === 100 ? 'text-green-600' : 'text-gray-700'}`}>
                    {totalMes[i] > 0 ? `${porcentajesMes[i]}%` : '-'}
                  </th>
                ))}
                <th className="px-4 py-2 border-b border-gray-200 bg-blue-100 text-center text-blue-900">-</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-100">
              {loading ? (
                <tr><td colSpan="18" className="px-4 py-10 text-center text-gray-400">Cargando cronogramas...</td></tr>
              ) : cronogramas.length === 0 ? (
                <tr><td colSpan="18" className="px-4 py-10 text-center text-gray-400">No hay registros para este año.</td></tr>
              ) : cronogramas.map(item => (
                <tr key={item.id} className="hover:bg-gray-50 transition-colors">
                  <td className="px-3 py-3 sticky left-0 bg-white z-10 text-center">
                    <div className="flex flex-col gap-1.5 items-center">
                      <button onClick={() => openEdit(item)} className="p-1.5 bg-amber-50 text-amber-600 rounded hover:bg-amber-100 transition-colors"><PencilSquareIcon className="h-4 w-4" /></button>
                      <button onClick={() => handleDelete(item.id)} className="p-1.5 bg-red-50 text-red-600 rounded hover:bg-red-100 transition-colors"><TrashIcon className="h-4 w-4" /></button>
                    </div>
                  </td>
                  <td className="px-4 py-3 sticky left-16 bg-white z-10 shadow-[10px_0_10px_-10px_rgba(0,0,0,0.1)]">
                    <p className="font-bold text-gray-800 whitespace-normal min-w-[200px]">{item.curso}</p>
                  </td>
                  <td className="px-4 py-3 text-xs text-gray-600">{item.areas}</td>
                  <td className="px-4 py-3 text-xs font-medium">{item.responsable}</td>
                  <td className="px-4 py-3 text-xs text-gray-500">{item.eficacia}</td>
                  
                  {MONTHS.map((m, i) => {
                    const fechasMes = item.fechas ? item.fechas.filter(f => f.mes === i) : [];
                    return (
                      <td key={`${item.id}-${i}`} className="px-2 py-3 text-center align-middle border-l border-gray-50">
                        {fechasMes.map(f => (
                          <div 
                            key={f.id}
                            title="Clic para cambiar estado"
                            onClick={() => cambiarEstadoFecha(f.id, f.estado === 0 ? 1 : (f.estado === 1 ? 2 : 0))}
                            className={`mb-1 p-2 rounded cursor-pointer transition-all shadow-sm flex items-center justify-center font-black text-white ${
                              f.estado === 0 ? 'bg-red-500 hover:bg-red-600' :
                              f.estado === 1 ? 'bg-green-500 hover:bg-green-600' :
                              'bg-amber-500 hover:bg-amber-600'
                            }`}
                          >
                            {f.dia?.toString().padStart(2, '0')}
                          </div>
                        ))}
                      </td>
                    );
                  })}
                  
                  <td className="px-4 py-3 text-center bg-blue-50/50">
                    <span className="font-black text-lg text-blue-700">{calcularPorcentajeItem(item)}%</span>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* Modal */}
      {showModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
          <div className="bg-white rounded-2xl shadow-2xl w-full max-w-3xl overflow-hidden animate-in zoom-in duration-200 flex flex-col max-h-[90vh]">
            <div className="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
              <h2 className="text-lg font-bold text-gray-900">{editingId ? 'Editar Ítem de Cronograma' : 'Agregar Ítem a Cronograma'}</h2>
              <button onClick={closeModal} className="text-gray-400 hover:text-gray-700 p-1.5 rounded-lg hover:bg-gray-200 transition-colors">
                <XMarkIcon className="h-5 w-5" />
              </button>
            </div>
            
            <form onSubmit={handleSubmit} className="p-6 overflow-y-auto">
              <div className="space-y-5">
                <div>
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Ítem (Máquina / Detalle) *</label>
                  <select 
                    required 
                    className="w-full p-2.5 border border-gray-300 rounded-md text-sm outline-none focus:border-blue-500" 
                    value={formData.curso} 
                    onChange={e => setFormData({ ...formData, curso: e.target.value })}
                  >
                    <option value="">-- Seleccionar --</option>
                    {maquinas.map(m => (
                      <option key={m.maquina_id} value={`${m.maquina_tipo} - ${m.maquina_codigo} - ${m.maquina_modelo} - ${m.maquina_descripcion}`}>
                        {m.maquina_tipo} - {m.maquina_codigo} - {m.maquina_descripcion}
                      </option>
                    ))}
                    {editingId && !maquinas.find(m => `${m.maquina_tipo} - ${m.maquina_codigo} - ${m.maquina_modelo} - ${m.maquina_descripcion}` === formData.curso) && (
                      <option value={formData.curso}>{formData.curso}</option>
                    )}
                  </select>
                </div>

                <div>
                  <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Áreas</label>
                  <input type="text" className="w-full p-2.5 border border-gray-300 rounded-md text-sm outline-none" value={formData.areas} onChange={e => setFormData({ ...formData, areas: e.target.value })} />
                </div>

                <div className="grid grid-cols-2 gap-4">
                  <div>
                    <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Responsable</label>
                    <input type="text" className="w-full p-2.5 border border-gray-300 rounded-md text-sm outline-none" value={formData.responsable} onChange={e => setFormData({ ...formData, responsable: e.target.value })} />
                  </div>
                  <div>
                    <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Verificador de Eficacia</label>
                    <input type="text" className="w-full p-2.5 border border-gray-300 rounded-md text-sm outline-none" value={formData.eficacia} onChange={e => setFormData({ ...formData, eficacia: e.target.value })} />
                  </div>
                </div>

                <div className="bg-gray-50 p-4 rounded-xl border border-gray-200">
                  <div className="flex items-center justify-between mb-3">
                    <h3 className="text-xs font-bold text-gray-700 uppercase tracking-wider">Fechas Programadas ({formData.anio})</h3>
                    <button type="button" onClick={handleAddMesDia} className="text-[10px] font-bold px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-100 flex items-center gap-1">
                      <PlusIcon className="h-3 w-3" /> Añadir Fecha
                    </button>
                  </div>
                  
                  {formData.meses_dias.map((md, index) => (
                    <div key={index} className="flex items-center gap-3 mb-2">
                      <div className="flex-1">
                        <select className="w-full p-2 border border-gray-300 rounded-md text-sm outline-none" value={md.mes} onChange={e => handleMesDiaChange(index, 'mes', e.target.value)} required>
                          <option value="">-- MES --</option>
                          {MONTHS.map((m, i) => <option key={i} value={i}>{m}</option>)}
                        </select>
                      </div>
                      <div className="flex-1">
                        <input type="number" min="1" max="31" placeholder="Día" className="w-full p-2 border border-gray-300 rounded-md text-sm outline-none" value={md.dia} onChange={e => handleMesDiaChange(index, 'dia', e.target.value)} required />
                      </div>
                      <button type="button" onClick={() => handleRemoveMesDia(index)} className="p-2 text-red-500 hover:bg-red-50 rounded-md" disabled={formData.meses_dias.length === 1}>
                        <TrashIcon className="h-5 w-5" />
                      </button>
                    </div>
                  ))}
                </div>
              </div>

              <div className="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-100">
                <button type="button" onClick={closeModal} className="px-6 py-2.5 text-gray-700 font-bold text-sm hover:bg-gray-100 rounded-md transition-colors">Cancelar</button>
                <button type="submit" disabled={saving} className="px-10 py-2.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-bold text-sm transition-all shadow-sm disabled:opacity-50">
                  {saving ? 'Guardando...' : 'Guardar'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
