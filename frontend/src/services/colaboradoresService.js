import api from './api';

export const getColaboradores = async () => {
    try {
        const response = await api.get('/sig/colaboradores');
        return response.data;
    } catch (error) {
        console.error("Error fetching colaboradores:", error);
        return [];
    }
};
