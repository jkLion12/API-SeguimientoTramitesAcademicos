<?php
require_once 'public/respuestas.class.php';
require_once 'public/tramites.class.php';

$_respuestas = new respuestas;
$_tramite = new tramites;

if($_SERVER['REQUEST_METHOD'] == "GET"){
    //echo "hola get";

    if(isset($_GET["page"])){
        $pagina = $_GET["page"];
        $listaTramites = $_tramite ->listaTramites($pagina);
        header("Content-Type: application/json");
        echo json_encode($listaTramites);
        http_response_code(200);
    }else if(isset($_GET['id'])){
        $_id = $_GET['id'];
        $datosTramite = $_tramite->obtenerTramite($_id);
        header("Content-Type: application/json");
        echo json_encode($datosTramite);
        http_response_code(200);
    }
    else if(isset($_GET['codtramite'])){
        $_codtramite = $_GET['codtramite'];
        $datosTramite = $_tramite->buscarTramite($_codtramite);
        header("Content-Type: application/json");
        echo json_encode($datosTramite);
        http_response_code(200);
    }
    else if(isset($_GET['tramite'])){
        //$_codtramite = $_GET['codtramite'];
        $datosTramite = $_tramite->idTramite();
        header("Content-Type: application/json");
        echo json_encode($datosTramite);
        http_response_code(200);
    }
    else if(isset($_GET['mostrar_tramite'])){
        $_id = $_GET['mostrar_tramite'];
        $datosTramite = $_tramite->mostrarTramite($_id);
        header("Content-Type: application/json");
        echo json_encode($datosTramite);
        http_response_code(200);
    }
    else if(isset($_GET['nrocarta'])){
        $_id = $_GET['nrocarta'];
        $datosTramite = $_tramite->getNroCarta($_id);
        header("Content-Type: application/json");
        echo json_encode($datosTramite);
        http_response_code(200);
    }
    else if(isset($_GET['nrodictamen'])){
        $_id = $_GET['nrodictamen'];
        $datosTramite = $_tramite->getNroDictamen($_id);
        header("Content-Type: application/json");
        echo json_encode($datosTramite);
        http_response_code(200);
    }
    else if(isset($_GET['tramitefecha'])){
        $_tramitefecha = $_GET['tramitefecha'];
        $datosTramite = $_tramite->tramitesMes($_tramitefecha);
        header("Content-Type: application/json");
        echo json_encode($datosTramite);
        http_response_code(200);
    }
    else if(isset($_GET['tipotramite'])){
        $_tramitefecha = $_GET['tipotramite'];
        $datosTramite = $_tramite->TipoTramiteMes($_tramitefecha);
        header("Content-Type: application/json");
        echo json_encode($datosTramite);
        http_response_code(200);
    }
    else if(isset($_GET['porcentajetramite'])){
        $_tramitefecha = $_GET['porcentajetramite'];
        $datosTramite = $_tramite->porcentaje($_tramitefecha);
        header("Content-Type: application/json");
        echo json_encode($datosTramite);
        http_response_code(200);
    }else if(isset($_GET['radartramites'])){
        $_tramitefecha = $_GET['radartramites'];
        $datosTramite = $_tramite->radar($_tramitefecha);
        header("Content-Type: application/json");
        echo json_encode($datosTramite);
        http_response_code(200);
    }else if(isset($_GET['cantidad_tipo'])){
        $_tramitefecha = $_GET['cantidad_tipo'];
        $datosTramite = $_tramite->cantidad_tipo($_tramitefecha);
        header("Content-Type: application/json");
        echo json_encode($datosTramite);
        http_response_code(200);
    }

}else if($_SERVER['REQUEST_METHOD'] == "POST"){
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos los datos al manejador
    $datosArray = $_tramite->post($postBody);
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
    $datosArray = $_tramite->put($postBody);
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
        $datosArray = $_tramite->delete($_id);
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