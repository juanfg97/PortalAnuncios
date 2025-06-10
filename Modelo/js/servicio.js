document.getElementById('ServicioForm').addEventListener('submit', function (e) {
    e.preventDefault();

    // Validar que todos los campos estén llenos
    const requiredFields = ['nombre_servicio', 'descripcion', 'proveedor',
        'contacto', 'servicioCategory'];
    let isValid = true;

    requiredFields.forEach(field => {
        const input = document.getElementById(field);
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    });

    if (!isValid) {
        Swal.fire({
            icon: 'error',
            title: 'Campos incompletos',
            text: 'Por favor, complete todos los campos obligatorios.',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    // Obtener modo y id del anuncio
    const modo = document.getElementById('formMode').value || 'crear';
    const servicioId = document.getElementById('announcementId').value || '';
    console.log(servicioId);
    // Recopilar datos del formulario
    const formData = new FormData();

    // Agregar campos obligatorios
    requiredFields.forEach(field => {
        formData.append(field, document.getElementById(field).value);
    });

    // Agregar modo y id
    formData.append('modo', modo);
    formData.append('id_servicio', servicioId);

    // Agregar imagen si existe
    const imageInput = document.getElementById('servicioImage');
    if (imageInput && imageInput.files.length > 0) {
        formData.append('servicioImage', imageInput.files[0]);
    }

    // Elegir URL según modo
    const url = modo === 'editar'
        ? '../../Controlador/formularios/modificarservicio.php'  // archivo que procesa edición
        : '../../Controlador/formularios/agregarservicio.php'; // archivo que procesa creación

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: modo === 'editar' ? '¡Servicio actualizado!' : '¡Servicio agregado!',
                text: data.message,
                confirmButtonText: 'Excelente'
            }).then(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('addAnnouncementModal'));
                modal.hide();
                document.getElementById('ServicioForm').reset();

                // Resetear formulario a modo creación
                document.getElementById('formMode').value = 'crear';
                document.getElementById('announcementId').value = '';
                document.getElementById('addAnnouncementModalLabel').textContent = '📝 Crear Nuevo Servicio';
                const submitBtn = document.getElementById('submitAnnouncementBtn');
                submitBtn.textContent = '📤 Publicar Servicio';
                submitBtn.classList.remove('btn-azul');
                submitBtn.classList.add('btn-primary');

                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonText: 'Entendido'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor.',
            confirmButtonText: 'Entendido'
        });
    });
});



function eliminarservicio(Id) {
    console.log(Id);
    Swal.fire({
        title: '¿Eliminar este servicio?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../../Controlador/formularios/eliminarservicio.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id_servicio=' + encodeURIComponent(Id)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Eliminado', data.message, 'success');
                    const servicio = document.getElementById('servicio-' + Id);
                    if (servicio) servicio.remove();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Ocurrió un error al eliminar.', 'error');
                console.error(error);
            });
        }
    });
}

function modificarservicio(id) {
    console.log(id);
    fetch(`../../Controlador/formularios/obtenerservicio.php?id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const servicio = data.servicio;

                // Rellenar formulario
                document.getElementById('formMode').value = 'editar';
                document.getElementById('announcementId').value = servicio.Id;
                document.getElementById('nombre_servicio').value = servicio.Nombre;
                document.getElementById('descripcion').value = servicio.Descripcion;
                document.getElementById('proveedor').value = servicio.Proveedor;
                document.getElementById('contacto').value = servicio.Contacto;
                document.getElementById('servicioCategory').value = servicio.Categoria;

                // Vista previa de imagen
                if (servicio.Imagen) {
                    document.getElementById('imagePreview').src = servicio.Imagen;
                    document.getElementById('imagePreview').style.display = 'block';
                } else {
                    document.getElementById('imagePreview').style.display = 'none';
                }

                // Cambiar título del modal y botón
                document.getElementById('addAnnouncementModalLabel').textContent = '✏️ Modificar Servicio';
                const submitBtn = document.getElementById('submitAnnouncementBtn');
                submitBtn.textContent = '💾 Guardar Cambios';
                submitBtn.classList.remove('btn-primary');
                submitBtn.classList.add('btn-azul');

                // Abrir modal
                const modal = new bootstrap.Modal(document.getElementById('addAnnouncementModal'));
                modal.show();
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Error', 'No se pudo cargar el servicio.', 'error');
        });
}
document.addEventListener('DOMContentLoaded', () => {
    const filtro = document.getElementById('categoriaFiltro');
    filtro.addEventListener('change', () => {
        const categoriaSeleccionada = filtro.value;
        const servicios = document.querySelectorAll('.announcement-card');

        servicios.forEach(servicio => {
            const categoriaServicio = servicio.getAttribute('data-categoria');
            if (categoriaSeleccionada === 'Todos' || categoriaServicio === categoriaSeleccionada) {
                servicio.style.display = '';
            } else {
                servicio.style.display = 'none';
            }
        });
    });
});