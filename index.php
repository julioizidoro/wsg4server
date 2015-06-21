<?php
require 'Slim-2.6.2/Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->response()->header('Content-Type', 'application/json;charset=utf-8');
$app->response()->header('Access-Control-Allow-Origin', '*');

$app->get('/corridas', 'getCorridas');
$app->post('/corridas', 'addCorrida');

$app->get('/corridas/:id', 'getCorrida');
$app->put('/corridas/:id', 'updateCorrida');
$app->delete('/corridas/:id', 'deleteCorrida');

$app->get('/corredores', 'getCorredores');
$app->post('/corredores', 'addCorredor');

$app->get('/corredores/:id', 'getCorredor');
$app->put('/corredores/:id', 'updateCorredor');
$app->delete('/corredores/:id', 'deleteCorredor');

$app->get('/corridas/:id/corredores', 'getCorredoresNaCorrida');
$app->get('/corredores/:id/corridas', 'getCorridasDoCorredor');

$app->post('/corridas/:idCorrida/corredores/:idCorredor', 'inscreveCorredorNaCorrida');
$app->post('/corredores/:idCorredor/corridas/:idCorrida', 'inscreveCorredorNaCorrida');
$app->put('/corridas/:idCorrida/corredores/:idCorredor', 'updateInscricao');
$app->put('/corredores/:idCorredor/corridas/:idCorrida', 'updateInscricao');
$app->get('/corridas/:idCorrida/corredores/:idCorredor', 'getInscricao');
$app->get('/corredores/:idCorredor/corridas/:idCorrida', 'getInscricao');

$app->run();

function getConn()
{
  return new PDO('mysql:host=186.215.116.63:8082;dbname=wsg4','root','jfhmaster123',
                  array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
	   			));
}

function getRequestContents() 
{
	$request = \Slim\Slim::getInstance()->request();
	return json_decode($request->getBody());
}

function getCorridas() {
	$stmt = getConn()->query("SELECT * FROM corrida");
	$corridas = $stmt->fetchAll(PDO::FETCH_OBJ);

	http_response_code(200);
	echo '{"corridas":'.json_encode($corridas)."}";
}

function bindCorridaParams($sql, $corrida)
{
	$stmt = getConn()->prepare($sql);
	$stmt->bindParam("nome",$corrida->nome); 
	$stmt->bindParam("descricao",$corrida->descricao); 
	$stmt->bindParam("data",$corrida->data); 
	$stmt->bindParam("cidade",$corrida->cidade); 
	$stmt->bindParam("estado",$corrida->estado); 
	$stmt->bindParam("valorinscricao",$corrida->valorinscricao); 
	$stmt->bindParam("status",$corrida->status); 
	return $stmt;
}

function addCorrida()
{
	$corrida = getRequestContents();
	$sql = "INSERT INTO corrida (nome,descricao,data,cidade,estado,valorinscricao,status) ".
						"VALUES (:nome,:descricao,:data,:cidade,:estado,:valorinscricao,:status) "; 
	$stmt = bindCorridaParams($sql, $corrida);
	$stmt->execute();
	$corrida->idcorrida = $conn->lastInsertId();
	
	http_response_code(201);
	echo json_encode($corrida);
}

function getCorrida($id)
{
	$sql = "SELECT * FROM corrida WHERE idcorrida=:id";
	$stmt = getConn()->prepare($sql);
	$stmt->bindParam("id",$id);
	$stmt->execute();
	$corrida = $stmt->fetchObject();
	
	if ($corrida != false) {
		http_response_code(200);
		echo json_encode($corrida);
	} else {
		http_response_code(404);
		echo "{'message':'Nao existe corrida com esse ID.'}";
	}
}

function updateCorrida($id)
{
	$corrida = getRequestContents();
	$sql = "UPDATE corrida SET nome=:nome,descricao=:descricao,data=:data,cidade=:cidade,estado=:estado,valorinscricao=:valorinscricao,status=:status ".
	               "WHERE idcorrida=:id"; 
	$stmt = bindCorridaParams($sql, $corrida);
	$stmt->bindParam("id",$id);
	$stmt->execute();

	http_response_code(200);
	echo json_encode($corrida);
}

function deleteCorrida($id)
{
	$sql = "DELETE FROM corrida WHERE idcorrida=:id";
	$stmt = getConn()->prepare($sql);
	$stmt->bindParam("id",$id);
	$stmt->execute();

	http_response_code(200);
	echo "{'message':'Corrida apagada'}";
}

function getCorredores() {
	$stmt = getConn()->query("SELECT * FROM corredor");
	$corredores = $stmt->fetchAll(PDO::FETCH_OBJ);

	http_response_code(200);
	echo '{"corredores":'.json_encode($corredores)."}";
}

