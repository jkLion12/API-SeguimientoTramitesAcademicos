<?php

require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class usuario extends conexion{
    //tabla
    private $table = "usuarios";
    private $id = "";
    private $codusuario = "";
    private $usuario = "";
    private $nombreusuario = "";
    private $contrasena = "";
    private $estado = "";
    private $nivel = "";
    private $rol = "";
    private $fechacreacion = "";

    private $token = "";
    //459808482bf351709ff42d6911b440b1

    //lista todas las personas con paginacion de 1 a 10
    public function listaUsuarios($pagina = 1){
        $cantidad = 100000;
        $inicio = ($pagina - 1) * $cantidad;

        $query = "SELECT ID, usuario, nombreusuario, codusuario, contrasena, estado, nivel, rol, fechacreacion FROM " . $this->table . " ORDER BY ID DESC LIMIT $inicio, $cantidad";
        $datos = parent::obtenerDatos($query);
        
        return $datos;
        //
    }

    //muestra los datos de una sola persona
    public function obtenerUsuario($id){
        $query = "SELECT * FROM " . $this->table . " WHERE ID = '$id'";
        //print_r($query);
        //$datos = parent::obtenerDatos($query);
        return parent::obtenerDatos($query);
    }

    //muestra los datos de un solo usuario, para busquedas
    public function buscarUsuario($codusuario){
        $query = "SELECT * FROM " . $this->table . " WHERE codusuario = '$codusuario'";
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
                if( !isset($datos['codusuario']) || !isset($datos['usuario']) || !isset($datos['nombreusuario']) || !isset($datos['contrasena']) || !isset($datos['estado']) || !isset($datos['nivel']) || !isset($datos['rol']) || !isset($datos['fechacreacion']) ){
                    return $_respuestas->error_400();
                }else{
                    $this->codusuario=$datos['codusuario'];
                    $this->usuario=$datos['usuario'];
                    $this->nombreusuario=$datos['nombreusuario'];
                    $this->contrasena=$datos['contrasena'];
                    $this->estado=$datos['estado'];
                    $this->nivel=$datos['nivel'];
                    $this->rol=$datos['rol'];
                    $this->fechacreacion=$datos['fechacreacion'];
                    
                    $resp = $this->insertarUsuario();
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
    private function insertarUsuario(){
        $query = "INSERT INTO " . $this->table . " (codusuario ,usuario, nombreusuario, contrasena, estado, nivel, rol, fechacreacion)
        values
        ('" . $this->codusuario . "','" . $this->usuario . "','" . $this->nombreusuario . "','" . $this->contrasena . "','" . $this->estado . "','" . $this->nivel . "','" . $this->rol . "','" . $this->fechacreacion . "')";
        //print_r($query);
        $resp = parent::nonQueryId($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }

    //para el metodo put
// para el metodo put
public function put($json){
    $_respuestas = new respuestas;
    $datos = json_decode($json, true);

    // Verificar si se proporciona el token
    if (!isset($datos['token'])) {
        return $_respuestas->error_401();
    } else {
        $this->token = $datos['token'];
        $arrayToken = $this->buscarToken();

        if ($arrayToken) {
            if (!isset($datos['ID'])) {
                return $_respuestas->error_400();
            } else {
                $this->id = $datos['ID'];

                // Verificar si se proporciona una nueva contrasena
                if (isset($datos['nuevacontrasena'])) {
                    $this->contrasena = $datos['nuevacontrasena'];
                } else {
                    // Si no se proporciona una nueva contrasena, conserva la contrasena existente
                    $usuarioActual = $this->obtenerUsuario($this->id);
                    if ($usuarioActual && isset($usuarioActual[0]['contrasena'])) {
                        $this->contrasena = $usuarioActual[0]['contrasena'];
                    }
                }

                // Otras actualizaciones de campos, como usuario, estado, rol, etc.
                if (isset($datos['usuario'])) {
                    $this->usuario = $datos['usuario'];
                }
                // Otras actualizaciones de campos, como usuario, estado, rol, etc.
                if (isset($datos['nombreusuario'])) {
                    $this->nombreusuario = $datos['nombreusuario'];
                }
                // Otras actualizaciones de campos, como usuario, estado, rol, etc.
                if (isset($datos['codusuario'])) {
                    $this->codusuario = $datos['codusuario'];
                }

                if (isset($datos['estado'])) {
                    $this->estado = $datos['estado'];
                }

                if (isset($datos['nivel'])) {
                    $this->nivel = $datos['nivel'];
                }

                if (isset($datos['rol'])) {
                    $this->rol = $datos['rol'];
                }
                if (isset($datos['fechacreacion'])) {
                    $this->fechacreacion = $datos['fechacreacion'];
                }

                // Agrega aquí otros campos que deseas actualizar

                $resp = $this->modificarUsuario();

                if ($resp) {
                    $respuesta = $_respuestas->response;
                    $respuesta["result"] = array(
                        "id" => $this->id
                    );
                    return $respuesta;
                } else {
                    return $_respuestas->error_500();
                }
            }
        } else {
            return $_respuestas->error_401("El Token que envió es inválido o ha caducado");
        }
    }
}

    

    ///para insertar persona
    private function modificarUsuario(){
        $query = "UPDATE " . $this->table . " SET codusuario = '" . $this->codusuario . "' , usuario = '"  . $this->usuario . "' , nombreusuario = '"  . $this->nombreusuario . "' , contrasena = '" . $this->contrasena . "' , estado = '" . $this->estado . "' , nivel = '" . $this->nivel . "' , rol = '" . $this->rol . "', fechacreacion = '" . $this->fechacreacion 
        . "'WHERE ID = '" . $this->id . "'";
                
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
    public function delete($id){
        $_respuestas = new respuestas;

        // if(!isset($datos['token'])){
        //     return $_respuestas->error_401();
        // }else{
        //     $this->token = $datos['token'];
        //     $arrayToken = $this->buscarToken();
        //     if($arrayToken){

        //     }else{
        //         return $_respuestas->error_401("El Token que envio es invalido o ha caducado");

        //     }
        // }

        if(!isset($id)){
            return $_respuestas->error_400();
        } else {
            $this->id = $id;
            
            $resp = $this->eliminarUsuario();
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

    private function eliminarUsuario(){
        $query = "DELETE FROM " . $this->table . " WHERE ID = '" . $this->id . "'";
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