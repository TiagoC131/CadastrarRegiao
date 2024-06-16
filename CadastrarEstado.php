<?php

require_once('../../conexao/conexaoBD.php');

if(isset($_GET['modalRegiao_inputNomeCadastrarEstado'])){
	$cadastrarEstado_Nome = $_GET['modalRegiao_inputNomeCadastrarEstado'];
	$cadastrarEstado_Uf = $_GET['modalRegiao_inputUfCadastrarEstado'];
	$cadastrarEstado_IdPais = $_GET['modalRegiao_inputIdPaisCadastrarEstado'];
	$fuso = new DateTimeZone('America/New_York');
	$dataHora_atual = new DateTime();
	$dataHora_atual->setTimezone($fuso);
	$dataCadastrarEstado = $dataHora_atual->format("Y/m/d H:i:s");
	
				
		//Transformar o Nome do Estado em Array
		$array_cadastrarEstado_Nome = (array) $cadastrarEstado_Nome;
		$array_cadastrarEstado_Uf = (array) $cadastrarEstado_Uf;
		//Array de Marcas Vinculadas
		$estadosCadastrados = [];
		$ufCadastradas = [];
		$consulta_estadosVinculados = [];

	
	if ($cadastrarEstado_IdPais === '') {
		$retorna_cad_Estado = ['erro' => false, 'msg' => "Primeiramente você deve Selecionar um País!"];

	}else{
		if ($cadastrarEstado_Nome === ''){
			$retorna_cad_Estado = ['erro' => false, 'msg' => "Digite o nome do Estado que deseja cadastrar!"];

		}else{
			if ($cadastrarEstado_Uf === ''){
				
				//Buscar apenas as Marcas Vinculadas a Categoria no BD
				$consulta_Estado = $conexaoBD->prepare("
					SELECT
						estados.id,
						estados.nome,
						estados.uf,
						estados.id_pais,
						estados.removido
					FROM
						estados
					WHERE 
						estados.id_pais=:relacao_id_pais");
				$dataConsulta_Estado = ['relacao_id_pais' => $cadastrarEstado_IdPais];	
				$consulta_Estado->execute($dataConsulta_Estado);	
				$registros_EstadosCadastrados = $consulta_Estado->fetchALL(PDO::FETCH_ASSOC); /* */
								
				//Array Marcas Vinculadas
				for ($i_estadosCadastrados = 0; $i_estadosCadastrados <= $registros_EstadosCadastrados; $i_estadosCadastrados++){	
					foreach ($registros_EstadosCadastrados as $linha_EstadosCadastrados){ 
						/*$estadosCadastrados [] = $linha_EstadosCadastrados['nome_produto_marca']; /* */
						$estadosCadastrados [($linha_EstadosCadastrados['id'])] = $linha_EstadosCadastrados['nome'];
					}//Fim do foreach		
					break; //Fechar foreach 	
				}//Fim do for /* */
				
				
				//Função para Buscar apenas os Estados Vinculadas
				$array_2D_estadosVinculados [] = array_diff($array_cadastrarEstado_Nome, $estadosCadastrados); /* */
				
				//Array - Transformar Array Bidimensional em Array Simples
				for ($array_2D_i_estadosCadastrados = 0; $array_2D_i_estadosCadastrados <= $estadosCadastrados; $array_2D_i_estadosCadastrados++){	
					foreach ($array_2D_estadosVinculados[0] as $array_2D_linha_EstadosVinculados_id => $array_2D_linha_EstadosVinculados_nome){
						$array_2D_id = $array_2D_linha_EstadosVinculados_id;
						$array_2D_nome = $array_2D_linha_EstadosVinculados_nome;
						$consulta_estadosVinculados [$array_2D_id] = $array_2D_nome; /* */	
					}//Fim do foreach
					break; //Fechar foreach 	
				}//Fim do for

					if (!empty($consulta_estadosVinculados)){

						//echo ("<pre>");
						//print_r($consulta_estadosVinculados);
						//echo ("</pre>");
						
//Adicionar Função para Cadastrar Estado sem a Sigla UF

								$retorna_cad_Estado = ['erro' => false, 'msg' => "Estado OK!"];
								
					}else{
						$retorna_cad_Estado = ['erro' => false, 'msg' => "Você já possui este Estado cadastrado a esse País!"];
					}

			}else{ //if ($cadastrarEstado_Uf != ''){
				
				//Buscar apenas as Marcas Vinculadas a Categoria no BD
				$consulta_Estado = $conexaoBD->prepare("
					SELECT
						estados.id,
						estados.nome,
						estados.uf,
						estados.id_pais,
						estados.removido
					FROM
						estados
					WHERE 
						estados.id_pais=:relacao_id_pais");
				$dataConsulta_Estado = ['relacao_id_pais' => $cadastrarEstado_IdPais];	
				$consulta_Estado->execute($dataConsulta_Estado);	
				$registros_EstadosCadastrados = $consulta_Estado->fetchALL(PDO::FETCH_ASSOC); /* */
				
				//Array Marcas Vinculadas
				for ($i_estadosCadastrados = 0; $i_estadosCadastrados <= $registros_EstadosCadastrados; $i_estadosCadastrados++){	
					foreach ($registros_EstadosCadastrados as $linha_EstadosCadastrados){ 
						/*$estadosCadastrados [] = $linha_EstadosCadastrados['nome_produto_marca']; /* */
						$estadosCadastrados [($linha_EstadosCadastrados['id'])] = $linha_EstadosCadastrados['nome'];
					}//Fim do foreach		
					break; //Fechar foreach 	
				}//Fim do for /* */
				
				//Função para Buscar apenas os Estados Vinculadas
				$array_2D_estadosVinculados [] = array_diff($array_cadastrarEstado_Nome, $estadosCadastrados); /* */
				
				//Array - Transformar Array Bidimensional em Array Simples
				for ($array_2D_i_estadosCadastrados = 0; $array_2D_i_estadosCadastrados <= $estadosCadastrados; $array_2D_i_estadosCadastrados++){	
					foreach ($array_2D_estadosVinculados[0] as $array_2D_linha_EstadosVinculados_id => $array_2D_linha_EstadosVinculados_nome){
						$array_2D_id = $array_2D_linha_EstadosVinculados_id;
						$array_2D_nome = $array_2D_linha_EstadosVinculados_nome;
						$consulta_estadosVinculados [$array_2D_id] = $array_2D_nome; /* */	
					}//Fim do foreach
					break; //Fechar foreach 	
				}//Fim do for

					if (!empty($consulta_estadosVinculados)){
				
						//Array Marcas Vinculadas
						for ($i_ufCadastradas = 0; $i_ufCadastradas <= $registros_EstadosCadastrados; $i_ufCadastradas++){	
							foreach ($registros_EstadosCadastrados as $linha_UfCadastradas){ 
								/*$ufCadastradas [] = $linha_UfCadastradas['nome_produto_marca']; /* */
								$ufCadastradas [($linha_UfCadastradas['id'])] = $linha_UfCadastradas['uf'];
							}//Fim do foreach		
							break; //Fechar foreach 	
						}//Fim do for /* */
						
							//Função para Buscar apenas as UF Vinculadas
							$array_2D_ufVinculados [] = array_diff($array_cadastrarEstado_Uf, $ufCadastradas); /* */
							
							//Array - Transformar Array Bidimensional em Array Simples
							for ($array_2D_i_ufCadastrados = 0; $array_2D_i_ufCadastrados <= $estadosCadastrados; $array_2D_i_ufCadastrados++){	
								foreach ($array_2D_ufVinculados[0] as $array_2D_linha_UFVinculadas_id => $array_2D_linha_UFVinculadas_nome){
									$array_2D_uf_id = $array_2D_linha_UFVinculadas_id;
									$array_2D_uf_nome = $array_2D_linha_UFVinculadas_nome;
									$consulta_ufVinculados [$array_2D_uf_id] = $array_2D_uf_nome; /* */	
								}//Fim do foreach
								break; //Fechar foreach 	
							}//Fim do for


						echo ("<pre>");
						print_r($array_2D_ufVinculados);
						echo ("</pre>");
		

							if (!empty($consulta_ufVinculados)){

								$retorna_cad_Estado = ['erro' => false, 'msg' => "Você já possui essa UF cadastrada a um Estado nesse País!"];
							
							}else{
//Iniciar Função para Cadastrar Estado e UF
								$retorna_cad_Estado = ['erro' => false, 'msg' => "Estado e UF - Iniciar Cadastro de Estado!"];
							
							}

					}else{
						$retorna_cad_Estado = ['erro' => false, 'msg' => "Você já possui este Estado cadastrado a esse País!"];
					}

			}			
				
		}
	}
				echo json_encode($retorna_cad_Estado);
		
}


?>
