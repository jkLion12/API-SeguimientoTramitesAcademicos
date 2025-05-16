<?php

require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class estudiante extends conexion{
    //tabla
    private $table = "estudiante";    
    private $id_estudiante;
    //private $codigo;
    private $nombre;
    private $apellido;
    private $dni;
    private $direccion;
    private $nrocelular;
    private $sexo;
    // private $correo;

    private $token = "";
    //459808482bf351709ff42d6911b440b1

    //lista todas las personas con paginacion de 1 a 10
    public function listaEstudiantes($pagina = 1){
        $cantidad = 100000;
        $inicio = ($pagina - 1) * $cantidad;

        // $query = "SELECT es.id_estudiante, es.nombre, es.apellido, es.dni, es.direccion, es.nrocelular, 
        // CASE es.sexo
        //     WHEN 1 THEN 'Masculino'
        //     WHEN 2 THEN 'Femenino'
        //     ELSE 'Otro'
        // END AS sexo,
        // ee.codigo ,ee.correo, esc.Nombre as escuela FROM " . $this->table . " es
        // INNER JOIN estudiante_escuela ee ON es.id_estudiante = ee.id_estudiante
        // INNER JOIN escuela esc ON esc.id_escuela = ee.id_escuela
        // ORDER BY id_estudiante DESC LIMIT $inicio, $cantidad";
        $query = "SELECT es.id_estudiante, es.nombre, es.apellido, es.nrocelular, es.direccion, 
        CASE es.sexo
            WHEN 1 THEN 'Masculino'
            WHEN 2 THEN 'Femenino'
            ELSE 'Otro'
        END AS sexo,
        ee.codigo FROM " . $this->table . " es
        INNER JOIN estudiante_escuela ee ON es.id_estudiante = ee.id_estudiante
        INNER JOIN escuela esc ON esc.id_escuela = ee.id_escuela
        ORDER BY id_estudiante DESC LIMIT $inicio, $cantidad";
        $datos = parent::obtenerDatos($query);
        
        return $datos;
        //
    }
    public function listaContacts($pagina = 1){
        $cantidad = 100000;
        $inicio = ($pagina - 1) * $cantidad;

        $query = "SELECT es.id_estudiante, es.nombre, es.apellido, es.dni, es.direccion, es.nrocelular, es.sexo FROM " . $this->table . " es


        ORDER BY id_estudiante DESC LIMIT $inicio, $cantidad";
        $datos = parent::obtenerDatos($query);
        
        return $datos;
        //
    }

    //detalle de un solo estudiante
    public function singleStudent($id_student){
        
        $query = "SELECT es.id_estudiante, es.nombre, es.apellido, es.dni, es.direccion, es.nrocelular, 
        CASE es.sexo
            WHEN 1 THEN 'Masculino'
            WHEN 2 THEN 'Femenino'
            ELSE 'Otro'
        END AS sexo,
        ee.codigo ,ee.correo, esc.Nombre as escuela, fac.nombre as facultad  FROM estudiante es
        INNER JOIN estudiante_escuela ee ON es.id_estudiante = ee.id_estudiante
        INNER JOIN escuela esc ON esc.id_escuela = ee.id_escuela
        INNER JOIN facultad fac ON esc.id_facultad = fac.id_facultad
        WHERE es.id_estudiante = '$id_student'";
        // $query = "SELECT * FROM " . $this->table . " WHERE id_estudiante = '$id_student'";

        return parent::obtenerDatos($query);
        //
    }

    //muestra los datos de una sola persona
    public function obtenerEstudiante($id_estudiante){
        $query = "SELECT * FROM " . $this->table . " WHERE id_estudiante = '$id_estudiante'";
        //print_r($query);
        //$datos = parent::obtenerDatos($query);
        return parent::obtenerDatos($query);
    }
    public function obtenerIdEstudiante($dni){
        $query = "SELECT id_estudiante FROM " . $this->table . " WHERE dni = '$dni'";
        //print_r($query);
        //$datos = parent::obtenerDatos($query);
        return parent::obtenerDatos($query);
    }

    public function datosProgreso(){
        $query = "SELECT tra.id_tramite, es.nombre, es.apellido, es.sexo, dt.estado, h.nro_hito   FROM " . $this->table . " es
        INNER JOIN estudiante_escuela ee ON es.id_estudiante = ee.id_estudiante
        INNER JOIN tramites tra ON ee.id_estudiante_escuela = tra.id_estudiante_escuela
        INNER JOIN detalle_tramite dt ON tra.id_tramite = dt.id_tramite
        INNER JOIN hito h ON dt.id_hito = h.id_hito
        ORDER  BY tra.id_tramite DESC";
        //print_r($query);
        //$datos = parent::obtenerDatos($query);
        return parent::obtenerDatos($query);
    }

    //muestra los datos de una solo tramite, para busquedas
    // public function buscarEstudiante($codigo){
    //     $query = "SELECT * FROM " . $this->table . " WHERE codigo = '$codigo'";
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
                if(!isset($datos['nombre']) || !isset($datos['apellido']) || !isset($datos['dni']) || !isset($datos['direccion']) || !isset($datos['nrocelular']) || !isset($datos['sexo'])){
                    return $_respuestas->error_400();
                }else{
                    //$this->codigo=$datos['codigo'];
                    $this->nombre=$datos['nombre'];
                    $this->apellido=$datos['apellido'];
                    $this->dni=$datos['dni'];
                    $this->direccion=$datos['direccion'];
                    $this->nrocelular=$datos['nrocelular'];
                    $this->sexo=$datos['sexo'];
                    $resp = $this->insertarEstudiante();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_estudiante" => $resp
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
    private function insertarEstudiante(){
        $query = "INSERT INTO " . $this->table . " (nombre, apellido, dni, direccion, nrocelular, sexo)
        values
        ('" . $this->nombre . "','" . $this->apellido . "','" . $this->dni . "','" . $this->direccion . "','" . $this->nrocelular . "','" . $this->sexo . "')";
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
                if(!isset($datos['id_estudiante'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id_estudiante = $datos['id_estudiante'];
                    //if(isset($datos['codigo'])){$this->codigo=$datos['codigo'];}
                    //if(isset($datos['nombre'])){$this->nombre=$datos['nombre'];}
                    // if(isset($datos['apellido'])){$this->apellido=$datos['apellido'];}
                    // if(isset($datos['correo'])){$this->correo=$datos['correo'];}      
                    if(isset($datos['direccion'])){$this->direccion=$datos['direccion'];}      
                    if(isset($datos['nrocelular'])){$this->nrocelular=$datos['nrocelular'];}      
                   // if(isset($datos['fechatramite'])){$this->fechatramite=$datos['fechatramite'];}      
                    
                    $resp = $this->modificarEstudiante();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_estudiante" => $this->id_estudiante
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
    private function modificarEstudiante(){
        $query = "UPDATE " . $this->table . " SET direccion = '" . $this->direccion . "', nrocelular = '" . $this->nrocelular .  
        "'WHERE id_estudiante = '" . $this->id_estudiante . "'";
                
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
    public function delete($id_estudiante){
        $_respuestas = new respuestas;

        if(!isset($id_estudiante)){
            return $_respuestas->error_400();
        } else {
            $this->id_estudiante = $id_estudiante;
            
            $resp = $this->eliminarTramite();
            if($resp){
                $respuesta = $_respuestas->response;
                $respuesta["result"] = array(
                    "id_estudiante" => $this->id_estudiante
                );
                return $respuesta;
            } else {
                return $_respuestas->error_500();
            }
        }
    }

    private function eliminarTramite(){
        $query = "DELETE FROM " . $this->table . " WHERE id_estudiante = '" . $this->id_estudiante . "'";
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