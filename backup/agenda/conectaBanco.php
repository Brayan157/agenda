<?php
    define('HOST', 'localhost');
    define('USER', 'root');
    define('PASS', 'root');
    define('DBNAME', 'agenda');
    define('PORT', '3306');
    $conexao = new pdo('mysql: host='.HOST.'; port='.PORT.'; dbname='.DBNAME,USER,PASS);

    // try {
    //     $conexao = new pdo('mysql: host='.HOST.'; port='.PORT.'; dbname='.DBNAME,USER,PASS);
    //     echo "Conexão realizada";
    // } catch (PDOException $exception) {
    //     echo "Erro ao conectar mo banco: ". $exception->getMessage();
    // }
?>