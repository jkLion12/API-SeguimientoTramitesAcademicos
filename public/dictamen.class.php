<?php

require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class dictamen extends conexion{
    //tabla
    private $table = "dictamen";
    private $id_dictamen = "";
    private $id_anio_trabajo = "";
    private $nro_correlativo = "";
    private $id_plan_estudio = "";
    private $detalle = "";
    

    private $token = "";
    //459808482bf351709ff42d6911b440b1

    //lista todas los años con paginacion de 1 a 10
    public function listaDictamen($pagina = 1){
        $cantidad = 100000;
        $inicio = ($pagina - 1) * $cantidad;

        $query = "SELECT * FROM " . $this->table . " ORDER BY id_dictamen DESC LIMIT $inicio, $cantidad";
        $datos = parent::obtenerDatos($query);
        
        return $datos;
        //
    }

    //muestra los datos de un solo año de trabajo
    public function obtenerDictamen(){
        $query = "SELECT id_dictamen, nro_correlativo FROM " . $this->table . " ORDER BY id_dictamen DESC LIMIT 1";
        //print_r($query);
        //$datos = parent::obtenerDatos($query);
        return parent::obtenerDatos($query);
    }
    //muestra los datos de una solo tramite, para busquedas
    // public function buscarAnioTrabajo($codtramite){
    //     $query = "SELECT * FROM " . $this->table . " WHERE codtramite = '$codtramite'";
    //     //print_r($query);
    //     //$datos = parent::obtenerDatos($query);
    //     return parent::obtenerDatos($query);
    //}

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
                if(!isset($datos['id_anio_trabajo']) || !isset($datos['nro_correlativo']) || !isset($datos['detalle']) || !isset($datos['id_plan_estudio'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id_anio_trabajo=$datos['id_anio_trabajo'];
                    $this->nro_correlativo=$datos['nro_correlativo'];
                    $this->detalle=$datos['detalle'];
                    $this->id_plan_estudio=$datos['id_plan_estudio'];
                    
                    $resp = $this->insertarDictamen();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_dictamen" => $resp
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
    private function insertarDictamen(){
        $query = "INSERT INTO " . $this->table . " (id_anio_trabajo, id_plan_estudio ,nro_correlativo, detalle)
        values
        ('" . $this->id_anio_trabajo . "','" . $this->id_plan_estudio . "','" . $this->nro_correlativo . "','" . $this->detalle . "')";
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
                if(!isset($datos['id_dictamen'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id_dictamen = $datos['id_dictamen'];
                    // if(isset($datos['id_anio_trabajo'])){$this->id_anio_trabajo	=$datos['id_anio_trabajo'];}
                    if(isset($datos['id_plan_estudio'])){$this->id_plan_estudio	=$datos['id_plan_estudio'];}
                    //if(isset($datos['id_dictamen'])){$this->id_dictamen=$datos['id_dictamen'];}
                    // if(isset($datos['id_dictamen'])){$this->id_dictamen=$datos['id_dictamen'];}
                    // if(isset($datos['id_estudiante_escuela'])){$this->id_estudiante_escuela=$datos['id_estudiante_escuela'];}      
                    // if(isset($datos['nro_correlativo'])){$this->nro_correlativo=$datos['nro_correlativo'];}      
                    // if(isset($datos['detalle'])){$this->detalle=$datos['detalle'];}      
                   // if(isset($datos['fechatramite'])){$this->fechatramite=$datos['fechatramite'];}      
                    
                    $resp = $this->modificarDictamen();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_dictamen" => $this->id_dictamen
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
    private function modificarDictamen(){
        $query = "UPDATE " . $this->table . " SET id_plan_estudio = '" . $this->id_plan_estudio . 
        "'WHERE id_dictamen = '" . $this->id_dictamen . "'";
                
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
    public function delete($id){
        $_respuestas = new respuestas;

        if(!isset($id)){
            return $_respuestas->error_400();
        } else {
            $this->id = $id;
            
            $resp = $this->eliminarCarta();
            if($resp){
                $respuesta = $_respuestas->response;
                $respuesta["result"] = array(
                    "id" => $this->id
                );
                return $respuesta;
            } else {
                return $_respuestas->error_500();
            }
        }
    }

    private function eliminarCarta(){
        $query = "DELETE FROM " . $this->table . " WHERE id = '" . $this->id . "'";
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