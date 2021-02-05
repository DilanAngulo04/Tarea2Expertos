<?php

include '../../conexion.php';


$sql_procedure = "CALL procedimientoEjercicio1(?, ?, ?, ?, ?, @ca, @ec, @ea, @or);";


//Obtengo el porcentaje de Divergente en relacion a los datos
$probabilidadTipoEstilo[0] = 0.2342;
//Obtengo el porcentaje de Convergente en relacion a los datos
$probabilidadTipoEstilo[1] = 0.2703;
//Obtengo el porcentaje de Asimilador en relacion a los datos
$probabilidadTipoEstilo[2] = 0.1847;
//Obtengo el porcentaje de Acomodador en relacion a los datos
$probabilidadTipoEstilo[3] = 0.3108;


$CA = $_POST['CA'];
$EC   = $_POST['EC'];
$EA = $_POST['EA'];
$OR = $_POST['OR'];
$estilo = "";
$estiloP = 0;

// clases a las que puede pertenecer la entrada
$tipoEstilo[0] = 'Divergente';
$tipoEstilo[1] = 'Convergente';
$tipoEstilo[2] = 'Asimilador';
$tipoEstilo[3] = 'Acomodador';

//Recorro el arreglo  
 for ($contador = 0; $contador < count($tipoEstilo) ; $contador++) {

      //Hago la primera llamada a la base de datos para definir el procedimiento almacenado
      $call = mysqli_prepare($conection, $sql_procedure);
      mysqli_stmt_bind_param($call, 'iiiis', $CA, $EC, $EA, $OR, $tipoEstilo[$contador]);
      mysqli_stmt_execute($call);
 
      //extrae los datos
      $sql_select = mysqli_query($conection, 'SELECT @ca, @ec, @ea, @or');
      $get_values = mysqli_fetch_assoc($sql_select);
      
      // almacenan las probabilidades de
      $probabiliadCA[$contador] = $get_values['@ca'];
      $probabiliadEC[$contador] = $get_values['@ec'];
      $probabiliadEA[$contador] = $get_values['@ea'];
      $probabiliadOR[$contador] = $get_values['@or'];

      $probabilidadTotal[$contador] =  $probabiliadCA[$contador] * $probabiliadEC[$contador] * $probabiliadEA[$contador] * $probabiliadOR[$contador] * $probabilidadTipoEstilo[$contador];
      
      echo "Probabilidad que sea " . $tipoEstilo[$contador] . " = " . $probabiliadCA[$contador];

      // saca la probabilidad más alta entre todas
      if($contador==0){
          $estiloP = $probabilidadTotal[0];
          $estilo = $tipoEstilo[0];
      }
      
      if($probabilidadTotal[$contador] > $estiloP ){
          $estiloP = $probabilidadTotal[$contador];
          $estilo = $tipoEstilo[$contador];
      }
 }

//Envio una respuesta
//En este caso, el estilo evaluado

 echo json_encode("Su estilo de aprendizaje es = " . $estilo);

?>