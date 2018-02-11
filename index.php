<?php
/**
 * Created by PhpStorm.
 * User: ele
 * Date: 2/9/18
 * Time: 11:24 PM
 */
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST,GET,OPTIONS');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

//Make sure that it is a POST request.
if (strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0) {
    throw new Exception('Request method must be POST!');
}


//Make sure that the content type of the POST request has been set to application/json
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if (strcasecmp($contentType, 'application/json') != 0) {
    throw new Exception('Content type must be: application/json');
}

//Receive the RAW post data.
$content = trim(file_get_contents("php://input"));

//Attempt to decode the incoming RAW post data from JSON.
$decoded = json_decode($content, true);

require_once 'APIS/APIClima.php';

$params = array();

// message to return
$message = array();


switch ($decoded['action']) {

    case 'getRecomendacion':
        if (!(isset($decoded['idciudad']) && !is_null($decoded['idciudad']))) {
            $message["message"] = "E2";
            jsonView($message);
        } else {
            $params = $decoded;
            $myAPI = new APIClima();
            if (($data = $myAPI->getRecomendacion($decoded['idciudad']))) {
                $message = $data;
                jsonView($message);
            } else {
                $message["message"] = "E1";
                jsonView($message);
            }
        }
        break;
    default:
        $message["message"] = "E0";
        jsonView($message);
        break;
}
/*
 * E2 – Faltan parámetros para procesar la petición
 * E1 – Ocurrió un error al ejecutar el método
 * E0 – El método solicitado no existe
 * */
function jsonView($message)
{

    header('Content-type: application/json; charset=utf-8');
    print json_encode($message, JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_HEX_TAG |
        JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE |
        JSON_PRETTY_PRINT);
    //print $message;
}