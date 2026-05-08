<?php
echo "Timezone configurado: " . date_default_timezone_get() . "<br>";
echo "Fecha y hora actual: " . date('Y-m-d H:i:s') . "<br>";
echo "Hora PHP: " . date('H:i:s') . "<br>";

// Verificar en MySQL también
try {
    include('env.php'); // Tu archivo de conexión
    
    $query = $mbd->query("SELECT NOW() as fecha_mysql, @@session.time_zone as tz");
    $result = $query->fetch(PDO::FETCH_ASSOC);
    
    echo "Fecha MySQL: " . $result['fecha_mysql'] . "<br>";
    echo "Timezone MySQL: " . $result['tz'] . "<br>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>