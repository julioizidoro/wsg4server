<?php

// *********************************************************************************
// Métodos auxiliares
// *********************************************************************************

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

// *********************************************************************************
// Métodos CRUD
// *********************************************************************************

function getCorredores() {
	$stmt = getConn()->query("SELECT * FROM corredor");
	$corredores = $stmt->fetchAll(PDO::FETCH_OBJ);

	header('X-PHP-Response-Code: 200', true, 200);
	echo '{"corredores":'.json_encode($corredores)."}";
}

function addCorredor()
{
	$corredor = getRequestContents();
	$sql = "INSERT INTO corredor (nome,datanascimento,cidade,estado,status) ".
	                    "VALUES (:nome,:datanascimento,:cidade,:estado,:status) "; 
	$stmt = bindCorredorParams($sql, $corredor); 
	$stmt->execute();
	$corredor->idcorredor = $conn->lastInsertId();
	
	header('X-PHP-Response-Code: 201', true, 201);
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
		header('X-PHP-Response-Code: 200', true, 200);
		echo json_encode($corredor);
	} else {
		header('X-PHP-Response-Code: 404', true, 404);
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

	header('X-PHP-Response-Code: 200', true, 200);
	echo json_encode($corredor);
}

function deleteCorredor($id)
{
	$sql = "DELETE FROM corredor WHERE idcorredor=:id";
	$stmt = getConn()->prepare($sql);
	$stmt->bindParam("id",$id);
	$stmt->execute();

	header('X-PHP-Response-Code: 200', true, 200);
	echo "{'message':'Corredor apagado'}";
}

?>
