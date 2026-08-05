import React, { useState, useEffect } from 'react';
import reportService from '../../services/reportService';
import { CheckIcon, MagnifyingGlassIcon } from '@heroicons/react/24/outline';

const VentasCruzadoView = () => {
  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(false);

  const d = new Date();
  d.setMonth(d.getMonth() - 1);
  const [desde, setDesde] = useState(d.toISOString().split('T')[0]);
  const [hasta, setHasta] = useState(new Date().toISOString().split('T')[0]);

  const fetchData = async () => {
    setLoading(true);
    try {
      const response = await reportService.getVentasCruzado({ desde, hasta });
      if (response.Result === 'OK') {
        setData(response.Records);
      }
    } catch (error) {
      console.error('Error fetching ventas cruzado:', error);
    }
    setLoading(false);
  };

  useEffect(() => {
    fetchData();
  }, []);

  return (
    <div className="p-6 space-y-6">
      <h1 className="text-2xl font-bold">Ventas Guias y Pedidos</h1>

      <div className="bg-white p-4 rounded-lg shadow flex gap-4 items-end">
        <div>
          <label className="block text-sm font-medium text-gray-700">Desde</label>
          <input type="date" className="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-blue-500 outline-none font-mono" value={desde} onChange={(e) => setDesde(e.target.value)} />
        </div>
        <div>
          <label className="block text-sm font-medium text-gray-700">Hasta</label>
          <input type="date" className="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-blue-500 outline-none font-mono" value={hasta} onChange={(e) => setHasta(e.target.value)} />
        </div>
        <div>
          <button onClick={fetchData} className="w-full p-3 bg-blue-500 text-white font-bold rounded-xl hover:bg-blue-700 transition flex justify-center items-center gap-2 text-sm"><MagnifyingGlassIcon className="w-5 h-5" /> Buscar</button>
        </div>
      </div>

      <div className="bg-white rounded-lg shadow overflow-hidden overflow-x-auto">
        <table className="min-w-full divide-y divide-gray-200">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nro Documento</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nro Guia</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nro Pedido</th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {loading ? (
              <tr><td colSpan="4" className="px-6 py-4 text-center">Cargando...</td></tr>
            ) : data.length === 0 ? (
              <tr><td colSpan="4" className="px-6 py-4 text-center">No hay datos</td></tr>
            ) : (
              data.map((item, index) => (
                <tr key={index}>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{item.fecha}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{item.venta}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{item.guia}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{item.pedido}</td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default VentasCruzadoView;
