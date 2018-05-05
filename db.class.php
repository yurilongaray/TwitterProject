<?php

    class db {

        //host
        private $host = 'localhost';

        //usuario
        private $usuario = 'root';

        //senha
        private $senha = '123456';

        //banco de dados
        private $database = 'twitter';

        public $conn;

        public function connect_db(){

            //criar conexao
            $this->conn = mysqli_connect($this->host, $this->usuario, $this->senha, $this->database);

            //ajustar o charset de comunicação entre a aplicação e o banco de dados
            mysqli_set_charset($this->conn, 'utf8');

            //verificar se houve erro de conexão
            if (!$this->conn) {
              die("Falha de Conexão: " . mysqli_connect_error());
            }

            return $this->conn;

        }

        function insert_db($tabela, $dados) {
            $sql  = "INSERT INTO " . $tabela . '(';
            $sql .= implode(',', array_keys($dados)) . ") VALUES ('" . implode("','", array_values($dados)) . "')";

            //echo $sql."\n\n"; //Para depurar, pois irá escrever como está a query

            //executar a query
            if(mysqli_query($this->conn, $sql)) {
                echo "Usuário registrado com sucesso!";
            } else {
                echo "Erro ao registrar o usuário!";
            }
        }
    }

?>