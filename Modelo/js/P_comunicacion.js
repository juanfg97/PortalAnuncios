  // Manejar envío de comunicado
        document.getElementById('formComunicado').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const destinatario = document.getElementById('destinatario').value;
            const asunto = document.getElementById('asunto').value;
            const mensaje = document.getElementById('mensaje').value;
            const prioridad = document.getElementById('prioridad').value;
            
            // Actualizar modal de confirmación
            document.getElementById('confirmDestinatario').textContent = 
                document.getElementById('destinatario').selectedOptions[0].text;
            document.getElementById('confirmAsunto').textContent = asunto;
            document.getElementById('confirmMensaje').textContent = 
                mensaje.length > 100 ? mensaje.substring(0, 100) + '...' : mensaje;
            
            const prioridadTexto = {
                'normal': '📄 Normal',
                'importante': '⚠️ Importante', 
                'urgente': '🚨 Urgente'
            };
            document.getElementById('confirmPrioridad').textContent = prioridadTexto[prioridad];
            
            // Mostrar modal
            new bootstrap.Modal(document.getElementById('modalConfirmarEnvio')).show();
        });
    document.getElementById('btnConfirmarEnvio').addEventListener('click', function () {
    const destinatario = document.getElementById('destinatario').value.trim();
    const asunto = document.getElementById('asunto').value.trim();
    const mensaje = document.getElementById('mensaje').value.trim();
    const prioridad = document.getElementById('prioridad').value;

    // Validar campos vacíos
    if (!destinatario || !asunto || !mensaje || !prioridad) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos incompletos',
            text: 'Por favor, completa todos los campos antes de enviar el comunicado.'
        });
        return;
    }

    // Validar longitud de asunto
    if (asunto.length < 5 || asunto.length > 100) {
        Swal.fire({
            icon: 'warning',
            title: 'Asunto inválido',
            text: 'El asunto debe tener entre 5 y 100 caracteres.'
        });
        return;
    }

    // Validar longitud de mensaje
    if (mensaje.length < 10 || mensaje.length > 1000) {
        Swal.fire({
            icon: 'warning',
            title: 'Mensaje inválido',
            text: 'El mensaje debe tener entre 10 y 1000 caracteres.'
        });
        return;
    }

    const data = { destinatario, asunto, mensaje, prioridad };

    fetch('../../Controlador/funciones/P_enviarcomunicado.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(res => {
        if (!res.ok) throw new Error('Error al enviar comunicado');
        return res.json();
    })
    .then(response => {
        if (response.success) {
            Swal.fire({
                icon: 'success',
                title: 'Comunicado enviado',
                text: 'El comunicado ha sido enviado correctamente.'
            });

            document.getElementById('formComunicado').reset();
            bootstrap.Modal.getInstance(document.getElementById('modalConfirmarEnvio')).hide();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error al enviar',
                text: response.error || 'Hubo un problema inesperado.'
            });
        }
    })
    .catch(error => {
        console.error(error);
        Swal.fire({
            icon: 'error',
            title: 'Error del sistema',
            text: 'No se pudo enviar el comunicado. Intenta nuevamente.'
        });
    });
});



     


        
       //filtro

document.addEventListener('DOMContentLoaded', function () {
    const filtroTerraza = document.getElementById('filtroTerraza');
    const filtroEdificio = document.getElementById('filtroEdificio');
    const filtroPrioridad = document.getElementById('filtroPrioridad');
    const filtroTipo = document.getElementById('filtroTipo');
    const informes = document.querySelectorAll('.informe-item');

    function aplicarFiltros() {
        const terrazaSeleccionada = filtroTerraza.value;
        const edificioIngresado = filtroEdificio.value.trim().toUpperCase();
        const prioridadSeleccionada = filtroPrioridad.value;
        const tipoSeleccionado = filtroTipo.value;

        informes.forEach(informe => {
            const terraza = informe.getAttribute('data-terraza');
            const edificio = informe.getAttribute('data-edificio')?.toUpperCase() || '';
            const prioridad = informe.getAttribute('data-prioridad');
            const tipo = informe.getAttribute('data-tipo');

            const coincideTerraza = !terrazaSeleccionada || terraza === terrazaSeleccionada;
            const coincideEdificio = !edificioIngresado || edificio === edificioIngresado;
            const coincidePrioridad = !prioridadSeleccionada || prioridad === prioridadSeleccionada;
            const coincideTipo = !tipoSeleccionado || tipo === tipoSeleccionado;

            if (coincideTerraza && coincideEdificio && coincidePrioridad && coincideTipo) {
                informe.style.display = '';
            } else {
                informe.style.display = 'none';
            }
        });
    }

    filtroTerraza.addEventListener('change', aplicarFiltros);
    filtroEdificio.addEventListener('input', aplicarFiltros);
    filtroPrioridad.addEventListener('change', aplicarFiltros);
    filtroTipo.addEventListener('change', aplicarFiltros);
});




  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.ver-completo').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const informeItem = this.closest('.informe-item');
            if (informeItem) {
                // Quitar clases que resaltan como nuevo
                informeItem.classList.remove('border-primary', 'bg-light');

                // Quitar badge "Nuevo"
                const badgeNuevo = informeItem.querySelector('span.badge.bg-primary.ms-2');
                if (badgeNuevo && badgeNuevo.textContent.includes('🆕')) {
                    badgeNuevo.remove();
                }
            }

            const asunto = this.dataset.asunto;
            const tipo = this.dataset.tipo;
            const prioridad = this.dataset.prioridad;
            const remitente = this.dataset.remitente;
            const fecha = this.dataset.fecha;
            const descripcion = this.dataset.descripcion;
            const adjuntos = JSON.parse(this.dataset.adjuntos || "[]");

            const contenido = `
                <div>
                    <div class="mb-2">
                        <span class="badge bg-secondary me-2">Tipo: ${tipo}</span>
                        <span class="badge bg-info me-2">Prioridad: ${prioridad}</span>
                        <span class="badge bg-light text-dark">Fecha: ${new Date(fecha).toLocaleString()}</span>
                    </div>
                    <h5 class="text-primary fw-bold">${asunto}</h5>
                    <p><strong>Remitente:</strong> ${remitente}</p>
                    <p>${descripcion.replace(/\n/g, '<br>')}</p>
                    ${adjuntos.length > 0 ? `
                        <p><strong>Archivos adjuntos:</strong></p>
                        <ul>
                            ${adjuntos.map(a => `<li><a href="/PortalDeAnuncios/Vista/${a.ruta}" download="${a.nombre}" target="_blank">📎 ${a.nombre}</a></li>`).join('')}
                        </ul>
                    ` : '<p class="text-muted">No hay archivos adjuntos.</p>'}
                </div>
            `;

            document.getElementById('contenidoInformeCompleto').innerHTML = contenido;

            const modal = new bootstrap.Modal(document.getElementById('modalInformeCompleto'));
            modal.show();
        });
    });
});
