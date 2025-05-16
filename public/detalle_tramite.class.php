<?php

require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class detalle_tramite extends conexion{
    //tabla
    private $table = "detalle_tramite";
    private $id_detalle_tramite  = "";	
    private $id_tramite	= "";
    private $id_oficina  = "";	
    private $id_observacion = "";	 
    private $id_hito = "";
    private $fechatramite	  = "";
    private $estado	= "";
    private $nro_orden  = "";	
    private $verificacion  = "";	

    private $token = "";
    //459808482bf351709ff42d6911b440b1

    //lista todas las personas con paginacion de 1 a 10
    public function listaDetalle_Tramite($pagina = 1){
        $cantidad = 100000;
        $inicio = ($pagina - 1) * $cantidad;

        $query = "SELECT * FROM " . $this->table . " ORDER BY id_detalle_tramite DESC LIMIT $inicio, $cantidad";
        $datos = parent::obtenerDatos($query);
        
        return $datos;
        //
    }

    //muestra los datos de una sola persona
    public function obtenerDetalle_Tramite($id_detalle_tramite){
        $query = "SELECT * FROM " . $this->table . " WHERE id_detalle_tramite = '$id_detalle_tramite'";
        //print_r($query);
        //$datos = parent::obtenerDatos($query);
        return parent::obtenerDatos($query);
    }

    //muestra los datos de una solo tramite, para busquedas
    // public function buscarTramite($codtramite){
    //     $query = "SELECT * FROM " . $this->table . " WHERE codtramite = '$codtramite'";
    //     //print_r($query);
    //     //$datos = parent::obtenerDatos($query);
    //     return parent::obtenerDatos($query);
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
                if(!isset($datos['id_tramite']) || !isset($datos['id_oficina']) || !isset($datos['id_observacion']) || !isset($datos['id_hito']) || !isset($datos['fechatramite']) || !isset($datos['estado']) || !isset($datos['nro_orden']) || !isset($datos['verificacion'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id_tramite=$datos['id_tramite'];
                    $this->id_oficina=$datos['id_oficina'];
                    $this->id_observacion=$datos['id_observacion'];
                    $this->id_hito=$datos['id_hito'];
                    $this->fechatramite=$datos['fechatramite'];
                    $this->estado=$datos['estado'];
                    $this->nro_orden=$datos['nro_orden'];
                    $this->verificacion=$datos['verificacion'];
                    $resp = $this->insertarDetalle_Tramite();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_detalle_tramite" => $resp
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
    private function insertarDetalle_Tramite(){
        $query = "INSERT INTO " . $this->table . " (id_tramite,	id_oficina,	id_observacion,	id_hito, fechatramite, estado, nro_orden, verificacion)
        values
        ('" . $this->id_tramite . "','" . $this->id_oficina . "','" . $this->id_observacion . "','" . $this->id_hito . "','" . $this->fechatramite . "','" . $this->estado . "','" . $this->nro_orden . "','" . $this->verificacion . "')";
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
                if(!isset($datos['id_detalle_tramite'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id_detalle_tramite = $datos['id_detalle_tramite'];
                    if(isset($datos['estado'])){$this->estado=$datos['estado'];}
                    //if(isset($datos['id_carta'])){$this->id_carta=$datos['id_carta'];}
                    // if(isset($datos['id_dictamen'])){$this->id_dictamen=$datos['id_dictamen'];}
                    // if(isset($datos['id_estudiante_escuela'])){$this->id_estudiante_escuela=$datos['id_estudiante_escuela'];}      
                    if(isset($datos['verificacion'])){$this->verificacion=$datos['verificacion'];}      
                   // if(isset($datos['fechatramite'])){$this->fechatramite=$datos['fechatramite'];}      
                    
                    $resp = $this->modificarDetalle_Tramite();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_detalle_tramite" => $this->id_detalle_tramite
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
    private function modificarDetalle_Tramite(){
        $query = "UPDATE " . $this->table . " SET estado = '" . $this->estado . "', verificacion = '" . $this->verificacion .  
        "'WHERE id_detalle_tramite = '" . $this->id_detalle_tramite . "'";
                
        //print_r($query);
        $resp = parent::nonQuery($query);
        if($resp>=1){
            return $resp;
        }else{
            return 0;
        }
    }

    ///para eliminar persona
    // public function delete($json){
    //     $_respuestas = new respuestas;
    //     $datos = json_decode($json,true);
    //     if(!isset($datos['ID'])){
    //         return $_respuestas->error_400();
    //     }else{
    //         $this->personaid = $datos['ID'];
            
    //         $resp = $this->elinimarPersona();
    //         if($resp){
    //             $respuesta = $_respuestas->response;
    //             $respuesta["result"] = array(
    //                 "personaid" => $this->personaid
    //             );
    //             return $respuesta;
    //         }else{
    //             return $_respuestas->error_500();
    //         }
    //     }
    // }

    // private function elinimarPersona(){
    //     $query = "DELETE FROM " . $this->table . " WHERE ID= '" . $this->personaid . "'";
    //     $resp = parent::nonQuery($query);
    //     if($resp >= 1){
    //         return $resp;
    //     }else{
    //         return 0;
    //     }
    // }


    //AQUI VA LA MODIFICACION
    ///para eliminar persona
    public function delete($id_tramite){
        $_respuestas = new respuestas;

        if(!isset($id_tramite)){
            return $_respuestas->error_400();
        } else {
            $this->id_tramite = $id_tramite;
            
            $resp = $this->eliminarTramite();
            if($resp){
                $respuesta = $_respuestas->response;
                $respuesta["result"] = array(
                    "id_tramite" => $this->id_tramite
                );
                return $respuesta;
            } else {
                return $_respuestas->error_500();
            }
        }
    }

    private function eliminarTramite(){
        $query = "DELETE FROM " . $this->table . " WHERE id_tramite = '" . $this->id_tramite . "'";
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