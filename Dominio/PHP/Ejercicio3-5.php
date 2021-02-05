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
$tipoProfesorE5[0] = 'Beginner';
$tipoProfesorE5[1] = 'Intermediate';
$tipoProfesorE5[2] = 'Advanced';


//consulta del procedimiento
$sql = "CALL procedimientoEjercicio5(?, ?, ?, ?, ?, ?, ?, ?, ?, @edadOutput, @generoOutput, @autoevaliacionOutput, @cvecesOutput, 
          @areaOutput, @habilidadCOutput, @habilidadWebOutput, @experienciaWebnOutput)";


//corro tantos posibles resultados haya
for ($contador = 0; $contador < 3 ; $contador ++) {

    //Hago la llamada a la base de datos
     $call = mysqli_prepare($conection, $sql);
     mysqli_stmt_bind_param($call, 'iiiiiiiis', $edad_tipo_profesor, $genero_tipo_profesor, $autoevaluacion_tipo_profesor, $total_cursos_tipo_profesor, $area_tipo_profesor, $habilidad_tipo_profesor, $experiencia_ensennanza_tipo_profesor, $experiencia_web_tipo_profesor, $tipoProfesorE5[$contador]);
     mysqli_stmt_execute($call);
     
     $select_ejercicio4 = mysqli_query($conection, 'SELECT @edadOutput, @generoOutput, @autoevaliacionOutput, @cvecesOutput,
                                             @areaOutput, @habilidadCOutput,
                                             @habilidadWebOutput, @experienciaWebnOutput');
     $get_profesor_ejercicio4 = mysqli_fetch_assoc($select_ejercicio4);
     
     //obtengo el valor de las probabilidades
     $ageP[$contador] = $get_profesor_ejercicio4['@edadOutput'];
     $genderP[$contador] = $get_profesor_ejercicio4['@generoOutput'];
     $experienceP[$contador] = $get_profesor_ejercicio4['@autoevaliacionOutput'];
     $timesP[$contador] = $get_profesor_ejercicio4['@cvecesOutput'];
     $backgroundP[$contador] = $get_profesor_ejercicio4['@areaOutput'];
     $computerP[$contador] = $get_profesor_ejercicio4['@habilidadCOutput'];
     $webToolsP[$contador] = $get_profesor_ejercicio4['@habilidadWebOutput'];
     $webUseP[$contador] = $get_profesor_ejercicio4['@experienciaWebnOutput'];
     
     //Multiplico proabilidades por iteracion
     $probabilidad_total[$contador] =  $ageP[$contador] * $genderP[$contador]  * $experienceP[$contador] * $timesP[$contador] 
     * $backgroundP[$contador] * $computerP[$contador] * $webToolsP[$contador] * $webUseP[$contador] *  $probabilityType[$contador];

     echo "Probabilidad que sea " . $tipoProfesorE5[$contador] . " = " . $probabilidad_total[$contador] . "   ";

    //Calculo cual probabilidad es la mayor y genero el resultado
    //Al final las iteraciones, la variable tendr[a el valor mas alto]
     if($contador==0){
          $tipoProfesorP = $probabilidad_total[$contador];
          $tipoProfesor = $tipoProfesorE5[$contador];
      }
      
      if($probabilidad_total[$contador] > $tipoProfesorP ){
          $tipoProfesorP = $probabilidad_total[$contador];
          $tipoProfesor = $tipoProfesorE5[$contador];
      }
        
}


//Genero una respuesta a la interfaz
echo json_encode("El tipo de profesor es = " . $tipoProfesor);


?>