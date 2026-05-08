<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SellPaymentController;
use App\Http\Controllers\OrderController;
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
use App\Http\Controllers\GuiaController;

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
Route::apiResource('sig/puestos-crud', PuestoController::class); // Named crud to avoid conflict with existing sig/puestos if needed
Route::get('sig/colaboradores/metadata', [ColaboradorController::class, 'getMetadata']);
Route::apiResource('sig/colaboradores', ColaboradorController::class);

// Guías de Remisión
Route::get('guias/next-num', [GuiaController::class, 'nextNumGuia']);
Route::get('guias/search-products', [GuiaController::class, 'searchProducts']);
Route::get('guias/departamentos', [GuiaController::class, 'getDepartamentos']);
Route::get('guias/provincias', [GuiaController::class, 'getProvincias']);
Route::get('guias/distritos', [GuiaController::class, 'getDistritos']);
Route::get('guias/{id}/detalle', [GuiaController::class, 'show']);
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

Route::get('transactions/sells', [TransactionController::class, 'getSells']);
Route::post('transactions/sells', [TransactionController::class, 'storeSell']);
Route::get('transactions/sells/{codigo}', [TransactionController::class, 'getSellDetail']);

// Sell Payments (Ventas Pagos) - migrated from sells-view.php / clsVenta::actualizar_pago
Route::get('sell-payments', [SellPaymentController::class, 'index']);
Route::get('sell-payments/tipos-documento', [SellPaymentController::class, 'tiposDocumento']);
Route::get('sell-payments/{codigo}/history', [SellPaymentController::class, 'paymentHistory']);
Route::post('sell-payments/{codigo}/pay', [SellPaymentController::class, 'storePayment']);
Route::delete('sell-payments/payment/{id}', [SellPaymentController::class, 'deletePayment']);

// Cotizations
Route::get('cotizations', [CotizationController::class, 'index']);
Route::get('cotizations/{codigo}', [CotizationController::class, 'show']);
Route::post('cotizations', [CotizationController::class, 'store']);
Route::delete('cotizations/{codigo}', [CotizationController::class, 'destroy']);

// Orders (Pedidos)
Route::get('transactions/orders', [OrderController::class, 'getOrders']);
Route::post('transactions/orders', [OrderController::class, 'storeOrder']);
Route::get('transactions/orders/{codigo}', [OrderController::class, 'getOrderDetail']);
Route::patch('transactions/orders/{codigo}/status', [OrderController::class, 'updateOrderStatus']);
Route::delete('transactions/orders/{codigo}', [OrderController::class, 'deleteOrder']);

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
