<?php
require_once 'public/respuestas.class.php';
require_once 'public/fecha_hito.class.php';

$_respuestas = new respuestas;
$_fecha_hito = new fecha_hito;

if($_SERVER['REQUEST_METHOD'] == "GET"){
    //echo "hola get";

    if(isset($_GET["page"])){
        $pagina = $_GET["page"];
        $listafechaHito = $_fecha_hito ->listaFechaHito($pagina);
        header("Content-Type: application/json");
        echo json_encode($listafechaHito);
        http_response_code(200);
    } else if(isset($_GET['id'])){
        $_id = $_GET['id'];
        $datos_fecha_hito = $_fecha_hito->traerFechaHito($_id);
        header("Content-Type: application/json");
        echo json_encode($datos_fecha_hito);
        http_response_code(200);
    }
    else if(isset($_GET['rastreo'])){
        $_rastreo = $_GET['rastreo'];
        $datos_fecha_hito = $_fecha_hito->Rastreo($_rastreo);
        header("Content-Type: application/json");
        echo json_encode($datos_fecha_hito);
        http_response_code(200);
    }
    else if(isset($_GET['singleR'])){
        $_rastreo = $_GET['singleR'];
        $datos_fecha_hito = $fecha_hito->SingleRastreo($_rastreo);
        header("Content-Type: application/json");
        echo json_encode($datos_fecha_hito);
        http_response_code(200);
    }

}else if($_SERVER['REQUEST_METHOD'] == "POST"){
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos los datos al manejador
    $datosArray = $_fecha_hito->post($postBody);
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
    $datosArray = $_fecha_hito->put($postBody);
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

    //MODIFICACION
    if(isset($_GET['id'])){
        $_id = $_GET['id'];
        $datosArray = $_fecha_hito->delete($_id);
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