<?php
require_once 'public/respuestas.class.php';
require_once 'public/estudiante_escuela.class.php';

$_respuestas = new respuestas;
$_estudiante_escuela = new estudiante_escuela;

if($_SERVER['REQUEST_METHOD'] == "GET"){
    //echo "hola get";

    if(isset($_GET["page"])){
        $pagina = $_GET["page"];
        $listaEstudiante_Escuela = $_estudiante_escuela ->listaEstudiante_Escuela($pagina);
        header("Content-Type: application/json");
        echo json_encode($listaEstudiante_Escuela);
        http_response_code(200);
    }else if(isset($_GET['id'])){
        $_id = $_GET['id'];
        $datos_estudiante_escuela = $_estudiante_escuela->obtenerEstudiante_Escuela($_id);
        header("Content-Type: application/json");
        echo json_encode($datos_estudiante_escuela);
        http_response_code(200);
    }else if(isset($_GET['id_estudiante_escuela'])){
        $_id_estudiante_escuela = $_GET['id_estudiante_escuela'];
        $datos_estudiante_escuela = $_estudiante_escuela->buscarExisteEstudiante_Escuela($_id_estudiante_escuela);
        header("Content-Type: application/json");
        echo json_encode($datos_estudiante_escuela);
        http_response_code(200);
    }

}else if($_SERVER['REQUEST_METHOD'] == "POST"){
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos los datos al manejador
    $datosArray = $_estudiante_escuela->post($postBody);
    //devolvemos la respuesta
    header("Content-Type: application/json");
    if(isset($datosArray["result"]["error_id"])){
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    echo json_encode($datosArray);

}else if($_SERVER['REQUEST_METHOD'] == "PUT"){
    //echo "hola put";
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos datos al manejador
    $datosArray = $_estudiante_escuela->put($postBody);
    //print_r($postBody);
    //devolvemos la respuesta
    header("Content-Type: application/json");
    if(isset($datosArray["result"]["error_id"])){
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    echo json_encode($datosArray);

}else if($_SERVER['REQUEST_METHOD'] == "DELETE"){


    // //recibimos los datos enviados
    // $postBody = file_get_contents("php://input");
    // //enviamos datos al manejador
    // $datosArray = $_persona->delete($postBody);
    // //print_r($postBody);
    // //devolvemos la respuesta
    // header("Content-Type: application/json");
    // if(isset($datosArray["result"]["error_id"])){
    //     $responseCode = $datosArray["result"]["error_id"];
    //     http_response_code($responseCode);
    // }else{
    //     http_response_code(200);
    // }
    // echo json_encode($datosArray);

    //MODIFICACION
    if(isset($_GET['id'])){
        $_id = $_GET['id'];
        $datosArray = $_estudiante_escuela->delete($_id);
        header("Content-Type: application/json");
        echo json_encode($datosArray);
        http_response_code(200);
    } else {
        $datosArray = $_respuestas->error_400();
        header("Content-Type: application/json");
        echo json_encode($datosArray);
        http_response_code(400);
    }
}else{
    // header('Content-Type: application/json');
    // $datosArray = $_respuestas->error_405();
    // echo json_decode($datosArray);
}


?>