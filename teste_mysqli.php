<?php

	require_once('db.class.php');

	$sql = " SELECT * FROM usuarios";

	$objDb = new db();
	$link = $objDb->connect_db();

	$resultado_id = mysqli_query($link, $sql);

	if($resultado_id){
		$dados_usuario = array();

		/*
		//mysqli_fetch_array retorna apenas o primeiro registro, a primeira ocorrencia da pesquisa, ou seja, a primeira linha e seus dados
        $dados = mysqli_fetch_array($resultado_query, MYSQLI_NUM); //MYSQLI_NUM faz com que somente os indices num sejam apresentados, os associativos são descartados, facilitando a leitura e podendo futuramente utilizar em relatórios

        $dados2 = mysqli_fetch_array($resultado_query, MYSQLI_ASSOC); //MYSQLI_ASSOC faz o oposto, ao invés de exibir os indices numericos, apresenta os associativos, ou seja, a descrição da coluna do campo

        var_dump($dados);
        echo "<br>";
        var_dump($dados2);
        echo "<br>";
        */

        //Utilizando o while abaixo fará com que todos os registos (não só o primeiro) sejam recuperados e escritos pelo foreach
		while($linha = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC)){
			$dados_usuario[] = $linha;
		}

		foreach($dados_usuario as $usuario){
			echo $usuario['email'];
			echo '<br /><br />';
		}

	} else {
		echo 'Erro na execução da consulta, favor entrar em contato com o admin do site';
	}

?>