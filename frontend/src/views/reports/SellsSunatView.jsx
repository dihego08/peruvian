import React, { useState, useEffect } from 'react';
import reportService from '../../services/reportService';
import { CheckIcon, MagnifyingGlassIcon } from '@heroicons/react/24/outline';

const SellsSunatView = () => {
  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(false);
  const [desde, setDesde] = useState('');
  const [hasta, setHasta] = useState('');

  // Estados temporales para los inputs de cada fila (codigo_venta -> valores)
  const [inputs, setInputs] = useState({});

  const fetchData = async () => {
    setLoading(true);
    try {
      const response = await reportService.getSellsSunat({ desde, hasta, filtro: 'ninguno' });
      if (response.Result === 'OK') {
        setData(response.Records);
        // Inicializar inputs
        const newInputs = {};
        response.Records.forEach(r => {
          newInputs[r.codigo_venta] = {
            fecha_pago: r.fecha_pago || '',
            entidad: r.entidad || '',
            fecha_detraccion: r.fecha_detraccion || '',
            guia: r.guia || ''
          };
        });
        setInputs(newInputs);
      }
    } catch (error) {
      console.error('Error fetching sells sunat:', error);
    }
    setLoading(false);
  };

  useEffect(() => {
    fetchData();
  }, []);

  const handleInputChange = (codigo, field, value) => {
    setInputs(prev => ({
      ...prev,
      [codigo]: {
        ...prev[codigo],
        [field]: value
      }
    }));
  };

  const handleActualizar = async (codigo) => {
    const payload = {
      guia: inputs[codigo]?.guia,
      fecha_pago: inputs[codigo]?.fecha_pago,
      entidad: inputs[codigo]?.entidad,
      fecha_det: inputs[codigo]?.fecha_detraccion
    };

    try {
      const res = await reportService.updateSale(codigo, payload);
      if (res.Result === 'OK') {
        alert('Actualizado Correctamente.');
        fetchData();
      } else {
        alert('Hubo un error al actualizar.');
      }
    } catch (error) {
      console.error(error);
      alert('Hubo un error al actualizar.');
    }
  };

  const handleAnular = async (codigo) => {
    if (!window.confirm('¿Seguro de Anular esta Venta?')) return;

    try {
      const res = await reportService.anularSale(codigo);
      if (res.Result === 'OK') {
        alert('Anulado Correctamente.');
        fetchData();
      } else {
        alert('Hubo un error al anular.');
      }
    } catch (error) {
      console.error(error);
      alert('Hubo un error al anular.');
    }
  };

  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold mb-4">Ventas - Sunat</h1>
      <div className="mb-4 flex gap-4">
        <div>
          <label className="block text-sm font-medium text-gray-700">Desde</label>
          <input type="date" className="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-blue-500 outline-none font-mono" value={desde} onChange={(e) => setDesde(e.target.value)} />
        </div>
        <div>
          <label className="block text-sm font-medium text-gray-700">Hasta</label>
          <input type="date" className="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-blue-500 outline-none font-mono" value={hasta} onChange={(e) => setHasta(e.target.value)} />
        </div>
        <div className="flex items-end">
          <button onClick={fetchData} className="w-full p-3 bg-blue-500 text-white font-bold rounded-xl hover:bg-blue-700 transition flex justify-center items-center gap-2 text-sm">
            <MagnifyingGlassIcon className="w-5 h-5" /> Buscar
          </button>
        </div>
      </div>

      <div className="bg-white rounded-lg shadow overflow-hidden overflow-x-auto">
        <table className="min-w-full divide-y divide-gray-200" style={{ fontSize: '11px' }}>
          <thead className="bg-gray-50">
            <tr>
              <th className="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
              <th className="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">N° Doc</th>
              <th className="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
              <th className="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">V. Venta</th>
              <th className="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">P. Venta</th>
              <th className="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">IGV</th>
              <th className="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Detrac.</th>
              <th className="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">IGV x Pagar</th>
              <th className="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Renta 3ra</th>
              <th className="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Valor a Pagar</th>
              <th className="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Fecha Pago</th>
              <th className="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Entidad</th>
              <th className="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Fecha Detrac.</th>
              <th className="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Guía</th>
              <th className="px-2 py-2 text-center font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {loading ? (
              <tr><td colSpan="15" className="px-6 py-4 text-center">Cargando...</td></tr>
            ) : data.length === 0 ? (
              <tr><td colSpan="15" className="px-6 py-4 text-center">No hay datos</td></tr>
            ) : (
              data.map((item) => {
                const isAnulado = item.id_estado_entrega === 4 || item.estado_anulado === 1;
                const rowClass = isAnulado ? 'bg-red-100 text-red-900' : 'text-gray-900';

                // Cálculos replicados de clsVenta.php
                const renta3ra = (parseFloat(item.subtotal || 0) * 0.02).toFixed(2);
                const valorPagar = (parseFloat(item.total || 0) - parseFloat(item.detraccion_p || 0)).toFixed(2);

                const currInputs = inputs[item.codigo_venta] || {};

                return (
                  <tr key={item.codigo_venta} className={rowClass}>
                    <td className="px-2 py-2 whitespace-nowrap">{item.person}</td>
                    <td className="px-2 py-2 whitespace-nowrap">{item.codigo_venta}</td>
                    <td className="px-2 py-2 whitespace-nowrap">{item.fecha_creacion}</td>
                    <td className="px-2 py-2 whitespace-nowrap">{item.subtotal}</td>
                    <td className="px-2 py-2 whitespace-nowrap">{item.total}</td>
                    <td className="px-2 py-2 whitespace-nowrap">{item.igv}</td>
                    <td className="px-2 py-2 whitespace-nowrap">{item.detraccion_p}</td>
                    <td className="px-2 py-2 whitespace-nowrap">{item.igv_p}</td>
                    <td className="px-2 py-2 whitespace-nowrap">{renta3ra}</td>
                    <td className="px-2 py-2 whitespace-nowrap">{valorPagar}</td>

                    <td className="px-2 py-2">
                      <input type="text" className="border rounded px-1 py-0.5 w-20 text-xs"
                        value={currInputs.fecha_pago || ''}
                        onChange={(e) => handleInputChange(item.codigo_venta, 'fecha_pago', e.target.value)} />
                    </td>
                    <td className="px-2 py-2">
                      <input type="text" className="border rounded px-1 py-0.5 w-20 text-xs"
                        value={currInputs.entidad || ''}
                        onChange={(e) => handleInputChange(item.codigo_venta, 'entidad', e.target.value)} />
                    </td>
                    <td className="px-2 py-2">
                      <input type="text" className="border rounded px-1 py-0.5 w-20 text-xs"
                        value={currInputs.fecha_detraccion || ''}
                        onChange={(e) => handleInputChange(item.codigo_venta, 'fecha_detraccion', e.target.value)} />
                    </td>
                    <td className="px-2 py-2">
                      <input type="text" className="border rounded px-1 py-0.5 w-20 text-xs"
                        value={currInputs.guia || ''}
                        onChange={(e) => handleInputChange(item.codigo_venta, 'guia', e.target.value)} />
                    </td>

                    <td className="px-2 py-2 text-center whitespace-nowrap flex gap-1">
                      <button onClick={() => handleActualizar(item.codigo_venta)} className="text-green-600 hover:bg-green-100 p-1 rounded" title="Actualizar">
                        <CheckIcon className="w-4 h-4" />
                      </button>
                    </td>
                  </tr>
                );
              })
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default SellsSunatView;
