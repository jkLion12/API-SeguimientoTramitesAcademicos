<?php

require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class plan_estudio extends conexion{
    
    //tabla
    private $table = "plan_estudio";

    private $id_plan_estudio	= "";
    private $id_escuela	= "";
    private $fecha	= "";
    private $nombre	= "";
    private $estudios_generales	= "";
    private $estudios_esfecificos	= "";
    private $estudios_especialidad	= "";
    private $practicas_preprofecionales	= "";
    private $total= "";
    

    private $token = "";
    //459808482bf351709ff42d6911b440b1

    //lista todas los años con paginacion de 1 a 10
    public function listaPlanesEstudio($pagina = 1){
        $cantidad = 100000;
        $inicio = ($pagina - 1) * $cantidad;

        $query = "SELECT * FROM " . $this->table . " ORDER BY id_plan_estudio DESC LIMIT $inicio, $cantidad";
        $datos = parent::obtenerDatos($query);
        
        return $datos;
        //
    }

    //muestra los datos de un solo año de trabajo
    public function obtenerPlanEstudio(){
        $query = "SELECT * FROM plan_estudio";
        //print_r($query);
        $datos = parent::obtenerDatos($query);
        return $datos;
    }
    public function obtenerDataPlanEstudio($id){
        $query = "SELECT * FROM plan_estudio WHERE id_plan_estudio = $id";
        //print_r($query);
        $datos = parent::obtenerDatos($query);
        return $datos;
    }
    //muestra los datos de una solo tramite, para busquedas
    public function ObtenerRequisitos(){
        $query = "SELECT * FROM requisitos";
        //print_r($query);
        $datos = parent::obtenerDatos($query);
        return $datos;
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
                if(!isset($datos['id_escuela']) || !isset($datos['fecha']) || !isset($datos['nombre']) || !isset($datos['estudios_generales']) || !isset($datos['estudios_esfecificos']) || !isset($datos['estudios_especialidad']) || !isset($datos['practicas_preprofecionales']) || !isset($datos['total'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id_escuela=$datos['id_escuela'];
                    $this->nombre=$datos['nombre'];
                    $this->fecha=$datos['fecha'];
                    $this->estudios_generales=$datos['estudios_generales'];
                    $this->estudios_esfecificos=$datos['estudios_esfecificos'];
                    $this->estudios_especialidad=$datos['estudios_especialidad'];
                    $this->practicas_preprofecionales=$datos['practicas_preprofecionales'];
                    $this->total=$datos['total'];
                    
                    $resp = $this->insertarPlanEstudio();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_plan_estudio" => $resp
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
        $query = "INSERT INTO " . $this->table . " (id_escuela, nombre, fecha,estudios_generales, estudios_esfecificos, estudios_especialidad, practicas_preprofecionales, total)
        values
        ('" . $this->id_escuela . "','"  . $this->nombre . "','" . $this->fecha . "','" . $this->estudios_generales . "','" . $this->estudios_esfecificos . "','" . $this->estudios_especialidad . "','" . $this->practicas_preprofecionales . "','" . $this->total . "')";
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
                if(!isset($datos['id_plan_estudio'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id_plan_estudio = $datos['id_plan_estudio'];
                    if(isset($datos['id_escuela'])){$this->id_escuela	=$datos['id_escuela'];}
                    if(isset($datos['nombre'])){$this->nombre	=$datos['nombre'];}
                    if(isset($datos['fecha'])){$this->fecha	=$datos['fecha'];}
                    if(isset($datos['estudios_generales'])){$this->estudios_generales	=$datos['estudios_generales'];}
                    if(isset($datos['estudios_esfecificos'])){$this->estudios_esfecificos	=$datos['estudios_esfecificos'];}
                    //if(isset($datos['id_dictamen'])){$this->id_dictamen=$datos['id_dictamen'];}
                    // if(isset($datos['id_dictamen'])){$this->id_dictamen=$datos['id_dictamen'];}
                    // if(isset($datos['id_estudiante_escuela'])){$this->id_estudiante_escuela=$datos['id_estudiante_escuela'];}      
                    if(isset($datos['estudios_especialidad'])){$this->estudios_especialidad=$datos['estudios_especialidad'];}      
                    if(isset($datos['practicas_preprofecionales'])){$this->practicas_preprofecionales=$datos['practicas_preprofecionales'];}      
                    if(isset($datos['total'])){$this->total=$datos['total'];}      
                   // if(isset($datos['fechatramite'])){$this->fechatramite=$datos['fechatramite'];}      
                   // if(isset($datos['fechatramite'])){$this->fechatramite=$datos['fechatramite'];}      
                    
                    $resp = $this->modificarPlanEstudio();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_plan_estudio" => $this->id_plan_estudio
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
    private function modificarPlanEstudio(){
        $query = "UPDATE " . $this->table . " SET id_escuela = '" . $this->id_escuela . "', nombre = '" . $this->nombre . "', fecha = '" . $this->fecha .  "', estudios_generales = '" . $this->estudios_generales .  "', estudios_esfecificos = '" . $this->estudios_esfecificos .  "', estudios_especialidad = '" . $this->estudios_especialidad . "', practicas_preprofecionales = '" . $this->practicas_preprofecionales . "', total = '" . $this->total . 
        "'WHERE id_plan_estudio = '" . $this->id_plan_estudio . "'";
                
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
            $this->id_plan_estudio = $id;
            
            $resp = $this->eliminarPlan();
            if($resp){
                $respuesta = $_respuestas->response;
                $respuesta["result"] = array(
                    "id_plan_estudio" => $this->id_plan_estudio
                );
                return $respuesta;
            } else {
                return $_respuestas->error_500();
            }
        }
    }

    private function eliminarPlan(){
        $query = "DELETE FROM " . $this->table . " WHERE id_plan_estudio = '" . $this->id_plan_estudio . "'";
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