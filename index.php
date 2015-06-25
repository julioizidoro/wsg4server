<?php
require 'Slim-2.6.2/Slim/Slim.php';
require 'bd.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->response()->header('Content-Type', 'application/json;charset=utf-8');
$app->response()->header('Access-Control-Allow-Origin', '*');

include 'corridas.php';
$app->get('/corridas', 'getCorridas');
$app->post('/corridas', 'addCorrida');
$app->get('/corridas/ultimas', 'getUltimasCorridas');
$app->get('/corridas/proximas', 'getProximasCorridas');
$app->get('/corridas/:id', 'getCorrida');
$app->put('/corridas/:id', 'updateCorrida');
$app->delete('/corridas/:id', 'deleteCorrida');

include 'corredores.php';
$app->get('/corredores', 'getCorredores');
$app->post('/corredores', 'addCorredor');
$app->get('/corredores/:id', 'getCorredor');
$app->put('/corredores/:id', 'updateCorredor');
$app->delete('/corredores/:id', 'deleteCorredor');

include 'inscricoes.php';
$app->get('/corridas/:id/corredores', 'getCorredoresNaCorrida');
$app->get('/corredores/:id/corridas', 'getCorridasDoCorredor');
$app->post('/corridas/:idCorrida/corredores/:idCorredor', 'inscreveCorredorNaCorrida');
$app->post('/corredores/:idCorredor/corridas/:idCorrida', 'inscreveCorredorNaCorrida');
$app->put('/corridas/:idCorrida/corredores/:idCorredor', 'updateInscricao');
$app->put('/corredores/:idCorredor/corridas/:idCorrida', 'updateInscricao');
$app->get('/corridas/:idCorrida/corredores/:idCorredor', 'getInscricao');
$app->get('/corredores/:idCorredor/corridas/:idCorrida', 'getInscricao');
$app->delete('/corridas/:idCorrida/corredores/:idCorredor', 'deleteInscricao');
$app->delete('/corredores/:idCorredor/corridas/:idCorrida', 'deleteInscricao');

$app->run();

function getConn()
{
  return new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_DATABASE , DB_USER , DB_PASSWORD ,
                  array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
	   			));
}

function getRequestContents() 
{
	$request = \Slim\Slim::getInstance()->request();
	return json_decode($request->getBody());
}

?>
