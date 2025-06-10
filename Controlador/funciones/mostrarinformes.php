<?php

$fechaUltimoLogin = $_SESSION['ultimo_login'] ?? null;

$conexion = new mysqli("localhost", "root", "", "urbanizacion");
$conexion->set_charset("utf8");
$iconos_prioridad = [
    'baja' => '🟢 Baja - Informativo',
    'media' => '🟡 Media - Requiere Atención',
    'alta' => '🔴 Alta - Urgente'
];

$iconos_tipo = [
    'mantenimiento' => '🔧 Mantenimiento',
    'seguridad' => '🛡️ Seguridad',
    'financiero' => '💰 Financiero',
    'limpieza' => '🧹 Limpieza',
    'servicios' => '⚡ Servicios Públicos',
    'reparaciones' => '🔨 Reparaciones',
    'general' => '📋 General'
];

$query = "
    SELECT 
        i.id, i.tipo, i.asunto, i.descripcion, i.prioridad, i.remitente, i.fecha_creacion,
        a.nombre_archivo, a.ruta_archivo
    FROM informes i
    LEFT JOIN archivos_adjuntos a ON i.id = a.informe_id
    ORDER BY i.fecha_creacion DESC
";

$resultado = $conexion->query($query);

$informes = [];
while ($row = $resultado->fetch_assoc()) {
    $id = $row['id'];
    if (!isset($informes[$id])) {
        $informes[$id] = [
            'tipo' => $row['tipo'],
            'asunto' => $row['asunto'],
            'descripcion' => $row['descripcion'],
            'prioridad' => $row['prioridad'],
            'remitente' => $row['remitente'],
            'fecha' => $row['fecha_creacion'],
            'adjuntos' => []
        ];
    }

    if (!empty($row['nombre_archivo'])) {
        $informes[$id]['adjuntos'][] = [
            'nombre' => $row['nombre_archivo'],
            'ruta' => $row['ruta_archivo']
        ];
    }
}

foreach ($informes as $id => $info) {

 $esNuevo = false;
    if ($fechaUltimoLogin && strtotime($info['fecha']) > strtotime($fechaUltimoLogin)) {
        $esNuevo = true;
    } elseif (!$fechaUltimoLogin) {
        // Primer login, mostrar todos como nuevos
        $esNuevo = true;
    }

    // Clases Bootstrap dinámicas
    $cardClasses = 'informe-item p-3 mb-4 border rounded';
    if ($esNuevo) {
        $cardClasses .= ' border-primary bg-light'; // Resalta visualmente
    }

    echo '<div class="' . $cardClasses . '" 
        data-terraza="' . intval($info['remitente']) . '" 
        data-edificio="' . htmlspecialchars($info['remitente']) . '" 
        data-prioridad="' . htmlspecialchars(strtolower($info['prioridad'])) . '" 
        data-tipo="' . htmlspecialchars(strtolower($info['tipo'])) . '">';

    echo '<div class="informe-header mb-2 d-flex justify-content-between align-items-center">';
    echo '<span class="badge-edificio">Edificio ' . htmlspecialchars($info['remitente']) . '</span>';
    echo '<span class="badge-fecha badge bg-secondary">' . date('d M Y', strtotime($info['fecha'])) . '</span>';
    
    if ($esNuevo) {
        echo '<span class="badge bg-primary ms-2">🆕 Nuevo</span>';
    }
    echo '</div>';
   if (!empty($info['adjuntos'])) {
    echo '<p><strong>Archivos adjuntos:</strong></p>';
    echo '<ul class="list-group">';
    foreach ($info['adjuntos'] as $adj) {
        $rutacompleta = '/PortalDeAnuncios/Vista/' . $adj['ruta'];
        echo '<li class="list-group-item py-2">';
        echo '<a href="' . htmlspecialchars($rutacompleta) . '" download="' . htmlspecialchars($adj['nombre']) . '" target="_blank" class="text-decoration-none d-flex align-items-center">';
        echo '<span class="me-2">📎</span> ' . htmlspecialchars($adj['nombre']);
        echo '</a>';
        echo '</li>';
    }
    echo '</ul>';
} else {
    echo '<p class="text-muted">No hay archivos adjuntos.</p>';
}


    echo '<div class="d-flex justify-content-between align-items-center">';
    echo '<small class="text-muted">' . date('H:i', strtotime($info['fecha'])) . ' | Edificio' . htmlspecialchars($info['remitente']) . '</small>';
  echo '<button 
        class="btn btn-outline-primary btn-sm ver-completo"
        data-id="' . $id . '"
        data-asunto="' . htmlspecialchars($info['asunto'], ENT_QUOTES) . '"
        data-tipo="' . htmlspecialchars($info['tipo'], ENT_QUOTES) . '"
        data-prioridad="' . htmlspecialchars($info['prioridad'], ENT_QUOTES) . '"
        data-remitente="' . htmlspecialchars($info['remitente'], ENT_QUOTES) . '"
        data-fecha="' . htmlspecialchars($info['fecha'], ENT_QUOTES) . '"
        data-descripcion="' . htmlspecialchars($info['descripcion'], ENT_QUOTES) . '"
        data-adjuntos=\'' . json_encode($info['adjuntos'], JSON_HEX_APOS | JSON_HEX_QUOT) . '\'
    >
    Ver Completo
</button>';

    echo '</div>';

    echo '</div>';
}

$conexion->close();
?>
