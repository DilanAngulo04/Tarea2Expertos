<?php

include_once '../../conexion.php';

//Probabilidades fijas
$probabiliadadEstiloAprendizaje[0] = 0.2763;
$probabiliadadEstiloAprendizaje[1] = 0.2763;
$probabiliadadEstiloAprendizaje[2] = 0.2632;
$probabiliadadEstiloAprendizaje[3] = 0.1842;

//Valores del formulario
$genero_estilo_simple = $_POST['genero_estilo_simple'];
$promedio_estilo_simple = $_POST['promedio_estilo_simple'];
$recinto_estilo_simple = $_POST['recinto_estilo_simple'];
$estilo = "";
$estiloP = 0;

//Posibles estilos
$tipoEstilo[0] = 'Divergente';
$tipoEstilo[1] = 'Convergente';
$tipoEstilo[2] = 'Asimilador';
$tipoEstilo[3] = 'Acomodador';

//consulta del procedimiento
$sql_procedimiento_ejercicio4 = "CALL procedimientoEjercicio4(?, ?, ?, ?, @recintoOutput, @promedioOutput, @generoOutput)";

//corro tantos posibles resultados haya
for ($contador = 0; $contador< count($tipoEstilo)  ; $contador++) {

     //Hago la llamada a la base de datos
     $call = mysqli_prepare($conection, $sql_procedimiento_ejercicio4);
     mysqli_stmt_bind_param($call, 'ssss', $recinto_estilo_simple, $promedio_estilo_simple, $genero_estilo_simple, $tipoEstilo[$contador]);
     mysqli_stmt_execute($call);
     
     $select_ejercicio4 = mysqli_query($conection, 'SELECT @recintoOutput, @promedioOutput, @generoOutput');
     $get_estilo_ejercicio4 = mysqli_fetch_assoc($select_ejercicio4);
     
     //obtengo el valor de las probabilidades
     $recinto_p_ejercicio4[$contador] = $get_estilo_ejercicio4['@recintoOutput'];
     $promedio_p_ejercicio4[$contador] = $get_estilo_ejercicio4['@promedioOutput'];
     $genero_p_ejericicio4[$contador] = $get_estilo_ejercicio4['@generoOutput'];
      
      //Multiplico proabilidades por iteracion
     $probabiliadad_total_ejercicio4[$contador] =  $recinto_p_ejercicio4[$contador] * $promedio_p_ejercicio4[$contador] * $genero_p_ejericicio4[$contador] * $probabiliadadEstiloAprendizaje[$contador];
     
     echo "Probabilidad que sea " . $tipoEstilo[$contador] . " = " . $probabiliadad_total_ejercicio4[$contador] . "    ";

     //Calculo cual probabilidad es la mayor y genero el resultado
    //Al final las iteraciones, la variable tendr[a el valor mas alto]
     if($contador==0){
          $estiloP = $probabiliadad_total_ejercicio4[$contador];
          $estilo = $tipoEstilo[$contador]; 
     }
     
     if($probabiliadad_total_ejercicio4[$contador]> $estiloP ){
          $estiloP = $probabiliadad_total_ejercicio4[$contador];
          $estilo = $tipoEstilo[$contador]; 
     }
}

//Genero una respuesta a la interfaz
echo json_encode("Su estilo de aprendizaje es = " . $estilo);

?>