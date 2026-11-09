<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\CajaController;

//////////////////////////////////////////////////
// PÚBLICO
//////////////////////////////////////////////////

Route::get('/', function () {
    return redirect()->route('login');
});

//////////////////////////////////////////////////
// AUTENTICACIÓN PROPIA
//////////////////////////////////////////////////

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

//////////////////////////////////////////////////
// SISTEMA PROTEGIDO
//////////////////////////////////////////////////

Route::middleware('auth')->group(function () {

    //////////////////////////////////////////////////
    // DASHBOARD
    //////////////////////////////////////////////////

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    //////////////////////////////////////////////////
    // CONTROL DE INVENTARIO, INGRESOS Y EGRESOS
    //////////////////////////////////////////////////

    Route::get('/inventario', [InventarioController::class, 'index'])
        ->name('inventario.index');

    Route::get('/inventario/movimientos', [InventarioController::class, 'movimientos'])
        ->name('inventario.movimientos');

    Route::get('/inventario/ingresos', [InventarioController::class, 'ingresos'])
        ->name('inventario.ingresos');

    Route::get('/inventario/egresos', [InventarioController::class, 'egresos'])
        ->name('inventario.egresos');

    Route::get('/inventario/kardex/{producto?}', [InventarioController::class, 'kardex'])
        ->name('inventario.kardex');

    Route::get('/inventario/valorizado', [InventarioController::class, 'valorizado'])
        ->name('inventario.valorizado');

    Route::get('/inventario/costos', [InventarioController::class, 'analisisCostos'])
        ->name('inventario.costos');

    Route::post('/inventario/actualizar-precio/{id}', [InventarioController::class, 'actualizarPrecioVenta'])
        ->name('inventario.actualizarPrecio');

    Route::post('/inventario/movimiento', [InventarioController::class, 'storeMovimiento'])
        ->name('inventario.movimiento.store');

    //////////////////////////////////////////////////
    // ARQUEO DE CAJA Y CONTROL DE EFECTIVO
    //////////////////////////////////////////////////

    Route::get('/caja', [CajaController::class, 'index'])
        ->name('caja.index');

    Route::post('/caja/aperturar', [CajaController::class, 'aperturar'])
        ->name('caja.aperturar');

    Route::post('/caja/movimiento', [CajaController::class, 'storeMovimiento'])
        ->name('caja.movimiento.store');

    Route::post('/caja/{id}/cerrar', [CajaController::class, 'cerrar'])
        ->name('caja.cerrar');

    Route::get('/caja/{id}/comprobante', [CajaController::class, 'show'])
        ->name('caja.show');

    Route::get('/caja/{id}/imprimir', [CajaController::class, 'imprimir'])
        ->name('caja.imprimir');

    //////////////////////////////////////////////////
    // CONFIGURACIÓN Y CATÁLOGO
    //////////////////////////////////////////////////

    Route::resource('users', UserController::class);

    Route::resource('roles', RolController::class);

    Route::resource('categorias', CategoriaController::class);

    Route::resource('clientes', ClienteController::class);

    Route::resource('productos', ProductoController::class);

    Route::resource('proveedores', ProveedorController::class);

    Route::resource('compras', CompraController::class);

    //////////////////////////////////////////////////
    // VENTAS Y COMPROBANTE
    //////////////////////////////////////////////////

    /*
     * Esta ruta debe estar antes de Route::resource('ventas', ...)
     * para evitar conflictos con la ruta ventas/{venta}.
     */
    Route::get(
        '/ventas/{venta}/comprobante',
        [VentaController::class, 'comprobante']
    )->name('ventas.comprobante');

    Route::resource('ventas', VentaController::class);

    //////////////////////////////////////////////////
    // BITÁCORA Y AUDITORÍA
    //////////////////////////////////////////////////

    Route::get('/bitacoras/exportar', [BitacoraController::class, 'exportar'])
        ->name('bitacoras.exportar');

    Route::post('/bitacoras/limpiar', [BitacoraController::class, 'limpiar'])
        ->name('bitacoras.limpiar');

    Route::resource('bitacoras', BitacoraController::class)
        ->only([
            'index',
            'show'
        ]);

    //////////////////////////////////////////////////
    // BUSCAR PRODUCTOS
    //////////////////////////////////////////////////

    Route::get('/buscar-producto/{codigo}', function ($codigo) {

        return \App\Models\Producto::where('codigo', $codigo)
            ->first();

    })->name('productos.buscar');

    Route::get('/buscar-producto-venta/{codigo}', function ($codigo) {

        return \App\Models\Producto::where('codigo', $codigo)
            ->first();

    })->name('productos.buscar.venta');

    //////////////////////////////////////////////////
    // PERMISOS POR ROL
    //////////////////////////////////////////////////

    Route::get(
        '/roles/{id}/permisos',
        [RolController::class, 'permisos']
    )->name('roles.permisos');

    Route::post(
        '/roles/{id}/permisos',
        [RolController::class, 'guardarPermisos']
    )->name('roles.guardarPermisos');

    //////////////////////////////////////////////////
    // COPIAS DE SEGURIDAD
    //////////////////////////////////////////////////

    Route::get(
        '/backups',
        [BackupController::class, 'index']
    )->name('backups.index');

    Route::post(
        '/backups/create',
        [BackupController::class, 'create']
    )->name('backups.create');

    Route::get(
        '/backups/download/{archivo}',
        [BackupController::class, 'download']
    )->name('backups.download');

    Route::post(
        '/backups/restore/{archivo}',
        [BackupController::class, 'restore']
    )->name('backups.restore');

    Route::post(
        '/backups/upload',
        [BackupController::class, 'upload']
    )->name('backups.upload');

    Route::delete(
        '/backups/delete/{archivo}',
        [BackupController::class, 'delete']
    )->name('backups.delete');
});

//////////////////////////////////////////////////
// BREEZE: RECUPERACIÓN DE CONTRASEÑA
//////////////////////////////////////////////////

require __DIR__.'/auth.php';