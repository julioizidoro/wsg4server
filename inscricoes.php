<?php

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