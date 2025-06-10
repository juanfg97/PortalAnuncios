// Tipos permitidos para archivos adjuntos
const tiposPermitidos = [
    'pdf',
    // Word
    'doc', 'docx', 'dot', 'dotx',
    // Excel
    'xls', 'xlsx', 'xlsm', 'xlsb',
    // PowerPoint
    'ppt', 'pptx', 'pps', 'ppsx',
    // Imágenes
    'jpg', 'jpeg', 'png'
];

// Manejar envío de informe
document.getElementById('formInforme').addEventListener('submit', function (e) {
    e.preventDefault();

    const tipoInforme = document.getElementById('tipoInforme');
    const asuntoInforme = document.getElementById('asuntoInforme');
    const descripcionInforme = document.getElementById('descripcionInforme');
    const prioridadInforme = document.getElementById('prioridadInforme');
    const checkboxAdjuntos = document.getElementById('adjuntarDocumentos');
    const fileInput = document.getElementById('fileAdjuntos');

    // Validaciones básicas con trim para evitar espacios vacíos
    if (!tipoInforme.value || !asuntoInforme.value.trim() || !descripcionInforme.value.trim() || !prioridadInforme.value) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos incompletos',
            text: 'Por favor completa todos los campos obligatorios.'
        });
        return;
    }

    // Validar archivos adjuntos (si están activados y tienen archivos)
    if (checkboxAdjuntos.checked && fileInput && fileInput.files.length > 0) {
        for (let i = 0; i < fileInput.files.length; i++) {
            const archivo = fileInput.files[i];
            const extension = archivo.name.split('.').pop().toLowerCase();

            if (!tiposPermitidos.includes(extension)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Archivo no permitido',
                    text: `El archivo "${archivo.name}" no tiene un formato permitido.\nFormatos permitidos: ${tiposPermitidos.join(', ')}`
                });
                return;
            }
        }
    }

    // Mostrar datos en el modal de confirmación
    document.getElementById('confirmTipo').textContent = tipoInforme.selectedOptions[0].text;
    document.getElementById('confirmAsuntoInforme').textContent = asuntoInforme.value.trim();
    document.getElementById('confirmDescripcion').textContent =
        descripcionInforme.value.trim().length > 100
            ? descripcionInforme.value.trim().substring(0, 100) + '...'
            : descripcionInforme.value.trim();

    const urgenciaTexto = {
        'baja': '🟢 Baja - Informativo',
        'media': '🟡 Media - Requiere Atención',
        'alta': '🔴 Alta - Urgente'
    };
    document.getElementById('confirmUrgencia').textContent = urgenciaTexto[prioridadInforme.value];

    const confirmAdjuntos = document.getElementById('confirmAdjuntos');
    if (confirmAdjuntos) {
        const cantidad = fileInput && fileInput.files.length > 0 ? fileInput.files.length : 0;
        confirmAdjuntos.textContent = cantidad > 0 ? `${cantidad} archivo(s)` : 'No';
    }

    // Mostrar modal de confirmación
    new bootstrap.Modal(document.getElementById('modalConfirmarInforme')).show();
});

document.getElementById('btnConfirmarInforme').addEventListener('click', function () {
    const form = document.getElementById('formInforme');
    const formData = new FormData(form); // Incluye archivos y demás campos

    fetch('../../Controlador/funciones/enviarinforme.php', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.estado === 'ok') {
                Swal.fire({
                    icon: 'success',
                    title: 'Informe enviado',
                    text: 'El informe fue enviado exitosamente al Presidente Central.'
                });

                form.reset();

                // Limpiar input file (no eliminarlo)
                const fileInput = document.getElementById('fileAdjuntos');
                if (fileInput) {
                    fileInput.value = '';
                }

                // Ocultar input file si estaba visible
                document.getElementById('adjuntarDocumentos').checked = false;
                if (fileInput) fileInput.remove();

                bootstrap.Modal.getInstance(document.getElementById('modalConfirmarInforme')).hide();
            } else {
                throw new Error(data.mensaje || 'Error desconocido');
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error al enviar',
                text: error.message || 'No se pudo enviar el informe.'
            });
        });
});



// Manejo botón ver completo
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btnVerComunicado').forEach(btn => {
        btn.addEventListener('click', function () {
            const comunicadoId = this.getAttribute('data-id');
            const contenedor = document.getElementById('contenidoComunicadoCompleto');
            contenedor.innerHTML = '<p class="text-muted">Cargando comunicado...</p>';

            fetch('../../Controlador/funciones/obtenercomunicado.php?id=' + encodeURIComponent(comunicadoId))
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Respuesta no válida del servidor');
                    }
                    return response.text();
                })
                .then(html => {
                    if (html.includes('ERROR_COMUNICADO')) {
                        throw new Error(html.replace('ERROR_COMUNICADO:', '').trim());
                    }
                    contenedor.innerHTML = html;
                })
                .catch(error => {
                    contenedor.innerHTML = '';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al cargar',
                        text: error.message || 'No se pudo cargar el comunicado.'
                    });
                });
        });
    });
});
