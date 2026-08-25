<?php

use App\Http\Controllers\ColaboradorHorarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionPdfController;
use App\Http\Controllers\SellPaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderPdfController;
use App\Http\Controllers\CotizationController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\InsumoConfigController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TechSheetController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\MaquinaController;
use App\Http\Controllers\MaquinaMantenimientoController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\PerfilPuestoController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\PuestoController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\ColaboradorFamiliarController;
use App\Http\Controllers\ColaboradorFormacionController;
use App\Http\Controllers\ColaboradorExperienciaLaboralController;
use App\Http\Controllers\ColaboradorHabilidadController;
use App\Http\Controllers\ColaboradorCapacitacionController;
use App\Http\Controllers\ColaboradorVacacionController;
use App\Http\Controllers\ColaboradorContratoController;
use App\Http\Controllers\ColaboradorExamenMedicoController;
use App\Http\Controllers\ColaboradorRecomendacionSstController;
use App\Http\Controllers\ColaboradorVerificacionCompetenciaController;
use App\Http\Controllers\GuiaController;
use App\Http\Controllers\GuiaPdfController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TipoContratoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RelojController;
use App\Http\Controllers\FeriadoController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\TipoPermisoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\HorarioDiasController;
use App\Http\Controllers\MarcacionController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Purchases (Compras)
Route::get('purchases/tipos-documento', [CompraController::class, 'getTiposDocumento']);
Route::apiResource('purchases', CompraController::class);

// SIG Routes
Route::get('sig/puestos', [PerfilPuestoController::class, 'getPuestos']);
Route::get('sig/perfil/{id}', [PerfilPuestoController::class, 'show']);
Route::post('sig/perfil', [PerfilPuestoController::class, 'store']);

Route::apiResource('sig/areas', AreaController::class);
Route::apiResource('tipos_contratos', TipoContratoController::class);
Route::apiResource('sig/puestos-crud', PuestoController::class); // Named crud to avoid conflict with existing sig/puestos if needed
Route::get('sig/colaboradores/metadata', [ColaboradorController::class, 'getMetadata']);
Route::apiResource('sig/colaboradores', ColaboradorController::class);

Route::get('sig/colaboradores/{colaborador}/familiares', [ColaboradorFamiliarController::class, 'index']);
Route::post('sig/colaboradores/{colaborador}/familiares', [ColaboradorFamiliarController::class, 'store']);
Route::put('sig/colaboradores/{colaborador}/familiares/{id}', [ColaboradorFamiliarController::class, 'update']);
Route::delete('sig/colaboradores/{colaborador}/familiares/{id}', [ColaboradorFamiliarController::class, 'destroy']);

Route::get('sig/colaboradores/{colaborador}/formacion', [ColaboradorFormacionController::class, 'index']);
Route::post('sig/colaboradores/{colaborador}/formacion', [ColaboradorFormacionController::class, 'store']);
Route::post('sig/colaboradores/{colaborador}/formacion/{id}', [ColaboradorFormacionController::class, 'update']); // POST to handle file uploads
Route::delete('sig/colaboradores/{colaborador}/formacion/{id}', [ColaboradorFormacionController::class, 'destroy']);

Route::get('sig/colaboradores/{colaborador}/experiencia', [ColaboradorExperienciaLaboralController::class, 'index']);
Route::post('sig/colaboradores/{colaborador}/experiencia', [ColaboradorExperienciaLaboralController::class, 'store']);
Route::post('sig/colaboradores/{colaborador}/experiencia/{id}', [ColaboradorExperienciaLaboralController::class, 'update']); // POST to handle file uploads
Route::delete('sig/colaboradores/{colaborador}/experiencia/{id}', [ColaboradorExperienciaLaboralController::class, 'destroy']);

Route::get('sig/colaboradores/{colaborador}/habilidades', [ColaboradorHabilidadController::class, 'index']);
Route::post('sig/colaboradores/{colaborador}/habilidades', [ColaboradorHabilidadController::class, 'store']);
Route::put('sig/colaboradores/{colaborador}/habilidades/{id}', [ColaboradorHabilidadController::class, 'update']);
Route::delete('sig/colaboradores/{colaborador}/habilidades/{id}', [ColaboradorHabilidadController::class, 'destroy']);

Route::get('sig/colaboradores/{colaborador}/capacitaciones', [ColaboradorCapacitacionController::class, 'index']);
Route::post('sig/colaboradores/{colaborador}/capacitaciones', [ColaboradorCapacitacionController::class, 'store']);
Route::post('sig/colaboradores/{colaborador}/capacitaciones/{id}', [ColaboradorCapacitacionController::class, 'update']); // POST to handle file uploads
Route::delete('sig/colaboradores/{colaborador}/capacitaciones/{id}', [ColaboradorCapacitacionController::class, 'destroy']);

