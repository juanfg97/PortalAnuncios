<?php
$conexion = new mysqli("localhost","root","","urbanizacion","3306");
$conexion ->set_charset("utf8"); 

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$query = "SELECT Terraza, Edificio, nombre_completo, correo, telefono 
          FROM presidente_condominio 
          ORDER BY CAST(Terraza AS UNSIGNED), Edificio";
$stmt = $conexion->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()) {
    $Edificio = $row['Terraza'].$row['Edificio']; // ej: "1A-11"
    
    echo "<tr data-terraza='" . $row['Terraza'] . "' data-edificio='" . $row['Edificio'] . "'>";
    echo "<td>
            <div class='d-flex align-items-center'>
                <div class='ms-3'>
                    <strong>" . $Edificio . "</strong>
                </div>
            </div>
          </td>";
    echo "<td>" . $row['nombre_completo'] . "</td>";
    echo "<td>
            <div class='contact-info'>
                <a href='mailto:" . $row['correo'] . "' class='contact-email'>" . $row['correo'] . "</a>
            </div>
          </td>";
    echo "<td>
            <div class='contact-phone'>" . $row['telefono'] . "</div>
          </td>";
    echo "<td>
            <div class='action-buttons'>
                <button type='button' class='btn btn-danger btn-sm btn-delete' 
                        data-terraza='" . $row['Terraza'] . "' 
                        data-edificio='" . $row['Edificio'] . "'
                        data-name='" . htmlspecialchars($row['nombre_completo']) . "'
                        data-building='" . $Edificio . "'>
                    🗑️ Eliminar
                </button>
            </div>
          </td>";
    echo "</tr>";
}
?>