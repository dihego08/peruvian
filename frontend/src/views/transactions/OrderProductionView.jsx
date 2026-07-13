import React, { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import api from '../../services/api';

const SIZE_COLS = ['_2','_4','_6','_8','_10','_12','_14','_16','s','m','l','xl','xxl'];
const PROD_COLS = ['p2','p4','p6','p8','p10','p12','p14','p16','ps','pm','pl','pxl','pxxl'];

function toDateInput(val) {
  if (!val) return '';
  return String(val).slice(0, 10);
}

function prodSum(produced) {
  return produced.reduce((acc, v) => acc + (parseInt(v, 10) || 0), 0);
}

function orderSum(record) {
  return SIZE_COLS.reduce((acc, col) => acc + (parseInt(record[col], 10) || 0), 0);
}

export default function OrderProductionView() {
  const { codigo } = useParams();
  const navigate = useNavigate();
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [headers, setHeaders] = useState([]);
  const [records, setRecords] = useState([]);
  const [meta, setMeta] = useState({
    n_contrato: '',
    guia: '',
    fecha_estimada: '',
    fecha_entrega: '',
    fecha_desde: '',
    nombre_modelo: '',
  });

  useEffect(() => { loadProduction(); }, [codigo]);

  const loadProduction = async () => {
    setLoading(true);
    try {
      const res = await api.get(`/transactions/orders/${codigo}/production`);
      const data = res.data;
      const recs = (data.Records || []).map((r) => ({
        ...r,
        produced: PROD_COLS.map((col) => (r[col] != null && r[col] !== '' ? String(r[col]) : '')),
      }));
      setRecords(recs);
      if (recs.length > 0) {
        setHeaders(Array.from({ length: 13 }, (_, i) => recs[0][`n${i + 1}`] || '-'));
      }
      setMeta({
        n_contrato: data.num_contrato || '',
        guia: data.guia_remision || '',
        fecha_estimada: toDateInput(data.fecha_entrega),
        fecha_entrega: toDateInput(data.fecha_entrega_real),
        fecha_desde: toDateInput(data.fecha_creacion),
        nombre_modelo: data.nombre_modelo || '',
      });
    } catch (e) {
      console.error(e);
      alert('Error al cargar el pedido');
      navigate('/orders');
    } finally {
      setLoading(false);
    }
  };

  const handleProducedChange = (rowIdx, colIdx, val) => {
    setRecords((prev) => prev.map((r, i) => {
      if (i !== rowIdx) return r;
      const produced = [...r.produced];
      produced[colIdx] = val;
      return { ...r, produced };
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      await api.put(`/transactions/orders/${codigo}/production`, {
        ...meta,
        rows: records.map((r) => ({ id: r.id, produced: r.produced })),
      });
      alert('Avance de producción guardado correctamente');
      loadProduction();
    } catch (err) {
      console.error(err);
      alert('Error al guardar');
    } finally {
      setSaving(false);
    }
  };

  const totalPedido = records.reduce((acc, r) => acc + orderSum(r), 0);
  const totalProducido = records.reduce((acc, r) => acc + prodSum(r.produced), 0);

  if (loading) {
    return (
      <div className="flex flex-col items-center justify-center py-24 text-gray-400">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4" />
        <p>Cargando avance del pedido {codigo}…</p>
      </div>
    );
  }

  return (
    <div className="flex flex-col gap-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Avance de Producción</h1>
          <p className="text-sm text-gray-500 mt-0.5">
            Pedido <span 
              className="font-mono font-bold text-blue-600 cursor-pointer hover:underline"
              onClick={() => navigate('/orders', { state: { highlightOrder: codigo } })}
            >
              {codigo}
            </span>
          </p>
        </div>
        <button onClick={() => navigate('/orders', { state: { highlightOrder: codigo } })} className="text-sm text-gray-500 hover:text-gray-800">← Volver a pedidos</button>
      </div>

      <form onSubmit={handleSubmit} className="flex flex-col gap-6">
        <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
              <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Número Contrato</label>
              <input type="text" className="w-full p-2 border rounded-md text-sm" value={meta.n_contrato} onChange={(e) => setMeta({ ...meta, n_contrato: e.target.value })} />
            </div>
            <div>
              <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Número de Guía</label>
              <input type="text" className="w-full p-2 border rounded-md text-sm" value={meta.guia} onChange={(e) => setMeta({ ...meta, guia: e.target.value })} />
            </div>
            <div>
              <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Fecha Entrega Estimada</label>
              <input type="date" className="w-full p-2 border rounded-md text-sm" value={meta.fecha_estimada} onChange={(e) => setMeta({ ...meta, fecha_estimada: e.target.value })} />
            </div>
            <div>
              <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Fecha de Entrega</label>
              <input type="date" className="w-full p-2 border rounded-md text-sm" value={meta.fecha_entrega} onChange={(e) => setMeta({ ...meta, fecha_entrega: e.target.value })} />
            </div>
            <div>
              <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Fecha de Pedido</label>
              <input type="date" className="w-full p-2 border rounded-md text-sm" value={meta.fecha_desde} onChange={(e) => setMeta({ ...meta, fecha_desde: e.target.value })} />
            </div>
            <div>
              <label className="block text-xs font-bold text-gray-500 uppercase mb-1">Producto</label>
              <input type="text" className="w-full p-2 border rounded-md text-sm" value={meta.nombre_modelo} onChange={(e) => setMeta({ ...meta, nombre_modelo: e.target.value })} />
            </div>
          </div>
        </div>

        <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div className="overflow-x-auto">
            <table className="w-full text-sm border-collapse">
              <thead className="bg-gray-50">
                <tr>
                  <th rowSpan={2} className="px-3 py-2 border-b border-r text-center align-middle">Modelo</th>
                  <th rowSpan={2} className="px-3 py-2 border-b border-r text-center align-middle">Color</th>
                  <th colSpan={13} className="px-3 py-2 border-b text-center">Cantidades por Talla</th>
                  <th rowSpan={2} className="px-3 py-2 border-b text-center align-middle">Tot.</th>
                </tr>
                <tr className="text-[10px] font-bold text-blue-600">
                  {headers.map((h, i) => (
                    <th key={i} className="px-2 py-1 border-b text-center">{h}</th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {records.map((rec, ri) => (
                  <React.Fragment key={rec.id}>
                    <tr className="bg-white">
                      <td rowSpan={2} className="px-3 py-2 border-r border-b align-middle font-medium">{rec.modelo}</td>
                      <td rowSpan={2} className="px-3 py-2 border-r border-b align-middle">{rec.color || '—'}</td>
                      {SIZE_COLS.map((col) => (
                        <td key={col} className="px-2 py-2 border-b text-center text-gray-700">{rec[col] || 0}</td>
                      ))}
                      <td rowSpan={2} className="px-3 py-2 border-b text-center font-bold align-middle">{rec.total || orderSum(rec)}</td>
                    </tr>
                    <tr className="bg-green-50/40">
                      {rec.produced.map((val, ci) => (
                        <td key={ci} className="px-1 py-1 border-b">
                          <input
                            type="number"
                            min="0"
                            className="w-full p-1 border border-green-200 rounded text-center text-sm font-semibold text-green-800 bg-white"
                            value={val}
                            onChange={(e) => handleProducedChange(ri, ci, e.target.value)}
                          />
                        </td>
                      ))}
                    </tr>
                  </React.Fragment>
                ))}
              </tbody>
            </table>
          </div>
          <div className="px-6 py-4 bg-gray-50 border-t flex flex-wrap gap-6 text-sm font-bold">
            <span className="text-red-700">TOTAL PEDIDO: {totalPedido}</span>
            <span className="text-green-700">TOTAL PRODUCIDO: {totalProducido}</span>
          </div>
        </div>

        <div className="flex justify-end gap-3">
          <button type="button" onClick={() => navigate('/orders')} className="bg-white border border-gray-300 text-gray-700 px-6 py-2.5 rounded-md text-sm">Cancelar</button>
          <button type="submit" disabled={saving} className="bg-green-600 text-white px-8 py-2.5 rounded-md text-sm font-medium disabled:opacity-60">
            {saving ? 'Guardando...' : 'Guardar Cambios'}
          </button>
        </div>
      </form>
    </div>
  );
}
