<?php

// *********************************************************************************
// Métodos auxiliares
// *********************************************************************************

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

// *********************************************************************************
// Métodos CRUD
// *********************************************************************************

function getCorridas() {
	$stmt = getConn()->query("SELECT * FROM corrida");
	$corridas = $stmt->fetchAll(PDO::FETCH_OBJ);

	header('X-PHP-Response-Code: 200', true, 200);
	echo '{"corridas":'.json_encode($corridas)."}";
}

function addCorrida()
{
	$corrida = getRequestContents();
	$sql = "INSERT INTO corrida (nome,descricao,data,cidade,estado,valorinscricao,status) ".
						"VALUES (:nome,:descricao,:data,:cidade,:estado,:valorinscricao,:status) "; 
	$stmt = bindCorridaParams($sql, $corrida);
	$stmt->execute();
	$corrida->idcorrida = $conn->lastInsertId();
	
	header('X-PHP-Response-Code: 201', true, 201);
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
		header('X-PHP-Response-Code: 200', true, 200);
		echo json_encode($corrida);
	} else {
		header('X-PHP-Response-Code: 404', true, 404);
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

	header('X-PHP-Response-Code: 200', true, 200);
	echo json_encode($corrida);
}

function deleteCorrida($id)
{
	$sql = "DELETE FROM corrida WHERE idcorrida=:id";
	$stmt = getConn()->prepare($sql);
	$stmt->bindParam("id",$id);
	$stmt->execute();

	header('X-PHP-Response-Code: 200', true, 200);
	echo "{'message':'Corrida apagada'}";
}

?>
