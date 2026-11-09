@extends('dashboard')

@section('content')

<div class="container-fluid mt-3">

    <div class="card card-primary card-outline shadow">

        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-eye"></i>
                Detalle de Venta
            </h3>
        </div>

        <div class="card-body">

            <p><strong>Fecha:</strong> {{ $venta->fecha }}</p>
            <p><strong>Comprobante:</strong> {{ $venta->comprobante }}</p>
            <p><strong>Total:</strong> Bs {{ number_format($venta->total, 2) }}</p>

            <table class="table table-bordered text-center mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($venta->detalles as $i => $d)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $d->producto->nombre ?? 'Producto eliminado' }}</td>
                        <td>{{ $d->cantidad }}</td>
                        <td>Bs {{ number_format($d->precio, 2) }}</td>
                        <td>Bs {{ number_format($d->cantidad * $d->precio, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>

        </div>

    </div>

</div>

@endsection