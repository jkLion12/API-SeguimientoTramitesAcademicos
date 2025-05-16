<?php
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class auth extends conexion{
    public function login($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);
    
        if(!isset($datos['usuario']) || !isset($datos["password"])){
            //error con los campos
            return $_respuestas->error_400();
        } else {
            //todo está bien
            $usuario = $datos['usuario'];
            $password = $datos['password'];
            //$password = parent::encriptar($password);
            $datos = $this->obtenerDatosUsuario($usuario);
            if($datos){
                //si existe usuario
                //verificar si la contraseña es igual
                if($password == $datos[0]['contrasena']){
                    if($datos[0]['estado'] == "Activo"){
                        //obtiene el rol del usuario
                        $rol = $datos[0]['rol'];
                        $codusuario = $datos[0]['codusuario'];
    
                        // Verificar si el usuario ya tiene un token
                        $token = $this->obtenerToken($datos[0]['ID']);
    
                        if(!$token) {
                            // Si el usuario no tiene un token, crear uno nuevo
                            $token = $this->insertarToken($datos[0]['ID']);
                        }
    
                        if($token){
                            //si se guardó el token correctamente
                            $result = $_respuestas->response;
                            $result["result"] = array(
                                "token" => $token,
                                "rol" => $rol,
                                "codusuario" => $codusuario
                            );
                            return $result;
                        }else{
                            //error al guardar o obtener el token
                            return $_respuestas->error_500("Error interno, No hemos podido obtener/guardar el token");
                        }
                    } else {
                        //el usuario está inactivo
                        return $_respuestas->error_200("el usuario está inactivo");
                    }
                } else {
                    //la contraseña no es igual
                    return $_respuestas->error_200("el password es inválido");
                }
            } else {
                //no existe el usuario
                return $_respuestas->error_200("El usuario $usuario no existe ");
            }
        }
    }
    

    //para obtener los usuarios
    private function obtenerDatosUsuario($correo){
        $query = "SELECT ID,usuario,contrasena,codusuario,estado, rol FROM usuarios WHERE usuario = '$correo' ";
        $datos = parent::obtenerDatos($query);
        if(isset($datos[0]["ID"])){
            return $datos;
        }else{
            return 0;
        }
    }

    private function insertarToken($id){
        $val = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16,$val));
        //ESTABLECEMOS LA ZONA HORARIA DE PERU
        date_default_timezone_set('America/Lima');
        $date = date("Y-m-d H:i");
        $estado = "Activo";
        $query = "INSERT INTO usuarios_token (UsuarioId, token, estado,fecha) VALUES('$id','$token', '$estado', '$date')";
        $verifica = parent::nonQuery($query);
        if($verifica){
            return $token;
        }else{
            return 0;
        }
    }

    private function obtenerToken($codusuario){
        $query = "SELECT token FROM usuarios_token WHERE UsuarioId = '$codusuario' AND estado = 'Activo'";
        $datos = parent::obtenerDatos($query);
        if(isset($datos[0]["token"])){
            return $datos[0]["token"];
        } else {
            return false;
        }
    }
}
?>