<?php 
header("Access-Control-Allow-Origin: http://localhost:8100");
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once 'auth.class.php';
require_once 'respuestas.class.php';

$_auth = new auth;
$_respuestas = new respuestas;



if($_SERVER['REQUEST_METHOD'] == "POST"){

    //recibir datosut");


   $postBody = file_get_contents("php://input");

    //enviamos los datos al manejador
    $datosArray = $_auth->login($postBody);

    //delvolvemos una respuesta
    //header('Content-Type: application/json');
    if(isset($datosArray["result"]["error_id"])){
        echo json_encode(["sucess"=>200]);
    }else{
        $responseCode = $datosArray;
        echo json_encode(["sucess"=>$responseCode]);
    }
    echo json_encode($datosArray);


}else if($_SERVER['REQUEST_METHOD'] == "GET"){


        if(isset($_GET['usuario'])){
            $pacienteid = $_GET['usuario'];
            $datosPaciente = $_auth ->obtenerDatosUsuario($pacienteid);
            header("Content-Type: application/json");
            echo json_encode($datosPaciente);
            http_response_code(200);
        }

    



}else{
    header('Content-Type: application/json');
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray);

}


?>