<?php
require_once 'public/respuestas.class.php';
require_once 'public/estudiante.class.php';

$_respuestas = new respuestas;
$_estudiante = new estudiante;

if($_SERVER['REQUEST_METHOD'] == "GET"){
    //echo "hola get";

    if(isset($_GET["page"])){
        $pagina = $_GET["page"];
        $listaEstudiantes = $_estudiante ->listaEstudiantes($pagina);
        header("Content-Type: application/json");
        echo json_encode($listaEstudiantes);
        http_response_code(200);
    }else if(isset($_GET['id'])){
        $_id = $_GET['id'];
        $datosEstudiante = $_estudiante->obtenerEstudiante($_id);
        header("Content-Type: application/json");
        echo json_encode($datosEstudiante);
        http_response_code(200);
    }else if(isset($_GET['dni'])){
        $_dni = $_GET['dni'];
        $datosEstudiante = $_estudiante->obtenerIdEstudiante($_dni);
        header("Content-Type: application/json");
        echo json_encode($datosEstudiante);
        http_response_code(200);
    }
    else if(isset($_GET['id_estudiante'])){
        $_id_estudiante = $_GET['id_estudiante'];
        $datosEstudiante = $_estudiante->datosProgreso($_id_estudiante);
        header("Content-Type: application/json");
        echo json_encode($datosEstudiante);
        http_response_code(200);
    }
    else if(isset($_GET['pagina'])){
        $_pagina = $_GET['pagina'];
        $datosEstudiante = $_estudiante->listaContacts($_pagina);
        header("Content-Type: application/json");
        echo json_encode($datosEstudiante);
        http_response_code(200);
    }
    else if(isset($_GET['id_student'])){
        $_id_student = $_GET['id_student'];
        $datosEstudiante = $_estudiante->singleStudent($_id_student);
        header("Content-Type: application/json");
        echo json_encode($datosEstudiante);
        http_response_code(200);
    }

}else if($_SERVER['REQUEST_METHOD'] == "POST"){
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos los datos al manejador
    $datosArray = $_estudiante->post($postBody);
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
    $datosArray = $_estudiante->put($postBody);
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
        $datosArray = $_estudiante->delete($_id);
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