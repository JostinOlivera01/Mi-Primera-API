<?php

require_once 'respuestas.class.php';
require_once 'conexion.php';

class auth extends conexion{

    public function login($json){
      
        $_respustas = new respuestas;
        $datos = json_decode($json,true);

        if(!isset($datos["usuario"]) || !isset($datos['password'])){
            //error con los campos
            return $_respustas->error_400();
        }else{
            //todo esta bien 
            $usuario = $datos["usuario"];
            $password = $datos["password"];
            //$password = parent::encriptar($password);
            //$datos = $this->obtenerDatosUsuario($usuario);
                //no existe el usuario
                $registrar = $this->crearUsuario($usuario, $password);
                $result = 'OKKKK';
                return  $result;
            }
        }
    



    public function obtenerDatosUsuario($correo){
        $query = "SELECT UsuarioId FROM usuarios WHERE Usuario = '$correo'";
        $datos = parent::obtenerDatos($query);
        if(isset($datos[0]["UsuarioId"])){
            return true;
        }else{
            return false;
        }
    }


    private function insertarToken($usuarioid){
        $val = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16,$val));
        $date = date("Y-m-d H:i");
        $estado = "Activo";
        $query = "INSERT INTO usuarios_token (UsuarioId,Token,Estado,Fecha)VALUES('$usuarioid','$token','$estado','$date')";
        $verifica = parent::nonQuery($query);
        if($verifica){
            return $token;
        }else{
            return 0;
        }
    }


    private function crearUsuario($usuario, $password){

        $query = "INSERT INTO `usuarios` ( `Usuario`, `Password`, `Estado`) VALUES ('$usuario', '$password', 'Activo')";
        $verifica = parent::insertarUsuario($query);
        if($verifica){
            return $usuario;
        }else{
            return 0;
        }




    }


}




?>