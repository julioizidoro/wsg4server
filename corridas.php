<?php

// *********************************************************************************
// Métodos auxiliares
// *********************************************************************************

function bindCorridaParams($sql, $corrida, $conn = null)
{
	if (!isset($conn))
	{
		$conn = getConn();
	}
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("nome",$corrida->nome); 
	$stmt->bindParam("descricao",$corrida->descricao); 
	$stmt->bindParam("data",$corrida->data); 
	$stmt->bindParam("cidade",$corrida->cidade); 
	$stmt->bindParam("estado",$corrida->estado); 
	$stmt->bindParam("valorinscricao",$corrida->valorinscricao); 
	$stmt->bindParam("status",$corrida->status); 
	return $stmt;
}

function selectCorridaById($id)
{
	$sql = "SELECT * FROM corrida WHERE idcorrida=:id";
	$stmt = getConn()->prepare($sql);
	$stmt->bindParam("id",$id);
	$stmt->execute();
	return $stmt->fetchObject();
}

// *********************************************************************************
// Métodos CRUD
// *********************************************************************************

function getCorridas() 
{
	try
	{
		$stmt = getConn()->query("SELECT * FROM corrida");
		$corridas = $stmt->fetchAll(PDO::FETCH_OBJ);

		header('X-PHP-Response-Code: 200', true, 200);
		echo '{"corridas":'.json_encode($corridas)."}";
	}
	catch (Exception $ex)
	{
		header('X-PHP-Response-Code: 500', true, 500);
		echo "{'message':'Ocorreu um erro processando o comando. Detalhes: " . $ex->getMessage() ."'}";	
	}		
}

function addCorrida()
{
	try
	{
		$corrida = getRequestContents();
		$sql = "INSERT INTO corrida (nome,descricao,data,cidade,estado,valorinscricao,status) ".
							"VALUES (:nome,:descricao,:data,:cidade,:estado,:valorinscricao,:status) "; 
		$conn = getConn();
		$stmt = bindCorridaParams($sql, $corrida, $conn);
		if ($stmt->execute())
		{
			$corrida->idcorrida = $conn->lastInsertId();
			
			header('X-PHP-Response-Code: 201', true, 201);
			echo json_encode($corrida);
		}
		else
		{
			header('X-PHP-Response-Code: 412', true, 412);
			echo "{'message':'Os dados da corrida não puderam ser inseridos.'}";
		}
	}
	catch (Exception $ex)
	{
		header('X-PHP-Response-Code: 500', true, 500);
		echo "{'message':'Ocorreu um erro processando o comando. Detalhes: " . $ex->getMessage() ."'}";	
	}		
}

function getCorrida($id)
{
	try
	{
		$corrida = selectCorridaById($id);
		
		if ($corrida != false) 
		{		
			header('X-PHP-Response-Code: 200', true, 200);
			echo json_encode($corrida);
		} 
		else 
		{
			header('X-PHP-Response-Code: 404', true, 404);
			echo "{'message':'Nao existe corrida com esse ID.'}";
		}
	}
	catch (Exception $ex)
	{
		header('X-PHP-Response-Code: 500', true, 500);
		echo "{'message':'Ocorreu um erro processando o comando. Detalhes: " . $ex->getMessage() ."'}";	
	}	
}

function updateCorrida($id)
{
	try
	{
		$corrida = selectCorridaById($id);
		
		if ($corrida != false) 
		{
			$corrida = getRequestContents();
			$sql = "UPDATE corrida SET nome=:nome,descricao=:descricao,data=:data,cidade=:cidade,estado=:estado,valorinscricao=:valorinscricao,status=:status ".
						   "WHERE idcorrida=:id"; 
			$stmt = bindCorridaParams($sql, $corrida);
			$stmt->bindParam("id",$id);
			if ($stmt->execute())
			{
				header('X-PHP-Response-Code: 200', true, 200);
				echo json_encode($corrida);
			}
			else
			{
				header('X-PHP-Response-Code: 412', true, 412);
				echo "{'message':'Os dados da corrida não puderam ser atualizados.'}";
			}
		}
		else 
		{
			header('X-PHP-Response-Code: 404', true, 404);
			echo "{'message':'Nao existe corrida com esse ID.'}";
		}		
	}
	catch (Exception $ex)
	{
		header('X-PHP-Response-Code: 500', true, 500);
		echo "{'message':'Ocorreu um erro processando o comando. Detalhes: " . $ex->getMessage() ."'}";	
	}			
}

function deleteCorrida($id)
{
	try
	{
		$corrida = selectCorridaById($id);
		
		if ($corrida != false) 
		{
			$sql = "DELETE FROM corrida WHERE idcorrida=:id";
			$stmt = getConn()->prepare($sql);
			$stmt->bindParam("id",$id);
			if ($stmt->execute())
			{
				header('X-PHP-Response-Code: 200', true, 200);
				echo "{'message':'Corrida apagada'}";
			}
			else // vai entrar aqui em algum caso?
			{
				header('X-PHP-Response-Code: 412', true, 412);
				echo "{'message':'A corrida não pôde ser excluída.'}";
			}
		}
		else 
		{
			header('X-PHP-Response-Code: 404', true, 404);
			echo "{'message':'Nao existe corrida com esse ID.'}";
		}		
	}
	catch (Exception $ex)
	{
		header('X-PHP-Response-Code: 500', true, 500);
		echo "{'message':'Ocorreu um erro processando o comando. Detalhes: " . $ex->getMessage() ."'}";	
	}	
}

function getUltimasCorridas(){
	
	try
	{
		$sql = "SELECT * FROM corrida WHERE data < sysdate() order by data DESC";
		$stmt = getConn()->query($sql);
		$corridas = $stmt->fetchAll(PDO::FETCH_OBJ);

		header('X-PHP-Response-Code: 200', true, 200);
		echo '{"corridas":'.json_encode($corridas)."}";
	}
	catch (Exception $ex)
	{
		header('X-PHP-Response-Code: 500', true, 500);
		echo "{'message':'Ocorreu um erro processando o comando. Detalhes: " . $ex->getMessage() ."'}";	
	}	
}

function getProximasCorridas(){
	
	try
	{
		$sql = "SELECT * FROM corrida WHERE data >= sysdate() order by data ASC";
		$stmt = getConn()->query($sql);
		$corridas = $stmt->fetchAll(PDO::FETCH_OBJ);

		header('X-PHP-Response-Code: 200', true, 200);
		echo '{"corridas":'.json_encode($corridas)."}";
	}
	catch (Exception $ex)
	{
		header('X-PHP-Response-Code: 500', true, 500);
		echo "{'message':'Ocorreu um erro processando o comando. Detalhes: " . $ex->getMessage() ."'}";	
	}	
}

?>
