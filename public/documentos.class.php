<?php

require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class documentos extends conexion{
    
    //tabla
    private $table = "documentos";

    private $id	= "";
    private $id_dictamen	= "";
    private $nombre	= "";
    private $descripcion= "";
    

    private $token = "";
    //459808482bf351709ff42d6911b440b1

    //lista todas los años con paginacion de 1 a 10
    public function ListaDocumentos($pagina = 1){
        $cantidad = 100000;
        $inicio = ($pagina - 1) * $cantidad;

        $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC LIMIT $inicio, $cantidad";
        $datos = parent::obtenerDatos($query);
        
        return $datos;
        //
    }
    // public function MostrarDocumento($id){
    //     $query = "SELECT * FROM " . $this->table . " WHERE id";
    //     $datos = parent::obtenerDatos($query);
        
    //     return $datos;
    //     //
    // }

    public function obtenerDocumento($id_dictamen){
        $query = "SELECT * FROM documentos WHERE id_dictamen = $id_dictamen ORDER BY id DESC";
        //print_r($query);
        $datos = parent::obtenerDatos($query);
        return $datos;
    }
    // public function obtenerDataPlanEstudio($id){
    //     $query = "SELECT * FROM plan_estudio WHERE id = $id";
    //     //print_r($query);
    //     $datos = parent::obtenerDatos($query);
    //     return $datos;
    // }
    // //muestra los datos de una solo tramite, para busquedas
    // public function ObtenerRequisitos(){
    //     $query = "SELECT * FROM requisitos";
    //     //print_r($query);
    //     $datos = parent::obtenerDatos($query);
    //     return $datos;
    // }

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
                if(!isset($datos['id_dictamen']) || !isset($datos['nombre']) || !isset($datos['descripcion']) ){
                    return $_respuestas->error_400();
                }else{
                    $this->id_dictamen=$datos['id_dictamen'];
                    $this->nombre=$datos['nombre'];
                    $this->descripcion=$datos['descripcion'];
                    
                    $resp = $this->insertarPlanEstudio();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id" => $resp
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
    private function insertarPlanEstudio(){
        $query = "INSERT INTO " . $this->table . " (id_dictamen, nombre, descripcion)
        values
        ('" . $this->id_dictamen . "','"  . $this->nombre . "','" . $this->descripcion . "')";
        //print_r($query);
        $resp = parent::nonQueryId($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }

    //para el metodo put actualizar
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
                if(!isset($datos['id'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id = $datos['id'];
                    if(isset($datos['nombre'])){$this->nombre	=$datos['nombre'];}
                    if(isset($datos['descripcion'])){$this->descripcion	=$datos['descripcion'];}
                   // if(isset($datos['descripciontramite'])){$this->descripciontramite=$datos['descripciontramite'];}      
                   // if(isset($datos['descripciontramite'])){$this->descripciontramite=$datos['descripciontramite'];}      
                    
                    $resp = $this->modificarDocumento();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id" => $this->id
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

    ///para insertar actualizar
    private function modificarDocumento(){
        $query = "UPDATE " . $this->table . " SET nombre = '" . $this->nombre . "', descripcion = '" . $this->descripcion .   
        "'WHERE id = '" . $this->id . "'";
                
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
            
            $resp = $this->eliminarPlan();
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

    private function eliminarPlan(){
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
        $query = "UPDATE usuarios_token SET descripcion = '$date' WHERE TokenId = '$tokenid' ";
        if($resp >= 1){
            return $resp;
        }else{
            return 0;
        }
    }


}

?>