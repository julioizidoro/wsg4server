<?php
require 'Slim-2.6.2/Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->response()->header('Content-Type', 'application/json;charset=utf-8');
$app->response()->header('Access-Control-Allow-Origin', '*');

$app->get('/corridas', 'GetCorridas');
$app->post('/corridas', 'AddCorrida');

$app->get('/corridas/:id', 'GetCorridaById');
$app->put('/corridas/:id', 'UpdateCorridaById');
$app->delete('/corridas/:id', 'DeleteCorridaById');

$app->get('/corredores', 'GetCorredores');
$app->post('/corredores', 'AddCorredor');

$app->get('/corridas/:id/corredores', 'GetCorredoresNaCorrida');
$app->post('/corridas/:id/corredores', 'InscreveCorredorNaCorrida');

$app->put('/corridas/:idCorrida/corredores/:idCorredor', 'UpdateInscricao');

$app->run();

function getConn()
{
  return new PDO('mysql:host=186.215.116.63:8082;dbname=wsg4','root','jfhmaster123',
                  array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
	   			));
}

function GetCorridas() {
	$stmt = getConn()->query("SELECT * FROM Corrida");
	$corridas = $stmt->fetchAll(PDO::FETCH_OBJ);

	http_response_code(200);
	echo '{"corridas":'.json_encode($corridas)."}";
}

function AddCorrida()
{
	$request = \Slim\Slim::getInstance()->request();
	$corrida = json_decode($request->getBody());
	$sql = "INSERT INTO corridas (nome,preco,dataInclusao,idCategoria) values (:nome,:preco,:dataInclusao,:idCategoria) "; //todo
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("nome",$produto->nome); // todo
	$stmt->bindParam("preco",$produto->preco);
	$stmt->bindParam("dataInclusao",$produto->dataInclusao);
	$stmt->bindParam("idCategoria",$produto->idCategoria);
	$stmt->execute();
	$corrida->idcorrida = $conn->lastInsertId();
	
	http_response_code(201);
	echo json_encode($corrida);
}

function GetCorridaById($id)
{
	$conn = getConn();
	$sql = "SELECT * FROM corrida WHERE idcorrida=:id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id",$id);
	$stmt->execute();
	$corrida = $stmt->fetchObject();
	
	if ($corrida != false) {
		http_response_code(200);
		echo json_encode($corrida);
	} else {
		http_response_code(404);
		echo "{'message':'Corrida nao encontrada'}";
	}
}

function UpdateCorridaById($id)
{
	$request = \Slim\Slim::getInstance()->request();
	$corrida = json_decode($request->getBody());
	$sql = "UPDATE corridas SET nome=:nome,preco=:preco,dataInclusao=:dataInclusao,idCategoria=:idCategoria WHERE   idcorrida=:id"; // todo
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("nome",$produto->nome); // todo
	$stmt->bindParam("preco",$produto->preco);
	$stmt->bindParam("dataInclusao",$produto->dataInclusao);
	$stmt->bindParam("idCategoria",$produto->idCategoria);
	$stmt->bindParam("idcorrida",$id);
	$stmt->execute();

	http_response_code(200);
	echo json_encode($corrida);
}

function DeleteCorridaById($id)
{
	$sql = "DELETE FROM corridas WHERE idcorrida=:id";
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("idcorrida",$id);
	$stmt->execute();

	http_response_code(200);
	echo "{'message':'Produto apagado'}";
}

function GetCorredores() {
	$stmt = getConn()->query("SELECT * FROM corredores");
	$corredores = $stmt->fetchAll(PDO::FETCH_OBJ);

	http_response_code(200);
	echo '{"corredores":'.json_encode($corredores)."}";
}

function AddCorredor()
{
	$request = \Slim\Slim::getInstance()->request();
	$corredor = json_decode($request->getBody());
	$sql = "INSERT INTO corredores (nome,preco,dataInclusao,idCategoria) values (:nome,:preco,:dataInclusao,:idCategoria) "; //todo
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("nome",$corredor->nome); // todo
	$stmt->bindParam("preco",$produto->preco);
	$stmt->bindParam("dataInclusao",$produto->dataInclusao);
	$stmt->bindParam("idCategoria",$produto->idCategoria);
	$stmt->execute();
	$corredor->idcorredor = $conn->lastInsertId();
	
	http_response_code(201);
	echo json_encode($corredor);
}

function GetCorredoresNaCorrida($id)
{
	$conn = getConn();
	$sql = "SELECT c.* FROM inscricoes i INNER JOIN corredores c ON (i.corredor_idcorredor = c.idcorredor) WHERE corrida_idcorrida=:id"; 
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id",$id);
	$stmt->execute();
	$corredores = $stmt->fetchAll(PDO::FETCH_OBJ);

	http_response_code(200);
	echo '{"corredores":'.json_encode($corredores)."}";
}

function InscreveCorredorNaCorrida($id)
{
	$request = \Slim\Slim::getInstance()->request();
	$corredor = json_decode($request->getBody());
	$sql = "INSERT INTO inscricoes (corrida_idcorrida, corredor_idcorredor, statuspagamento) values (:id_corrida,:id_corredor, false) "; 
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id_corrida",$id);
	$stmt->bindParam("id_corredor", $corredor->idcorredor);
	$stmt->execute();
	http_response_code(201);
}

function UpdateInscricao($idCorrida, $idCorredor)
{
	$request = \Slim\Slim::getInstance()->request();
	$inscricao = json_decode($request->getBody());
	$sql = "UPDATE inscricoes SET statuspagamento=:status_pgto,posicao=:posicao,tempo=:tempo ".
	               "WHERE corrida_idcorrida = :id_corrida AND corredor_idcorredor = :id_corredor";

	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("status_pgto",$inscricao->statuspagamento); 
	$stmt->bindParam("posicao",$inscricao->posicao); 
	$stmt->bindParam("tempo",$inscricao->tempo); 
	$stmt->bindParam("id_corrida",$idCorrida); 
	$stmt->bindParam("id_corredor",$idCorredor); 
	$stmt->execute();
	http_response_code(200);
}

?>
