<?php

include_once '../../conexion.php';

//Probabilidades fijas
$probabilidadGenero[0] = 0.1711;
$probabilidadGenero[1] = 0.8289;

//Posibles generos
$posiblegenero[0] = "Femenino";
$posiblegenero[1] = "Masculino";

//Valores del formulario
$estilo_ejercicio3 = $_POST['estilo_genero'];
$promedio_ejercicio3 = $_POST['promedio_genero'];
$recinto_ejercicio3 = $_POST['recinto_genero'];

//consulta del procedimiento
$sql_procedimiento_ejercicio_3 = "CALL procedimientoEjercicio3(?, ?, ?, ?, @recintoOutput, @promedioOutput, @estiloOutput)";
    
//corro tantos posibles resultados haya
for ($contador = 0; $contador < count($posiblegenero) ; $contador++) {

     //Hago la llamada a la base de datos
     $call = mysqli_prepare($conection, $sql_procedimiento_ejercicio_3);
     mysqli_stmt_bind_param($call, 'ssss', $recinto_ejercicio3, $promedio_ejercicio3, $estilo_ejercicio3, $posiblegenero[$contador]);
     mysqli_stmt_execute($call);
     
     $select_generos_ejercicio3 = mysqli_query($conection, 'SELECT @recintoOutput, @promedioOutput, @estiloOutput');
     $get_generos_ejercicio3 = mysqli_fetch_assoc($select_generos_ejercicio3);
     
    
     //obtengo el valor de las probabilidades
     $recinto_probabilidad[$contador] = $get_generos_ejercicio3['@recintoOutput'];
     $promedio_probabilidad[$contador] = $get_generos_ejercicio3['@promedioOutput'];
     $estilo_probabilidad[$contador] = $get_generos_ejercicio3['@estiloOutput'];
     
      //Multiplico proabilidades por iteracion
     $probabilidad_total_ejercicio3[$contador] =  $recinto_probabilidad[$contador] * $promedio_probabilidad[$contador] * $estilo_probabilidad[$contador] * $probabilidadGenero[$contador];
     
     echo "Probabilidad que sea " . $posiblegenero[$contador] . " = " . $probabilidad_total_ejercicio3[$contador];

}

//Envio una respuesta
if($probabilidad_total_ejercicio3[0]>$probabilidad_total_ejercicio3[1]){
     echo json_encode("Femenino");
}else{
     echo json_encode("Masculino");
}


?>