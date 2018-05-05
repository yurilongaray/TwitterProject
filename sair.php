<?php 
	session_start();

	unset($_SESSION['id']);
	unset($_SESSION['usuario']);
	unset($_SESSION['email']);
	unset($_SESSION['senha']);

	echo "Usuário Deslogado";


	//Certificando que está fechando a sessão
	session_unset();
	mysqli_close();
?>

<a href="index.php">Voltar</a>