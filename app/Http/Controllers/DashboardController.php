<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Proveedor;
use App\Models\Compra;
use App\Models\Venta;
use App\Models\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Contadores principales
        $totalUsuarios = User::count();
        $totalRoles = Rol::count();
        $totalProductos = Producto::count();
        $totalCategorias = Categoria::count();
        $totalClientes = Cliente::count();
        $totalProveedores = Proveedor::count();
        $totalVentas = Venta::count();
        $totalCompras = Compra::count();

        // 2. Métricas financieras
        $montoTotalVentas = Venta::sum('total') ?? 0;
        $montoTotalCompras = Compra::sum('total') ?? 0;
        
        $hoy = Carbon::today()->toDateString();
        $ventasHoyMonto = Venta::whereDate('fecha', $hoy)->sum('total') ?? 0;
        $ventasHoyCantidad = Venta::whereDate('fecha', $hoy)->count();

        $stockTotalUnidades = Producto::sum('stock') ?? 0;

        // 3. Alertas de inventario (Stock bajo o crítico)
        $productosBajoStock = Producto::with('categoria')
            ->where(function ($query) {
                $query->whereColumn('stock', '<=', 'stock_minimo')
                      ->orWhere('stock', '<=', 3);
            })
            ->orderBy('stock', 'asc')
            ->take(6)
            ->get();

        $totalStockBajo = Producto::where(function ($query) {
            $query->whereColumn('stock', '<=', 'stock_minimo')
                  ->orWhere('stock', '<=', 3);
        })->count();

        // 4. Actividades y registros recientes
        $ultimasVentas = Venta::latest('fecha')
            ->latest('id')
            ->take(5)
            ->get();

        $ultimasCompras = Compra::with('proveedor')
            ->latest('fecha')
            ->latest('id')
            ->take(5)
            ->get();

        $ultimasBitacoras = Bitacora::with('usuario')
            ->latest('created_at')
            ->take(6)
            ->get();

        // 5. Gráfico: Ventas vs Compras de los últimos 6 meses
        $mesesNombres = [
            1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr',
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
        ];

        $chartMeses = [];
        $chartVentas = [];
        $chartCompras = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $year = $date->year;
            $month = $date->month;
            $label = $mesesNombres[$month] . ' ' . $year;
            $chartMeses[] = $label;

            $sumVentas = Venta::whereYear('fecha', $year)
                ->whereMonth('fecha', $month)
                ->sum('total') ?? 0;

            $sumCompras = Compra::whereYear('fecha', $year)
                ->whereMonth('fecha', $month)
                ->sum('total') ?? 0;

            $chartVentas[] = round($sumVentas, 2);
            $chartCompras[] = round($sumCompras, 2);
        }

        // 6. Gráfico: Distribución de productos por categorías
        $categoriasChart = Categoria::withCount('productos')
            ->orderBy('productos_count', 'desc')
            ->take(6)
            ->get();

        $catLabels = [];
        $catData = [];
        foreach ($categoriasChart as $cat) {
            $catLabels[] = $cat->nombre;
            $catData[] = $cat->productos_count;
        }

        return view('dashboard', compact(
            'totalUsuarios',
            'totalRoles',
            'totalProductos',
            'totalCategorias',
            'totalClientes',
            'totalProveedores',
            'totalVentas',
            'totalCompras',
            'montoTotalVentas',
            'montoTotalCompras',
            'ventasHoyMonto',
            'ventasHoyCantidad',
            'stockTotalUnidades',
            'productosBajoStock',
            'totalStockBajo',
            'ultimasVentas',
            'ultimasCompras',
            'ultimasBitacoras',
            'chartMeses',
            'chartVentas',
            'chartCompras',
            'catLabels',
            'catData'
        ));
    }
}
