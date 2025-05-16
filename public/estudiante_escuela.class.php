<?php

require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class estudiante_escuela extends conexion{
    //tabla
    private $table = "estudiante_escuela";
    private $id_estudiante_escuela;
    private $id_estudiante;
    private $id_escuela;
    private $codigo;
    private $correo;
    //para obtener datos de escuela  yt
    //private 

    private $token = "";
    //459808482bf351709ff42d6911b440b1

    //lista todas los años con paginacion de 1 a 10
    public function listaEstudiante_Escuela($pagina = 1){
        $cantidad = 100000;
        $inicio = ($pagina - 1) * $cantidad;

        $query = "SELECT * FROM " . $this->table . " ORDER BY id_estudiante_escuela DESC LIMIT $inicio, $cantidad";
        $datos = parent::obtenerDatos($query);
        
        return $datos;
        //
    }

    //muestra los datos de un 
    public function obtenerDatos_Estudiante_Escuela($id_estudiante_escuela){
        $query = "SELECT * FROM " . $this->table . " WHERE id_estudiante_escuela = '$id_estudiante_escuela'";
        //print_r($query);
        //$datos = parent::obtenerDatos($query);
        return parent::obtenerDatos($query);
    }

    //muestra los datos de de estudiante_escuela, 
    public function buscarExisteEstudiante_Escuela($id_estudiante){
        //$query = "SELECT * FROM " . $this->table . " WHERE id_estudiante = '$id_estudiante'";
        
        $query = "SELECT estudiante_escuela.id_estudiante_escuela, estudiante_escuela.codigo ,estudiante_escuela.id_estudiante, estudiante_escuela.id_escuela, escuela.nombre
          FROM " . $this->table . "
          INNER JOIN estudiante ON " . $this->table . ".id_estudiante = estudiante.id_estudiante
          INNER JOIN escuela ON " . $this->table . ".id_escuela = escuela.id_escuela
          WHERE estudiante_escuela.id_estudiante = '$id_estudiante'";

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
                if(!isset($datos['id_estudiante']) || !isset($datos['id_escuela']) || !isset($datos['codigo']) || !isset($datos['correo'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id_estudiante=$datos['id_estudiante'];
                    $this->id_escuela=$datos['id_escuela'];
                    $this->codigo=$datos['codigo'];
                    $this->correo=$datos['correo'];
                    
                    $resp = $this->insertarEstudiante_Escuela();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_estudiante_escuela" => $resp
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
    private function insertarEstudiante_Escuela(){
        $query = "INSERT INTO " . $this->table . " (id_estudiante, id_escuela, codigo, correo)
        values
        ('" . $this->id_estudiante . "','" . $this->id_escuela .  "','" . $this->codigo . "','" . $this->correo . "')";
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
                if(!isset($datos['id_estudiante_escuela'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id_estudiante_escuela = $datos['id_estudiante_escuela'];
                    //if(isset($datos['id_anio_trabajo'])){$this->id_anio_trabajo	=$datos['id_anio_trabajo'];}
                    //if(isset($datos['id_estudiante_escuela'])){$this->id_estudiante_escuela=$datos['id_estudiante_escuela'];}
                    // if(isset($datos['id_dictamen'])){$this->id_dictamen=$datos['id_dictamen'];}
                    // if(isset($datos['id_estudiante_escuela'])){$this->id_estudiante_escuela=$datos['id_estudiante_escuela'];}      
                    //if(isset($datos['nro_correlativo'])){$this->nro_correlativo=$datos['nro_correlativo'];}      
                    //if(isset($datos['detalle'])){$this->detalle=$datos['detalle'];}      
                   // if(isset($datos['fechatramite'])){$this->fechatramite=$datos['fechatramite'];}      
                    
                    $resp = $this->modificarCarta();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_estudiante_escuela" => $this->id_estudiante_escuela
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
    private function modificarCarta(){
        $query = "UPDATE " . $this->table . " SET id_anio_trabajo = '" . $this->id_anio_trabajo . "', nro_correlativo = '" . $this->nro_correlativo . "', detalle = '" . $this->detalle .  
        "'WHERE id_estudiante_escuela = '" . $this->id_estudiante_escuela . "'";
                
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