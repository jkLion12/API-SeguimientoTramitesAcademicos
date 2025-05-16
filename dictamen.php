<?php
require_once 'public/respuestas.class.php';
require_once 'public/dictamen.class.php';

$_respuestas = new respuestas;
$_dictamen = new dictamen;

if($_SERVER['REQUEST_METHOD'] == "GET"){
    //echo "hola get";

    if(isset($_GET["page"])){
        $pagina = $_GET["page"];
        $listaDictamen = $_dictamen ->listaDictamen($pagina);
        header("Content-Type: application/json");
        echo json_encode($listaDictamen);
        http_response_code(200);
    }else if(isset($_GET['nro'])){
        //$_id = $_GET['id'];
        $datos_dictamen = $_dictamen->obtenerDictamen();
        header("Content-Type: application/json");
        echo json_encode($datos_dictamen);
        http_response_code(200);
    }
    // else if(isset($_GET['codtramite'])){
    //     $_codtramite = $_GET['codtramite'];
    //     $datos_dictamen = $_dictamen->buscarTramite($_codtramite);
    //     header("Content-Type: application/json");
    //     echo json_encode($datos_dictamen);
    //     http_response_code(200);
    // }

}else if($_SERVER['REQUEST_METHOD'] == "POST"){
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos los datos al manejador
    $datosArray = $_dictamen->post($postBody);
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
    $datosArray = $_dictamen->put($postBody);
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
        $datosArray = $_dictamen->delete($_id);
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