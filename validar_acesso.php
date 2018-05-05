<?php
    
    session_start(); //Sempre que criarmos uma sessão, devemos iniciar ela para utilizar as variáveis de sessão

    require_once('db.class.php');


    $objDb = new db();
    $objDb->connect_db();

    if (isset($_POST['usuario']) && (isset($_POST['senha']))) { //verifica se estão vazias para então prosseguir
        $usuario = mysqli_real_escape_string($objDb->conn, $_POST['usuario']); //Escapar de caracteres especiais e SQL injection
        $senha   = mysqli_real_escape_string($objDb->conn, md5($_POST['senha'])); //para entrar com a senha criptografada pois se não a autenticação não será possível na hora de verificar senha com o banco de dados

        /* Busca na tabela o usuario que corresponde com os dados do form */
        $sql = " SELECT * FROM usuarios WHERE usuario = '$usuario' AND senha = '$senha' ";
        $resultado_query = mysqli_fetch_assoc(mysqli_query($objDb->conn, $sql));

        if(isset($resultado_query)) { //Se o usuário existir, iniciar a sessão:            
            $_SESSION['id_usuario'] = $resultado_query['id'];
            $_SESSION['usuario']    = $resultado_query['usuario'];
            //$_SESSION["usuarioNiveisAcessoId"] = $resultado["niveis_acesso_id"]; //Para quando existir niveis de acesso
            $_SESSION['email']   = $resultado_query['email'];
            $_SESSION['senha']   = $resultado_query['senha'];
            /* Para quando existir niveis de acesso:
                if ($_SESSION["usuarioNiveisAcessoId"] == "1") {
                    header("Location: administrativo.php");
                } elseif ($_SESSION["usuarioNiveisAcessoId"] == "2") {
                    header("Location: home.php");
                } else {
                    header("Location: aluno.php");
                }
            */
            header('Location: home.php?'); // type=1 para identificar que um usuario foi logado
        } else { /* Nenhum usuario encontrado com os dados do form */
            header('Location: index.php?erro=1'); //Redireciona para página no Location
        }
    } else { /* bloqueia usuario e senha em branco tentando informar url para passar pelo login*/
        header('Location: index.php?erro=1'); //Redireciona para página no Location
    }


?>