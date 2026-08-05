import React, { useState, useEffect } from 'react';
import { PieChart, Pie, Tooltip, Cell, ResponsiveContainer, Legend } from 'recharts';
import reportService from '../../services/reportService';
import { CheckIcon, MagnifyingGlassIcon } from '@heroicons/react/24/outline';

const COLORS = ['#0088FE', '#00C49F', '#FFBB28', '#FF8042', '#8884D8', '#82CA9D', '#F06292', '#BA68C8'];

const VentasClienteView = () => {
  const [data, setData] = useState([]);
  const [graficoClientes, setGraficoClientes] = useState([]);
  const [graficoModelos, setGraficoModelos] = useState([]);
  const [loading, setLoading] = useState(false);

  // Default to last month
  const d = new Date();
  d.setMonth(d.getMonth() - 1);
  const [desde, setDesde] = useState(d.toISOString().split('T')[0]);
  const [hasta, setHasta] = useState(new Date().toISOString().split('T')[0]);

  const fetchData = async () => {
    setLoading(true);
    try {
      const response = await reportService.getVentasCliente({ desde, hasta });
      if (response.Result === 'OK') {
        setData(response.Records);
        setGraficoClientes(response.graficoClientes || []);
        setGraficoModelos(response.graficoModelos || []);
      }
    } catch (error) {
      console.error('Error fetching ventas cliente:', error);
    }
    setLoading(false);
  };

  useEffect(() => {
    fetchData();
  }, []);

  return (
    <div className="p-6 space-y-6">
      <h1 className="text-2xl font-bold">Ventas x Cliente</h1>

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
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modelo</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {loading ? (
              <tr><td colSpan="5" className="px-6 py-4 text-center">Cargando...</td></tr>
            ) : data.length === 0 ? (
              <tr><td colSpan="5" className="px-6 py-4 text-center">No hay datos</td></tr>
            ) : (
              data.map((item, index) => (
                <tr key={index}>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{item.cliente}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{item.fecha}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{item.cantidad}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{item.modelo}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">S/. {parseFloat(item.subtotal).toFixed(2)}</td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div className="bg-white p-4 rounded-lg shadow">
          <h3 className="text-lg font-medium text-center mb-4">Ventas x Cliente</h3>
          <div className="h-80 w-full">
            <ResponsiveContainer width="100%" height="100%">
              <PieChart>
                <Pie data={graficoClientes} dataKey="y" nameKey="label" cx="50%" cy="50%" outerRadius={100} label>
                  {graficoClientes.map((entry, index) => (
                    <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                  ))}
                </Pie>
                <Tooltip formatter={(value) => `S/. ${value}`} />
                <Legend />
              </PieChart>
            </ResponsiveContainer>
          </div>
        </div>

        <div className="bg-white p-4 rounded-lg shadow">
          <h3 className="text-lg font-medium text-center mb-4">Ventas x Modelo</h3>
          <div className="h-80 w-full">
            <ResponsiveContainer width="100%" height="100%">
              <PieChart>
                <Pie data={graficoModelos} dataKey="y" nameKey="label" cx="50%" cy="50%" outerRadius={100} label>
                  {graficoModelos.map((entry, index) => (
                    <Cell key={`cell-${index}`} fill={COLORS[(index + 4) % COLORS.length]} />
                  ))}
                </Pie>
                <Tooltip formatter={(value) => `S/. ${value}`} />
                <Legend />
              </PieChart>
            </ResponsiveContainer>
          </div>
        </div>
      </div>
    </div>
  );
};

export default VentasClienteView;
