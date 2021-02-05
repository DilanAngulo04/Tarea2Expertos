<?php

include '../../conexion.php';

//consulta del procedimiento
$sql_procedure = "CALL procedimientoEjercicio1(?, ?, ?, ?, ?, @ca, @ec, @ea, @or);";

//Probabilidades fijas
$probabilidadTipoEstilo[0] = 0.2342;
$probabilidadTipoEstilo[1] = 0.2703;
$probabilidadTipoEstilo[2] = 0.1847;
$probabilidadTipoEstilo[3] = 0.3108;

//Valores del formulario
$CA = $_POST['CA'];
$EC   = $_POST['EC'];
$EA = $_POST['EA'];
$OR = $_POST['OR'];
$estilo = "";
$estiloP = 0;

//Posibles tipos de estilos
$tipoEstilo[0] = 'Divergente';
$tipoEstilo[1] = 'Convergente';
$tipoEstilo[2] = 'Asimilador';
$tipoEstilo[3] = 'Acomodador';

//corro tantos posibles resultados haya
 for ($contador = 0; $contador < count($tipoEstilo) ; $contador++) {

    //Hago la llamada a la base de datos
      $call = mysqli_prepare($conection, $sql_procedure);
      mysqli_stmt_bind_param($call, 'iiiis', $CA, $EC, $EA, $OR, $tipoEstilo[$contador]);
      mysqli_stmt_execute($call);
 
      $sql_select = mysqli_query($conection, 'SELECT @ca, @ec, @ea, @or');
      $get_values = mysqli_fetch_assoc($sql_select);
      
      //obtengo el valor de las probabilidades
      $probabiliadCA[$contador] = $get_values['@ca'];
      $probabiliadEC[$contador] = $get_values['@ec'];
      $probabiliadEA[$contador] = $get_values['@ea'];
      $probabiliadOR[$contador] = $get_values['@or'];

       //Multiplico proabilidades por iteracion
      $probabilidadTotal[$contador] =  $probabiliadCA[$contador] * $probabiliadEC[$contador] * $probabiliadEA[$contador] * $probabiliadOR[$contador] * $probabilidadTipoEstilo[$contador];
      
      echo "Probabilidad que sea " . $tipoEstilo[$contador] . " = " . $probabiliadCA[$contador];

      //Calculo cual probabilidad es la mayor y genero el resultado
    //Al final las iteraciones, la variable tendr[a el valor mas alto]
      if($contador==0){
          $estiloP = $probabilidadTotal[0];
          $estilo = $tipoEstilo[0];
      }
      
      if($probabilidadTotal[$contador] > $estiloP ){
          $estiloP = $probabilidadTotal[$contador];
          $estilo = $tipoEstilo[$contador];
      }
 }

 //Genero una respuesta a la interfaz
 echo json_encode("Su estilo de aprendizaje es = " . $estilo);

?>