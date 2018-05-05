<?php
	session_start(); //Para abrir as variáveis de sessão

	//Verificar se o usuario da sessao existe para que não entrem usuarios não logados -TESTAR EM ABA ANONIMA-
	if (!isset($_SESSION['usuario'])) { //se o usuario n passar pelo processo de autenticação entrará no if
		header('Location: index.php?erro=1');
	}

	require_once('db.class.php');

	$objDb = new db();
	$link = $objDb->connect_db();

	$id_usuario = $_SESSION['id_usuario'];

	//--qtde de tweets
	$sql = " SELECT COUNT(*) AS qtde_tweets FROM tweet WHERE id_usuario = $id_usuario ";
	$resultado_id = mysqli_query($link, $sql);//COUNT(*) traz o total de registros da consulta
	$qtde_tweets = 0;
	if($resultado_id){ //Testando se a query está OK
		$registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC);
		$qtde_tweets = $registro['qtde_tweets']; //qtde_tweets é o AS (alias) da pesquisa efetuada
	} else {
		echo 'Erro ao executar a query';
	}

	//--qtde de seguidores
	$sql_sguidores = " SELECT COUNT(*) AS qtde_seguidores FROM usuarios_seguidores WHERE seguindo_id_usuario = $id_usuario ";
	$resultado_seg = mysqli_query($link, $sql_sguidores);
	if ($resultado_seg) {
		$registro_seg = mysqli_fetch_array($resultado_seg, MYSQLI_ASSOC);
		$qtde_seguidores = $registro_seg['qtde_seguidores'];
	} else {
		echo 'Erro ao executar a query';
	}
?>

<!DOCTYPE HTML>
<html lang="pt-br">
	<head>
		<meta charset="UTF-8">

		<title>Twitter</title>
		
		<!-- jquery - link cdn -->
		<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>

		<!-- bootstrap - link cdn -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	
		<script type="text/javascript">

			var qnt_tweets = document.getElementById('qtd_tweets');
			console.log(qnt_tweets); 

			$(document).ready( function() {

				//associar o evento de click ao botão
				$('#btn_tweet').click( function() {
					
					if($('#texto_tweet').val().length > 0) { //Verifica se algo foi digitado antes de prosseguir
						
						$.ajax({ //Essa função recebe como parâmetro um Json
							url: 'inclui_tweet.php',
							method: 'post',
							//no data, abaixo, a estrutura segue como data: {chave1: valor, chave2: valor}
							//A chave corresponde ao indice e os valores aos valores dos indices
							//A função serialize retorna um Json
							data: $('#form_tweet').serialize(), //somente um parâmetro neste caso
							success: function(data) { //Caso haja sucesso ele efetua a função
								//alert($('#form_tweet').serialize()); 
								$('#texto_tweet').val('');//Para limpar o campo sempre que enviado o dado e então poder encaminhar um novo	
								atualizaTweet();
							}
						});
					}

				});
				
				function atualizaTweet(){
					//carregar os tweets a partir de uma requisição via ajax

					$.ajax({
						url: 'get_tweet.php',
						success: function(data) { //recupera o retorno
							$('#tweets').html(data); //insere os dados no id tweet, semelhante ao innerHtml do js
							
						}
					});
				}

				atualizaTweet();
				
			});

		</script>

	</head>

	<body>

		<!-- Static navbar -->
	    <nav class="navbar navbar-default navbar-static-top">
	      <div class="container">
	        <div class="navbar-header">
	          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	            <span class="sr-only">Toggle navigation</span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	          <img src="imagens/icone_twitter.png" />
	        </div>
	        
	        <div id="navbar" class="navbar-collapse collapse">
	          <ul class="nav navbar-nav navbar-right">
	            <li><a href="sair.php">Sair</a></li>
	          </ul>
	        </div><!--/.nav-collapse -->
	      </div>
	    </nav>


	    <div class="container">
	    	<div class="col-md-3">
	    		<div class="panel panel-default">
	    			<div class="panel-body">
	    				<h4>Matrícula:	<?= $_SESSION['id_usuario']?></h4>
						<h4>Usuário:	<?= $_SESSION['usuario'] ?>	 </h4>
						<h4>Email:		<?= $_SESSION['email'] ?>	 </h4>
	    				<hr />
	    				<div class="col-md-6">
	    					TWEETS 
	    					<h4> <?= $qtde_tweets ?> </h4>
	    				</div>
	    				<div class="col-md-6">
	    					SEGUIDORES
	    					<h4> <?= $qtde_seguidores ?> </h4>
	    				</div>
	    			</div>
	    		</div>
	    	</div>
	    	
	    	<div class="col-md-6">
	    		<div class="panel panel-default">
	    			<div class="panel-body">
	    				<form id="form_tweet" class="input-group">
	    					<!-- Maxlength para que seja digitado somente 140 caracteres-->
	    					<input type="text" id="texto_tweet" name="texto_tweet" class="form-control" placeholder="O que está acontecendo agora?" maxlength="140" /> 
	    					<span class="input-group-btn">
	    						<button class="btn btn-primary" id="btn_tweet" type="button">Tweet</button>
	    					</span>
	    				</form>
	    			</div>
	    		</div>
				<!-- No id tweets será inserido o post dos tweets -->
	    		<div id="tweets" class="list-group"></div> 

			</div>
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-body">
						<h4><a href="procurar_pessoas.php" style="text-decoration: none;">Procurar por pessoas</h4>
					</div>
				</div>
			</div>
		</div>


	    </div>
	
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	
	</body>
</html>