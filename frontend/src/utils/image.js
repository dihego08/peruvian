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

const LEGACY_VIEW_BASE = 'https://peruvian.peruviandress.com/core/app/view';

export function getDocumentUrl(filename, folder) {
  if (!filename) return '';
  const folderMap = {
    'formacion': 'formacion',
    'experiencia': 'experiencia',
    'vacaciones': 'vacaciones',
    'capacitaciones': 'capacitaciones',
    'examenes_medicos': 'certificado_medico',
    'contratos': 'contratos',
    'recomendaciones_sst': 'sst',
    'verificacion_competencias': 'competencias'
  };
  const legacyFolder = folderMap[folder] || folder;
  return `${LEGACY_VIEW_BASE}/${legacyFolder}/${filename}`;
}

export async function handleDocumentClick(e, filename, folder) {
  e.preventDefault();
  if (!filename) return;
  
  const folderMap = {
    'formacion': 'formacion',
    'experiencia': 'experiencia',
    'vacaciones': 'vacaciones',
    'capacitaciones': 'capacitaciones',
    'examenes_medicos': 'certificado_medico',
    'contratos': 'contratos',
    'recomendaciones_sst': 'sst',
    'verificacion_competencias': 'competencias'
  };
  
  const legacyFolder = folderMap[folder] || folder;
  const legacyUrl = `${LEGACY_VIEW_BASE}/${legacyFolder}/${filename}`;
  const newUrl = `${getNewBaseUrl()}/storage/${folder}/${filename}`;
  
  try {
    const res = await fetch(newUrl, { method: 'HEAD' });
    if (res.ok) {
      window.open(newUrl, '_blank');
      return;
    }
  } catch (err) {
    console.warn("Could not check new url, falling back to legacy", err);
  }
  
  window.open(legacyUrl, '_blank');
}
