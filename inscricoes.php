<?php

function getCorredoresNaCorrida($id)
{
	try
	{
		$corrida = selectCorridaById($id);

		if ($corrida != false) 
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

function getCorridasDoCorredor($id)
{
	try
	{
		$corredor = selectCorredorById($id);
		
		if ($corredor != false) 
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
		else 
		{
			header('X-PHP-Response-Code: 404', true, 404);
			echo "{'message':'Nao existe corredor com esse ID.'}";
		}		
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
		$corredor = selectCorredorById($idCorredor);
		$corrida = selectCorridaById($idCorrida);

		if ($corrida == false) 
		{		
			header('X-PHP-Response-Code: 404', true, 404);
			echo "{'message':'Não existe corrida com esse ID.'}";
		}
		elseif ($corredor == false) {
			header('X-PHP-Response-Code: 404', true, 404);
			echo "{'message':'Não existe corredor com esse ID.'}";
		}
		elseif ($corrida->status != ("Aberta" || "Confirmada")) {
			header('X-PHP-Response-Code: 403', true, 403);
			echo "{'message':'A corrida não está aberta. Não podem ser feitas inscrições.'}";
		}
		elseif ($corredor->status != "Ativo") {
			header('X-PHP-Response-Code: 403', true, 403);
			echo "{'message':'O corredor está inativo. A inscrição não pode ser efetuada.'}";
		}
		else
		{
			$sql = "INSERT INTO inscricao (corrida_idcorrida, corredor_idcorredor, statuspagamento) ".
							   "VALUES (:id_corrida,:id_corredor, false) "; // false pq supõe que primeiro só insere e depois registra o pagamento
			$conn = getConn();
			$stmt = $conn->prepare($sql);
			$stmt->bindParam("id_corrida",$idCorrida);
			$stmt->bindParam("id_corredor", $idCorredor);
			if ($stmt->execute()) {
				header('X-PHP-Response-Code: 201', true, 201);
				echo "{'message':'Inscrção realizada com sucesso'}";
			}	
			else // não deve entrar aqui em nenhum caso
			{
				header('X-PHP-Response-Code: 404', true, 404);
				echo "{'message':'Não foi possível concluir a operação. ID da corrida ou ID do corredor não existe'}";	
			}
		}	
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
		$corredor = selectCorredorById($idCorredor);
		$corrida = selectCorridaById($idCorrida);

		if ($corredor != false and $corrida != false) 
		{
			$inscricao = getRequestContents();
			$sql = "UPDATE inscricao SET statuspagamento=:status_pgto, posicao=:posicao, tempo=:tempo ".
						   "WHERE corrida_idcorrida=:id_corrida AND corredor_idcorredor=:id_corredor";

			$conn = getConn();
			$stmt = $conn->prepare($sql);
			$stmt->bindParam("status_pgto", $inscricao->statuspagamento); 
			$stmt->bindParam("posicao", $inscricao->posicao); 
			$stmt->bindParam("tempo", $inscricao->tempo); 
			$stmt->bindParam("id_corrida", $idCorrida); 
			$stmt->bindParam("id_corredor", $idCorredor); 
			if ($stmt->execute())
				header('X-PHP-Response-Code: 200', true, 200);
			else
			{
				header('X-PHP-Response-Code: 412', true, 412);
				echo "{'message':'Não foi possível concluir a operação.'}";
			}
		}
		else 	
		{
			header('X-PHP-Response-Code: 404', true, 404);
			echo "{'message':'ID da corrida ou ID do corredor não existe'}";	
		}	
	}
	catch (Exception $ex)
	{
		header('X-PHP-Response-Code: 500', true, 500);
		echo "{'message':'Ocorreu um erro processando o comando. Detalhes: " . $ex->getMessage() ."'}";	
	}	
}

function getInscricaoCorrida($idCorrida, $idCorredor)
{
	try
	{
		$corrida = selectCorridaById($idCorrida);
		$corredor = selectCorredorById($idCorredor);
		
		if ($corrida == false) 
		{		
			header('X-PHP-Response-Code: 404', true, 404);
			echo "{'message':'Não existe corrida com esse ID.'}";
		}
		elseif ($corredor == false) {
			header('X-PHP-Response-Code: 404', true, 404);
			echo "{'message':'Não existe corredor com esse ID.'}";
		}
		else 
		{
			$sql = "SELECT * FROM inscricao WHERE corredor_idcorredor=:idCorredor AND corrida_idcorrida=:idCorrida";
			$stmt = getConn()->prepare($sql);
			$stmt->bindParam("idCorredor",$idCorredor);
			$stmt->bindParam("idCorrida",$idCorrida);
			$stmt->execute();
			$inscricao = $stmt->fetchObject();
			
			if ($inscricao != false) 
			{
				if ($corrida->status != "Confirmada")
					echo "{'message':'Corrida não está aberta. Não podem ser feitas inscrições.'}";
				else {	
				header('X-PHP-Response-Code: 200', true, 200);
				echo json_encode($inscricao);
			}
			} 
			else 
			{
				header('X-PHP-Response-Code: 404', true, 404);
				echo "{'message':'Corredor nao esta inscrito nessa corrida.'}";
			}
		}	
	}
	catch (Exception $ex)
	{
		header('X-PHP-Response-Code: 500', true, 500);
		echo "{'message':'Ocorreu um erro processando o comando. Detalhes: " . $ex->getMessage() ."'}";	
	}		
}


function getInscricaoCorredor($idCorredor, $idCorrida)
{
	getInscricaoCorrida($idCorrida, $idCorredor);
}

function deleteInscricaoCorrida($idCorrida, $idCorredor)
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

function deleteInscricaoCorredor($idCorredor, $idCorrida)
{
	deleteInscricaoCorrida($idCorrida, $idCorredor);
}
?>
