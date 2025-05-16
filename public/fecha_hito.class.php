<?php

require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class fecha_hito extends conexion{
    //tabla
    private $table = "fecha_hito";
    private $id_fecha_hito = "";
    private $id_hito = "";
    private $fecha_hito_1 = "";
    private $fecha_hito_2 = "";
    private $fecha_hito_3 = "";
    private $fecha_hito_4 = "";

    private $token = "";
    //459808482bf351709ff42d6911b440b1

    //lista todas los años con paginacion de 1 a 10
    public function listaFechaHito($pagina = 1){
        $cantidad = 100000;
        $inicio = ($pagina - 1) * $cantidad;

        $query = "SELECT * FROM " . $this->table . " ORDER BY id_fecha_hito DESC LIMIT $inicio, $cantidad";
        $datos = parent::obtenerDatos($query);
        
        return $datos;
        //
    }

    //muestra los datos de un solo año de trabajo
    public function obtenerFechaHito(){
        $query = "SELECT * FROM " . $this->table . " ORDER BY id_fecha_hito DESC LIMIT 1";
        //print_r($query);
        //$datos = parent::obtenerDatos($query);
        return parent::obtenerDatos($query);
    }

    public function traerFechaHito($id){
        $query = "SELECT * FROM " . $this->table . " WHERE id_hito = '$id'";
        //print_r($query);
        //$datos = parent::obtenerDatos($query);
        return parent::obtenerDatos($query);
    }

    
    public function SingleRastreo($id){
        $query = "SELECT t.id_tramite,estu.nombre,es.nombre as escuela, fac.nombre as facultad ,dt.fechatramite, estu.sexo ,estu.dni ,estu.apellido,t.tipo,dt.estado ,ee.codigo, h.nro_hito 
        FROM " . $this->table . " h 
        INNER JOIN detalle_tramite dt ON h.id_hito = dt.id_hito 
        INNER JOIN tramites t ON dt.id_tramite = t.id_tramite 
        INNER JOIN estudiante_escuela ee ON ee.id_estudiante_escuela = t.id_estudiante_escuela 
        INNER JOIN escuela es ON ee.id_escuela = es.id_escuela 
        INNER JOIN estudiante estu ON estu.id_estudiante = ee.id_estudiante 
        INNER JOIN facultad fac ON es.id_facultad = fac.id_facultad 
        WHERE t.id_tramite = $id
        GROUP BY t.id_tramite, ee.codigo, h.nro_hito";

        // ORDER  BY t.id_tramite DESC";

        //print_r($query);
        //$datos = parent::obtenerDatos($query);
        return parent::obtenerDatos($query);

    }

    // SELECT t.id_tramite,estu.nombre, estu.apellido,t.tipo,dt.estado ,ee.codigo, h.nro_hito FROM hito h INNER JOIN detalle_tramite dt ON h.id_hito = dt.id_hito INNER JOIN tramites t ON dt.id_tramite = t.id_tramite INNER JOIN estudiante_escuela ee ON ee.id_estudiante_escuela = t.id_estudiante_escuela INNER JOIN escuela es ON ee.id_escuela = es.id_escuela INNER JOIN estudiante estu ON estu.id_estudiante = ee.id_estudiante GROUP BY t.id_tramite, ee.codigo, h.nro_hito;


    //
    public function post($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        if(!isset($datos['token'])){
            return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if($arrayToken){
                if(!isset($datos['id_hito']) || !isset($datos['fecha_hito_1']) || !isset($datos['fecha_hito_2']) || !isset($datos['fecha_hito_3']) || !isset($datos['fecha_hito_4'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id_hito=$datos['id_hito'];
                    $this->fecha_hito_1=$datos['fecha_hito_1'];
                    $this->fecha_hito_2=$datos['fecha_hito_2'];
                    $this->fecha_hito_3=$datos['fecha_hito_3'];
                    $this->fecha_hito_4=$datos['fecha_hito_4'];
                                    
                    $resp = $this->insertarFechaHito();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_fecha_hito" => $resp
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }                
            }else{
                return $_respuestas->error_401("El Token que envio es invalido o ha caducado");

            }
        }
    }

    ///para insertar tramite
    private function insertarFechaHito(){
        $query = "INSERT INTO " . $this->table . " (id_hito, fecha_hito_1, fecha_hito_2, fecha_hito_3, fecha_hito_4)
        values
        ('" . $this->id_hito . "','" . $this->fecha_hito_1 . "','" . $this->fecha_hito_2 . "','" . $this->fecha_hito_3 . "','" . $this->fecha_hito_4 . "')";
        //print_r($query);
        $resp = parent::nonQueryId($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }

    //para el metodo put
    public function put($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);
        //para la seguridad
        if(!isset($datos['token'])){
            return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if($arrayToken){
                if(!isset($datos['id_fecha_hito'])){
                    return $_respuestas->error_400();
                }else{

                    $this->id_fecha_hito = $datos['id_fecha_hito'];

                    if (isset($datos['fecha_hito_1'])) {
                        $this->fecha_hito_1 = $datos['fecha_hito_1'];
                    } else {
                        $this->fecha_hito_1 = $datos['fecha_hito_1'] ?? null;
                    }
                    if (isset($datos['fecha_hito_2'])) {
                        $this->fecha_hito_2 = $datos['fecha_hito_2'];
                    } else {
                        $this->fecha_hito_2 = $datos['fecha_hito_2'] ?? null;
                    }
                    if (isset($datos['fecha_hito_3'])) {
                        $this->fecha_hito_3 = $datos['fecha_hito_3'];
                    } else {
                        $this->fecha_hito_3 = $datos['fecha_hito_3'] ?? null;
                    }
                    if (isset($datos['fecha_hito_4'])) {
                        $this->fecha_hito_4 = $datos['fecha_hito_4'];
                    } else {
                        $this->fecha_hito_4 = $datos['fecha_hito_4'] ?? null;
                    }

                    $resp = $this->modificarFechaHito();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_fecha_hito" => $this->id_fecha_hito
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }
            }else{
                return $_respuestas->error_401("El Token que envio es invalido o ha caducado");

            }
        }
    }

    ///para insertar persona
    private function modificarFechaHito(){

        $query = "UPDATE " . $this->table . " SET fecha_hito_1 = '" . $this->fecha_hito_1 ."', fecha_hito_2 = '" . $this->fecha_hito_2 . "', fecha_hito_3 = '" . $this->fecha_hito_3 . "', fecha_hito_4 = '" . $this->fecha_hito_4 . 
        "'WHERE id_fecha_hito = '$this->id_fecha_hito'";
                
        //print_r($query);
        $resp = parent::nonQuery($query);
        if($resp>=1){
            return $resp;
        }else{
            return 0;
        }
    }

    

    //AQUI VA LA MODIFICACION
    ///para eliminar carta
    public function delete($id_fecha_hito){
        $_respuestas = new respuestas;

        if(!isset($id_fecha_hito)){
            return $_respuestas->error_400();
        } else {
            $this->id_fecha_hito = $id_fecha_hito;
            
            $resp = $this->eliminarFechaHito();
            if($resp){
                $respuesta = $_respuestas->response;
                $respuesta["result"] = array(
                    "id_fecha_hito" => $this->id_fecha_hito
                );
                return $respuesta;
            } else {
                return $_respuestas->error_500();
            }
        }
    }

    private function eliminarFechaHito(){
        $query = "DELETE FROM " . $this->table . " WHERE id_fecha_hito = '" . $this->id_fecha_hito . "'";
        $resp = parent::nonQuery($query);
        if($resp >= 1){
            return $resp;
        } else {
            return 0;
        }
    }


    //para el token
    private function buscarToken(){
        $query = "SELECT TokenId, UsuarioId, estado from usuarios_token WHERE token = '" . $this->token . "' AND estado = 'Activo'";
        $resp = parent::obtenerDatos($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }

    private function actualizarToken($tokenid){
        date_default_timezone_set('America/Lima');
        $date = date("Y-m-d H:i");
        $query = "UPDATE usuarios_token SET fecha = '$date' WHERE TokenId = '$tokenid' ";
        if($resp >= 1){
            return $resp;
        }else{
            return 0;
        }
    }

}


?>