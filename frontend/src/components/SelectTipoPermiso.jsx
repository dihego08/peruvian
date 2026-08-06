import { useState, useEffect } from 'react';
import {
    getTiposPermisos
} from "../services/tiposPermisosService";

export default function SelectTipoPermiso({ value, onChange }) {
    const [tiposPermisos, setTiposPermisos] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        listar();
    }, []);

    const listar = async () => {
        const data = await getTiposPermisos();
        setLoading(false);
        setTiposPermisos(data || []);
    };

    if (loading) return (
        <div className="flex items-center gap-2 text-sm text-gray-500">
            <div className="w-4 h-4 border-2 border-gray-300 border-t-blue-600 rounded-full animate-spin"></div>
            Cargando tipos de permisos...
        </div>
    );

    return (
        <select 
            value={value || ""} 
            onChange={(e) => onChange(e.target.value)}
            className="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm"
        >
            <option value="">--SELECCIONE--</option>
            {tiposPermisos.map((tipo_permiso) => (
                <option key={tipo_permiso.id} value={tipo_permiso.id}>
                    {tipo_permiso.nombres} {tipo_permiso.tipo}
                </option>
            ))}
        </select>
    );
}