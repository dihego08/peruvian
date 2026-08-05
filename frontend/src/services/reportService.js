import api from './api';

const reportService = {
  getSellsSunat: async (params) => {
    const response = await api.get('/reports/sells-sunat', { params });
    return response.data;
  },

  getVentasCliente: async (params) => {
    const response = await api.get('/reports/ventas-cliente', { params });
    return response.data;
  },

  getVentasMensuales: async (params) => {
    const response = await api.get('/reports/ventas-mensuales', { params });
    return response.data;
  },

  getVentasCruzado: async (params) => {
    const response = await api.get('/reports/ventas-cruzado', { params });
    return response.data;
  },

  updateSale: async (codigo, data) => {
    const response = await api.put(`/reports/sells-sunat/${codigo}`, data);
    return response.data;
  },

  anularSale: async (codigo) => {
    const response = await api.delete(`/reports/sells-sunat/${codigo}`);
    return response.data;
  }
};

export default reportService;
