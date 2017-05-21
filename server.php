<?php
require_once(__DIR__ . "/clases/claseWS.php");

ini_set('soap.wsdl_cache_enabled',0);
ini_set('soap.wsdl_cache_ttl',0);

$HTTP_RAW_POST_DATA =  !isset( $HTTP_RAW_POST_DATA ) ? file_get_contents( 'php://input' )  : $HTTP_RAW_POST_DATA;

$server = new soap_server();
$server ->configureWSDL('Web Service','urn:nombreEsquema');
$server ->soap_defencoding = 'UTF-8';

//Estructura de entrada
$server->wsdl->addComplexType(
    'datosEntrada',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'Input_1' => array('name' => 'Input_1','type' => 'xsd:string'),
        'Input_2' => array('name' => 'Input_2','type' => 'xsd:string'),
        'Input_3' => array('name' => 'Input_3','type' => 'xsd:string'),
        'Input_4' => array('name' => 'Input_4','type' => 'xsd:string'),
    )
);

//Estructura de salida
$server->wsdl->addComplexType(
    'datosSalida',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'Resp_1'  => array('name' => 'Resp_1','type'  => 'xsd:string'),
        'Resp_2'  => array('name' => 'Resp_2','type'  => 'xsd:string'),
        'Resp_3'  => array('name' => 'Resp_3','type'  => 'xsd:string'),
        'mensaje' => array('name' => 'mensaje','type' => 'xsd:string')
    )
);

//Registrar la estructura de entrada como Array
$server->wsdl->addComplexType(
'entradaArray',
'complexType',
'array',
'',
'SOAP-ENC:Array',
array(),
array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'xsd:datosEntrada[]')),'tns:datosEntrada');

//Registrar la estructura de salida como Array
$server->wsdl->addComplexType(
'salidaArray',
'complexType',
'array',
'',
'SOAP-ENC:Array',
array(),
array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:datosSalida[]')),'tns:datosSalida');

//Finalmente publicar la función a usar
$server -> register(
  "tuClase.nombreFuncionWS",
  array("datosEntrada"=>"tns:entradaArray"),
  array("return"=>"tns:salidaArray"),
  "urn:nombreEsquema",
  "urn:nombreEsquema#tuClase.nombreFuncionWS",
  "rpc",
  "encoded",
  'Documentacion total para tu WS. Aquí puedes explicar todos los metodos de entrada y salida, como tambien el tipo de dato y los posibles formatos recibidos'
);

$server -> service($HTTP_RAW_POST_DATA);


?>