Route::get('sig/colaboradores/{colaborador}/vacaciones', [ColaboradorVacacionController::class, 'index']);
Route::post('sig/colaboradores/{colaborador}/vacaciones', [ColaboradorVacacionController::class, 'store']);
Route::post('sig/colaboradores/{colaborador}/vacaciones/{id}', [ColaboradorVacacionController::class, 'update']);
Route::delete('sig/colaboradores/{colaborador}/vacaciones/{id}', [ColaboradorVacacionController::class, 'destroy']);

Route::get('sig/colaboradores/{colaborador}/contratos', [ColaboradorContratoController::class, 'index']);
Route::post('sig/colaboradores/{colaborador}/contratos', [ColaboradorContratoController::class, 'store']);
Route::post('sig/colaboradores/{colaborador}/contratos/{id}', [ColaboradorContratoController::class, 'update']);
Route::delete('sig/colaboradores/{colaborador}/contratos/{id}', [ColaboradorContratoController::class, 'destroy']);

Route::get('sig/colaboradores/{colaborador}/examenes_medicos', [ColaboradorExamenMedicoController::class, 'index']);
Route::post('sig/colaboradores/{colaborador}/examenes_medicos', [ColaboradorExamenMedicoController::class, 'store']);
Route::post('sig/colaboradores/{colaborador}/examenes_medicos/{id}', [ColaboradorExamenMedicoController::class, 'update']);
Route::delete('sig/colaboradores/{colaborador}/examenes_medicos/{id}', [ColaboradorExamenMedicoController::class, 'destroy']);

Route::get('sig/colaboradores/{colaborador}/recomendaciones_sst', [ColaboradorRecomendacionSstController::class, 'index']);
Route::post('sig/colaboradores/{colaborador}/recomendaciones_sst', [ColaboradorRecomendacionSstController::class, 'store']);
Route::post('sig/colaboradores/{colaborador}/recomendaciones_sst/{id}', [ColaboradorRecomendacionSstController::class, 'update']);
Route::delete('sig/colaboradores/{colaborador}/recomendaciones_sst/{id}', [ColaboradorRecomendacionSstController::class, 'destroy']);

Route::get('sig/colaboradores/{colaborador}/verificacion_competencias', [ColaboradorVerificacionCompetenciaController::class, 'index']);
Route::post('sig/colaboradores/{colaborador}/verificacion_competencias', [ColaboradorVerificacionCompetenciaController::class, 'store']);
Route::post('sig/colaboradores/{colaborador}/verificacion_competencias/{id}', [ColaboradorVerificacionCompetenciaController::class, 'update']);
Route::delete('sig/colaboradores/{colaborador}/verificacion_competencias/{id}', [ColaboradorVerificacionCompetenciaController::class, 'destroy']);
// Guías de Remisión
Route::get('guias/next-num', [GuiaController::class, 'nextNumGuia']);
Route::get('guias/search-products', [GuiaController::class, 'searchProducts']);
Route::get('guias/departamentos', [GuiaController::class, 'getDepartamentos']);
Route::get('guias/provincias', [GuiaController::class, 'getProvincias']);
Route::get('guias/distritos', [GuiaController::class, 'getDistritos']);
Route::get('guias/{id}/detalle', [GuiaController::class, 'show']);
Route::get('guias/{id}/pdf', [GuiaPdfController::class, 'downloadGuiaPdf']);
Route::post('guias/{id}/send-sunat', [GuiaController::class, 'sendToSunat']);
Route::delete('guias/{id}', [GuiaController::class, 'destroy']);
Route::get('guias', [GuiaController::class, 'index']);
Route::post('guias', [GuiaController::class, 'store']);

// Library / Documents
Route::get('/library', [DocumentController::class, 'index']);
Route::get('/library/search', [DocumentController::class, 'search']);
Route::post('/library/folders', [DocumentController::class, 'createFolder']);
Route::post('/library/files', [DocumentController::class, 'uploadFile']);
Route::delete('/library/folders/{id}', [DocumentController::class, 'deleteFolder']);
Route::delete('/library/files/{id}', [DocumentController::class, 'deleteFile']);

// Maintenance
Route::apiResource('machine-maintenance', MaquinaMantenimientoController::class);

// Machines
Route::post('/machines/{id}/restore', [MaquinaController::class, 'restore']);
Route::apiResource('machines', MaquinaController::class);