function bindCorredorParams($sql, $corredor)
{
	$stmt = getConn()->prepare($sql);
	$stmt->bindParam("nome",$corredor->nome); 
	$stmt->bindParam("datanascimento",$corredor->datanascimento); 
	$stmt->bindParam("cidade",$corredor->cidade); 
	$stmt->bindParam("estado",$corredor->estado); 
	$stmt->bindParam("status",$corredor->status); 
	return $stmt;
}

function addCorredor()
{
	$corredor = getRequestContents();
	$sql = "INSERT INTO corredor (nome,datanascimento,cidade,estado,status) ".
	                    "VALUES (:nome,:datanascimento,:cidade,:estado,:status) "; 
	$stmt = bindCorredorParams($sql, $corredor); 
	$stmt->execute();
	$corredor->idcorredor = $conn->lastInsertId();
	
	http_response_code(201);
	echo json_encode($corredor);
}

function getCorredor($id)
{
	$sql = "SELECT * FROM corredor WHERE idcorredor=:id";
	$stmt = getConn()->prepare($sql);
	$stmt->bindParam("id",$id);
	$stmt->execute();
	$corredor = $stmt->fetchObject();
	
	if ($corredor != false) {
		http_response_code(200);
		echo json_encode($corredor);
	} else {
		http_response_code(404);
		echo "{'message':'Nao existe corredor com esse ID.'}";
	}
}

function updateCorredor($id)
{
	$corrida = getRequestContents();
	$sql = "UPDATE corredor SET nome=:nome,datanascimento=:datanascimento,cidade=:cidade,estado=:estado,status=:status ".
	               "WHERE idcorredor=:id"; 
	$stmt = bindCorredorParams($sql, $corredor);
	$stmt->bindParam("id",$id);
	$stmt->execute();

	http_response_code(200);
	echo json_encode($corredor);
}

function deleteCorredor($id)
{
	$sql = "DELETE FROM corredor WHERE idcorredor=:id";
	$stmt = getConn()->prepare($sql);
	$stmt->bindParam("id",$id);
	$stmt->execute();

	http_response_code(200);
	echo "{'message':'Corredor apagado'}";
}

function getCorredoresNaCorrida($id)
{
	$conn = getConn();
	$sql = "SELECT c.* FROM inscricao i INNER JOIN corredor c ON (i.corredor_idcorredor = c.idcorredor) ".
				      "WHERE corrida_idcorrida=:id"; 
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id",$id);
	$stmt->execute();
	$corredores = $stmt->fetchAll(PDO::FETCH_OBJ);

	http_response_code(200);
	echo '{"corredores":'.json_encode($corredores)."}";
}

function getCorridasDoCorredor($id)
{
	$conn = getConn();
	$sql = "SELECT c.* FROM inscricao i INNER JOIN corrida c ON (i.corrida_idcorrida = c.idcorrida) ".
				      "WHERE corredor_idcorredor=:id"; 
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id",$id);
	$stmt->execute();
	$corridas = $stmt->fetchAll(PDO::FETCH_OBJ);

	http_response_code(200);
	echo '{"corridas":'.json_encode($corridas)."}";
}

function inscreveCorredorNaCorrida($idCorrida, $idCorredor)
{
	$sql = "INSERT INTO inscricao (corrida_idcorrida, corredor_idcorredor, statuspagamento) ".
	                   "VALUES (:id_corrida,:id_corredor, false) "; // false pq supõe que primeiro só insere e depois registra o pagamento
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id_corrida",$idCorrida);
	$stmt->bindParam("id_corredor", $idCorredor);
	$stmt->execute();
	http_response_code(201);
}

function updateInscricao($idCorrida, $idCorredor)
{
	$inscricao = getRequestContents();
	$sql = "UPDATE inscricao SET statuspagamento=:status_pgto,posicao=:posicao,tempo=:tempo ".
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

function getInscricao($idCorredor, $idCorrida)
{
	$sql = "SELECT * FROM inscricao WHERE corredor_idcorredor=:idCorredor AND corrida_idcorrida=:idCorrida";
	$stmt = getConn()->prepare($sql);
	$stmt->bindParam("idCorredor",$idCorredor);
	$stmt->bindParam("idCorrida",$idCorrida);
	$stmt->execute();
	$inscricao = $stmt->fetchObject();
	
	if ($inscricao != false) {
		http_response_code(200);
		echo json_encode($inscricao);
	} else {
		http_response_code(404);
		echo "{'message':'Corredor nao esta inscrito nessa corrida.'}";
	}
}

?>
