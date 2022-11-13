<?php
header("Access-Control-Allow-Origin: http://localhost:8100");
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: Content-Type, Authorization");
require_once 'conexion/respuestas.class.php';
require_once 'clases/pacientes.class.php';

$_respuestas = new respuestas;
$_pacientes = new pacientes;
 
       //METODO GET-----------------------------
if($_SERVER['REQUEST_METHOD'] == "GET"){

    if(isset($_GET["page"])){
        $pagina = $_GET["page"];
        $listaPacientes = $_pacientes->listaPacientes($pagina);
        header("Content-Type: application/json");
        echo json_encode($listaPacientes);
        http_response_code(200);
    }else if(isset($_GET['id'])){
        $productoid = $_GET['id'];
        $datosPaciente = $_pacientes->obtenerPaciente($productoid);
        header("Content-Type: application/json");
        echo json_encode($datosPaciente);
    }

           //METODO PARA POST-----------------------------
}else if($_SERVER['REQUEST_METHOD'] == "POST"){
    //recibimos los datos enviados
    print_r($_REQUEST);
    $postBody = $_REQUEST;
    //enviamos los datos al manejador
    $datosArray = $_pacientes->post($postBody);
    //delvovemos una respuesta 
     header('Content-Type: application/json');
     if(isset($datosArray["result"]["error_id"])){
         $responseCode = $datosArray["result"]["error_id"];
         http_response_code($responseCode);
     }else{
         http_response_code(200);
     }
     echo json_encode($datosArray);


     

        //METODO PARA ACTUALIZAR-----------------------------
}else if($_SERVER['REQUEST_METHOD'] == "PUT"){
      //recibimos los datos enviados
      print_r($_REQUEST);
      $postBody = $_REQUEST;
      //enviamos datos al manejador
      $datosArray = $_pacientes->put($postBody);
        //delvovemos una respuesta 
     header('Content-Type: application/json');
     if(isset($datosArray["result"]["error_id"])){
         $responseCode = $datosArray["result"]["error_id"];
         http_response_code($responseCode);
     }else{
         http_response_code(200);
     }
     echo json_encode($datosArray);






       //METODO PARA DELETE-----------------------------
}else if($_SERVER['REQUEST_METHOD'] == "DELETE"){
        print_r($_REQUEST);
        $headers = $_REQUEST;
        if(isset($headers["pacienteId"])){
            //recibimos los datos enviados por el header
            $send = [
                "pacienteId" =>$headers["pacienteId"]
            ];
            $postBody = $send;
        }else{
            //recibimos los datos enviados
            $postBody = file_get_contents("php://input");
        }
        
        //enviamos datos al manejador
        $datosArray = $_pacientes->delete($postBody);
        //delvovemos una respuesta 
        header('Content-Type: application/json');
        if(isset($datosArray["result"]["error_id"])){
            $responseCode = $datosArray["result"]["error_id"];
            http_response_code($responseCode);
        }else{
            http_response_code(200);
        }
        echo json_encode($datosArray);
       

}else{
    header('Content-Type: application/json');
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray);
}


?>