<?php

require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class año_trabajo extends conexion{
    //tabla
    private $table = "año_trabajo";
    private $id_anio_trabajo = "";
    private $anio = "";
    private $estado = "";
    

    private $token = "";
    //459808482bf351709ff42d6911b440b1

    //lista todas los años con paginacion de 1 a 10
    public function listaAnioTrabajo($pagina = 1){
        $cantid_anio_trabajoad = 100000;
        $inicio = ($pagina - 1) * $cantid_anio_trabajoad;

        $query = "SELECT id_anio_trabajo, anio, estado FROM " . $this->table . " ORDER BY id_anio_trabajo DESC LIMIT $inicio, $cantid_anio_trabajoad";
        $datos = parent::obtenerDatos($query);
        
        return $datos;
        //
    }

    //muestra los datos de un solo año de trabajo
    public function obtenerAnioTrabajo(){
        $query = "SELECT id_anio_trabajo FROM " . $this->table . " ORDER BY id_anio_trabajo DESC LIMIT 1";
        //print_r($query);
        //$datos = parent::obtenerDatos($query);
        return parent::obtenerDatos($query);
    }

    //muestra el numero consecutivo
    public function buscarNumeroConsecutivo(){
        $query = "SELECT anio FROM " . $this->table . " ORDER BY id_anio_trabajo DESC LIMIT 1";
        //print_r($query);
        //$datos = parent::obtenerDatos($query);
        return parent::obtenerDatos($query);
    }

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
                if(!isset($datos['anio']) || !isset($datos['estado'])){
                    return $_respuestas->error_400();
                }else{
                    $this->anio=$datos['anio'];
                    $this->estado=$datos['estado'];
                    
                    $resp = $this->insertarAnioTrabajo();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_anio_trabajo" => $resp
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }                
            }else{
                return $_respuestas->error_401("El Token que envio es invalid_anio_trabajoo o ha caducado");

            }
        }
    }

    ///para insertar tramite
    private function insertarAnioTrabajo(){
        $query = "INSERT INTO " . $this->table . " (anio, estado)
        values
        ('" . $this->anio . "','" . $this->estado . "')";
        //print_r($query);
        $resp = parent::nonQueryid($query);
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
        //para la segurid_anio_trabajoad
        if(!isset($datos['token'])){
            return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if($arrayToken){
                if(!isset($datos['id_anio_trabajo'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id_anio_trabajo = $datos['id_anio_trabajo'];
                    if(isset($datos['anio'])){$this->anio=$datos['anio'];}
                    //if(isset($datos['id_anio_trabajo_carta'])){$this->id_anio_trabajo_carta=$datos['id_anio_trabajo_carta'];}
                    // if(isset($datos['id_anio_trabajo_dictamen'])){$this->id_anio_trabajo_dictamen=$datos['id_anio_trabajo_dictamen'];}
                    // if(isset($datos['id_anio_trabajo_estudiante_escuela'])){$this->id_anio_trabajo_estudiante_escuela=$datos['id_anio_trabajo_estudiante_escuela'];}      
                    if(isset($datos['estado'])){$this->estado=$datos['estado'];}      
                   // if(isset($datos['fechatramite'])){$this->fechatramite=$datos['fechatramite'];}      
                    
                    $resp = $this->modificarAnioTramite();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_anio_trabajo" => $this->id_anio_trabajo
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }
            }else{
                return $_respuestas->error_401("El Token que envio es invalid_anio_trabajoo o ha caducado");

            }
        }
    }

    ///para insertar persona
    private function modificarAnioTramite(){
        $query = "UPDATE " . $this->table . " SET anio = '" . $this->anio . "', estado = '" . $this->estado .  
        "'WHERE id_anio_trabajo = '" . $this->id_anio_trabajo . "'";
                
        //print_r($query);
        $resp = parent::nonQuery($query);
        if($resp>=1){
            return $resp;
        }else{
            return 0;
        }
    }

    

    //AQUI VA LA MODIFICACION
    ///para eliminar persona
    public function delete($id_anio_trabajo){
        $_respuestas = new respuestas;

        if(!isset($id_anio_trabajo)){
            return $_respuestas->error_400();
        } else {
            $this->id_anio_trabajo = $id_anio_trabajo;
            
            $resp = $this->eliminarAnioTrabajo();
            if($resp){
                $respuesta = $_respuestas->response;
                $respuesta["result"] = array(
                    "id_anio_trabajo" => $this->id_anio_trabajo
                );
                return $respuesta;
            } else {
                return $_respuestas->error_500();
            }
        }
    }

    private function eliminarAnioTrabajo(){
        $query = "DELETE FROM " . $this->table . " WHERE id_anio_trabajo = '" . $this->id_anio_trabajo . "'";
        $resp = parent::nonQuery($query);
        if($resp >= 1){
            return $resp;
        } else {
            return 0;
        }
    }


    //para el token
    private function buscarToken(){
        $query = "SELECT Tokenid, Usuarioid, estado from usuarios_token WHERE token = '" . $this->token . "' AND estado = 'Activo'";
        $resp = parent::obtenerDatos($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }

    private function actualizarToken($Tokenid){
        date_default_timezone_set('America/Lima');
        $date = date("Y-m-d H:i");
        $query = "UPDATE usuarios_token SET fecha = '$date' WHERE Tokenid = '$Tokenid' ";
        if($resp >= 1){
            return $resp;
        }else{
            return 0;
        }
    }

}

?>