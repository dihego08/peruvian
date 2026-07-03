import { useState, useEffect } from 'react';
import api from '../services/api';
import { getColaboradorFotoUrl, handleColaboradorFotoError } from '../utils/image';
import {
  UserPlusIcon,
  PencilSquareIcon,
  TrashIcon,
  MagnifyingGlassIcon,
  IdentificationIcon,
  BriefcaseIcon,
  CakeIcon,
  ArrowDownTrayIcon,
  XMarkIcon,
  CameraIcon
} from '@heroicons/react/24/outline';

const EMPTY = {
  dni: '',
  nombres: '',
  apellido_paterno: '',
  apellido_materno: '',
  foto: '',
  fecha_nacimiento: '',
  lugar_nacimiento: '',
  id_estado_civil: '',
  celular: '',
  correo: '',
  brevette: '',
  direccion: '',
  telefono_emergencia: '',
  id_sistema_pension: '',
  id_entidad_pension: '',
  codigo: '',
  asegurado: false,
  proceso: '',
  sueldo: '',
  genero: 'M',
  estado_laboral: '1',
  fecha_ingreso: '',
  fecha_salida: '',
  id_cargo: '',
  linea: '1',
  estado: true
};

export default function ColaboradoresView() {
  const [colaboradores, setColaboradores] = useState([]);
  const [metadata, setMetadata] = useState({ areas: [], puestos: [], estado_civil: [], sistema_pensiones: [], afps: [] });
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [editingId, setEditingId] = useState(null);
  const [formData, setFormData] = useState(EMPTY);
  const [saving, setSaving] = useState(false);
  const [activeTab, setActiveTab] = useState('personal');
  const [fotoPreview, setFotoPreview] = useState(null);

  // Filters
  const [filterMonth, setFilterMonth] = useState('0');
  const [filterLine, setFilterLine] = useState('0');
  const [search, setSearch] = useState('');

  useEffect(() => {
    fetchMetadata();
    fetchColaboradores();
  }, [filterMonth, filterLine]);

  const fetchColaboradores = async () => {
    try {
      const r = await api.get('/sig/colaboradores', {
        params: { mes_cumpleanos: filterMonth, linea: filterLine, search }
      });
      setColaboradores(r.data);
    } catch (e) { console.error(e); }
    finally { setLoading(false); }
  };

  const fetchMetadata = async () => {
    try {
      const r = await api.get('/sig/colaboradores/metadata');
      setMetadata(r.data);
    } catch (e) { console.error(e); }
  };

  const openCreate = () => {
    setEditingId(null);
    setFormData(EMPTY);
    setFotoPreview(null);
    setActiveTab('personal');
    setShowModal(true);
  };

  const openEdit = (c) => {
    setEditingId(c.id);
    setFormData({
      ...c,
      asegurado: !!c.asegurado,
      estado: !!c.estado
    });
    setFotoPreview(getColaboradorFotoUrl(c.foto));
    setActiveTab('personal');
    setShowModal(true);
  };

  const closeModal = () => {
    setShowModal(false);
    setEditingId(null);
    setFormData(EMPTY);
    setFotoPreview(null);
  };

  const handleFotoChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setFormData(f => ({ ...f, foto: file }));
      setFotoPreview(URL.createObjectURL(file));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      const data = new FormData();
      Object.keys(formData).forEach(key => {
        if (formData[key] !== null && formData[key] !== undefined) {
          data.append(key, formData[key]);
        }
      });
      if (editingId) {
        data.append('id', editingId);
      }
      await api.post('/sig/colaboradores', data, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
      closeModal();
      fetchColaboradores();
    } catch (err) {
      alert(err.response?.data?.message || 'Error al guardar');
    } finally {
      setSaving(false);
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('¿Eliminar este colaborador?')) return;
    try {
      await api.delete(`/sig/colaboradores/${id}`);
      fetchColaboradores();
    } catch (e) {
      alert('Error al eliminar');
    }
  };

  const handleSearch = (e) => {
    e.preventDefault();
    fetchColaboradores();
  };

  const MESES = [
    { v: '0', n: '-- CUMPLEAÑOS --' },
    { v: '1', n: 'Enero' }, { v: '2', n: 'Febrero' }, { v: '3', n: 'Marzo' },
    { v: '4', n: 'Abril' }, { v: '5', n: 'Mayo' }, { v: '6', n: 'Junio' },
    { v: '7', n: 'Julio' }, { v: '8', n: 'Agosto' }, { v: '9', n: 'Septiembre' },
    { v: '10', n: 'Octubre' }, { v: '11', n: 'Noviembre' }, { v: '12', n: 'Diciembre' }
  ];

  const LINEAS = [
    { v: '0', n: '-- TODAS LAS LÍNEAS --' },
    { v: '1', n: 'Línea 1' }, { v: '2', n: 'Línea 2' }, { v: '3', n: 'Línea 3' },
    { v: '4', n: 'Línea 4' }, { v: '5', n: 'Línea 5' }, { v: '6', n: 'Línea 6' },
    { v: '7', n: 'Línea 7' }, { v: '8', n: 'Línea 8' }, { v: '9', n: 'Línea 9' },
    { v: '10', n: 'Inactivo' }
  ];

  return (
    <div className="flex flex-col gap-6 animate-in fade-in duration-500">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Personal / Colaboradores</h1>
          <p className="text-sm text-gray-500 mt-0.5">Gestión de datos maestros de trabajadores (SIG)</p>
        </div>
        <button onClick={openCreate} className="bg-gray-800 text-white px-5 py-2.5 rounded-lg hover:bg-gray-700 shadow-sm font-medium transition-colors flex items-center gap-2 text-sm">
          <UserPlusIcon className="h-4 w-4" />
          Nuevo Colaborador
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <select
          className="p-2 border border-gray-300 rounded-lg text-sm bg-gray-50 outline-none focus:border-blue-500"
          value={filterMonth}
          onChange={e => setFilterMonth(e.target.value)}
        >
          {MESES.map(m => <option key={m.v} value={m.v}>{m.n}</option>)}
        </select>
        <select
          className="p-2 border border-gray-300 rounded-lg text-sm bg-gray-50 outline-none focus:border-blue-500"
          value={filterLine}
          onChange={e => setFilterLine(e.target.value)}
        >
          {LINEAS.map(l => <option key={l.v} value={l.v}>{l.n}</option>)}
        </select>
        <form onSubmit={handleSearch} className="md:col-span-2 relative">
          <input
            type="text"
            placeholder="Buscar por DNI o Nombre..."
            className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50 outline-none focus:border-blue-500"
            value={search}
            onChange={e => setSearch(e.target.value)}
          />
          <MagnifyingGlassIcon className="h-4 w-4 absolute left-3 top-2.5 text-gray-400" />
        </form>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table className="w-full text-left text-sm">
          <thead className="bg-gray-50 text-gray-600 uppercase text-xs border-b border-gray-200">
            <tr>
              <th className="px-6 py-4 font-bold">DNI</th>
              <th className="px-6 py-4 font-bold">Nombres y Apellidos</th>
              <th className="px-6 py-4 font-bold">Puesto / Área</th>
              <th className="px-6 py-4 font-bold">Estado</th>
              <th className="px-6 py-4 font-bold text-center">Acciones</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-100">
            {loading && <tr><td colSpan="5" className="px-6 py-10 text-center text-gray-400">Cargando datos...</td></tr>}
            {!loading && colaboradores.length === 0 && <tr><td colSpan="5" className="px-6 py-10 text-center text-gray-400">No hay colaboradores registrados con los filtros seleccionados.</td></tr>}
            {colaboradores.map(c => (
              <tr key={c.id} className="hover:bg-gray-50 transition-colors">
                <td className="px-6 py-4 font-mono text-gray-500">{c.dni}</td>
                <td className="px-6 py-4 font-bold text-gray-800">
                  {c.nombres} {c.apellido_paterno} {c.apellido_materno}
                </td>
                <td className="px-6 py-4">
                  <div className="text-xs font-bold text-blue-600">{c.puesto?.puesto || 'S/P'}</div>
                  <div className="text-[10px] text-gray-400 uppercase tracking-wider">{c.area?.area || 'S/A'}</div>
                </td>
                <td className="px-6 py-4 text-xs">
                  <span className={`px-2 py-1 rounded-full font-bold ${c.estado ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`}>
                    {c.estado ? 'Activo' : 'Inactivo'}
                  </span>
                </td>
                <td className="px-6 py-4">
                  <div className="flex items-center justify-center gap-2">
                    <button onClick={() => openEdit(c)} className="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Editar">
                      <PencilSquareIcon className="h-5 w-5" />
                    </button>
                    <button onClick={() => handleDelete(c.id)} className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar">
                      <TrashIcon className="h-5 w-5" />
                    </button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {showModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-in fade-in duration-300">
          <div className="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden animate-in zoom-in-95 duration-300">
            <div className="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
              <div className="flex items-center gap-3">
                <div className="p-2 bg-blue-100 text-blue-600 rounded-lg">
                  <IdentificationIcon className="h-5 w-5" />
                </div>
                <h2 className="text-lg font-bold text-gray-900">{editingId ? 'Editar Colaborador' : 'Nuevo Colaborador'}</h2>
              </div>
              <button onClick={closeModal} className="text-gray-400 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-200 transition-colors">
                <XMarkIcon className="h-5 w-5" />
              </button>
            </div>

            <div className="flex border-b border-gray-200 bg-white">
              <button
                className={`px-6 py-3 text-sm font-bold transition-all border-b-2 ${activeTab === 'personal' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'}`}
                onClick={() => setActiveTab('personal')}
              >
                DATOS PERSONALES
              </button>
              <button
                className={`px-6 py-3 text-sm font-bold transition-all border-b-2 ${activeTab === 'profesional' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'}`}
                onClick={() => setActiveTab('profesional')}
              >
                DATOS LABORALES
              </button>
            </div>

            <form onSubmit={handleSubmit} className="p-6 overflow-y-auto max-h-[70vh]">
              {activeTab === 'personal' && (
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6 animate-in slide-in-from-left-2 duration-300">
                  <div className="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div className="space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">DNI *</label>
                      <input required type="text" className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={formData.dni} onChange={e => setFormData({ ...formData, dni: e.target.value })} />
                    </div>
                    <div className="space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Nombres *</label>
                      <input required type="text" className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={formData.nombres} onChange={e => setFormData({ ...formData, nombres: e.target.value })} />
                    </div>
                    <div className="space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Apellido Paterno *</label>
                      <input required type="text" className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={formData.apellido_paterno} onChange={e => setFormData({ ...formData, apellido_paterno: e.target.value })} />
                    </div>
                    <div className="space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Apellido Materno *</label>
                      <input required type="text" className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={formData.apellido_materno} onChange={e => setFormData({ ...formData, apellido_materno: e.target.value })} />
                    </div>
                    <div className="space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Género</label>
                      <select className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none bg-white" value={formData.genero} onChange={e => setFormData({ ...formData, genero: e.target.value })}>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                      </select>
                    </div>
                    <div className="space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Estado Civil</label>
                      <select className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none bg-white" value={formData.id_estado_civil} onChange={e => setFormData({ ...formData, id_estado_civil: e.target.value })}>
                        <option value="">Seleccione...</option>
                        {metadata.estado_civil.map(ec => <option key={ec.id} value={ec.id}>{ec.estado_civil}</option>)}
                      </select>
                    </div>
                    <div className="space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Fec. Nacimiento</label>
                      <input type="date" className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={formData.fecha_nacimiento} onChange={e => setFormData({ ...formData, fecha_nacimiento: e.target.value })} />
                    </div>
                    <div className="space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Lugar Nacimiento</label>
                      <input type="text" className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={formData.lugar_nacimiento} onChange={e => setFormData({ ...formData, lugar_nacimiento: e.target.value })} />
                    </div>
                  </div>

                  <div className="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-200 rounded-2xl gap-4">
                    <div className="w-40 h-40 bg-gray-100 rounded-2xl flex items-center justify-center border border-gray-200 overflow-hidden shadow-inner">
                      {fotoPreview ? (
                        <img
                          src={fotoPreview}
                          className="w-full h-full object-cover"
                          alt="Foto"
                          onError={(e) => {
                            if (typeof formData.foto === 'string') {
                              handleColaboradorFotoError(e, formData.foto);
                            }
                          }}
                        />
                      ) : (
                        <CameraIcon className="h-12 w-12 text-gray-300" />
                      )}
                    </div>
                    <label htmlFor="foto-upload" className="cursor-pointer text-xs font-bold text-blue-600 hover:underline">
                      CARGAR FOTOGRAFÍA
                    </label>
                    <input
                      id="foto-upload"
                      type="file"
                      accept="image/*"
                      className="hidden"
                      onChange={handleFotoChange}
                    />
                    <p className="text-[10px] text-gray-400 text-center uppercase tracking-widest px-4">Formato sugerido: 400x400px (JPG/PNG)</p>
                  </div>

                  <div className="md:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-4 border-t border-gray-100 pt-6 mt-2">
                    <div className="space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Celular</label>
                      <input type="text" className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={formData.celular} onChange={e => setFormData({ ...formData, celular: e.target.value })} />
                    </div>
                    <div className="space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Correo</label>
                      <input type="email" className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={formData.correo} onChange={e => setFormData({ ...formData, correo: e.target.value })} />
                    </div>
                    <div className="space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Brevette</label>
                      <input type="text" className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={formData.brevette} onChange={e => setFormData({ ...formData, brevette: e.target.value })} />
                    </div>
                    <div className="md:col-span-2 space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Dirección</label>
                      <input type="text" className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={formData.direccion} onChange={e => setFormData({ ...formData, direccion: e.target.value })} />
                    </div>
                    <div className="space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Teléf. Emergencia</label>
                      <input type="text" className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={formData.telefono_emergencia} onChange={e => setFormData({ ...formData, telefono_emergencia: e.target.value })} />
                    </div>
                  </div>
                </div>
              )}

              {activeTab === 'profesional' && (
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6 animate-in slide-in-from-right-2 duration-300">
                  <div className="space-y-4">
                    <div className="space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Puesto *</label>
                      <select required className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none bg-white" value={formData.id_cargo} onChange={e => setFormData({ ...formData, id_cargo: e.target.value })}>
                        <option value="">Seleccione Puesto...</option>
                        {metadata.puestos.map(p => <option key={p.id} value={p.id}>{p.puesto}</option>)}
                      </select>
                    </div>
                    <div className="space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Área / Proceso *</label>
                      <select required className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none bg-white" value={formData.proceso} onChange={e => setFormData({ ...formData, proceso: e.target.value })}>
                        <option value="">Seleccione Área...</option>
                        {metadata.areas.map(a => <option key={a.id} value={a.id}>{a.area}</option>)}
                      </select>
                    </div>
                    <div className="space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Línea de Trabajo</label>
                      <select className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none bg-white" value={formData.linea} onChange={e => setFormData({ ...formData, linea: e.target.value })}>
                        {LINEAS.filter(l => l.v !== '0').map(l => <option key={l.v} value={l.v}>{l.n}</option>)}
                      </select>
                    </div>
                    <div className="space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Sueldo</label>
                      <input type="number" step="0.01" className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={formData.sueldo} onChange={e => setFormData({ ...formData, sueldo: e.target.value })} />
                    </div>
                  </div>

                  <div className="space-y-4">
                    <div className="grid grid-cols-2 gap-4">
                      <div className="space-y-1">
                        <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha Ingreso</label>
                        <input type="date" className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={formData.fecha_ingreso} onChange={e => setFormData({ ...formData, fecha_ingreso: e.target.value })} />
                      </div>
                      <div className="space-y-1">
                        <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha Salida</label>
                        <input type="date" className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={formData.fecha_salida} onChange={e => setFormData({ ...formData, fecha_salida: e.target.value })} />
                      </div>
                    </div>
                    <div className="space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Estado Laboral</label>
                      <select className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none bg-white" value={formData.estado_laboral} onChange={e => setFormData({ ...formData, estado_laboral: e.target.value })}>
                        <option value="1">Contratado</option>
                        <option value="2">Labora s/Contrato</option>
                        <option value="3">Practicante</option>
                        <option value="4">Contrato Vencido</option>
                        <option value="5">Renuncia</option>
                        <option value="6">Despido</option>
                      </select>
                    </div>
                    <div className="grid grid-cols-2 gap-4 py-2">
                      <label className="flex items-center gap-3 cursor-pointer p-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-all">
                        <input type="checkbox" className="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500" checked={formData.asegurado} onChange={e => setFormData({ ...formData, asegurado: e.target.checked })} />
                        <span className="text-xs font-bold text-gray-700 uppercase">Asegurado</span>
                      </label>
                      <label className="flex items-center gap-3 cursor-pointer p-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-all">
                        <input type="checkbox" className="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500" checked={formData.estado} onChange={e => setFormData({ ...formData, estado: e.target.checked })} />
                        <span className="text-xs font-bold text-gray-700 uppercase">Activo</span>
                      </label>
                    </div>
                    <div className="space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Código Interno</label>
                      <input type="text" className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none" value={formData.codigo} onChange={e => setFormData({ ...formData, codigo: e.target.value })} />
                    </div>
                  </div>

                  <div className="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-100 pt-6">
                    <div className="space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Sistema de Pensiones</label>
                      <select className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none bg-white" value={formData.id_sistema_pension} onChange={e => setFormData({ ...formData, id_sistema_pension: e.target.value })}>
                        <option value="">Seleccione...</option>
                        {metadata.sistema_pensiones.map(sp => <option key={sp.id} value={sp.id}>{sp.sistema_pension}</option>)}
                      </select>
                    </div>
                    <div className="space-y-1">
                      <label className="text-xs font-bold text-gray-500 uppercase tracking-wider">Entidad de Pensiones (AFP)</label>
                      <select className="w-full p-2.5 border border-gray-300 rounded-lg text-sm focus:border-blue-500 outline-none bg-white" value={formData.id_entidad_pension} onChange={e => setFormData({ ...formData, id_entidad_pension: e.target.value })}>
                        <option value="">Seleccione...</option>
                        {metadata.afps.filter(afp => !formData.id_sistema_pension || afp.id_sistema_pensiones == formData.id_sistema_pension).map(afp => <option key={afp.id} value={afp.id}>{afp.afp}</option>)}
                      </select>
                    </div>
                  </div>
                </div>
              )}

              <div className="flex justify-end gap-3 pt-8 border-t border-gray-100 mt-6">
                <button type="button" onClick={closeModal} className="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-sm transition-colors">Cancelar</button>
                <button type="submit" disabled={saving} className="px-10 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold text-sm disabled:opacity-60 transition-all shadow-lg shadow-blue-500/20">
                  {saving ? 'Guardando...' : editingId ? 'Actualizar Colaborador' : 'Registrar Colaborador'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
