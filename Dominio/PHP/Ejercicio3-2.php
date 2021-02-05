<?php

include_once '../../conexion.php';

$estilo_ejercicio2 = $_POST['estilo_recinto'];
$promedio = $_POST['promedio_recinto'];
$genero = $_POST['genero_recinto'];

$probabilidadTipoRecinto[0] = 0.5;
$probabilidadTipoRecinto[1] = 0.5;

$posibleRecinto[0] = "Paraiso";
$posibleRecinto[1] = "Turrialba";

$sql_procedure_recinto = "CALL procedimientoEjercicio2(?, ?, ?, ?, @generoOutput, @promedioOutput, @estiloOutput)";

for ($contador = 0; $contador < count($posibleRecinto) ; $contador++) {
    
    $call = mysqli_prepare($conection, $sql_procedure_recinto);
    mysqli_stmt_bind_param($call, 'ssss', $estilo_ejercicio2, $promedio, $genero, $posibleRecinto[$contador]);
    mysqli_stmt_execute($call);
    
    $select_recinto = mysqli_query($conection, 'SELECT @generoOutput, @promedioOutput, @estiloOutput');
    $get_recinto = mysqli_fetch_assoc($select_recinto);

    $genero_recinto[$contador] = $get_recinto['@generoOutput'];
    $promedio_recinto[$contador] = $get_recinto['@promedioOutput'];
    $estilo_recinto[$contador] = $get_recinto['@estiloOutput'];

    $probabilidad_total_recinto[$contador] = $genero_recinto[$contador] * $promedio_recinto[$contador] * $estilo_recinto[$contador] * $probabilidadTipoRecinto[$contador];
    echo "Probabilidad de sea " . $posibleRecinto[$contador] . " = " .  $probabilidad_total_recinto[$contador]; 

}

//Devuelvo el resultado
if($probabilidad_total_recinto[0] > $probabilidad_total_recinto[1]){
    echo json_encode("Paraiso");
}else{
    echo json_encode("Turrialba");
}

?>