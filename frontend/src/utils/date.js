// Fecha de hoy en formato YYYY-MM-DD según la hora local del navegador.
// No usar new Date().toISOString(): devuelve la fecha en UTC y desde las 19:00 (hora de Perú) ya marca el día siguiente.
export function todayLocal() {
  const d = new Date();
  const pad = (n) => String(n).padStart(2, '0');
  return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
}
