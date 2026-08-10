const LEGACY_BASE = 'https://omcar.peruviandress.com/storage/products';
const LEGACY_COLABORADORES_BASE = 'https://omcar.peruviandress.com/core/app/view/img-colaboradores';

function getNewBaseUrl() {
  const apiBase = import.meta.env.VITE_API_BASE_URL || 'https://apiomcar.dbusinessaqp.com/api';
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

export const getBase64ImageFromUrl = async (imageUrl) => {
  return new Promise((resolve, reject) => {
    const img = new Image();
    img.crossOrigin = 'Anonymous';
    img.onload = () => {
      const canvas = document.createElement('canvas');
      canvas.width = img.width;
      canvas.height = img.height;
      const ctx = canvas.getContext('2d');
      // Llenar de blanco para imágenes con fondo transparente
      ctx.fillStyle = '#FFFFFF';
      ctx.fillRect(0, 0, canvas.width, canvas.height);
      ctx.drawImage(img, 0, 0);
      resolve(canvas.toDataURL('image/jpeg'));
    };
    img.onerror = reject;
    img.src = imageUrl;
  });
};

export const getProductImageBase64 = async (filename) => {
  if (!filename) return null;
  const legacyUrl = `${LEGACY_BASE}/${filename}`;
  const newUrl = `${getNewBaseUrl()}/storage/products/${filename}`;
  try {
    return await getBase64ImageFromUrl(legacyUrl);
  } catch (e) {
    return await getBase64ImageFromUrl(newUrl);
  }
};

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

const LEGACY_VIEW_BASE = 'https://omcar.peruviandress.com/core/app/view';

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
    'verificacion_competencias': 'competencias',
    'doc-colaboradores': 'dni'
  };
  const legacyFolder = folderMap[folder] || folder;
  return `${LEGACY_VIEW_BASE}/${legacyFolder}/${filename}`;
}

/*export async function handleDocumentClick(e, filename, folder) {
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
  console.log(newUrl);

  try {
    const res = await fetch(newUrl, { method: 'HEAD' });
    console.log(res);
    if (res.ok) {
      console.log("SI ES OK");
      window.open(newUrl, '_blank');
      return;
    } else {
      console.log("NO, NO ES OK");
      console.warn('New URL returned non-ok status', res.status);
      window.open(legacyUrl, '_blank');
      return;
    }
  } catch (err) {
    console.warn("Could not check new url (CORS or network). Opening new URL directly.", err);
    // Si la comprobación falla por CORS, abrir la URL nueva directamente
    // (la navegación no está sujeta a la misma política que las peticiones fetch).
    window.open(legacyUrl, '_blank');
    return;
  }
}*/
export async function handleDocumentClick(e, filename, folder) {
  e.preventDefault();

  if (!filename) return;

  const folderMap = {
    formacion: 'formacion',
    experiencia: 'experiencia',
    vacaciones: 'vacaciones',
    capacitaciones: 'capacitaciones',
    examenes_medicos: 'certificado_medico',
    contratos: 'contratos',
    recomendaciones_sst: 'sst',
    verificacion_competencias: 'competencias',
    'doc-colaboradores': 'dni'
  };

  const legacyFolder = folderMap[folder] || folder;

  const legacyUrl = `${LEGACY_VIEW_BASE}/${legacyFolder}/${encodeURIComponent(filename)}`;
  const newUrl = `${getNewBaseUrl()}/storage/${folder}/${encodeURIComponent(filename)}`;

  try {
    const response = await fetch(
      `${getNewBaseUrl()}/api/documento-existe/${folder}/${encodeURIComponent(filename)}`
    );

    const data = await response.json();

    if (data.exists) {
      window.open(newUrl, "_blank");
    } else {
      window.open(legacyUrl, "_blank");
    }

  } catch (err) {
    console.error(err);
    window.open(legacyUrl, "_blank");
  }
}