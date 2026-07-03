const LEGACY_BASE = 'https://peruvian.peruviandress.com/storage/products';
const LEGACY_COLABORADORES_BASE = 'https://peruvian.peruviandress.com/core/app/view/img-colaboradores';

function getNewBaseUrl() {
  const apiBase = import.meta.env.VITE_API_BASE_URL || 'https://apiperuvian.dbusinessaqp.com/api';
  return apiBase
    .replace(/\/api$/, '')
    .replace(/\/backend\/public$/, '');
}

export function getProductImageUrl(filename) {
  if (!filename) return '';
  return `${LEGACY_BASE}/${filename}`;
}

export function handleProductImageError(e, filename) {
  if (!e.target.src.startsWith(LEGACY_BASE)) {
    e.target.onerror = null;
    return;
  }
  const newBaseUrl = getNewBaseUrl();
  e.target.src = `${newBaseUrl}/storage/products/${filename}`;
  e.target.onerror = null;
}

export function getColaboradorFotoUrl(filename) {
  if (!filename) return '';
  return `${LEGACY_COLABORADORES_BASE}/${filename}`;
}

export function handleColaboradorFotoError(e, filename) {
  if (!e.target.src.startsWith(LEGACY_COLABORADORES_BASE)) {
    e.target.onerror = null;
    return;
  }
  const newBaseUrl = getNewBaseUrl();
  e.target.src = `${newBaseUrl}/storage/img-colaboradores/${filename}`;
  e.target.onerror = null;
}
