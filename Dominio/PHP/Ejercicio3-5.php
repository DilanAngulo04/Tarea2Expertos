<?php

include_once '../../conexion.php';

//Probabilidades fijas
 $probabilityType[0] = 0.45;
 $probabilityType[1] = 0.30;
 $probabilityType[2] = 0.25;


 //Valores del formulario
$edad_tipo_profesor = $_POST['edad_tipo_profesor'];
$genero_tipo_profesor = $_POST['genero_tipo_profesor'];
$autoevaluacion_tipo_profesor = $_POST['autoevaluacion_tipo_profesor'];
$total_cursos_tipo_profesor = $_POST['total_cursos_tipo_profesor'];
$area_tipo_profesor = $_POST['area_tipo_profesor'];
$habilidad_tipo_profesor = $_POST['habilidad_tipo_profesor'];
$experiencia_ensennanza_tipo_profesor = $_POST['experiencia_ensennanza_tipo_profesor'];
$experiencia_web_tipo_profesor = $_POST['experiencia_web_tipo_profesor'];
$promedio_estilo_simple = $_POST['experiencia_web_tipo_profesor'];
$tipoProfesor = '';
$tipoProfesorP = 0;

//Posibles tipos de profesores
$tipoProfesor[0] = 'Principiante';
$tipoProfesor[1] = 'Intermedio';
$tipoProfesor[2] = 'Avanzado';


//consulta del procedimiento
$sql = "CALL procedimientoEjercicio5(?, ?, ?, ?, ?, ?, ?, ?, ?, @edadOutput, @generoOutput, @autoevaluacionOutput, @cvecesOutput, 
          @areaOutput, @habilidadCOutput, @habilidadWebOutput, @experienciaWebOutput)";


//corro tantos posibles resultados haya
for ($contador = 0; $contador < 3 ; $contador ++) {

    //Hago la llamada a la base de datos
     $call = mysqli_prepare($conn, $sql);
     mysqli_stmt_bind_param($call, 'iiiiiiiis', $edad_tipo_profesor, $genero_tipo_profesor, $autoevaluacion_tipo_profesor, $total_cursos_tipo_profesor, $area_tipo_profesor, $habilidad_tipo_profesor, $experiencia_ensennanza_tipo_profesor, $experiencia_web_tipo_profesor, $tipoProfesor[$i]);
     mysqli_stmt_execute($call);
     
     $select_ejercicio4 = mysqli_query($conn, 'SELECT @edadOutput, @generoOutput, @autoevaluacionOutput, @cvecesOutput,
                                             @areaOutput, @habilidadCOutput,
                                             @habilidadWebOutput, @experienciaWebOutput');
     $get_profesor_ejercicio4 = mysqli_fetch_assoc($select_ejercicio4);
     
     //obtengo el valor de las probabilidades
     $ageP[$contador] = $result['@edadOutput'];
     $genderP[$contador] = $result['@generoOutput'];
     $experienceP[$contador] = $result['@autoevaluacionOutput'];
     $timesP[$contador] = $result['@cvecesOutput'];
     $backgroundP[$contador] = $result['@areaOutput'];
     $computerP[$contador] = $result['@habilidadCOutput'];
     $webToolsP[$contador] = $result['@habilidadWebOutput'];
     $webUseP[$contador] = $result['@experienciaWebOutput'];
     
     //Multiplico proabilidades por iteracion
     $probabilidad_total[$contador] =  $ageP[$contador] * $genderP[$contador]  * $experienceP[$contador] * $timesP[$contador] 
     * $backgroundP[$contador] * $computerP[$contador] * $webToolsP[$contador] * $webUseP[$contador] *  $probabilityType[$contador];

     echo "Probabilidad que sea " . $tipoProfesor[$contador] . " = " . $probabilidad_total[$contador];

    //Calculo cual probabilidad es la mayor y genero el resultado
    //Al final las iteraciones, la variable tendr[a el valor mas alto]
     if($contador==0){
          $tipoProfesorP = $probabilidad_total[$contador];
          $tipoProfesor = $tipoProfesor[$contador];
      }
      
      if($probabilidad_total[$contador] > $tipoProfesorP ){
          $tipoProfesorP = $probabilidad_total[$contador];
          $tipoProfesor = $tipoProfesor[$contador];
      }
        
}


//Genero una respuesta a la interfaz
echo json_encode("El tipo de profesor es = " . $tipoProfesor);


?>