import { useEffect, useState } from "react";
import alertify from "alertifyjs";
import Switch from "react-switch";
import {
	createHorario,
	deleteHorario,
	getHorarios,
	updateHorario,
	actualizarEstadoHorario
} from "../services/horariosService";
import { PencilSquareIcon, TrashIcon, PlusIcon, XMarkIcon } from '@heroicons/react/24/outline';

export default function Horarios() {
	const [horarios, setHorarios] = useState([]);
	const [nombre, setNombre] = useState("");
	const [descripcion, setDescripcion] = useState("");
	const [tolerancia_min, setToleranciaMin] = useState("");
	const [estado, setEstado] = useState("A");
	const [show, setShow] = useState(false);
	const [idEdit, setIdEdit] = useState(null);
	const [loadingId, setLoadingId] = useState(null);
	const dias_semana = [
		{ dia: 1, nombre: "Lunes", activo: false, hora_entrada: "", hora_salida: "", usaRefrigerio: false, hora_inicio_refrigerio: "", hora_fin_refrigerio: "" },
		{ dia: 2, nombre: "Martes", activo: false, hora_entrada: "", hora_salida: "", usaRefrigerio: false, hora_inicio_refrigerio: "", hora_fin_refrigerio: "" },
		{ dia: 3, nombre: "Miércoles", activo: false, hora_entrada: "", hora_salida: "", usaRefrigerio: false, hora_inicio_refrigerio: "", hora_fin_refrigerio: "" },
		{ dia: 4, nombre: "Jueves", activo: false, hora_entrada: "", hora_salida: "", usaRefrigerio: false, hora_inicio_refrigerio: "", hora_fin_refrigerio: "" },
		{ dia: 5, nombre: "Viernes", activo: false, hora_entrada: "", hora_salida: "", usaRefrigerio: false, hora_inicio_refrigerio: "", hora_fin_refrigerio: "" },
		{ dia: 6, nombre: "Sábado", activo: false, hora_entrada: "", hora_salida: "", usaRefrigerio: false, hora_inicio_refrigerio: "", hora_fin_refrigerio: "" },
		{ dia: 7, nombre: "Domingo", activo: false, hora_entrada: "", hora_salida: "", usaRefrigerio: false, hora_inicio_refrigerio: "", hora_fin_refrigerio: "" },
	];
	const [dias, setDias] = useState(dias_semana);

	useEffect(() => {

		const token = localStorage.getItem("token");
		if (!token) {
			window.location.href = "/login";
		} else {
			listar();
		}
	}, []);

	const listar = async () => {
		const data = await getHorarios();
		setHorarios(data);
	};

	const handleHoraChange = (index, campo, valor) => {
		const updated = [...dias];
		updated[index][campo] = valor;
		setDias(updated);
	};

	const guardar = async () => {
		try {
			const payload = {
				nombre,
				descripcion,
				estado,
				tolerancia_min,
				dias: dias
					.filter((d) => d.activo)
					.map((d) => ({
						dia: d.dia,
						activo: d.activo,
						hora_entrada: d.hora_entrada,
						hora_salida: d.hora_salida,
						usa_refrigerio: d.usaRefrigerio,
						hora_inicio_refrigerio: d.usaRefrigerio ? d.hora_inicio_refrigerio : null,
						hora_fin_refrigerio: d.usaRefrigerio ? d.hora_fin_refrigerio : null,
					})),
			};

			let res = null;
			if (idEdit) {
				res = await updateHorario(idEdit, payload);
			} else {
				res = await createHorario(payload);
			}

			if (res.status === "success") {
				alertify.success(res.message);
				listar();
				cerrarModal();
			} else {
				alertify.error(res.message);
			}
		} catch (err) {
			alertify.error("Error al intentar guardar el horario " + err);
		}
	};

	const cerrarModal = () => {
		setIdEdit(null);
		setNombre("");
		setDescripcion("");
		setToleranciaMin("");
		setEstado("A");
		setShow(false);
		setDias(dias.map(d => ({ ...d, activo: false, hora_entrada: "", hora_salida: "", usaRefrigerio: false, hora_inicio_refrigerio: "", hora_fin_refrigerio: "" })));
	};

	const editar = (horario) => {

		setIdEdit(horario.id);
		setNombre(horario.nombre);
		setDescripcion(horario.descripcion);
		setToleranciaMin(horario.tolerancia_min);
		setEstado(horario.estado);
		// Reconstruimos los días asegurando que estén en formato numérico (1 a 7)
		const diasMapeados = [1, 2, 3, 4, 5, 6, 7].map((numeroDia) => {
			const diaEncontrado = horario.dias.find((d) => d.dia_semana === numeroDia);

			return {
				dia: numeroDia,
				nombre: dias_semana[numeroDia - 1].nombre,
				activo: diaEncontrado ? diaEncontrado.activo : 0,
				hora_entrada: diaEncontrado?.hora_entrada || "",
				hora_salida: diaEncontrado?.hora_salida || "",
				usaRefrigerio: diaEncontrado ? diaEncontrado.descanso : 0,
				hora_inicio_refrigerio: diaEncontrado?.hora_inicio_refrigerio || "",
				hora_fin_refrigerio: diaEncontrado?.hora_fin_refrigerio || "",
			};
		});
		setDias(diasMapeados);
		setShow(true);
	};

	const eliminar = async (horario) => {
		alertify.confirm(
			"Confirmar eliminación",
			`¿Seguro que deseas eliminar el horario <b>${horario.descripcion}</b>?`,
			async function () {
				try {
					const res = await deleteHorario(horario.id);
					if (res.status === "success") {
						alertify.success(res.message);
						listar();
					} else {
						alertify.error(res.message);
					}
				} catch (err) {
					alertify.error("Error al intentar eliminar el horario");
				}
			},
			function () {
				alertify.message("Acción cancelada");
			}
		);
	};
	const handleEstadoChange = async (id, nuevoEstado) => {
		setLoadingId(id);
		try {
			const res = await actualizarEstadoHorario(id, nuevoEstado ? 1 : 0);
			if (res.status === "success") {
				// ✅ Actualizamos visualmente el estado del horario en el frontend
				setHorarios((prevHorarios) =>
					prevHorarios.map((h) =>
						h.id === id ? { ...h, estado: nuevoEstado ? 1 : 0 } : h
					)
				);
				alertify.success(res.message);
			} else {
				alertify.error(res.message);
			}
		} catch (err) {
			console.error("Error al actualizar el estado:", err);
			alertify.error("Error al actualizar el estado");
		} finally {
			setLoadingId(null);
		}
	};
	return (
		<div className="flex flex-col gap-6">
			<div className="flex items-center justify-between">
				<div>
					<h1 className="text-2xl font-bold text-gray-900">Horarios</h1>
					<p className="text-sm text-gray-500 mt-0.5">Gestión de horarios de trabajo</p>
				</div>
				<div className="flex gap-2">
					<button
						className="bg-gray-800 text-white px-5 py-2.5 rounded-md hover:bg-gray-700 shadow-sm font-medium transition-colors flex items-center gap-2"
						onClick={() => setShow(true)}
					>
						<PlusIcon className="h-4 w-4" /> Nuevo Horario
					</button>
				</div>
			</div>

			<div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
				<div className="overflow-x-auto">
					<table className="w-full text-left text-sm">
						<thead className="bg-gray-50 text-gray-600 uppercase text-xs border-b border-gray-200">
							<tr>
								<th className="px-4 py-3">Id</th>
								<th className="px-4 py-3">Nombre</th>
								<th className="px-4 py-3">Descripción</th>
								<th className="px-4 py-3 text-center">Tolerancia (Min)</th>
								<th className="px-4 py-3">Estado</th>
								<th className="px-4 py-3 text-center">Acciones</th>
							</tr>
						</thead>
						<tbody className="divide-y divide-gray-100">
							{horarios.map((c) => (
								<tr key={c.id} className="hover:bg-gray-50 transition-colors">
									<td className="px-4 py-3 font-mono">{c.id}</td>
									<td className="px-4 py-3 font-medium text-gray-800">{c.nombre}</td>
									<td className="px-4 py-3">{c.descripcion}</td>
									<td className="px-4 py-3 font-mono text-center">{c.tolerancia_min}</td>
									<td className="px-4 py-3">
										<Switch
											checked={c.estado === 1}
											onChange={(checked) => handleEstadoChange(c.id, checked)}
											onColor="#22c55e"
											offColor="#d1d5db"
											uncheckedIcon={false}
											checkedIcon={false}
											disabled={loadingId === c.id}
										/>
									</td>
									<td className="px-4 py-3 text-center">
										<div className="flex items-center justify-center gap-2">
											<button
												onClick={() => editar(c)}
												title="Editar"
												className="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
											>
												<PencilSquareIcon className="h-5 w-5" />
											</button>
											<button
												onClick={() => eliminar(c)}
												title="Eliminar"
												className="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
											>
												<TrashIcon className="h-5 w-5" />
											</button>
										</div>
									</td>
								</tr>
							))}
						</tbody>
					</table>
				</div>
			</div>

			{/* Modal Custom (Tailwind) */}
			{show && (
				<div className="fixed inset-0 z-50 flex items-center justify-center p-4">
					<div className="absolute inset-0 bg-black/50 backdrop-blur-sm" onClick={cerrarModal}></div>
					<div className="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden">
						<div className="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
							<h2 className="text-lg font-bold text-gray-900">{idEdit ? "Editar Horario" : "Nuevo Horario"}</h2>
							<button onClick={cerrarModal} className="text-gray-400 hover:text-gray-700 p-1.5 rounded-lg hover:bg-gray-200 transition-colors">
								<XMarkIcon className="h-5 w-5" />
							</button>
						</div>
						<div className="p-6 max-h-[70vh] overflow-y-auto space-y-5">
							<div className="grid grid-cols-1 md:grid-cols-2 gap-4">
								<div>
									<label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nombre</label>
									<input
										type="text"
										className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm outline-none transition-all"
										value={nombre}
										onChange={(e) => setNombre(e.target.value)}
									/>
								</div>

								<div>
									<label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Descripción</label>
									<input
										type="text"
										className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm outline-none transition-all"
										value={descripcion}
										onChange={(e) => setDescripcion(e.target.value)}
									/>
								</div>

								<div>
									<label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tolerancia (Min.)</label>
									<input
										type="number"
										className="w-full p-2 border border-gray-300 rounded-md focus:border-blue-500 text-sm font-mono outline-none transition-all"
										value={tolerancia_min}
										onChange={(e) => setToleranciaMin(e.target.value)}
									/>
								</div>

								<div>
									<label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Estado</label>
									<div className="flex items-center gap-3 mt-1">
										<Switch
											checked={estado === 1}
											onChange={(checked) => setEstado(checked ? 1 : 0)}
											onColor="#22c55e"
											offColor="#d1d5db"
											checkedIcon={false}
											uncheckedIcon={false}
											height={20}
											width={40}
										/>
										<span className={`text-sm font-medium ${estado === 1 ? "text-green-600" : "text-gray-500"}`}>
											{estado === 1 ? "Activo" : "Inactivo"}
										</span>
									</div>
								</div>
							</div>

							<div>
								<h5 className="text-sm font-bold text-gray-800 mb-3 border-b border-gray-100 pb-2">Días del horario</h5>
								<div className="overflow-x-auto border border-gray-200 rounded-lg">
									<table className="w-full text-left text-sm whitespace-nowrap">
										<thead className="bg-gray-50 text-gray-600 text-xs border-b border-gray-200">
											<tr>
												<th className="px-3 py-2 font-medium text-center">Día</th>
												<th className="px-3 py-2 font-medium text-center">Activo</th>
												<th className="px-3 py-2 font-medium text-center">Inicio</th>
												<th className="px-3 py-2 font-medium text-center">Fin</th>
												<th className="px-3 py-2 font-medium text-center">Refrigerio</th>
												<th className="px-3 py-2 font-medium text-center">Inicio Ref.</th>
												<th className="px-3 py-2 font-medium text-center">Fin Ref.</th>
											</tr>
										</thead>
										<tbody className="divide-y divide-gray-100">
											{dias.map((d, i) => (
												<tr key={d.dia} className={`${d.activo === 1 ? 'bg-white' : 'bg-gray-50'}`}>
													<td className="px-3 py-2 font-medium text-gray-700 text-center">{d.nombre}</td>

													<td className="px-3 py-2 text-center align-middle">
														<Switch
															checked={d.activo === 1}
															onChange={() =>
																handleHoraChange(i, "activo", d.activo === 1 ? 0 : 1)
															}
															onColor="#22c55e"
															offColor="#d1d5db"
															checkedIcon={false}
															uncheckedIcon={false}
															height={20}
															width={40}
														/>
													</td>

													<td className="px-3 py-2">
														<input
															type="time"
															className={`w-full p-1.5 border rounded-md text-sm outline-none focus:ring-2 focus:ring-blue-500 font-mono ${d.activo !== 1 ? 'bg-gray-100 text-gray-400 border-gray-200' : 'bg-white border-gray-300'}`}
															disabled={d.activo !== 1}
															value={d.hora_entrada}
															onChange={(e) =>
																handleHoraChange(i, "hora_entrada", e.target.value)
															}
														/>
													</td>

													<td className="px-3 py-2">
														<input
															type="time"
															className={`w-full p-1.5 border rounded-md text-sm outline-none focus:ring-2 focus:ring-blue-500 font-mono ${d.activo !== 1 ? 'bg-gray-100 text-gray-400 border-gray-200' : 'bg-white border-gray-300'}`}
															disabled={d.activo !== 1}
															value={d.hora_salida}
															onChange={(e) =>
																handleHoraChange(i, "hora_salida", e.target.value)
															}
														/>
													</td>

													<td className="px-3 py-2 text-center align-middle">
														<Switch
															checked={d.usaRefrigerio === 1}
															onChange={() =>
																handleHoraChange(i, "usaRefrigerio", d.usaRefrigerio === 1 ? 0 : 1)
															}
															onColor="#f59e0b"
															offColor="#d1d5db"
															checkedIcon={false}
															uncheckedIcon={false}
															disabled={d.activo !== 1}
															height={20}
															width={40}
														/>
													</td>

													<td className="px-3 py-2">
														<input
															type="time"
															className={`w-full p-1.5 border rounded-md text-sm outline-none focus:ring-2 focus:ring-blue-500 font-mono ${(d.activo !== 1 || d.usaRefrigerio !== 1) ? 'bg-gray-100 text-gray-400 border-gray-200' : 'bg-white border-gray-300'}`}
															disabled={d.activo !== 1 || d.usaRefrigerio !== 1}
															value={d.hora_inicio_refrigerio}
															onChange={(e) =>
																handleHoraChange(i, "hora_inicio_refrigerio", e.target.value)
															}
														/>
													</td>

													<td className="px-3 py-2">
														<input
															type="time"
															className={`w-full p-1.5 border rounded-md text-sm outline-none focus:ring-2 focus:ring-blue-500 font-mono ${(d.activo !== 1 || d.usaRefrigerio !== 1) ? 'bg-gray-100 text-gray-400 border-gray-200' : 'bg-white border-gray-300'}`}
															disabled={d.activo !== 1 || d.usaRefrigerio !== 1}
															value={d.hora_fin_refrigerio}
															onChange={(e) =>
																handleHoraChange(i, "hora_fin_refrigerio", e.target.value)
															}
														/>
													</td>
												</tr>
											))}
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div className="flex justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50">
							<button
								onClick={cerrarModal}
								className="px-5 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium text-sm transition-colors"
							>
								Cancelar
							</button>
							<button
								onClick={guardar}
								className="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium text-sm transition-colors"
							>
								Guardar
							</button>
						</div>
					</div>
				</div>
			)}
		</div>
	);
}
