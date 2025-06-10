  <?php
  $presidente_id = $_SESSION['id'] ?? null;
                            if (!$presidente_id) {
                                die("No autorizado");
                            }

                            $conexion = new mysqli("localhost", "root", "", "urbanizacion", 3306);
                            $conexion->set_charset("utf8");
                            if ($conexion->connect_error) {
                                die("Error en la conexión: " . $conexion->connect_error);
                            }

                            // Función para calcular texto "Hace X tiempo"
                            function tiempo_transcurrido($fecha) {
                                $ahora = new DateTime();
                                $fecha = new DateTime($fecha);
                                $diferencia = $ahora->diff($fecha);

                                if ($diferencia->y > 0) {
                                    return $diferencia->y . ' año' . ($diferencia->y > 1 ? 's' : '') . ' atrás';
                                }
                                if ($diferencia->m > 0) {
                                    return $diferencia->m . ' mes' . ($diferencia->m > 1 ? 'es' : '') . ' atrás';
                                }
                                if ($diferencia->d > 0) {
                                    if ($diferencia->d === 1) return "Ayer";
                                    return $diferencia->d . ' días atrás';
                                }
                                if ($diferencia->h > 0) {
                                    return $diferencia->h . ' hora' . ($diferencia->h > 1 ? 's' : '') . ' atrás';
                                }
                                if ($diferencia->i > 0) {
                                    return $diferencia->i . ' minuto' . ($diferencia->i > 1 ? 's' : '') . ' atrás';
                                }
                                return 'Justo ahora';
                            }

                            $sql = "SELECT * FROM comunicados 
                                    WHERE Destinatario = 'todos' OR Destinatario = ? 
                                    ORDER BY Fecha DESC";

                            $stmt = $conexion->prepare($sql);
                            $stmt->bind_param("s", $presidente_id);
                            $stmt->execute();
                            $resultado = $stmt->get_result();

                            while ($comunicado = $resultado->fetch_assoc()) {
    $prioridad = strtolower($comunicado['Prioridad']); // urgente, importante, normal
    $clasePrioridad = "comunicado-$prioridad";

    $fechaTexto = date("d M Y H:i", strtotime($comunicado['Fecha']));
    $tiempo = tiempo_transcurrido($comunicado['Fecha']);

    echo "<div class='comunicado-item $clasePrioridad'>";
    echo "  <div class='comunicado-header'>";
    echo "    <div class='comunicado-meta'>";
    echo "      <span class='badge-presidente-central'>👑 Presidente Central</span>";
    echo "      <span class='badge-prioridad badge-$prioridad'>";
    if ($prioridad === 'urgente') echo "🚨 Urgente";
    elseif ($prioridad === 'importante') echo "⚠️ Importante";
    else echo "📄 Normal";
    echo "</span>";
    echo "      <span class='badge-fecha'>$fechaTexto</span>";
    echo "    </div>";
    echo "  </div>";
    echo "  <h6 class='fw-bold text-info'>{$comunicado['Asunto']}</h6>";
    echo "  <p class='mb-2'>{$comunicado['Mensaje']}</p>";
    echo "  <div class='d-flex justify-content-between align-items-center'>";
    echo "    <small class='text-muted'>$tiempo</small>";
    echo "    <button class='btn btn-outline-info btn-sm btnVerComunicado' data-id='{$comunicado['Id']}' data-bs-toggle='modal' data-bs-target='#modalComunicadoCompleto'>Ver Completo</button>";
    echo "  </div>";
    echo "</div>";
}


                            $stmt->close();
                            $conexion->close();
                            ?>