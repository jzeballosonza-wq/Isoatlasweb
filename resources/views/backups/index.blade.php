@extends('dashboard')

@section('content')

<style>
    .backup-container {
        padding: 1.5rem 1rem;
    }

    .kpi-backup-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        padding: 1.25rem;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
    }

    .kpi-backup-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px -5px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
    }

    .kpi-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: #ffffff;
        flex-shrink: 0;
    }

    .table-container-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .table-custom thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem 1.2rem;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .table-custom tbody td {
        padding: 1rem 1.2rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.88rem;
    }

    .table-custom tbody tr:hover td {
        background-color: #f8fafc;
    }

    .upload-dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        padding: 2rem;
        text-align: center;
        background: #f8fafc;
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .upload-dropzone:hover, .upload-dropzone.dragover {
        border-color: #6366f1;
        background: #eef2ff;
    }

    .action-btn-circle {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        font-size: 0.85rem;
    }

    .action-btn-circle:hover {
        transform: scale(1.08);
    }
</style>

<div class="backup-container">

    <!-- 1. ENCABEZADO Y BREADCRUMB -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="p-2 rounded-3 text-white bg-dark d-inline-flex" style="width: 38px; height: 38px; font-size: 1.1rem; align-items: center; justify-content: center;">
                    <i class="fas fa-database text-warning"></i>
                </div>
                <h2 class="h4 fw-bold mb-0 text-dark">Copias de Seguridad de la Base de Datos</h2>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill small ms-1">
                    <i class="fas fa-circle text-success me-1" style="font-size: 0.55rem;"></i> Motor Activo
                </span>
            </div>
            <p class="text-muted small mb-0">
                Respaldo integral de la base de datos: catálogo de productos, inventario, ventas, compras, usuarios y bitácoras con opciones de descarga y restauración segura.
            </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- BOTÓN SUBIR RESPALDO EXTERNO -->
            <button type="button" class="btn btn-outline-primary rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalSubirBackup">
                <i class="fas fa-cloud-upload-alt"></i>
                <span>Subir Respaldo (.sql)</span>
            </button>

            <!-- FORMULARIO CREAR RESPALDO -->
            <form id="formCrearBackup" action="{{ route('backups.create') }}" method="POST" class="d-inline">
                @csrf
                <button type="button" onclick="ejecutarCreacionBackup()" class="btn text-white rounded-3 px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                    <i class="fas fa-plus-circle"></i>
                    <span>Crear Respaldo Ahora</span>
                </button>
            </form>
        </div>
    </div>

    <!-- 2. TARJETAS KPI DE ESTADO DEL SISTEMA -->
    <div class="row g-3 mb-4">
        <!-- Total de Respaldos -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-backup-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Respaldos Almacenados</div>
                        <h3 class="fw-bold text-dark mt-1 mb-0">{{ $totalBackups }}</h3>
                        <small class="text-muted d-block mt-1">Archivos .sql disponibles</small>
                    </div>
                    <div class="kpi-icon-box" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                        <i class="fas fa-database"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Espacio Ocupado -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-backup-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Espacio Ocupado</div>
                        <h3 class="fw-bold text-primary mt-1 mb-0">{{ $totalEspacio }}</h3>
                        <small class="text-muted d-block mt-1">Almacenamiento local</small>
                    </div>
                    <div class="kpi-icon-box" style="background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);">
                        <i class="fas fa-hard-drive"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Último Respaldo -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-backup-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div style="max-width: 70%;">
                        <div class="text-muted small fw-bold text-uppercase">Último Respaldo</div>
                        <h3 class="fw-bold text-dark mt-1 mb-0 fs-5 text-truncate" title="{{ $ultimoBackup['hace_tiempo'] ?? 'Ninguno' }}">
                            {{ $ultimoBackup['hace_tiempo'] ?? 'Ninguno' }}
                        </h3>
                        <small class="text-muted d-block mt-1 text-truncate" title="{{ $ultimoBackup['fecha'] ?? 'No generado' }}">
                            {{ $ultimoBackup['fecha'] ?? 'Sin registros' }}
                        </small>
                    </div>
                    <div class="kpi-icon-box" style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);">
                        <i class="fas fa-clock-rotate-left"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Base de Datos Activa -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-backup-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div style="max-width: 70%;">
                        <div class="text-muted small fw-bold text-uppercase">Base de Datos</div>
                        <h3 class="fw-bold text-dark mt-1 mb-0 fs-5 text-truncate" title="{{ $dbName }}">
                            {{ $dbName }}
                        </h3>
                        <small class="text-muted d-block mt-1">
                            <span class="badge bg-light text-dark border">{{ $tablasCount }} tablas</span>
                            <span class="text-secondary ms-1 fw-bold">{{ $dbSizeMB }}</span>
                        </small>
                    </div>
                    <div class="kpi-icon-box" style="background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);">
                        <i class="fas fa-server"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. PANEL DE INFORMACIÓN Y SEGURIDAD -->
    <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4 p-3 d-flex align-items-start gap-3 bg-white border-start border-4 border-info">
        <div class="p-2 rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
            <i class="fas fa-shield-halved fs-5"></i>
        </div>
        <div>
            <div class="fw-bold text-dark mb-1">Información sobre la Seguridad y Restauración de Datos</div>
            <p class="text-muted small mb-0">
                Cada copia de seguridad contiene la estructura completa y todos los registros del sistema (usuarios, productos, ventas, compras, caja chica y auditoría). 
                <strong>Atención:</strong> Al restaurar una copia de seguridad, los datos actuales de la base de datos serán reemplazados por el contenido del archivo seleccionado. Se recomienda descargar siempre una copia reciente antes de ejecutar cualquier restauración.
            </p>
        </div>
    </div>

    <!-- 4. LISTADO DE ARCHIVOS DE RESPALDO -->
    <div class="table-container-card mb-4">
        <div class="p-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-light">
            <div class="fw-bold text-dark d-flex align-items-center gap-2">
                <i class="fas fa-list-check text-primary"></i>
                <span>Historial de Copias de Seguridad Registradas</span>
                <span class="badge bg-secondary-subtle text-secondary border px-2 py-0.5 rounded-pill small" id="contadorFilas">
                    {{ $archivos->count() }} copias
                </span>
            </div>

            <!-- BÚSQUEDA RÁPIDA CLIENT-SIDE -->
            <div class="input-group input-group-sm" style="max-width: 320px;">
                <span class="input-group-text bg-white border-end-0 text-muted">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="filtroBackupInput" class="form-control border-start-0" placeholder="Buscar por nombre o fecha..." onkeyup="filtrarTablaBackups()">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0" id="tablaBackups">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th>Nombre del Archivo</th>
                        <th>Tipo / Origen</th>
                        <th>Fecha de Creación</th>
                        <th>Tamaño</th>
                        <th>Estado</th>
                        <th class="text-end" style="width: 170px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($archivos as $index => $archivo)
                    <tr class="fila-backup">
                        <td class="text-center fw-bold text-muted small">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="p-2 rounded-3 bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                                    <i class="fas fa-file-code"></i>
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block text-truncate" style="max-width: 360px;" title="{{ $archivo['nombre'] }}">
                                        {{ $archivo['nombre'] }}
                                    </span>
                                    <small class="text-muted">Formato SQL DDL & DML</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if(str_starts_with($archivo['nombre'], 'upload_'))
                                <span class="badge bg-purple-subtle text-purple border border-purple-subtle px-2 py-1 rounded-pill small" style="color: #7c3aed; background-color: #f3e8ff;">
                                    <i class="fas fa-cloud-arrow-up me-1"></i> Subido Externo
                                </span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill small">
                                    <i class="fas fa-robot me-1"></i> Generado por Sistema
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $archivo['fecha'] }}</div>
                            <small class="text-muted">{{ $archivo['hace_tiempo'] }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2.5 py-1.5 fw-bold">
                                {{ $archivo['tamano'] }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill small">
                                <i class="fas fa-check-circle me-1"></i> Listo
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1.5">
                                <!-- DESCARGAR -->
                                <a href="{{ route('backups.download', $archivo['nombre']) }}" 
                                   class="btn btn-outline-primary action-btn-circle" 
                                   title="Descargar archivo SQL a mi equipo">
                                    <i class="fas fa-download"></i>
                                </a>

                                <!-- RESTAURAR CON SWEETALERT -->
                                <form id="formRestore_{{ $loop->index }}" action="{{ route('backups.restore', $archivo['nombre']) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="button" 
                                            onclick="confirmarRestauracion('{{ $archivo['nombre'] }}', 'formRestore_{{ $loop->index }}')" 
                                            class="btn btn-outline-warning action-btn-circle" 
                                            title="Restaurar base de datos desde este respaldo">
                                        <i class="fas fa-rotate-left"></i>
                                    </button>
                                </form>

                                <!-- ELIMINAR CON SWEETALERT -->
                                <form id="formDelete_{{ $loop->index }}" action="{{ route('backups.delete', $archivo['nombre']) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            onclick="confirmarEliminacion('{{ $archivo['nombre'] }}', 'formDelete_{{ $loop->index }}')" 
                                            class="btn btn-outline-danger action-btn-circle" 
                                            title="Eliminar este archivo">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="filaVacia">
                        <td colspan="7" class="text-center py-5">
                            <div class="p-3 d-inline-block rounded-circle bg-light mb-3">
                                <i class="fas fa-database text-muted fa-3x"></i>
                            </div>
                            <h5 class="fw-bold text-dark">No hay copias de seguridad registradas</h5>
                            <p class="text-muted small mb-3">Genere su primer respaldo para proteger los datos y operaciones de la tienda.</p>
                            <button type="button" onclick="ejecutarCreacionBackup()" class="btn btn-success rounded-3 px-4 fw-bold">
                                <i class="fas fa-plus-circle me-1"></i> Generar Primera Copia de Seguridad
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- MODAL: SUBIR RESPALDO EXTERNO (.SQL) -->
<div class="modal fade" id="modalSubirBackup" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formSubirBackup" action="{{ route('backups.upload') }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg rounded-4">
            @csrf
            <div class="modal-header border-0 bg-primary text-white rounded-top-4 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-cloud-upload-alt fs-5"></i>
                    <h5 class="modal-title fw-bold">Subir Copia de Seguridad Externa</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="alert alert-primary-subtle text-primary-emphasis border border-primary-subtle rounded-3 small p-3 mb-3">
                    <i class="fas fa-info-circle me-1"></i>
                    Seleccione un archivo de respaldo generado previamente con extensión <strong>.sql</strong>. El archivo quedará disponible de inmediato en la lista para su descarga o restauración.
                </div>

                <div class="upload-dropzone mb-3" id="dropzoneBox" onclick="document.getElementById('inputSqlFile').click()">
                    <i class="fas fa-file-arrow-up text-primary fa-3x mb-2 d-block"></i>
                    <div class="fw-bold text-dark">Arrastre y suelte su archivo .sql aquí</div>
                    <div class="text-muted small">o haga clic para explorar archivos en su equipo</div>
                    <div class="badge bg-secondary-subtle text-secondary border mt-2">Tamaño máximo: 100 MB</div>
                </div>

                <input type="file" name="archivo_sql" id="inputSqlFile" class="d-none" accept=".sql" onchange="mostrarArchivoSeleccionado(this)" required>

                <div id="infoArchivoSeleccionado" class="d-none card border bg-light rounded-3 p-3 mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-file-code text-primary fs-4"></i>
                            <div>
                                <span class="fw-bold text-dark d-block text-truncate" style="max-width: 250px;" id="lblNombreArchivo"></span>
                                <small class="text-muted" id="lblTamanoArchivo"></small>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="limpiarArchivoSeleccionado()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 bg-light rounded-bottom-4 px-4 py-3">
                <button type="button" class="btn btn-secondary rounded-3 px-3" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" id="btnSubirSubmit" class="btn btn-primary rounded-3 px-4 fw-bold" disabled>
                    <i class="fas fa-cloud-upload-alt me-1"></i> Subir y Registrar Respaldo
                </button>
            </div>
        </form>
    </div>
</div>

<!-- SCRIPTS DE INTERACCIÓN Y SWEETALERT2 -->
<script>
    // 1. CREACIÓN DE RESPALDO CON SPINNER
    function ejecutarCreacionBackup() {
        Swal.fire({
            title: '¿Generar nueva copia de seguridad?',
            text: 'Se realizará un volcado completo de la estructura y datos de la base de datos actual.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-check me-1"></i> Sí, generar respaldo',
            cancelButtonText: 'Cancelar',
            customClass: { popup: 'rounded-4' }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Generando Copia de Seguridad...',
                    html: '<div class="py-2"><i class="fas fa-spinner fa-spin fa-2x text-success mb-3"></i><p class="text-muted small mb-0">Extrayendo tablas, esquemas y registros. Por favor no cierre el navegador...</p></div>',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-4' }
                });
                document.getElementById('formCrearBackup').submit();
            }
        });
    }

    // 2. RESTAURACIÓN CON DOBLE CONFIRMACIÓN Y ALERTA CRÍTICA
    function confirmarRestauracion(nombreArchivo, formId) {
        Swal.fire({
            title: '⚠️ ¿Restaurar Base de Datos?',
            html: `
                <div class="text-start p-2">
                    <p class="mb-2">Está a punto de restaurar el sistema desde el archivo:</p>
                    <div class="p-2 bg-light rounded-3 border fw-bold text-primary mb-3 text-break">
                        <i class="fas fa-file-code me-1"></i> ${nombreArchivo}
                    </div>
                    <div class="alert alert-danger p-2 small mb-0">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>¡ADVERTENCIA CRÍTICA!</strong> Todos los cambios, ventas y operaciones realizadas después de la fecha de este respaldo serán sobrescritos permanentemente.
                    </div>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-rotate-left me-1"></i> Sí, deseo restaurar ahora',
            cancelButtonText: 'Cancelar',
            customClass: { popup: 'rounded-4' }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Restaurando Base de Datos...',
                    html: '<div class="py-2"><i class="fas fa-spinner fa-spin fa-2x text-danger mb-3"></i><p class="text-muted small mb-0">Reescribiendo tablas e importando registros. Este proceso puede tardar unos segundos...</p></div>',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-4' }
                });
                document.getElementById(formId).submit();
            }
        });
    }

    // 3. ELIMINACIÓN DE ARCHIVO SQL CON CONFIRMACIÓN
    function confirmarEliminacion(nombreArchivo, formId) {
        Swal.fire({
            title: '¿Eliminar este respaldo?',
            html: `¿Está seguro de que desea eliminar el archivo <strong>${nombreArchivo}</strong>? Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar',
            customClass: { popup: 'rounded-4' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }

    // 4. BÚSQUEDA / FILTRO CLIENT-SIDE DE TABLA
    function filtrarTablaBackups() {
        const input = document.getElementById('filtroBackupInput');
        const filtro = input.value.toLowerCase().trim();
        const filas = document.querySelectorAll('#tablaBackups tbody tr.fila-backup');
        let contadorVisibles = 0;

        filas.forEach(fila => {
            const texto = fila.innerText.toLowerCase();
            if (texto.includes(filtro)) {
                fila.style.display = '';
                contadorVisibles++;
            } else {
                fila.style.display = 'none';
            }
        });

        const badgeContador = document.getElementById('contadorFilas');
        if (badgeContador) {
            badgeContador.textContent = `${contadorVisibles} copias`;
        }
    }

    // 5. MANEJO DE ARCHIVO EN MODAL DE SUBIDA
    function mostrarArchivoSeleccionado(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const sizeKB = (file.size / 1024).toFixed(1);
            const sizeFormatted = sizeKB > 1024 ? (sizeKB / 1024).toFixed(2) + ' MB' : sizeKB + ' KB';

            document.getElementById('lblNombreArchivo').textContent = file.name;
            document.getElementById('lblTamanoArchivo').textContent = sizeFormatted;
            document.getElementById('infoArchivoSeleccionado').classList.remove('d-none');
            document.getElementById('dropzoneBox').classList.add('d-none');
            document.getElementById('btnSubirSubmit').removeAttribute('disabled');
        }
    }

    function limpiarArchivoSeleccionado() {
        const input = document.getElementById('inputSqlFile');
        input.value = '';
        document.getElementById('infoArchivoSeleccionado').classList.add('d-none');
        document.getElementById('dropzoneBox').classList.remove('d-none');
        document.getElementById('btnSubirSubmit').setAttribute('disabled', 'disabled');
    }

    // Drag and Drop listeners
    const dropzone = document.getElementById('dropzoneBox');
    if (dropzone) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('dragover');
            }, false);
        });

        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files[0]) {
                const input = document.getElementById('inputSqlFile');
                input.files = files;
                mostrarArchivoSeleccionado(input);
            }
        }, false);
    }

    // Indicador de subida en progreso
    const formSubir = document.getElementById('formSubirBackup');
    if (formSubir) {
        formSubir.addEventListener('submit', function() {
            const btn = document.getElementById('btnSubirSubmit');
            btn.setAttribute('disabled', 'disabled');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Subiendo archivo...';
        });
    }
</script>

@endsection