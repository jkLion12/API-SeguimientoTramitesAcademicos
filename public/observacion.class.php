<?php

require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class observacion extends conexion{
    //tabla
    private $table = "observacion";
    //private $id_observacion = "";
    private $obs_foto = "";
    private $obs_doc = "";
    private $otros = "";

    //



    private $obs_fut = "";
    private $obs_constancia_egresado = "";
    private $obs_constancia_matricula = "";
    private $obs_certificado_estudio = "";
    private $obs_constancia_no_adeudar_libros = "";
    private $obs_fotogracia = "";
    private $obs_copia_legalizada_dni = "";
    private $obs_partida_nacimiento = "";
    private $obs_comprobante_pago_titulacion = "";
    private $obs_comprobante_pago_bachiller = "";
    private $obs_copia_certificado_idiomas = "";
    private $obs_resolucion_sustentacion_tesis = "";
    private $obs_constancia_investigacion = "";
    private $obs_constancia_biblioteca = "";
    private $obs_cd = "";
    private $obs_constancia_no_adeudar_escuela = "";
    private $obs_verificacion = "";

    ///

    private $token = "";
    //459808482bf351709ff42d6911b440b1

    //lista todas los años con paginacion de 1 a 10
    public function listaObservacion($pagina = 1){
        $cantidad = 100000;
        $inicio = ($pagina - 1) * $cantidad;

        $query = "SELECT * FROM " . $this->table . " ORDER BY id_observacion DESC LIMIT $inicio, $cantidad";
        $datos = parent::obtenerDatos($query);
        
        return $datos;
        //
    }

    //muestra los datos de un solo año de trabajo
    public function obtenerObservacion(){
        $query = "SELECT * FROM " . $this->table . " ORDER BY id_observacion DESC LIMIT 1";
        //print_r($query);
        //$datos = parent::obtenerDatos($query);
        return parent::obtenerDatos($query);
    }

    public function traerObservacion($id){
        //$query = "SELECT id_tramite, codtramite, id_carta, id_dictamen, id_estudiante_escuela, tipo FROM " . $this->table . " ORDER BY id_tramite DESC LIMIT $inicio, $cantidad";
        $query = "SELECT o.*, h.id_hito, dt.id_detalle_tramite
        FROM " . $this->table . " o
        INNER JOIN detalle_tramite dt ON o.id_observacion = dt.id_observacion 
        INNER JOIN tramites t ON t.id_tramite = dt.id_tramite
        INNER JOIN hito h ON h.id_hito = dt.id_hito WHERE t.id_tramite = '$id'";


        $datos = parent::obtenerDatos($query);
        
        return $datos;
        //
    }

    //traer observacion 
    public function verObservacion($id){
        $query = "SELECT * FROM " . $this->table . " WHERE id_observacion = '$id'";
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
                if (
                    !isset($datos['obs_foto']) ||
                    !isset($datos['obs_doc']) ||
                    !isset($datos['obs_fut']) ||
                    !isset($datos['obs_constancia_egresado']) ||
                    !isset($datos['obs_constancia_matricula']) ||
                    !isset($datos['obs_certificado_estudio']) ||
                    !isset($datos['obs_constancia_no_adeudar_libros']) ||
                    !isset($datos['obs_fotogracia']) ||
                    !isset($datos['obs_copia_legalizada_dni']) ||
                    !isset($datos['obs_partida_nacimiento']) ||
                    !isset($datos['obs_comprobante_pago_titulacion']) ||
                    !isset($datos['obs_comprobante_pago_bachiller']) ||
                    !isset($datos['obs_copia_certificado_idiomas']) ||
                    !isset($datos['obs_resolucion_sustentacion_tesis']) ||
                    !isset($datos['obs_constancia_investigacion']) ||
                    !isset($datos['obs_constancia_biblioteca']) ||
                    !isset($datos['obs_cd']) ||
                    !isset($datos['obs_constancia_no_adeudar_escuela']) ||
                    !isset($datos['obs_verificacion']) ||
                    !isset($datos['otros'])
                ) 
                {
                    return $_respuestas->error_400();
                }else{
                    $this->obs_foto = $datos['obs_foto'];
                    $this->obs_doc = $datos['obs_doc'];
                    $this->obs_fut = $datos['obs_fut'];
                    $this->obs_constancia_egresado = $datos['obs_constancia_egresado'];
                    $this->obs_constancia_matricula = $datos['obs_constancia_matricula'];
                    $this->obs_certificado_estudio = $datos['obs_certificado_estudio'];
                    $this->obs_constancia_no_adeudar_libros = $datos['obs_constancia_no_adeudar_libros'];
                    $this->obs_fotogracia = $datos['obs_fotogracia'];
                    $this->obs_copia_legalizada_dni = $datos['obs_copia_legalizada_dni'];
                    $this->obs_partida_nacimiento = $datos['obs_partida_nacimiento'];
                    $this->obs_comprobante_pago_titulacion = $datos['obs_comprobante_pago_titulacion'];
                    $this->obs_comprobante_pago_bachiller = $datos['obs_comprobante_pago_bachiller'];
                    $this->obs_copia_certificado_idiomas = $datos['obs_copia_certificado_idiomas'];
                    $this->obs_resolucion_sustentacion_tesis = $datos['obs_resolucion_sustentacion_tesis'];
                    $this->obs_constancia_investigacion = $datos['obs_constancia_investigacion'];
                    $this->obs_constancia_biblioteca = $datos['obs_constancia_biblioteca'];
                    $this->obs_cd = $datos['obs_cd'];
                    $this->obs_constancia_no_adeudar_escuela = $datos['obs_constancia_no_adeudar_escuela'];
                    $this->obs_verificacion = $datos['obs_verificacion'];
                    $this->otros = $datos['otros'];
                                        
                    $resp = $this->insertarObservacion();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_observacion" => $resp
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
    private function insertarObservacion(){
        $query = "INSERT INTO " . $this->table . " (obs_foto, obs_doc,
        obs_fut,
        obs_constancia_egresado,
        obs_constancia_matricula,
        obs_certificado_estudio,
        obs_constancia_no_adeudar_libros,
        obs_fotogracia,
        obs_copia_legalizada_dni,
        obs_partida_nacimiento,
        obs_comprobante_pago_titulacion,
        obs_comprobante_pago_bachiller,
        obs_copia_certificado_idiomas,
        obs_resolucion_sustentacion_tesis,
        obs_constancia_investigacion,
        obs_constancia_biblioteca,
        obs_cd,
        obs_constancia_no_adeudar_escuela,
        obs_verificacion,
        otros)
        values
        ('" . $this->obs_foto . "',
        '" . $this->obs_doc . "',
        '" . $this->obs_fut . "',
        '" . $this->obs_constancia_egresado . "',
        '" . $this->obs_constancia_matricula . "',
        '" . $this->obs_certificado_estudio . "',
        '" . $this->obs_constancia_no_adeudar_libros . "',
        '" . $this->obs_fotogracia . "',
        '" . $this->obs_copia_legalizada_dni . "',
        '" . $this->obs_partida_nacimiento . "',
        '" . $this->obs_comprobante_pago_titulacion . "',
        '" . $this->obs_comprobante_pago_bachiller . "',
        '" . $this->obs_copia_certificado_idiomas . "',
        '" . $this->obs_resolucion_sustentacion_tesis . "',
        '" . $this->obs_constancia_investigacion . "',
        '" . $this->obs_constancia_biblioteca . "',
        '" . $this->obs_cd . "',
        '" . $this->obs_constancia_no_adeudar_escuela . "',
        '" . $this->obs_verificacion . "',
        '" . $this->otros . "')";
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
                if(!isset($datos['id_observacion'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id_observacion = $datos['id_observacion'];
                    if (isset($datos['obs_foto'])) {
                        $this->obs_foto = $datos['obs_foto'];
                    }
                    if (isset($datos['obs_doc'])) {
                        $this->obs_doc = $datos['obs_doc'];
                    }
                    if (isset($datos['obs_fut'])) {
                        $this->obs_fut = $datos['obs_fut'];
                    }
                    if (isset($datos['obs_constancia_egresado'])) {
                        $this->obs_constancia_egresado = $datos['obs_constancia_egresado'];
                    }
                    if (isset($datos['obs_constancia_matricula'])) {
                        $this->obs_constancia_matricula = $datos['obs_constancia_matricula'];
                    }
                    if (isset($datos['obs_certificado_estudio'])) {
                        $this->obs_certificado_estudio = $datos['obs_certificado_estudio'];
                    }
                    if (isset($datos['obs_constancia_no_adeudar_libros'])) {
                        $this->obs_constancia_no_adeudar_libros = $datos['obs_constancia_no_adeudar_libros'];
                    }
                    if (isset($datos['obs_fotogracia'])) {
                        $this->obs_fotogracia = $datos['obs_fotogracia'];
                    }
                    if (isset($datos['obs_copia_legalizada_dni'])) {
                        $this->obs_copia_legalizada_dni = $datos['obs_copia_legalizada_dni'];
                    }
                    if (isset($datos['obs_partida_nacimiento'])) {
                        $this->obs_partida_nacimiento = $datos['obs_partida_nacimiento'];
                    } 
                    //  
                    if (isset($datos['obs_comprobante_pago_titulacion'])) {
                        $this->obs_comprobante_pago_titulacion = $datos['obs_comprobante_pago_titulacion'];
                    }   
                    if (isset($datos['obs_comprobante_pago_bachiller'])) {
                        $this->obs_comprobante_pago_bachiller = $datos['obs_comprobante_pago_bachiller'];
                    }   
                    if (isset($datos['obs_copia_certificado_idiomas'])) {
                        $this->obs_copia_certificado_idiomas = $datos['obs_copia_certificado_idiomas'];
                    }   
                    if (isset($datos['obs_resolucion_sustentacion_tesis'])) {
                        $this->obs_resolucion_sustentacion_tesis = $datos['obs_resolucion_sustentacion_tesis'];
                    }   
                    if (isset($datos['obs_constancia_investigacion'])) {
                        $this->obs_constancia_investigacion = $datos['obs_constancia_investigacion'];
                    }   
                    if (isset($datos['obs_constancia_biblioteca'])) {
                        $this->obs_constancia_biblioteca = $datos['obs_constancia_biblioteca'];
                    }   
                    if (isset($datos['obs_cd'])) {
                        $this->obs_cd = $datos['obs_cd'];
                    }   
                    if (isset($datos['obs_constancia_no_adeudar_escuela'])) {
                        $this->obs_constancia_no_adeudar_escuela = $datos['obs_constancia_no_adeudar_escuela'];
                    }   
                    if (isset($datos['obs_verificacion'])) {
                        $this->obs_verificacion = $datos['obs_verificacion'];
                    }   
                    if (isset($datos['otros'])) {
                        $this->otros = $datos['otros'];
                    }   

                    
                    $resp = $this->modificarObservacion();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_observacion" => $this->id_observacion
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
    private function modificarObservacion(){
        $query = "UPDATE " . $this->table . " SET 
        obs_foto = '" . $this->obs_foto . "',
        obs_doc = '" . $this->obs_doc . "',
        obs_fut = '" . $this->obs_fut . "',
        obs_constancia_egresado = '" . $this->obs_constancia_egresado . "',
        obs_constancia_matricula = '" . $this->obs_constancia_matricula . "',
        obs_certificado_estudio = '" . $this->obs_certificado_estudio . "',
        obs_constancia_no_adeudar_libros = '" . $this->obs_constancia_no_adeudar_libros . "',
        obs_fotogracia = '" . $this->obs_fotogracia . "',
        obs_copia_legalizada_dni = '" . $this->obs_copia_legalizada_dni . "',
        obs_partida_nacimiento = '" . $this->obs_partida_nacimiento . "',
        obs_comprobante_pago_titulacion = '" . $this->obs_comprobante_pago_titulacion . "',
        obs_comprobante_pago_bachiller = '" . $this->obs_comprobante_pago_bachiller . "',
        obs_copia_certificado_idiomas = '" . $this->obs_copia_certificado_idiomas . "',
        obs_resolucion_sustentacion_tesis = '" . $this->obs_resolucion_sustentacion_tesis . "',
        obs_constancia_investigacion = '" . $this->obs_constancia_investigacion . "',
        obs_constancia_biblioteca = '" . $this->obs_constancia_biblioteca . "',
        obs_cd = '" . $this->obs_cd . "',
        obs_constancia_no_adeudar_escuela = '" . $this->obs_constancia_no_adeudar_escuela . "',
        obs_verificacion = '" . $this->obs_verificacion . "',
        otros = '" . $this->otros . "'
        WHERE id_observacion = '" . $this->id_observacion . "'";

                
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
            
            $resp = $this->eliminarObservacion();
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

    private function eliminarObservacion(){
        $query = "DELETE FROM " . $this->table . " WHERE id_observacion = '" . $this->id . "'";
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