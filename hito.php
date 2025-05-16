<?php
require_once 'public/respuestas.class.php';
require_once 'public/hito.class.php';

$_respuestas = new respuestas;
$_hito = new hito;

if($_SERVER['REQUEST_METHOD'] == "GET"){
    //echo "hola get";

    if(isset($_GET["page"])){
        $pagina = $_GET["page"];
        $listaHito = $_hito ->listaHito($pagina);
        header("Content-Type: application/json");
        echo json_encode($listaHito);
        http_response_code(200);
    } else if(isset($_GET['id'])){
        $_id = $_GET['id'];
        $datos_hito = $_hito->obtenerHito($_id);
        header("Content-Type: application/json");
        echo json_encode($datos_hito);
        http_response_code(200);
    }
    else if(isset($_GET['rastreo'])){
        $_rastreo = $_GET['rastreo'];
        $datos_hito = $_hito->Rastreo($_rastreo);
        header("Content-Type: application/json");
        echo json_encode($datos_hito);
        http_response_code(200);
    }

    else if(isset($_GET['singleR'])){
        $_rastreo = $_GET['singleR'];
        $datos_hito = $_hito->SingleRastreo($_rastreo);
        header("Content-Type: application/json");
        echo json_encode($datos_hito);
        http_response_code(200);
    }

}else if($_SERVER['REQUEST_METHOD'] == "POST"){
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos los datos al manejador
    $datosArray = $_hito->post($postBody);
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
    $datosArray = $_hito->put($postBody);
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
        $datosArray = $_hito->delete($_id);
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