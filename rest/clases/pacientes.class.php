<?php
require_once "conexion/conexion.php";
require_once "conexion/respuestas.class.php";


class pacientes extends conexion {

    private $table = "pacientes";
    private $pacienteid = "";
    private $codigo = "";
    private $nombre = "";
    private $direccion = "";
    private $codigoPostal = "";
    private $genero = "";
    private $telefono = "";
    private $fechaNacimiento = "0000-00-00";
    private $precio = "";
    private $token = "";
    private $msj = "NO";
//912bc00f049ac8464472020c5cd06759



/// METODO GET PARA LISTAR POR GRUPO Y POR Id
    public function listaPacientes($pagina = 1){
        $inicio  = 0 ;
        $cantidad = 100;
        if($pagina > 1){
            $inicio = ($cantidad * ($pagina - 1)) +1 ;
            $cantidad = $cantidad * $pagina;
        }
        $query = "SELECT productoId,Nombre,codigo,precio FROM " . $this->table . " limit $inicio,$cantidad";
        $datos = parent::obtenerDatos($query);
        return ($datos);
    }

    public function obtenerPaciente($id){
        $query = "SELECT * FROM " . $this->table . " WHERE productoId = '$id'";
        return parent::obtenerDatos($query);

    }

//----------------------------------------GET-----------------------------------------------------------------------------------------------



/// METODO POST PARA INSERTAR PRODUCTOS
    public function post($json){
        $_respuestas = new respuestas;
        $datos = $json;

                if(!isset($datos['nombre']) || !isset($datos['codigo']) || !isset($datos['precio'])){
                    return $_respuestas->error_400();
                }else{
                    $this->nombre = $datos['nombre'];
                    $this->codigo = $datos['codigo'];
                    $this->precio = $datos['precio'];
                    if(isset($datos['telefono'])) { $this->telefono = $datos['telefono']; }
                    if(isset($datos['direccion'])) { $this->direccion = $datos['direccion']; }
                    if(isset($datos['codigoPostal'])) { $this->codigoPostal = $datos['codigoPostal']; }
                    if(isset($datos['genero'])) { $this->genero = $datos['genero']; }
                    if(isset($datos['fechaNacimiento'])) { $this->fechaNacimiento = $datos['fechaNacimiento']; }
                    $resp = $this->insertarPaciente();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "productoId" => $resp
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }


        }

    private function insertarPaciente(){
        $query = "INSERT INTO " . $this->table . " (codigo,Nombre,Direccion,CodigoPostal,Telefono,Genero,FechaNacimiento,Precio)
        values
        ('" . $this->codigo . "','" . $this->nombre . "','" . $this->direccion ."','" . $this->codigoPostal . "','"  . $this->telefono . "','" . $this->genero . "','" . $this->fechaNacimiento . "','" . $this->precio . "')"; 
        $resp = parent::nonQueryId($query);
        if($resp){
             return $resp;
        }else{
            return 0;
        }
    }
//--------------------------------------------------------POST-------------------------------------------------------------------------------




/// METODO PARA ACTUALIZAR UN PRODUCTO POR ID(requerido) y los campos a actualizar    
    public function put($json){
        $_respuestas = new respuestas;
        $datos = $json;
        print_r($datos);


                if(!isset($datos['productoId'])){
                    return $_respuestas->error_400();
                }else{
                    $this->pacienteid = $datos['productoId'];
                    if(isset($datos['nombre'])) { $this->nombre = $datos['nombre']; }
                    if(isset($datos['codigo'])) { $this->codigo = $datos['codigo']; }
                    if(isset($datos['precio'])) { $this->precio = $datos['precio']; }
                    if(isset($datos['telefono'])) { $this->telefono = $datos['telefono']; }
                    if(isset($datos['direccion'])) { $this->direccion = $datos['direccion']; }
                    if(isset($datos['codigoPostal'])) { $this->codigoPostal = $datos['codigoPostal']; }
                    if(isset($datos['genero'])) { $this->genero = $datos['genero']; }
                    if(isset($datos['fechaNacimiento'])) { $this->fechaNacimiento = $datos['fechaNacimiento']; }
        
                    $resp = $this->modificarPaciente();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "productoId" => $this->pacienteid
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }
        }

    private function modificarPaciente(){
        $query = "UPDATE " . $this->table . " SET Nombre ='" . $this->nombre . "',Direccion = '" . $this->direccion . "', codigo = '" . $this->codigo . "', CodigoPostal = '" .
        $this->codigoPostal . "', Telefono = '" . $this->telefono . "', Genero = '" . $this->genero . "', FechaNacimiento = '" . $this->fechaNacimiento . "', precio = '" . $this->precio .
         "' WHERE ProductoId = '" . $this->pacienteid . "'"; 
        $resp = parent::nonQuery($query);
        if($resp >= 1){
             return $resp;
        }else{
            return 0;
        }
    }
//------------------------------------------PUT-----------------------------------------------------------------------------------------------




// METODO ELIMINAR (DELETE)(ID)
    public function delete($json){
        $_respuestas = new respuestas;
        $datos = $json;


                if(!isset($datos['pacienteId'])){
                    return $_respuestas->error_400();
                }else{
                    $this->pacienteid = $datos['pacienteId'];
                    $resp = $this->eliminarPaciente();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "pacienteId" => $this->pacienteid
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }

     
    }


    private function eliminarPaciente(){
        $query = "DELETE FROM " . $this->table . " WHERE PacienteId= '" . $this->pacienteid . "'";
        $resp = parent::nonQuery($query);
        if($resp >= 1 ){
            return $resp;
        }else{
            return 0;
        }
    }

///--------------------------------------------------------------------------------------------------------------------
































    private function buscarToken(){
        $query = "SELECT  TokenId,UsuarioId,Estado from usuarios_token WHERE Token = '" . $this->token . "' AND Estado = 'Activo'";
        $resp = parent::obtenerDatos($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }


    private function actualizarToken($tokenid){
        $date = date("Y-m-d H:i");
        $query = "UPDATE usuarios_token SET Fecha = '$date' WHERE TokenId = '$tokenid' ";
        $resp = parent::nonQuery($query);
        if($resp >= 1){
            return $resp;
        }else{
            return 0;
        }
    }



}





?>