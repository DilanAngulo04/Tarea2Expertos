<?php

include_once '../../conexion.php';

$probabiliadadEstiloAprendizaje[0] = 0.2763;
$probabiliadadEstiloAprendizaje[1] = 0.2763;
$probabiliadadEstiloAprendizaje[2] = 0.2632;
$probabiliadadEstiloAprendizaje[3] = 0.1842;

$genero_estilo_simple = $_POST['genero_estilo_simple'];
$promedio_estilo_simple = $_POST['promedio_estilo_simple'];
$recinto_estilo_simple = $_POST['recinto_estilo_simple'];
$estilo = "";
$estiloP = 0;

$tipoEstilo[0] = 'Divergente';
$tipoEstilo[1] = 'Convergente';
$tipoEstilo[2] = 'Asimilador';
$tipoEstilo[3] = 'Acomodador';

$sql_procedimiento_ejercicio4 = "CALL procedimientoEjercicio4(?, ?, ?, ?, @recintoOutput, @promedioOutput, @generoOutput)";

for ($contador = 0; $contador< count($tipoEstilo)  ; $contador++) {
     $call = mysqli_prepare($conection, $sql_procedimiento_ejercicio4);
     mysqli_stmt_bind_param($call, 'ssss', $precint, $promedio_estilo_simple, $genero_estilo_simple, $tipoEstilo[$contador]);
     mysqli_stmt_execute($call);
     
     $select_ejercicio4 = mysqli_query($conn, 'SELECT @recintoOutput, @promedioOutput, @generoOutput');
     $get_estilo_ejercicio4 = mysqli_fetch_assoc($select_ejercicio4);
     
     $recinto_p_ejercicio4[$i] = $result['@recintoOutput'];
     $promedio_p_ejercicio4[$i] = $result['@promedioOutput'];
     $genero_p_ejericicio4[$i] = $result['@generoOutput'];
      
     $probabiliadad_total_ejercicio4[$contadori] =  $recinto_p_ejercicio4[$contador] * $promedio_p_ejercicio4[$contador] * $genero_p_ejericicio4[$contador] * $probabiliadadEstiloAprendizaje[$contador];
     
     echo "Probabilidad que sea " . $tipoEstilo[$contador] . " = " . $probabiliadad_total_ejercicio4[$contador];

     if($contador==0){
          $estiloP = $probabiliadad_total_ejercicio4[$contador];
          $estilo = $tipoEstilo[$contador]; 
     }
     
     if($probabiliadad_total_ejercicio4[$contador]> $estiloP ){
          $estiloP = $probabiliadad_total_ejercicio4[$contador];
          $estilo = $tipoEstilo[$contador]; 
     }
}

echo json_encode("Su estilo de aprendizaje es = " . $estilo);


?>