<?php


$conexion = new mysqli("localhost","root","","urbanizacion","3306");
$conexion ->set_charset("utf8"); 

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}





if (empty($_SESSION['usuario'])) {
    // Redirigir si no hay sesión activa
    session_destroy();
    header('Location: ../index.php');
    exit();
}

$stmt = $conexion->prepare("SELECT COUNT(*) AS total FROM edificios WHERE Terraza = ? AND Edificio = ?");
$stmt->bind_param("ss", $_SESSION['Terraza'], $_SESSION['Edificio']);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado) {
    $fila = $resultado->fetch_assoc();
    $Residentes = $fila['total'];
    echo $Residentes;
    // Ahora $Residentes contiene el número de usuarios con esa Terraza y Edificio
} else {
    $Residentes = 0; // En caso de error
    echo $Residentes;

}

$stmt->close();
$conexion->close();
?>