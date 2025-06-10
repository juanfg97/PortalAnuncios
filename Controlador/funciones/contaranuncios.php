

<?php

$conexion = new mysqli("localhost","root","","urbanizacion","3306");
$conexion ->set_charset("utf8"); 

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

function contarAnuncios($conexion) {
    $sql = "SELECT COUNT(*) as total FROM anunciosg";
    $resultado = $conexion->query($sql);
    if ($resultado) {
        $fila = $resultado->fetch_assoc();
        return (int)$fila['total'];
    }
    return 0;
}
function contarAnunciosMesActual($conexion) {
    // Fecha actual
    $sql = "SELECT COUNT(*) as total FROM anunciosg 
            WHERE YEAR(Fecha) = YEAR(CURRENT_DATE()) 
              AND MONTH(Fecha) = MONTH(CURRENT_DATE())";
    $resultado = $conexion->query($sql);
    if ($resultado) {
        $fila = $resultado->fetch_assoc();
        return (int)$fila['total'];
    }
    return 0;
}

?>