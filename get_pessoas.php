<?php

	session_start();

	if(!isset($_SESSION['usuario'])){
		header('Location: index.php?erro=1');
	}

	require_once('db.class.php');

	$nome_pessoa = $_POST['nome_pessoa']; //Parâmetro informado no formulário que foi enviado pelo AJAX
	$id_usuario = $_SESSION['id_usuario'];

	$objDb = new db();
	$link = $objDb->connect_db();

	//LIKE serve para verificar se contem aqueles caracteres digitados no campo
	//Like é muito utilizado em pesquisas
	//o %x% serve para considerar antes e depois da palavra
	//<> diferente
	$sql = " SELECT u.*, us.* ";
	$sql.= " FROM usuarios AS u ";
	$sql.= " LEFT JOIN usuarios_seguidores AS us ";
	$sql.= " ON (us.id_usuario = $id_usuario AND u.id = us.seguindo_id_usuario) ";
	$sql.= " WHERE u.usuario like '%$nome_pessoa%' AND u.id <> $id_usuario "; //Para que o ID do usuário seja diferente do ID usuario da sessão
	
	$resultado_id = mysqli_query($link, $sql);

	if($resultado_id){ //Se a query foi executada com sucesso

		while($registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC)){ //Percorremos todos os registros de retorno da variavel registro
			echo '<a href="#" class="list-group-item">';
				echo '<strong>'.$registro['usuario'].'</strong> <small> - '.$registro['email'].'</small>';
				echo '<p class="list-group-item-text pull-right">'; //botão

					$esta_seguindo_usuario_sn = isset($registro['id_usuario_seguidor']) && !empty($registro['id_usuario_seguidor']) ? 'S' : 'N'; //Verificando se não é vazio nem nulo e então atribuimos a letra S

					$btn_seguir_display = 'block';
					$btn_deixar_seguir_display = 'block';

					if($esta_seguindo_usuario_sn == 'N'){
						$btn_deixar_seguir_display = 'none'; //Ocultando botão de btn_deixar_seguir_display
					} else {
						$btn_seguir_display = 'none';
					}
					//tag strong destaca o registro
					//TODOS OS BOTÕES ESTÃO COM UMA CLASSE PARA IDENTIFICAR O SEGUIR (BTN_SEGUIR) PARA CAPTURAR O EVENTO DE CLICK DO AJAX
					//btn_seguir_'.$registro['id'] para que o campo nunca se repita pois o ID é unico
					echo '<button type="button" id="btn_seguir_'.$registro['id'].'" style="display: '.$btn_seguir_display.'" class="btn btn-default btn_seguir" data-id_usuario="'.$registro['id'].'">Seguir</button>';
					echo '<button type="button" id="btn_deixar_seguir_'.$registro['id'].'" style="display: '.$btn_deixar_seguir_display.'" class="btn btn-primary btn_deixar_seguir" data-id_usuario="'.$registro['id'].'">Deixar de Seguir</button>';
				echo '</p>';
				echo '<div class="clearfix"></div>'; //CORRIGE OS PROBLEMAS EM QUE OS ITENS FLUTUAM INCORRETAMENTE
			echo '</a>';
		}

	} else {
		echo $sql . "<br>";
		echo 'Erro na consulta de usuários no banco de dados!';
	}

?>