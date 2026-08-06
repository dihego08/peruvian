import { useState, useEffect } from 'react';
import { getColaboradores } from '../services/colaboradoresService';

export default function SelectColaborador({ value, onChange }) {
    const [colaboradores, setColaboradores] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        listar();
    }, []);

    const listar = async () => {
        const data = await getColaboradores();
        setLoading(false);
        setColaboradores(data || []);
    };

    if (loading) return (
        <div className="flex items-center gap-2 text-sm text-gray-500">
            <div className="w-4 h-4 border-2 border-gray-300 border-t-blue-600 rounded-full animate-spin"></div>
            Cargando colaboradores...
        </div>
    );

    return (
        <select
            value={value || ""}
            onChange={(e) => {
                const selectedOption = e.target.options[e.target.selectedIndex];
                onChange({
                    id: e.target.value,
                    nombre: selectedOption.text,
                    dni: selectedOption.dataset.dni || ""
                });
            }}
            className="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm"
        >
            <option value="">--SELECCIONE--</option>
            {colaboradores.map((colaborador) => (
                <option key={colaborador.id} value={colaborador.id} data-dni={colaborador.dni}>
                    {colaborador.nombres} {colaborador.apellido_paterno} {colaborador.apellido_materno}
                </option>
            ))}
        </select>
    );
}