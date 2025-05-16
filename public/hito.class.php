<?php

require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class hito extends conexion{
    //tabla
    private $table = "hito";
    private $id_hito = "";
    private $nro_hito = "";

    private $token = "";
    //459808482bf351709ff42d6911b440b1

    //lista todas los años con paginacion de 1 a 10
    public function listaHito($pagina = 1){
        $cantidad = 100000;
        $inicio = ($pagina - 1) * $cantidad;

        $query = "SELECT * FROM " . $this->table . " ORDER BY id_hito DESC LIMIT $inicio, $cantidad";
        $datos = parent::obtenerDatos($query);
        
        return $datos;
        //
    }

    //muestra los datos de un solo año de trabajo
    public function obtenerHito(){
        $query = "SELECT * FROM " . $this->table . " ORDER BY id_hito DESC LIMIT 1";
        //print_r($query);
        //$datos = parent::obtenerDatos($query);
        return parent::obtenerDatos($query);
    }

    public function traerHito($id){
        $query = "SELECT h.id_hito, h.nro_hito, fh.fecha_hito_1 FROM hito h
        INNER JOIN fecha_hito fh ON fh.id_hito = h.id_hito
        WHERE h.id_hito = '$id'";
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

    //funcion para el rastreo
    public function Rastreo($codigo){
        $query = "SELECT 
        t.id_tramite,t.codtramite,estu.nombre,es.nombre as escuela, fac.nombre as facultad ,dt.fechatramite, 
        estu.sexo ,estu.dni ,estu.apellido,t.tipo,dt.estado,ee.codigo, h.nro_hito,h.id_hito, 
        fh.fecha_hito_1, fh.fecha_hito_2, fh.fecha_hito_3, fh.fecha_hito_4  
        
        FROM " . $this->table . " h 
        
        INNER JOIN fecha_hito fh ON fh.id_hito = h.id_hito
        INNER JOIN detalle_tramite dt ON h.id_hito = dt.id_hito 
        INNER JOIN tramites t ON dt.id_tramite = t.id_tramite 
        INNER JOIN estudiante_escuela ee ON ee.id_estudiante_escuela = t.id_estudiante_escuela 
        INNER JOIN escuela es ON ee.id_escuela = es.id_escuela 
        INNER JOIN estudiante estu ON estu.id_estudiante = ee.id_estudiante 
        INNER JOIN facultad fac ON es.id_facultad = fac.id_facultad 

        WHERE ee.codigo = '$codigo' || estu.dni = '$codigo' || t.codtramite = '$codigo'
        
        GROUP BY t.id_tramite, ee.codigo, h.nro_hito
        
        ORDER BY dt.id_detalle_tramite DESC;";

        // ORDER  BY t.id_tramite DESC";

        //print_r($query);
        //$datos = parent::obtenerDatos($query);
        return parent::obtenerDatos($query);

    }

    // public function SingleRastreo($id){
    //     $query = "SELECT t.id_tramite,estu.nombre,es.nombre as escuela, fac.nombre as facultad ,dt.fechatramite, estu.sexo ,estu.dni ,
    //     estu.apellido,t.tipo,dt.estado ,ee.codigo, h.nro_hito, fh.fecha_hito_1, fh.fecha_hito_2, fh.fecha_hito_3, fh.fecha_hito_4 
    //     FROM " . $this->table . " h 
    //     INNER JOIN fecha_hito fh ON fh.id_hito = h.id_hito
    //     INNER JOIN detalle_tramite dt ON h.id_hito = dt.id_hito 
    //     INNER JOIN tramites t ON dt.id_tramite = t.id_tramite 
    //     INNER JOIN estudiante_escuela ee ON ee.id_estudiante_escuela = t.id_estudiante_escuela 
    //     INNER JOIN escuela es ON ee.id_escuela = es.id_escuela 
    //     INNER JOIN estudiante estu ON estu.id_estudiante = ee.id_estudiante 
    //     INNER JOIN facultad fac ON es.id_facultad = fac.id_facultad 
    //     WHERE t.id_tramite = $id
    //     GROUP BY t.id_tramite, ee.codigo, h.nro_hito";

    //     // ORDER  BY t.id_tramite DESC";

    //     //print_r($query);
    //     //$datos = parent::obtenerDatos($query);
    //     return parent::obtenerDatos($query);

    // }


    public function SingleRastreo($id){
        $query = "SELECT 
        tramites.id_tramite,estudiante.nombre,escuela.nombre as escuela, facultad.nombre as facultad,
        detalle_tramite.fechatramite, estudiante.sexo, estudiante.dni, estudiante.apellido,
        tramites.tipo,detalle_tramite.estado ,estudiante_escuela.codigo, hito.nro_hito, 
        fecha_hito.fecha_hito_1, fecha_hito.fecha_hito_2, fecha_hito.fecha_hito_3, fecha_hito.fecha_hito_4, 
        observacion.*
        
        FROM " . $this->table . " hito 
        
        INNER JOIN fecha_hito ON fecha_hito.id_hito = hito.id_hito
        INNER JOIN detalle_tramite ON hito.id_hito = detalle_tramite.id_hito 
        INNER JOIN tramites ON detalle_tramite.id_tramite = tramites.id_tramite 
        INNER JOIN estudiante_escuela ON estudiante_escuela.id_estudiante_escuela = tramites.id_estudiante_escuela 
        INNER JOIN escuela ON estudiante_escuela.id_escuela = escuela.id_escuela 
        INNER JOIN estudiante ON estudiante.id_estudiante = estudiante_escuela.id_estudiante 
        INNER JOIN facultad ON escuela.id_facultad = facultad.id_facultad
        INNER JOIN observacion ON observacion.id_observacion = detalle_tramite.id_observacion
        
        WHERE tramites.id_tramite = $id
        
        GROUP BY tramites.id_tramite, estudiante_escuela.codigo, hito.nro_hito";

        // ORDER  BY t.id_tramite DESC";

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
                if(!isset($datos['nro_hito'])){
                    return $_respuestas->error_400();
                }else{
                    $this->nro_hito=$datos['nro_hito'];
                    //$this->nro_correlativo=$datos['nro_correlativo'];
                    //$this->detalle=$datos['detalle'];
                    
                    $resp = $this->insertarHito();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_hito" => $resp
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
    private function insertarHito(){
        $query = "INSERT INTO " . $this->table . " (nro_hito)
        values
        ('" . $this->nro_hito . "')";
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
                if(!isset($datos['id_hito'])){
                    return $_respuestas->error_400();
                }else{
                    // $this->id_hito = $datos['id_hito'];
                    
                    // // Verificar si se proporciona una nueva contrasena
                    // if (isset($datos['nro_hito'])) {
                    //     $this->nro_hito = $datos['nro_hito'];
                    // } else {
                    //     // Si no se proporciona una nueva contrasena, conserva la contrasena existente
                    //     $nro_actual = $this->traerHito($this->id);
                    //     if ($nro_actual && isset($nro_actual[0]['nro_hito'])) {
                    //         $this->contrasena = $nro_actual[0]['nro_hito'];
                    //     }
                    // }

                    $this->id_hito = $datos['id_hito'];

                    if (isset($datos['nro_hito'])) {
                        $this->nro_hito = $datos['nro_hito'];
                    } else {
                        $this->nro_hito = $datos['nro_hito'] ?? null;
                    }

                    // if(isset($datos['nro_hito'])){$this->nro_hito = $datos['nro_hito'];}
                    //if(isset($datos['id_carta'])){$this->id_carta=$datos['id_carta'];}
                    // if(isset($datos['id_dictamen'])){$this->id_dictamen=$datos['id_dictamen'];}
                    // if(isset($datos['id_estudiante_escuela'])){$this->id_estudiante_escuela=$datos['id_estudiante_escuela'];}      
                    //if(isset($datos['nro_correlativo'])){$this->nro_correlativo=$datos['nro_correlativo'];}      
                    //if(isset($datos['detalle'])){$this->detalle=$datos['detalle'];}      
                   // if(isset($datos['fechatramite'])){$this->fechatramite=$datos['fechatramite'];}      
                    
                    $resp = $this->modificarHito();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_hito" => $this->id_hito
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
    private function modificarHito(){
        // $query = "UPDATE " . $this->table . " SET nro_hito = '" . $this->nro_hito .  
        // "'WHERE id_hito = '" . $this->id_hito . "'";

        $query = "UPDATE " . $this->table . " SET nro_hito = '$this->nro_hito' WHERE id_hito = '$this->id_hito'";

                
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
    public function delete($id_hito){
        $_respuestas = new respuestas;

        if(!isset($id_hito)){
            return $_respuestas->error_400();
        } else {
            $this->id_hito = $id_hito;
            
            $resp = $this->eliminarHito();
            if($resp){
                $respuesta = $_respuestas->response;
                $respuesta["result"] = array(
                    "id_hito" => $this->id_hito
                );
                return $respuesta;
            } else {
                return $_respuestas->error_500();
            }
        }
    }

    private function eliminarHito(){
        $query = "DELETE FROM " . $this->table . " WHERE id_hito = '" . $this->id_hito . "'";
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