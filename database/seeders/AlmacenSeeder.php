<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Almacen;
use App\Models\Producto;

class AlmacenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Almacen::count() == 0) {
            $a1 = Almacen::create([
                'codigo' => 'ALM-01',
                'nombre' => 'Almacén Central (Sede Principal)',
                'direccion' => 'Av. Principal #450, Zona Comercial',
                'ciudad' => 'Cochabamba',
                'responsable' => 'Ing. Carlos Mendoza',
                'telefono' => '70712345',
                'descripcion' => 'Depósito principal con estanterías organizadas y control de stock para accesorios y periféricos electrónicos.',
                'activo' => true
            ]);

            $a2 = Almacen::create([
                'codigo' => 'ALM-02',
                'nombre' => 'Sucursal Norte (Showroom & Stock)',
                'direccion' => 'Calle América #128, Galería Tech',
                'ciudad' => 'Cochabamba',
                'responsable' => 'Lic. Sofia Ramos',
                'telefono' => '71498765',
                'descripcion' => 'Almacén de exhibición y entrega rápida a clientes de accesorios y productos seleccionados.',
                'activo' => true
            ]);

            Producto::whereNull('almacen_id')->update([
                'almacen_id' => $a1->id,
                'ubicacion_estante' => 'Pasillo A - Estante 1'
            ]);
        }
    }
}
