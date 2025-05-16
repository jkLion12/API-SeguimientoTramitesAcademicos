<?php
require_once 'public/respuestas.class.php';
require_once 'public/plan_estudio.class.php';

$_respuestas = new respuestas;
$_plan_estudio = new plan_estudio;

if($_SERVER['REQUEST_METHOD'] == "GET"){
    //echo "hola get";

    if(isset($_GET["page"])){
        $pagina = $_GET["page"];
        $listaPlanesEstudio = $_plan_estudio ->listaPlanesEstudio($pagina);
        header("Content-Type: application/json");
        echo json_encode($listaPlanesEstudio);
        http_response_code(200);
    }
    else if(isset($_GET['plan'])){
        $plan_estudio = $_GET['plan'];
        $datosUsuario = $_plan_estudio->obtenerPlanEstudio();
        header("Content-Type: application/json");
        echo json_encode($datosUsuario);
        http_response_code(200);
    }
    else if(isset($_GET['id'])){
        $plan_estudio = $_GET['id'];
        $datosUsuario = $_plan_estudio->obtenerDataPlanEstudio($plan_estudio);
        header("Content-Type: application/json");
        echo json_encode($datosUsuario);
        http_response_code(200);
    }
    else if(isset($_GET['requisitos'])){
        $_codusuario = $_GET['requisitos'];
        $datosUsuario = $_plan_estudio->ObtenerRequisitos();
        header("Content-Type: application/json");
        echo json_encode($datosUsuario);
        http_response_code(200);
    }

}else if($_SERVER['REQUEST_METHOD'] == "POST"){
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos los datos al manejador
    $datosArray = $_plan_estudio->post($postBody);
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
    $datosArray = $_plan_estudio->put($postBody);
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
        $datosArray = $_plan_estudio->delete($_id);
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