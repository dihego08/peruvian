import React, { useState, useEffect } from 'react';
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, LabelList } from 'recharts';
import reportService from '../../services/reportService';
import { CheckIcon, MagnifyingGlassIcon } from '@heroicons/react/24/outline';

const VentasMensualesView = () => {
  const [data, setData] = useState([]);
  const [anios, setAnios] = useState([]);
  const [meses, setMeses] = useState([]);
  const [graficoDatos, setGraficoDatos] = useState([]);
  const [loading, setLoading] = useState(false);

  const [desde, setDesde] = useState('');
  const [hasta, setHasta] = useState('');

  const fetchData = async () => {
    setLoading(true);
    try {
      // Pedimos datos para la tabla y gráfico
      const response = await reportService.getVentasMensuales({ desde, hasta });
      if (response.Result === 'OK') {
        if (desde && hasta) {
          // Response mode para grafico
          const formattedData = response.meses.map((mes, index) => ({
            name: mes,
            Soles: response.totales[index]
          }));
          setGraficoDatos(formattedData);
        } else {
          // Response mode para tabla de años
          setAnios(response.anios);

          // Formatear data para la tabla
          // Necesitamos filas para cada mes (1-12)
          const tablaMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
          const filas = tablaMeses.map((mes, index) => {
            const fila = { mes };
            response.anios.forEach(a => {
              const yearData = response.Records[a.anio] || [];
              const found = yearData.find(d => d.mes === index + 1);
              fila[a.anio] = found ? found.total : '0.00';
            });
            return fila;
          });
          setData(filas);

          // Además obtener el gráfico por defecto (este año)
          const resGrafico = await reportService.getVentasMensuales({ desde: `${new Date().getFullYear()}-01-01`, hasta: `${new Date().getFullYear()}-12-31` });
          if (resGrafico.Result === 'OK') {
            const formattedData = resGrafico.meses.map((mes, index) => ({
              name: mes,
              Soles: resGrafico.totales[index]
            }));
            setGraficoDatos(formattedData);
          }
        }
      }
    } catch (error) {
      console.error('Error fetching ventas mensuales:', error);
    }
    setLoading(false);
  };

  useEffect(() => {
    fetchData();
  }, []);

  const handleFiltrarGrafico = async () => {
    fetchData();
  };

  return (
    <div className="p-6 space-y-6">
      <h1 className="text-2xl font-bold">Ventas x Periodo</h1>

      {!desde && !hasta && (
        <div className="bg-white rounded-lg shadow overflow-hidden overflow-x-auto">
          <table className="min-w-full divide-y divide-gray-200">
            <thead className="bg-gray-50">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periodo</th>
                {anios.map((a, i) => (
                  <th key={i} className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{a.anio}</th>
                ))}
              </tr>
            </thead>
            <tbody className="bg-white divide-y divide-gray-200">
              {loading ? (
                <tr><td colSpan={anios.length + 1} className="px-6 py-4 text-center">Cargando...</td></tr>
              ) : data.length === 0 ? (
                <tr><td colSpan={anios.length + 1} className="px-6 py-4 text-center">No hay datos</td></tr>
              ) : (
                data.map((fila, index) => (
                  <tr key={index}>
                    <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{fila.mes}</td>
                    {anios.map((a, i) => (
                      <td key={i} className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">S/. {parseFloat(fila[a.anio]).toFixed(2)}</td>
                    ))}
                  </tr>
                ))
              )}
              <tr className="bg-gray-50 font-bold">
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">TOTALES</td>
                {anios.map((a, i) => {
                  const total = data.reduce((acc, row) => acc + parseFloat(row[a.anio] || 0), 0);
                  return <td key={i} className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">S/. {total.toFixed(2)}</td>;
                })}
              </tr>
            </tbody>
          </table>
        </div>
      )}

      <div className="bg-white rounded-lg shadow p-6">
        <h3 className="text-lg font-medium mb-4">Gráfico de Ventas Mensuales</h3>
        <div className="mb-4 flex gap-4 items-end">
          <div>
            <label className="block text-sm font-medium text-gray-700">Desde</label>
            <input type="date" className="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-blue-500 outline-none font-mono" value={desde} onChange={(e) => setDesde(e.target.value)} />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700">Hasta</label>
            <input type="date" className="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-blue-500 outline-none font-mono" value={hasta} onChange={(e) => setHasta(e.target.value)} />
          </div>
          <div>
            <button onClick={handleFiltrarGrafico} className="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Filtrar Gráfico</button>
            <button onClick={() => { setDesde(''); setHasta(''); fetchData(); }} className="ml-2 bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Ver Tabla General</button>
          </div>
        </div>

        <div className="h-96 w-full">
          <ResponsiveContainer width="100%" height="100%">
            <BarChart data={graficoDatos} margin={{ top: 20, right: 30, left: 20, bottom: 5 }}>
              <CartesianGrid strokeDasharray="3 3" />
              <XAxis dataKey="name" />
              <YAxis tickFormatter={(value) => `S/. ${value}`} />
              <Tooltip formatter={(value) => `S/. ${value}`} />
              <Bar dataKey="Soles" fill="#3b82f6" radius={[4, 4, 0, 0]}>
                <LabelList 
                  dataKey="Soles" 
                  position="top" 
                  formatter={(value) => `S/. ${parseFloat(value).toFixed(2)}`} 
                  style={{ fill: '#4b5563', fontSize: 11, fontWeight: 'bold' }} 
                />
              </Bar>
            </BarChart>
          </ResponsiveContainer>
        </div>
      </div>
    </div>
  );
};

export default VentasMensualesView;
