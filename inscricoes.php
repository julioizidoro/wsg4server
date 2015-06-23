<?php

function getCorredoresNaCorrida($id)
{
	try
	{
		$conn = getConn();
		$sql = "SELECT c.* FROM inscricao i INNER JOIN corredor c ON (i.corredor_idcorredor = c.idcorredor) ".
						  "WHERE corrida_idcorrida=:id"; 
		$stmt = $conn->prepare($sql);
		$stmt->bindParam("id",$id);
		$stmt->execute();
		$corredores = $stmt->fetchAll(PDO::FETCH_OBJ);

		header('X-PHP-Response-Code: 200', true, 200);
		echo '{"corredores":'.json_encode($corredores)."}";
	}
	catch (Exception $ex)
	{
		header('X-PHP-Response-Code: 500', true, 500);
		echo "{'message':'Ocorreu um erro processando o comando. Detalhes: " . $ex->getMessage() ."'}";	
	}
}

function getCorridasDoCorredor($id)
{
	try
	{
		$conn = getConn();
		$sql = "SELECT c.* FROM inscricao i INNER JOIN corrida c ON (i.corrida_idcorrida = c.idcorrida) ".
						  "WHERE corredor_idcorredor=:id"; 
		$stmt = $conn->prepare($sql);
		$stmt->bindParam("id",$id);
		$stmt->execute();
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

function inscreveCorredorNaCorrida($idCorrida, $idCorredor)
{
	try
	{
		$sql = "INSERT INTO inscricao (corrida_idcorrida, corredor_idcorredor, statuspagamento) ".
						   "VALUES (:id_corrida,:id_corredor, false) "; // false pq supõe que primeiro só insere e depois registra o pagamento
		$conn = getConn();
		$stmt = $conn->prepare($sql);
		$stmt->bindParam("id_corrida",$idCorrida);
		$stmt->bindParam("id_corredor", $idCorredor);
		if ($stmt->execute())
			header('X-PHP-Response-Code: 201', true, 201);
		else
			header('X-PHP-Response-Code: 412', true, 412);
	}
	catch (Exception $ex)
	{
		header('X-PHP-Response-Code: 500', true, 500);
		echo "{'message':'Ocorreu um erro processando o comando. Detalhes: " . $ex->getMessage() ."'}";	
	}	
}

function updateInscricao($idCorrida, $idCorredor)
{
	try
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
		if ($stmt->execute())
			header('X-PHP-Response-Code: 200', true, 200);
		else
			header('X-PHP-Response-Code: 412', true, 412);
	}
	catch (Exception $ex)
	{
		header('X-PHP-Response-Code: 500', true, 500);
		echo "{'message':'Ocorreu um erro processando o comando. Detalhes: " . $ex->getMessage() ."'}";	
	}	
}

function getInscricao($idCorredor, $idCorrida)
{
	try
	{
		$sql = "SELECT * FROM inscricao WHERE corredor_idcorredor=:idCorredor AND corrida_idcorrida=:idCorrida";
		$stmt = getConn()->prepare($sql);
		$stmt->bindParam("idCorredor",$idCorredor);
		$stmt->bindParam("idCorrida",$idCorrida);
		$stmt->execute();
		$inscricao = $stmt->fetchObject();
		
		if ($inscricao != false) 
		{
			header('X-PHP-Response-Code: 200', true, 200);
			echo json_encode($inscricao);
		} 
		else 
		{
			header('X-PHP-Response-Code: 404', true, 404);
			echo "{'message':'Corredor nao esta inscrito nessa corrida.'}";
		}
	}
	catch (Exception $ex)
	{
		header('X-PHP-Response-Code: 500', true, 500);
		echo "{'message':'Ocorreu um erro processando o comando. Detalhes: " . $ex->getMessage() ."'}";	
	}		
}

function deleteInscricao($idCorredor, $idCorrida)
{
	try
	{
		$sql = "DELETE FROM inscricao WHERE corredor_idcorredor=:idCorredor AND corrida_idcorrida=:idCorrida";
		$stmt = getConn()->prepare($sql);
		$stmt->bindParam("idCorredor",$idCorredor);
		$stmt->bindParam("idCorrida",$idCorrida);
		if ($stmt->execute())
		{
			header('X-PHP-Response-Code: 200', true, 200);
			echo "{'message':'Inscricao removida'}";
		}
		else
		{
			header('X-PHP-Response-Code: 404', true, 404);
			echo "{'message':'Não foi possível excluir a inscrição.'}";
		}
	}
	catch (Exception $ex)
	{
		header('X-PHP-Response-Code: 500', true, 500);
		echo "{'message':'Ocorreu um erro processando o comando. Detalhes: " . $ex->getMessage() ."'}";	
	}		
}

?>
