<?php
require_once 'public/respuestas.class.php';
require_once 'public/observacion.class.php';

$_respuestas = new respuestas;
$_observacion = new Observacion;

if($_SERVER['REQUEST_METHOD'] == "GET"){
    //echo "hola get";

    if(isset($_GET["page"])){
        $pagina = $_GET["page"];
        $listaObservacion = $_observacion ->listaObservacion($pagina);
        header("Content-Type: application/json");
        echo json_encode($listaObservacion);
        http_response_code(200);
    } else if(isset($_GET['id'])){
        $_id = $_GET['id'];
        $datos_observacion = $_observacion->obtenerObservacion($_id);
        header("Content-Type: application/json");
        echo json_encode($datos_observacion);
        http_response_code(200);
    }
    else if(isset($_GET['id_tramite'])){
        $_id_tramite = $_GET['id_tramite'];
        $datos_observacion = $_observacion->traerObservacion($_id_tramite);
        header("Content-Type: application/json");
        echo json_encode($datos_observacion);
        http_response_code(200);
    }
    else if(isset($_GET['id_observacion'])){
        $_id = $_GET['id_observacion'];
        $datos_observacion = $_observacion->verObservacion($_id);
        header("Content-Type: application/json");
        echo json_encode($datos_observacion);
        http_response_code(200);
    }

}else if($_SERVER['REQUEST_METHOD'] == "POST"){
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos los datos al manejador
    $datosArray = $_observacion->post($postBody);
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
    $datosArray = $_observacion->put($postBody);
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
        $datosArray = $_observacion->delete($_id);
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