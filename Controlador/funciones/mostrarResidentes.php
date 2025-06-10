<?php

$conexion = new mysqli("localhost","root","","urbanizacion","3306");
$conexion ->set_charset("utf8"); 

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}


$query = "SELECT usuario, nombre_completo, correo, telefono FROM edificios WHERE Terraza = ? AND Edificio =?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("ss", $_SESSION['Terraza'],$_SESSION['Edificio']);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()) {
    $userCode = $row['usuario']; // ej: "1A-11"
    
    echo "<tr>";
    echo "<td>
            <div class='d-flex align-items-center'>
                <div class='ms-3'>
                    <strong>" . $userCode . "</strong>
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
    echo "</tr>";
}
?>