// Navigation (menú lateral por usuario, igual que layout.php)
Route::get('/menu/navigation', [MenuController::class, 'navigation']);

// Permissions
Route::get('/permissions/menus', [PermissionController::class, 'getMenus']);
Route::post('/permissions/save', [PermissionController::class, 'savePermissions']);

// Cargos
Route::get('/cargos/clients', [CargoController::class, 'getClients']);
Route::apiResource('cargos', CargoController::class);

// Tech Sheets
Route::prefix('tech-sheets')->group(function () {
    Route::get('/{code}', [TechSheetController::class, 'getFicha']);
    Route::put('/{code}', [TechSheetController::class, 'updateFicha']);

    Route::get('/{code}/manual', [TechSheetController::class, 'getManual']);
    Route::post('/instruccion', [TechSheetController::class, 'saveInstruccion']);
    Route::put('/instruccion/{id}', [TechSheetController::class, 'updateInstruccion']);
    Route::delete('/instruccion/{id}', [TechSheetController::class, 'deleteInstruccion']);

    Route::get('/{code}/medidas', [TechSheetController::class, 'getMedidas']);
    Route::post('/medidas', [TechSheetController::class, 'saveMedida']);
    Route::put('/medidas/{id}', [TechSheetController::class, 'updateMedida']);
    Route::delete('/medidas/{id}', [TechSheetController::class, 'deleteMedida']);

    Route::post('/complementos', [TechSheetController::class, 'saveComplemento']);
    Route::delete('/complementos/{id}', [TechSheetController::class, 'deleteComplemento']);

    Route::post('/identificacion', [TechSheetController::class, 'saveIdentificacion']);
    Route::delete('/identificacion/{id}', [TechSheetController::class, 'deleteIdentificacion']);

    Route::post('/modificaciones', [TechSheetController::class, 'saveModificacion']);
    Route::delete('/modificaciones/{id}', [TechSheetController::class, 'deleteModificacion']);

    Route::post('/observaciones', [TechSheetController::class, 'saveObservacion']);
    Route::delete('/observaciones/{id}', [TechSheetController::class, 'deleteObservacion']);

    Route::post('/maquinas', [TechSheetController::class, 'saveMaquina']);
    Route::delete('/maquinas/{id}', [TechSheetController::class, 'deleteMaquina']);
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// For now, these are unprotected routes to facilitate migration testing
// Later, wrap them in auth:sanctum middleware
Route::apiResource('users', UserController::class);
Route::apiResource('clients', ClientController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('providers', ProviderController::class);
Route::apiResource('brands', BrandController::class);

Route::get('products-search', [ProductController::class, 'search']);

// Transactions
Route::get('codigos-sunat', function () {
    return response()->json(\App\Models\CodigoSunat::all());
});
Route::get('tipos-pago', function () {
    return response()->json(\Illuminate\Support\Facades\DB::table('p')->get());
});
Route::get('tipos-entrega', function () {
    return response()->json(\Illuminate\Support\Facades\DB::table('d')->get());
});
Route::get('forma-pago', function () {
    return response()->json(\Illuminate\Support\Facades\DB::table('f')->get());
});

Route::get('transactions/sells/correlativo', [TransactionController::class, 'getCorrelativo']);
Route::get('transactions/sells', [TransactionController::class, 'getSells']);
Route::post('transactions/sells', [TransactionController::class, 'storeSell']);
Route::get('transactions/sells/{codigo}', [TransactionController::class, 'getSellDetail']);
Route::get('transactions/sells/{codigo}/pdf', [TransactionPdfController::class, 'downloadSellPdf']);
Route::post('transactions/sells/{codigo}/send-sunat', [TransactionController::class, 'sendToSunat']);
Route::get('transactions/sells/{codigo}/sunat-files', [TransactionController::class, 'downloadSunatFiles']);

// Sell Payments (Ventas Pagos) - migrated from sells-view.php / clsVenta::actualizar_pago
Route::get('sell-payments', [SellPaymentController::class, 'index']);
Route::get('sell-payments/tipos-documento', [SellPaymentController::class, 'tiposDocumento']);
Route::get('sell-payments/{codigo}/history', [SellPaymentController::class, 'paymentHistory']);
Route::post('sell-payments/{codigo}/pay', [SellPaymentController::class, 'storePayment']);
Route::post('sell-payments/{codigo}/pay-detraccion', [SellPaymentController::class, 'payDetraccion']);
Route::delete('sell-payments/payment/{id}', [SellPaymentController::class, 'deletePayment']);

// Cotizations
Route::get('cotizations', [CotizationController::class, 'index']);
Route::get('cotizations/{codigo}', [CotizationController::class, 'show']);
Route::post('cotizations', [CotizationController::class, 'store']);
Route::delete('cotizations/{codigo}', [CotizationController::class, 'destroy']);

// Orders (Pedidos) — rutas específicas antes de {codigo}
Route::get('transactions/orders', [OrderController::class, 'getOrders']);
Route::post('transactions/orders', [OrderController::class, 'storeOrder']);
Route::post('transactions/orders/upload', [OrderController::class, 'uploadImage']);
Route::delete('transactions/orders/detail/{id}', [OrderController::class, 'deleteOrderDetail']);
Route::get('transactions/orders/{codigo}/pdf', [OrderPdfController::class, 'downloadOrderPdf']);
Route::get('transactions/orders/{codigo}/production', [OrderController::class, 'getProductionDetail']);
Route::put('transactions/orders/{codigo}/production', [OrderController::class, 'updateProduction']);
Route::patch('transactions/orders/{codigo}/status', [OrderController::class, 'updateOrderStatus']);
Route::get('transactions/orders/{codigo}', [OrderController::class, 'getOrderDetail']);
Route::put('transactions/orders/{codigo}', [OrderController::class, 'updateOrder']);
Route::delete('transactions/orders/{codigo}', [OrderController::class, 'deleteOrder']);

// Reports (Reportes)
Route::prefix('reports')->group(function () {
    Route::get('/sells-sunat', [ReportController::class, 'getSellsSunat']);
    Route::put('/sells-sunat/{codigo}', [ReportController::class, 'updateSale']);
    Route::delete('/sells-sunat/{codigo}', [ReportController::class, 'anularSale']);
    Route::get('/ventas-cliente', [ReportController::class, 'getVentasCliente']);
    Route::get('/ventas-mensuales', [ReportController::class, 'getVentasMensuales']);
    Route::get('/ventas-cruzado', [ReportController::class, 'getVentasCruzado']);
});

// Insumos
Route::prefix('insumos')->group(function () {
    Route::get('/', [InsumoController::class, 'index']);
    Route::get('/familias', [InsumoController::class, 'getFamilias']);
    Route::get('/clases', [InsumoController::class, 'getClases']);
    Route::get('/subclases', [InsumoController::class, 'getSubclases']);
    Route::get('/unidades', [InsumoController::class, 'getUnidades']);
    Route::get('/providers', [InsumoController::class, 'getProviders']);
    Route::get('/{id}', [InsumoController::class, 'show']);
    Route::post('/', [InsumoController::class, 'store']);
    Route::put('/{id}', [InsumoController::class, 'update']);
    Route::delete('/{id}', [InsumoController::class, 'destroy']);

    // Stock
    Route::get('/{id_insumo}/stock', [InsumoController::class, 'getStock']);
    Route::post('/stock', [InsumoController::class, 'storeStock']);
    Route::get('/stock/{id}/edit', [InsumoController::class, 'editStock']);
    Route::post('/stock', [InsumoController::class, 'storeStock']);
    Route::delete('/stock/{id}', [InsumoController::class, 'destroyStock']);

    // Insumo Config Routes
    Route::get('/familias', [InsumoConfigController::class, 'getFamilias']);
    Route::post('/familias', [InsumoConfigController::class, 'storeFamilia']);
    Route::put('/familias/{codigo}', [InsumoConfigController::class, 'updateFamilia']);
    Route::delete('/familias/{codigo}', [InsumoConfigController::class, 'destroyFamilia']);

    Route::get('/clases', [InsumoConfigController::class, 'getClases']);
    Route::post('/clases', [InsumoConfigController::class, 'storeClase']);
    Route::put('/clases/{id}', [InsumoConfigController::class, 'updateClase']);
    Route::delete('/clases/{id}', [InsumoConfigController::class, 'destroyClase']);

    Route::get('/subclases', [InsumoConfigController::class, 'getSubclases']);
    Route::post('/subclases', [InsumoConfigController::class, 'storeSubclase']);
    Route::put('/subclases/{id}', [InsumoConfigController::class, 'updateSubclase']);
    Route::delete('/subclases/{id}', [InsumoConfigController::class, 'destroySubclase']);

    Route::get('/unidades', [InsumoConfigController::class, 'getUnidades']);
    Route::post('/unidades', [InsumoConfigController::class, 'storeUnidad']);
    Route::put('/unidades/{codigo}', [InsumoConfigController::class, 'updateUnidad']);
    Route::delete('/unidades/{codigo}', [InsumoConfigController::class, 'destroyUnidad']);
});

/*Route::get('/documento-existe/{carpeta}/{archivo}', function ($carpeta, $archivo) {

    $ruta = "storage/${carpeta}/${archivo}";

    return response()->json([
        'exists' => Storage::disk('public')->exists($ruta)
    ]);
});*/
Route::get('/documento-existe/{carpeta}/{archivo}', function ($carpeta, $archivo) {

    $ruta = public_path("storage/{$carpeta}/{$archivo}");

    return response()->json([
        'exists' => file_exists($ruta)
    ]);
});


$router->get('/relojes', [RelojController::class, 'lista']);
$router->post('/relojes', [RelojController::class, 'insertar']);
$router->get('/relojes/{id}', [RelojController::class, 'editar']);
$router->put('/relojes/{id}', [RelojController::class, 'actualizar']);
$router->delete('/relojes/{id}', [RelojController::class, 'eliminar']);
$router->put('/relojes/{id}/estado', [RelojController::class, 'editarEstado']);

$router->get('/feriados', [FeriadoController::class, 'lista']);
$router->post('/feriados', [FeriadoController::class, 'insertar']);
$router->get('/feriados/{id}', [FeriadoController::class, 'editar']);
$router->put('/feriados/{id}', [FeriadoController::class, 'actualizar']);
$router->delete('/feriados/{id}', [FeriadoController::class, 'eliminar']);
$router->put('/feriados/{id}/estado', [FeriadoController::class, 'editarEstado']);

//$router->group(['middleware' => 'auth'], function () use ($router) {
$router->get('/permisos', [PermisoController::class, 'lista']);
$router->post('/permisos', [PermisoController::class, 'insertar']);
$router->get('/permisos/{id}', [PermisoController::class, 'editar']);
$router->put('/permisos/{id}', [PermisoController::class, 'actualizar']);
$router->delete('/permisos/{id}', [PermisoController::class, 'eliminar']);
$router->put('/permisos/{id}/estado', [PermisoController::class, 'editarEstado']);
//});

$router->get('/tipos_permisos', [TipoPermisoController::class, 'lista']);
$router->post('/tipos_permisos', [TipoPermisoController::class, 'insertar']);
$router->get('/tipos_permisos/{id}', [TipoPermisoController::class, 'editar']);
$router->put('/tipos_permisos/{id}', [TipoPermisoController::class, 'actualizar']);
$router->delete('/tipos_permisos/{id}', [TipoPermisoController::class, 'eliminar']);

$router->get('/colaboradores', [ColaboradorController::class, 'lista']);
$router->put('/colaboradores/{id}/estado', [ColaboradorController::class, 'editarEstado']);
$router->put('/colaboradores/{id}/marcacion', [ColaboradorController::class, 'editarMarcacion']);


$router->get('/horarios', [HorarioController::class, 'lista']);
$router->post('/horarios', [HorarioController::class, 'insertar']);
$router->get('/horarios/{id}', [HorarioController::class, 'editar']);
$router->put('/horarios/{id}', [HorarioController::class, 'actualizar']);
$router->delete('/horarios/{id}', [HorarioController::class, 'eliminar']);
$router->put('/horarios/{id}/estado', [HorarioController::class, 'editarEstado']);

$router->get('/horario_dias', [HorarioDiasController::class, 'lista']);
$router->post('/horario_dias', [HorarioDiasController::class, 'insertar']);
$router->get('/horario_dias/{id}', [HorarioDiasController::class, 'editar']);
$router->put('/horario_dias/{id}', [HorarioDiasController::class, 'actualizar']);
$router->delete('/horario_dias/{id}', [HorarioDiasController::class, 'eliminar']);

$router->get('/colaborador_horario', [ColaboradorHorarioController::class, 'lista']);
$router->post('/colaborador_horario', [ColaboradorHorarioController::class, 'insertar']);
$router->get('/colaborador_horario/{id}', [ColaboradorHorarioController::class, 'editar']);
$router->put('/colaborador_horario/{id}', [ColaboradorHorarioController::class, 'actualizar']);
$router->delete('/colaborador_horario/{id}', [ColaboradorHorarioController::class, 'eliminar']);
$router->put('/colaborador_horario/{id}/estado', [ColaboradorHorarioController::class, 'editarEstado']);

$router->post('/reportes/colaborador', [ReporteController::class, 'asistencia']);
$router->post('/reportes/dia', [ReporteController::class, 'asistencia_dia']);
$router->post('/reportes/dias', [ReporteController::class, 'asistencia_dias']);

$router->post('/marcaciones/batch', [MarcacionController::class, 'insertarBatch']);