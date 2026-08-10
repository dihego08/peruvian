import { useState, useEffect } from 'react';
import api from '../services/api';
import {
  XMarkIcon,
  PlusIcon,
  TrashIcon,
  PencilSquareIcon,
  ArrowDownTrayIcon,
  DocumentArrowUpIcon,
  CheckIcon,
  PrinterIcon
} from '@heroicons/react/24/outline';

export default function TechSheetModal({ isOpen, onClose, productCode }) {
  const [activeTab, setActiveTab] = useState('ficha');
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);

  // Form states
  const [newComplemento, setNewComplemento] = useState({ titulo: '', complemento: '' });
  const [newIdentificacion, setNewIdentificacion] = useState({ titulo: '', complemento: '' });
  const [newModificacion, setNewModificacion] = useState({ titulo: '', aprobado_por: '', ultima_modificacion: new Date().toISOString().split('T')[0] });
  const [newObservacion, setNewObservacion] = useState({ observacion: '', detalle: '' });

  // Manual states
  const [manualData, setManualData] = useState({ maquinas: [], records: [] });
  const [newMaquina, setNewMaquina] = useState('');

  // Medidas states
  const [medidas, setMedidas] = useState([]);
  const [newMedida, setNewMedida] = useState({
    descripcion: '', t_2: '', t_4: '', t_6: '', t_8: '', t_10: '', t_12: '', t_14: '', t_16: '',
    s: '', m: '', l: '', xl: '', xxl: '', xxxl: ''
  });

  useEffect(() => {
    if (isOpen && productCode) {
      fetchData();
    }
  }, [isOpen, productCode]);

  const fetchData = async () => {
    setLoading(true);
    try {
      const [fichaRes, manualRes, medidasRes] = await Promise.all([
        api.get(`/tech-sheets/${productCode}`),
        api.get(`/tech-sheets/${productCode}/manual`),
        api.get(`/tech-sheets/${productCode}/medidas`)
      ]);
      setData(fichaRes.data);
      setManualData(manualRes.data);
      setMedidas(medidasRes.data);
    } catch (error) {
      console.error("Error fetching tech sheet data", error);
    } finally {
      setLoading(false);
    }
  };

  const handleSaveFicha = async () => {
    setSaving(true);
    try {
      await api.put(`/tech-sheets/${productCode}`, data.ficha);
      alert("Ficha actualizada correctamente");
    } catch (error) {
      alert("Error al guardar ficha");
    } finally {
      setSaving(false);
    }
  };

  const addComplemento = async () => {
    if (!newComplemento.titulo || !newComplemento.complemento) return;
    try {
      await api.post('/tech-sheets/complementos', { ...newComplemento, code_producto: productCode });
      setNewComplemento({ titulo: '', complemento: '' });
      fetchData();
    } catch (error) { console.error(error); }
  };

  const deleteComplemento = async (id) => {
    if (!confirm("¿Eliminar complemento?")) return;
    try {
      await api.delete(`/tech-sheets/complementos/${id}`);
      fetchData();
    } catch (error) { console.error(error); }
  };

  const addIdentificacion = async () => {
    if (!newIdentificacion.titulo || !newIdentificacion.complemento) return;
    try {
      await api.post('/tech-sheets/identificacion', { ...newIdentificacion, code_producto: productCode });
      setNewIdentificacion({ titulo: '', complemento: '' });
      fetchData();
    } catch (error) { console.error(error); }
  };

  const deleteIdentificacion = async (id) => {
    if (!confirm("¿Eliminar identificación?")) return;
    try {
      await api.delete(`/tech-sheets/identificacion/${id}`);
      fetchData();
    } catch (error) { console.error(error); }
  };

  const addModificacion = async () => {
    if (!newModificacion.titulo) return;
    try {
      await api.post('/tech-sheets/modificaciones', { ...newModificacion, code_producto: productCode });
      setNewModificacion({ titulo: '', aprobado_por: '', ultima_modificacion: new Date().toISOString().split('T')[0] });
      fetchData();
    } catch (error) { console.error(error); }
  };

  const deleteModificacion = async (id) => {
    if (!confirm("¿Eliminar modificación?")) return;
    try {
      await api.delete(`/tech-sheets/modificaciones/${id}`);
      fetchData();
    } catch (error) { console.error(error); }
  };

  const addObservacion = async () => {
    if (!newObservacion.observacion) return;
    try {
      await api.post('/tech-sheets/observaciones', { ...newObservacion, code_producto: productCode });
      setNewObservacion({ observacion: '', detalle: '' });
      fetchData();
    } catch (error) { console.error(error); }
  };

  const deleteObservacion = async (id) => {
    if (!confirm("¿Eliminar observación?")) return;
    try {
      await api.delete(`/tech-sheets/observaciones/${id}`);
      fetchData();
    } catch (error) { console.error(error); }
  };

  const addMaquina = async () => {
    if (!newMaquina) return;
    try {
      await api.post('/tech-sheets/maquinas', { maquina: newMaquina, code_producto: productCode });
      setNewMaquina('');
      fetchData();
    } catch (error) { console.error(error); }
  };

  const deleteMaquina = async (id) => {
    try {
      await api.delete(`/tech-sheets/maquinas/${id}`);
      fetchData();
    } catch (error) { console.error(error); }
  };

  const addInstruccion = async (etapaId) => {
    const inst = manualData.records.find(r => r.id === etapaId).newInstruccion || {};
    if (!inst.paso || !inst.instruccion) return;
    try {
      await api.post('/tech-sheets/instruccion', { ...inst, id_etapa: etapaId, code_producto: productCode, orden: inst.orden || 0 });
      fetchData();
    } catch (error) { console.error(error); }
  };

  const deleteInstruccion = async (id) => {
    if (!confirm("¿Eliminar instrucción?")) return;
    try {
      await api.delete(`/tech-sheets/instruccion/${id}`);
      fetchData();
    } catch (error) { console.error(error); }
  };

  const addMedida = async () => {
    if (!newMedida.descripcion) return;
    try {
      await api.post('/tech-sheets/medidas', { ...newMedida, code_producto: productCode });
      setNewMedida({
        descripcion: '', t_2: '', t_4: '', t_6: '', t_8: '', t_10: '', t_12: '', t_14: '', t_16: '',
        s: '', m: '', l: '', xl: '', xxl: '', xxxl: ''
      });
      fetchData();
    } catch (error) { console.error(error); }
  };

  const deleteMedida = async (id) => {
    if (!confirm("¿Eliminar medida?")) return;
    try {
      await api.delete(`/tech-sheets/medidas/${id}`);
      fetchData();
    } catch (error) { console.error(error); }
  };

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
      <div className="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-[95vh] overflow-hidden flex flex-col animate-in fade-in zoom-in duration-200">
        {/* Header */}
        <div className="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
          <div>
            <h2 className="text-xl font-bold text-gray-900">Ficha Técnica de Producto</h2>
            <p className="text-sm text-gray-500 font-medium">Modelo: <span className="text-blue-600 font-bold">{productCode}</span></p>
          </div>
          <button onClick={onClose} className="p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-200 rounded-lg transition-colors">
            <XMarkIcon className="h-6 w-6" />
          </button>
        </div>

        {/* Tabs */}
        <div className="flex border-b border-gray-200 bg-gray-50/50">
          {[
            { id: 'ficha', label: 'Ficha Técnica' },
            { id: 'manual', label: 'Manual' },
            { id: 'medidas', label: 'Medidas' },
            { id: 'adjunto', label: 'Adjuntos' },
            { id: 'observaciones', label: 'Observaciones' }
          ].map((tab) => (
            <button
              key={tab.id}
              onClick={() => setActiveTab(tab.id)}
              className={`px-6 py-3 text-sm font-bold transition-all relative ${activeTab === tab.id ? 'text-blue-600' : 'text-gray-500 hover:text-gray-700'
                }`}
            >
              {tab.label}
              {activeTab === tab.id && <div className="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600"></div>}
            </button>
          ))}
        </div>

        {/* Content */}
        <div className="flex-1 overflow-y-auto p-6 bg-white">
          {loading ? (
            <div className="flex flex-col items-center justify-center py-20 text-gray-400">
              <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
              <p className="font-medium">Cargando información técnica...</p>
            </div>
          ) : (
            <div className="animate-in fade-in duration-500">
              {activeTab === 'ficha' && (
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                  <div className="space-y-6">
                    <section>
                      <h4 className="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 border-b border-gray-100 pb-1">Identificación del Producto</h4>
                      <div className="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                        <table className="w-full text-sm">
                          <tbody className="divide-y divide-gray-200">
                            {data.identificacion.map((item) => (
                              <tr key={item.id} className="group hover:bg-white transition-colors">
                                <td className="px-4 py-2.5 font-bold text-gray-600 w-1/3">{item.titulo}</td>
                                <td className="px-4 py-2.5 text-gray-800">{item.complemento}</td>
                                <td className="px-4 py-2.5 text-right">
                                  <button onClick={() => deleteIdentificacion(item.id)} className="p-1 text-red-600 hover:bg-red-50 rounded transition-colors opacity-0 group-hover:opacity-100">
                                    <TrashIcon className="h-4 w-4" />
                                  </button>
                                </td>
                              </tr>
                            ))}
                            <tr className="bg-white">
                              <td className="p-2"><input value={newIdentificacion.titulo} onChange={e => setNewIdentificacion({ ...newIdentificacion, titulo: e.target.value })} placeholder="Título" className="w-full p-2 border border-gray-200 rounded text-xs outline-none focus:border-blue-500" /></td>
                              <td className="p-2"><input value={newIdentificacion.complemento} onChange={e => setNewIdentificacion({ ...newIdentificacion, complemento: e.target.value })} placeholder="Detalle" className="w-full p-2 border border-gray-200 rounded text-xs outline-none focus:border-blue-500" /></td>
                              <td className="p-2 text-right"><button onClick={addIdentificacion} className="p-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"><PlusIcon className="h-4 w-4" /></button></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </section>

                    <section>
                      <h4 className="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 border-b border-gray-100 pb-1">Materiales y Complementos</h4>
                      <div className="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                        <table className="w-full text-sm">
                          <tbody className="divide-y divide-gray-200">
                            {data.complementos.map((item) => (
                              <tr key={item.id} className="group hover:bg-white transition-colors">
                                <td className="px-4 py-2.5 font-bold text-gray-600 w-1/3">{item.titulo}</td>
                                <td className="px-4 py-2.5 text-gray-800">{item.complemento}</td>
                                <td className="px-4 py-2.5 text-right">
                                  <button onClick={() => deleteComplemento(item.id)} className="p-1 text-red-600 hover:bg-red-50 rounded transition-colors opacity-0 group-hover:opacity-100">
                                    <TrashIcon className="h-4 w-4" />
                                  </button>
                                </td>
                              </tr>
                            ))}
                            <tr className="bg-white">
                              <td className="p-2"><input value={newComplemento.titulo} onChange={e => setNewComplemento({ ...newComplemento, titulo: e.target.value })} placeholder="Título" className="w-full p-2 border border-gray-200 rounded text-xs outline-none focus:border-blue-500" /></td>
                              <td className="p-2"><input value={newComplemento.complemento} onChange={e => setNewComplemento({ ...newComplemento, complemento: e.target.value })} placeholder="Detalle" className="w-full p-2 border border-gray-200 rounded text-xs outline-none focus:border-blue-500" /></td>
                              <td className="p-2 text-right"><button onClick={addComplemento} className="p-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"><PlusIcon className="h-4 w-4" /></button></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </section>
                  </div>

                  <div className="space-y-6">
                    <div className="bg-gray-50 rounded-xl overflow-hidden aspect-video flex items-center justify-center border border-gray-200">
                      {data.product.image ? (
                        <img src={`https://omcar.peruviandress.com/storage/products/${data.product.image}`} className="w-full h-full object-contain" alt="Product" />
                      ) : (
                        <div className="text-center text-gray-300">
                          <EyeIcon className="h-12 w-12 mx-auto mb-2" />
                          <p className="text-xs font-bold uppercase">Sin imagen</p>
                        </div>
                      )}
                    </div>

                    <div className="bg-gray-50 rounded-xl p-5 border border-gray-200">
                      <h4 className="text-xs font-bold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <PencilSquareIcon className="h-4 w-4" />
                        Control de Edición
                      </h4>
                      <div className="grid grid-cols-2 gap-4 mb-4">
                        <div>
                          <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Elaborado por</label>
                          <input
                            value={data.ficha?.elaborado_por || ''}
                            onChange={e => setData({ ...data, ficha: { ...data.ficha, elaborado_por: e.target.value } })}
                            className="w-full bg-white border border-gray-300 rounded-md p-2 text-sm focus:border-blue-500 outline-none"
                          />
                        </div>
                        <div>
                          <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Última Modificación</label>
                          <input
                            type="date"
                            value={data.ficha?.u_modificacion || ''}
                            onChange={e => setData({ ...data, ficha: { ...data.ficha, u_modificacion: e.target.value } })}
                            className="w-full bg-white border border-gray-300 rounded-md p-2 text-sm focus:border-blue-500 outline-none"
                          />
                        </div>
                      </div>
                      <button
                        onClick={handleSaveFicha}
                        disabled={saving}
                        className="w-full bg-blue-600 text-white py-2 rounded-md font-bold text-sm hover:bg-blue-700 transition-all flex items-center justify-center gap-2"
                      >
                        {saving ? 'Guardando...' : 'Guardar Cambios'}
                      </button>
                    </div>

                    <section>
                      <h4 className="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 border-b border-gray-100 pb-1">Historial de Modificaciones</h4>
                      <div className="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                        <table className="w-full text-[11px]">
                          <thead className="bg-gray-100 text-gray-500 uppercase font-bold">
                            <tr>
                              <th className="px-3 py-2 text-left">Cambio</th>
                              <th className="px-3 py-2 text-left">Aprobado</th>
                              <th className="px-3 py-2 text-left">Fecha</th>
                              <th className="px-3 py-2"></th>
                            </tr>
                          </thead>
                          <tbody className="divide-y divide-gray-200">
                            {data.modificaciones.map((item) => (
                              <tr key={item.id} className="group hover:bg-white transition-colors">
                                <td className="px-3 py-2 text-gray-800">{item.titulo}</td>
                                <td className="px-3 py-2 text-gray-600 italic">{item.aprobado_por}</td>
                                <td className="px-3 py-2 text-gray-500">{item.ultima_modificacion}</td>
                                <td className="px-3 py-2 text-right">
                                  <button onClick={() => deleteModificacion(item.id)} className="p-1 text-red-600 hover:bg-red-50 rounded opacity-0 group-hover:opacity-100 transition-colors">
                                    <TrashIcon className="h-3.5 w-3.5" />
                                  </button>
                                </td>
                              </tr>
                            ))}
                            <tr className="bg-white">
                              <td className="p-1.5"><input value={newModificacion.titulo} onChange={e => setNewModificacion({ ...newModificacion, titulo: e.target.value })} placeholder="Cambio" className="w-full p-1.5 border border-gray-200 rounded outline-none focus:border-blue-500" /></td>
                              <td className="p-1.5"><input value={newModificacion.aprobado_por} onChange={e => setNewModificacion({ ...newModificacion, aprobado_por: e.target.value })} placeholder="Nombre" className="w-full p-1.5 border border-gray-200 rounded outline-none focus:border-blue-500" /></td>
                              <td className="p-1.5"><input type="date" value={newModificacion.ultima_modificacion} onChange={e => setNewModificacion({ ...newModificacion, ultima_modificacion: e.target.value })} className="w-full p-1.5 border border-gray-200 rounded outline-none focus:border-blue-500" /></td>
                              <td className="p-1.5 text-right"><button onClick={addModificacion} className="p-1.5 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"><PlusIcon className="h-4 w-4" /></button></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </section>
                  </div>
                </div>
              )}

              {activeTab === 'manual' && (
                <div className="space-y-6">
                  <section>
                    <h4 className="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-1">Maquinaria y Puntadas</h4>
                    <div className="flex flex-wrap gap-2 mb-6">
                      {manualData.maquinas.map(m => (
                        <div key={m.id} className="bg-blue-50 text-blue-700 border border-blue-200 rounded-lg px-3 py-1.5 text-xs font-bold flex items-center gap-2 group">
                          {m.maquina}
                          <button onClick={() => deleteMaquina(m.id)} className="text-blue-400 hover:text-red-600 transition-colors opacity-0 group-hover:opacity-100">
                            <XMarkIcon className="h-3.5 w-3.5" />
                          </button>
                        </div>
                      ))}
                      <div className="flex items-center gap-2">
                        <input
                          value={newMaquina}
                          onChange={e => setNewMaquina(e.target.value)}
                          placeholder="Nueva Máquina / Puntada"
                          className="border border-gray-300 rounded-md px-3 py-1.5 text-xs focus:border-blue-500 outline-none"
                        />
                        <button onClick={addMaquina} className="p-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors shadow-sm">
                          <PlusIcon className="h-4 w-4" />
                        </button>
                      </div>
                    </div>
                  </section>

                  <div className="grid grid-cols-1 gap-6">
                    {manualData.records.map(etapa => (
                      <div key={etapa.id} className="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                        <div className="bg-gray-100 px-4 py-2 border-b border-gray-200 flex justify-between items-center">
                          <h5 className="font-bold text-xs text-gray-700 uppercase tracking-wide">{etapa.etapa}</h5>
                          <span className="text-[10px] text-gray-400 font-bold uppercase">{etapa.pasos.length} Pasos</span>
                        </div>
                        <div className="bg-white">
                          <table className="w-full text-xs">
                            <thead className="bg-gray-50/50 text-gray-500 uppercase font-bold border-b border-gray-100">
                              <tr>
                                <th className="px-4 py-2 text-left w-1/4">Paso</th>
                                <th className="px-4 py-2 text-left">Instrucción</th>
                                <th className="px-4 py-2 text-center w-16">Orden</th>
                                <th className="px-4 py-2 w-10"></th>
                              </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                              {etapa.pasos.map(paso => (
                                <tr key={paso.id} className="hover:bg-gray-50 transition-colors">
                                  <td className="px-4 py-3 font-bold text-gray-800">{paso.paso}</td>
                                  <td className="px-4 py-3 text-gray-600 leading-relaxed" dangerouslySetInnerHTML={{ __html: paso.instruccion }}></td>
                                  <td className="px-4 py-3 text-center font-mono text-gray-500">{paso.orden}</td>
                                  <td className="px-4 py-3 text-right">
                                    <button onClick={() => deleteInstruccion(paso.id)} className="p-1.5 text-red-600 hover:bg-red-50 rounded transition-colors opacity-0 group-hover:opacity-100">
                                      <TrashIcon className="h-4 w-4" />
                                    </button>
                                  </td>
                                </tr>
                              ))}
                              <tr className="bg-gray-50/30">
                                <td className="p-2"><input placeholder="Ej: Costura hombros" className="w-full p-2 border border-gray-200 rounded text-xs outline-none focus:border-blue-500" onChange={e => {
                                  const records = [...manualData.records];
                                  const idx = records.findIndex(r => r.id === etapa.id);
                                  records[idx].newInstruccion = { ...records[idx].newInstruccion, paso: e.target.value };
                                  setManualData({ ...manualData, records });
                                }} /></td>
                                <td className="p-2"><textarea placeholder="Detalle técnico..." rows={1} className="w-full p-2 border border-gray-200 rounded text-xs outline-none focus:border-blue-500" onChange={e => {
                                  const records = [...manualData.records];
                                  const idx = records.findIndex(r => r.id === etapa.id);
                                  records[idx].newInstruccion = { ...records[idx].newInstruccion, instruccion: e.target.value };
                                  setManualData({ ...manualData, records });
                                }} /></td>
                                <td className="p-2"><input type="number" placeholder="0" className="w-full p-2 border border-gray-200 rounded text-xs text-center outline-none focus:border-blue-500" onChange={e => {
                                  const records = [...manualData.records];
                                  const idx = records.findIndex(r => r.id === etapa.id);
                                  records[idx].newInstruccion = { ...records[idx].newInstruccion, orden: e.target.value };
                                  setManualData({ ...manualData, records });
                                }} /></td>
                                <td className="p-2"><button onClick={() => addInstruccion(etapa.id)} className="p-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"><PlusIcon className="h-4 w-4" /></button></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              )}

              {activeTab === 'medidas' && (
                <div className="space-y-4">
                  <h4 className="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-1">Matriz de Medidas y Tallas</h4>
                  <div className="bg-white rounded-xl border border-gray-200 overflow-x-auto shadow-sm">
                    <table className="w-full text-[11px] min-w-[1200px]">
                      <thead className="bg-gray-800 text-white font-bold uppercase">
                        <tr>
                          <th className="px-4 py-3 text-left w-64">Descripción / Punto de Medida</th>
                          {['2', '4', '6', '8', '10', '12', '14', '16', 'S', 'M', 'L', 'XL', '2XL', '3XL'].map(t => (
                            <th key={t} className="px-2 py-3 text-center">{t}</th>
                          ))}
                          <th className="px-4 py-3"></th>
                        </tr>
                      </thead>
                      <tbody className="divide-y divide-gray-200">
                        {medidas.map((item) => (
                          <tr key={item.id} className="hover:bg-gray-50 transition-colors">
                            <td className="px-4 py-3 font-bold text-gray-800">{item.descripcion}</td>
                            {[item.t_2, item.t_4, item.t_6, item.t_8, item.t_10, item.t_12, item.t_14, item.t_16, item.s, item.m, item.l, item.xl, item.xxl, item.xxxl].map((val, i) => (
                              <td key={i} className="px-2 py-3 text-center text-gray-600 font-mono">{val || '-'}</td>
                            ))}
                            <td className="px-4 py-3 text-right">
                              <button onClick={() => deleteMedida(item.id)} className="p-1.5 text-red-600 hover:bg-red-50 rounded transition-colors opacity-0 group-hover:opacity-100">
                                <TrashIcon className="h-4 w-4" />
                              </button>
                            </td>
                          </tr>
                        ))}
                        <tr className="bg-blue-50/30">
                          <td className="p-2"><input value={newMedida.descripcion} onChange={e => setNewMedida({ ...newMedida, descripcion: e.target.value })} placeholder="Nuevo punto de medida" className="w-full p-2 border border-gray-200 rounded text-xs outline-none focus:border-blue-500 font-bold" /></td>
                          {['t_2', 't_4', 't_6', 't_8', 't_10', 't_12', 't_14', 't_16', 's', 'm', 'l', 'xl', 'xxl', 'xxxl'].map(t => (
                            <td key={t} className="p-1.5"><input value={newMedida[t]} onChange={e => setNewMedida({ ...newMedida, [t]: e.target.value })} className="w-full p-1.5 border border-gray-200 rounded text-xs text-center font-mono outline-none focus:border-blue-500" /></td>
                          ))}
                          <td className="p-2 text-right"><button onClick={addMedida} className="p-2.5 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors shadow-sm"><PlusIcon className="h-4 w-4" /></button></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              )}

              {activeTab === 'adjunto' && (
                <div className="flex flex-col items-center justify-center py-20 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                  <div className="bg-white rounded-full p-8 shadow-sm border border-gray-100 mb-6 group cursor-pointer hover:border-blue-500 transition-all">
                    <DocumentArrowUpIcon className="h-16 w-16 text-gray-400 group-hover:text-blue-600 transition-colors" />
                    <input type="file" className="hidden" />
                  </div>
                  <div className="text-center mb-10">
                    <h4 className="text-lg font-bold text-gray-800">Cargar Archivo Técnico</h4>
                    <p className="text-sm text-gray-500 mt-1">Haz clic para subir especificaciones en PDF, Excel o Imagen</p>
                  </div>
                  {data.archivo && (
                    <div className="bg-white p-4 rounded-xl border border-gray-200 flex items-center gap-4 w-full max-w-md shadow-sm animate-in slide-in-from-bottom-4">
                      <div className="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                        <DocumentArrowUpIcon className="h-7 w-7" />
                      </div>
                      <div className="flex-1 overflow-hidden">
                        <p className="font-bold text-sm truncate text-gray-800">{data.archivo.archivo}</p>
                        <p className="text-[10px] uppercase font-bold text-gray-400">Archivo Actual</p>
                      </div>
                      <div className="flex gap-2">
                        <a href={`https://omcar.peruviandress.com/core/app/view/img-colaboradores/${data.archivo.archivo}`} target="_blank" rel="noreferrer" className="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Descargar">
                          <ArrowDownTrayIcon className="h-5 w-5" />
                        </a>
                      </div>
                    </div>
                  )}
                </div>
              )}

              {activeTab === 'observaciones' && (
                <div className="space-y-6">
                  <h4 className="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-1">Notas y Observaciones Técnicas</h4>
                  <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {data.observaciones.map(item => (
                      <div key={item.id} className="bg-gray-50 p-5 rounded-xl border border-gray-200 relative group">
                        <button onClick={() => deleteObservacion(item.id)} className="absolute top-3 right-3 p-1.5 text-red-600 hover:bg-red-100 rounded-md transition-colors opacity-0 group-hover:opacity-100">
                          <TrashIcon className="h-4 w-4" />
                        </button>
                        <h5 className="font-bold text-gray-900 mb-2">{item.observacion}</h5>
                        <p className="text-gray-600 text-sm leading-relaxed italic">"{item.detalle}"</p>
                      </div>
                    ))}
                    <div className="bg-white p-6 rounded-xl border-2 border-dashed border-gray-200 flex flex-col gap-4">
                      <div>
                        <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Título de Nota</label>
                        <input
                          value={newObservacion.observacion}
                          onChange={e => setNewObservacion({ ...newObservacion, observacion: e.target.value })}
                          placeholder="Ej: Lavado / Encogimiento"
                          className="w-full border border-gray-300 rounded-md p-2 text-sm focus:border-blue-500 outline-none"
                        />
                      </div>
                      <div>
                        <label className="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Detalle</label>
                        <textarea
                          value={newObservacion.detalle}
                          onChange={e => setNewObservacion({ ...newObservacion, detalle: e.target.value })}
                          placeholder="Escribir observación técnica..."
                          rows={3}
                          className="w-full border border-gray-300 rounded-md p-2 text-sm focus:border-blue-500 outline-none resize-none"
                        />
                      </div>
                      <button
                        onClick={addObservacion}
                        className="bg-gray-800 text-white py-2 rounded-md font-bold text-sm hover:bg-gray-700 transition-all flex items-center justify-center gap-2"
                      >
                        <PlusIcon className="h-4 w-4" />
                        Agregar Nota
                      </button>
                    </div>
                  </div>
                </div>
              )}
            </div>
          )}
        </div>

        {/* Footer */}
        <div className="bg-gray-100 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
          <button
            className="flex items-center gap-2 px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-md font-bold hover:bg-gray-50 transition-all shadow-sm text-sm"
            onClick={() => window.open(`https://omcar.peruviandress.com/core/app/view/pdf-ficha_tecnica.php?num_modelo=${productCode}`, '_blank')}
          >
            <PrinterIcon className="h-5 w-5" />
            Exportar PDF
          </button>
          <div className="flex gap-3">
            <button
              onClick={onClose}
              className="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-md font-bold hover:bg-gray-50 transition-all text-sm"
            >
              Cerrar
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
