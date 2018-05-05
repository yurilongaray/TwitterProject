<?php

	require_once('db.class.php');

	$usuario = $_POST['usuario'];
	$email = $_POST['email'];
	$senha = md5($_POST['senha']); //criptografando a senha com md5 32 caracteres

	$objDb = new db();
	$link = $objDb->connect_db();

	$usuario_existe = false; //iniciando variaveis com false
	$email_existe = false; //iniciando variaveis com false

	//verificar se o usuário já
	$sql = " SELECT * FROM usuarios WHERE usuario = '$usuario' ";
	if($resultado_id = mysqli_query($link, $sql)) {

		$dados_usuario = mysqli_fetch_array($resultado_id);

		if(isset($dados_usuario['usuario'])){//Verifica se existe 
			$usuario_existe = true; //Se já existe, vai para o if e então retorna a página de registra
		}
	} else {
		echo 'Erro ao tentar localizar o registro de usuário';
	}

	//verificar se o e-mail já
	$sql = " SELECT * FROM usuarios WHERE email = '$email' ";
	if($resultado_id = mysqli_query($link, $sql)) {

		$dados_usuario = mysqli_fetch_array($resultado_id);

		if(isset($dados_usuario['email'])){ //Verifica se existe 
			$email_existe = true;//Se já existe, vai para o if e então retorna a página de registra
		} 
	} else {
		echo 'Erro ao tentar localizar o registro de email';
	}


	if($usuario_existe || $email_existe){ //VVerifica se usuario ou email são true

		$retorno_get = '';

		if($usuario_existe){
			$retorno_get.= "erro_usuario=1&"; //& determinar onde acaba para então verificar outra variavel com get
		}

		if($email_existe){
			$retorno_get.= "erro_email=1&"; //.= para concatenar com a variavel e caso o usuario tbm exista, ficará: 
			//erro_usuario=1&erro_email=1&
		}

		header('Location: inscrevase.php?'.$retorno_get);
		die(); //Para que não seja inserido nenhum usuario no Banco de dados
	}

	$sql = " INSERT INTO usuarios(usuario, email, senha) VALUES ('$usuario', '$email', '$senha') ";

	//executar a query
	if(mysqli_query($link, $sql)){
		echo 'Usuário registrado com sucesso!';
	} else {
		echo 'Erro ao registrar o usuário!';
	}


?>