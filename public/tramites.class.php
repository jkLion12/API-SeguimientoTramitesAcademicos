<?php

require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class tramites extends conexion{
    //tabla
    private $table = "tramites";
    private $table2 = "carta";
    private $id_tramite = "";
    private $codtramite = "";
    private $id_carta = "";
    private $id_dictamen = "";
    private $id_estudiante_escuela = "";
    private $tipo = "";

    private $token = "";
    //459808482bf351709ff42d6911b440b1

    //lista todas las personas con paginacion de 1 a 10
    public function listaTramites($pagina = 1){
        $cantidad = 100000;
        $inicio = ($pagina - 1) * $cantidad;

        //$query = "SELECT id_tramite, codtramite, id_carta, id_dictamen, id_estudiante_escuela, tipo FROM " . $this->table . " ORDER BY id_tramite DESC LIMIT $inicio, $cantidad";
        $query = "SELECT t.id_tramite,estu.nrocelular ,dt.estado, dt.verificacion, t.tipo, dt.fechatramite, 
        CASE estu.sexo
            WHEN 1 THEN 'Masculino'
            WHEN 2 THEN 'Femenino'
            ELSE 'Otro'
        END AS sexo,
        ee.codigo, esc.Nombre as escuela
        FROM " . $this->table . " t
        INNER JOIN detalle_tramite dt ON t.id_tramite = dt.id_tramite 
        INNER JOIN estudiante_escuela ee ON t.id_estudiante_escuela = ee.id_estudiante_escuela
        INNER JOIN escuela esc ON ee.id_escuela = esc.id_escuela
        INNER JOIN estudiante estu ON ee.id_estudiante = estu.id_estudiante
        ORDER BY t.id_tramite DESC
        LIMIT $inicio, $cantidad";


        $datos = parent::obtenerDatos($query);
        
        return $datos;
        //
    }

    //Trae datos para mostrar en la revision del tramite
    public function mostrarTramite($id){
        //$query = "SELECT id_tramite, codtramite, id_carta, id_dictamen, id_estudiante_escuela, tipo FROM " . $this->table . " ORDER BY id_tramite DESC LIMIT $inicio, $cantidad";
        $query = "SELECT t.id_tramite,fh.fecha_hito_1, fh.fecha_hito_2 ,t.codtramite ,fac.nombre as facultad ,estu.nombre, estu.dni, estu.sexo, estu.nrocelular ,estu.apellido,dt.estado, dt.verificacion, dt.fechatramite, t.tipo, dt.fechatramite, dt.nro_orden, ee.codigo, esc.Nombre as escuela
        FROM " . $this->table . " t
        INNER JOIN detalle_tramite dt ON t.id_tramite = dt.id_tramite 
        INNER JOIN estudiante_escuela ee ON t.id_estudiante_escuela = ee.id_estudiante_escuela
        INNER JOIN estudiante estu ON ee.id_estudiante = estu.id_estudiante
        INNER JOIN escuela esc ON ee.id_escuela = esc.id_escuela 
        INNER JOIN facultad fac ON esc.id_facultad = fac.id_facultad 
        INNER JOIN hito h ON dt.id_hito = h.id_hito 
        INNER JOIN fecha_hito fh ON h.id_hito = fh.id_hito 
        WHERE t.id_tramite = '$id'";


        $datos = parent::obtenerDatos($query);
        
        return $datos;
        //
    }
    //

    public function radar($anio) {
        $query = "SELECT e.Nombre AS escuela,
        COUNT(CASE WHEN t.tipo = 'Bachiller' THEN 1 END) AS tipo_1,
        COUNT(CASE WHEN t.tipo = 'Titulo' THEN 1 END) AS tipo_2
        FROM detalle_tramite dt
        INNER JOIN tramites t ON t.id_tramite = dt.id_tramite
        INNER JOIN estudiante_escuela ee ON t.id_estudiante_escuela = ee.id_estudiante_escuela
        INNER JOIN escuela e ON e.id_escuela = ee.id_escuela
        WHERE YEAR(STR_TO_DATE(fechatramite, '%d/%m/%Y')) = '$anio'
        GROUP BY e.Nombre";
    
        $datos = parent::obtenerDatos($query);
    
        return $datos;
    }

    public function cantidad_tipo($anio) {
        $query = "SELECT COUNT(CASE WHEN tramites.tipo = 'Bachiller' THEN 1 END) AS tipo_1,
        COUNT(CASE WHEN tramites.tipo = 'Titulo' THEN 1 END) AS tipo_2
        FROM detalle_tramite
        INNER join tramites on tramites.id_tramite=detalle_tramite.id_tramite
        WHERE YEAR(STR_TO_DATE(fechatramite, '%d/%m/%Y')) = '$anio'";
    
        $datos = parent::obtenerDatos($query);
    
        return $datos;
    }

    //
    public function tramitesMes($anio){
        $query_set_language = "SET lc_time_names = 'es_ES'";
        parent::obtenerDatos($query_set_language);
        $query = "SELECT DATE_FORMAT(STR_TO_DATE(dt.fechatramite, '%d/%m/%Y %h:%i %p'), '%M') AS mes, estu.sexo,
        COUNT(*) AS cantidad_tramites
        FROM " . $this->table . " t
        INNER JOIN detalle_tramite dt ON t.id_tramite = dt.id_tramite 
        INNER JOIN estudiante_escuela ee ON t.id_estudiante_escuela = ee.id_estudiante_escuela
        INNER JOIN estudiante estu ON ee.id_estudiante = estu.id_estudiante
        WHERE YEAR(STR_TO_DATE(fechatramite, '%d/%m/%Y')) = '$anio'
        GROUP BY mes, estu.sexo
        ORDER BY mes, estu.sexo";
    
        // Array para almacenar los resultados de la consulta
        $datos = [];
    
        $datos = parent::obtenerDatos($query);
    
        return $datos;
    }
      
    public function TipoTramiteMes($anio){
        $query_set_language = "SET lc_time_names = 'es_ES'";
        parent::obtenerDatos($query_set_language);

        $query = "SELECT DATE_FORMAT(STR_TO_DATE(dt.fechatramite, '%d/%m/%Y %h:%i %p'), '%M') AS mes, t.tipo,
        COUNT(*) AS cantidad_tramites
        FROM " . $this->table . " t
        INNER JOIN detalle_tramite dt ON t.id_tramite = dt.id_tramite 
        WHERE YEAR(STR_TO_DATE(fechatramite, '%d/%m/%Y')) = '$anio'
        GROUP BY  mes, t.tipo
        ORDER BY mes";

        $datos = parent::obtenerDatos($query);
        
        return $datos;

    }

                  

    //muestra los datos de una sola persona
    public function obtenerTramite($id_tramite){
        $query = "SELECT * FROM " . $this->table . " WHERE id_tramite = '$id_tramite'";
        //print_r($query);
        //$datos = parent::obtenerDatos($query);
        return parent::obtenerDatos($query);
    }

    //muestra los datos de una solo tramite, para busquedas
    public function buscarTramite($codtramite){
        $query = "SELECT * FROM " . $this->table . " WHERE codtramite = '$codtramite'";
        //print_r($query);
        //$datos = parent::obtenerDatos($query);
        return parent::obtenerDatos($query);
    }
    public function idTramite(){
        $query = "SELECT * FROM " . $this->table . " ORDER BY id_tramite DESC LIMIT 1";
        //print_r($query);
        //$datos = parent::obtenerDatos($query);
        return parent::obtenerDatos($query);
    }

    public function getNroCarta($id){
        $query = "SELECT c.id_carta, c.nro_correlativo 
        FROM " . $this->table . " t 
        INNER JOIN carta c ON t.id_carta = c.id_carta WHERE t.id_tramite = '$id'";
        //print_r($query);
        //$datos = parent::obtenerDatos($query);
        return parent::obtenerDatos($query);
    }
    public function getNroDictamen($id){
        $query = "SELECT d.id_dictamen, d.nro_correlativo, pe.id_plan_estudio
        FROM " . $this->table . " t 
        INNER JOIN dictamen d ON t.id_dictamen = d.id_dictamen 
        INNER JOIN plan_estudio pe ON d.id_plan_estudio = pe.id_plan_estudio 
        WHERE t.id_tramite = '$id'";
        //print_r($query);
        //$datos = parent::obtenerDatos($query);
        return parent::obtenerDatos($query);
    }
    
    public function porcentaje($anio) {
        $query = "SELECT e.estado, COUNT(dt.estado) AS cantidad,
                ROUND((COUNT(dt.estado) / (SELECT COUNT(*) FROM detalle_tramite WHERE YEAR(STR_TO_DATE(fechatramite, '%d/%m/%Y')) = $anio)) * 100, 2) AS porcentaje
            FROM (
                SELECT DISTINCT estado FROM detalle_tramite
            ) e
            LEFT JOIN detalle_tramite dt ON e.estado = dt.estado AND YEAR(STR_TO_DATE(dt.fechatramite, '%d/%m/%Y')) = $anio
            GROUP BY e.estado";
    
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
                if(!isset($datos['codtramite']) || !isset($datos['id_carta']) || !isset($datos['id_dictamen']) || !isset($datos['id_estudiante_escuela']) || !isset($datos['tipo'])){
                    return $_respuestas->error_400();
                }else{
                    $this->codtramite=$datos['codtramite'];
                    $this->id_carta=$datos['id_carta'];
                    $this->id_dictamen=$datos['id_dictamen'];
                    $this->id_estudiante_escuela=$datos['id_estudiante_escuela'];
                    $this->tipo=$datos['tipo'];
                    $resp = $this->insertarTramite();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_tramite" => $resp
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
    private function insertarTramite(){
        $query = "INSERT INTO " . $this->table . " (codtramite, id_carta, id_dictamen, id_estudiante_escuela, tipo)
        values
        ('" . $this->codtramite . "','" . $this->id_carta . "','" . $this->id_dictamen . "','" . $this->id_estudiante_escuela . "','" . $this->tipo . "')";
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
                if(!isset($datos['id_tramite'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id_tramite = $datos['id_tramite'];
                    if(isset($datos['codtramite'])){$this->codtramite=$datos['codtramite'];}
                    //if(isset($datos['id_carta'])){$this->id_carta=$datos['id_carta'];}
                    // if(isset($datos['id_dictamen'])){$this->id_dictamen=$datos['id_dictamen'];}
                    // if(isset($datos['id_estudiante_escuela'])){$this->id_estudiante_escuela=$datos['id_estudiante_escuela'];}      
                    if(isset($datos['tipo'])){$this->tipo=$datos['tipo'];}      
                   // if(isset($datos['fechatramite'])){$this->fechatramite=$datos['fechatramite'];}      
                    
                    $resp = $this->modificarTramite();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_tramite" => $this->id_tramite
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
    private function modificarTramite(){
        $query = "UPDATE " . $this->table . " SET codtramite = '" . $this->codtramite . "', tipo = '" . $this->tipo .  
        "'WHERE id_tramite = '" . $this->id_tramite . "'";
                
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