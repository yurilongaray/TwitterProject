<?php

	session_start();

	if(!isset($_SESSION['usuario'])){ //Sempre verificar se o usuário está ou não autenticado
		header('Location: index.php?erro=1');
	}

	require_once('db.class.php');

	$id_usuario = $_SESSION['id_usuario'];

	$objDb = new db();
	$link = $objDb->connect_db();
	
	// t apelido da tabela tweet e u apelido da tabela usuarios
	// DATE_FORMAT(t.data_inclusao, '%d %b %Y %T') é uma função nativa para alteração da data
	$sql = " SELECT DATE_FORMAT(t.data_inclusao, '%d %b %Y %T') AS data_inclusao_formatada, t.tweet, u.usuario ";
	$sql.= " FROM tweet AS t JOIN usuarios AS u ON (t.id_usuario = u.id) ";
	$sql.= " WHERE id_usuario = $id_usuario "; //id_usuario tem q ser igual ao id_usuario da sessão
	$sql.= " OR id_usuario IN (select seguindo_id_usuario from usuarios_seguidores where id_usuario = $id_usuario) ";
	//O operador In irá verificar se o campo id_usuario corresponde ao parâmetro inserido dentro dos parẽnteses do In
	$sql.= " ORDER BY data_inclusao DESC "; //Ordenar os tweets pela data de inclusão do POST efetuado no home.php e inserido no inclui_tweet.php

	$resultado_id = mysqli_query($link, $sql);

	if($resultado_id){ //irá conter o resource para o resultado da query, se se ele for válido: 

		while($registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC)){ //Para executar até o ultimo registro da consulta
			//MUITO IMPORTANTE O CÓDIGO ABAIXO, INSERINDO TAGS E CLASSES em uma estrutura de repetição
			echo '<a href="#" class="list-group-item">';
				echo '<h4 class="list-group-item-heading">'. $registro['usuario'] . ' <small> - ' . $registro['data_inclusao_formatada'].'</small></h4>';
				echo '<p class="list-group-item-text">' . $registro['tweet'] . '</p>';
			echo '</a>';
		}

	} else {
		echo $sql . "<br>";
		echo 'Erro na consulta de tweets no banco de dados!';
	}

?>