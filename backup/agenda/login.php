<?php
    session_start();
    if (isset($_POST['mailUsuario']) && isset($_POST['senhaUsuario'])) {
        include "conectaBanco.php";
        $mailUsuario = $_POST['mailUsuario'];
        $senhaUsuario = $_POST['senhaUsuario'];
        $senhaUsuario = md5($senhaUsuario);
        $sqlUsuario = "SELECT codigoUsuario, nomeUsuario FROM usuarios WHERE mailUsuario=:mailUsuario AND senhaUsuario=:senhaUsuario LIMIT 1";
        $sqlUsuarioST = $conexao -> prepare($sqlUsuario);
        $sqlUsuarioST->bindValue(':mailUsuario', $mailUsuario);
        $sqlUsuarioST->bindValue(':senhaUsuario', $senhaUsuario);
        $sqlUsuarioST->execute();
        $quantidadeUsuario = $sqlUsuarioST->rowCount();
        if ($quantidadeUsuario == 1){
            $resultadoUsuario = $sqlUsuarioST->fetchAll();
            list($codigoUsuario, $nomeUsuario) = $resultadoUsuario[0];
            $_SESSION['verificaUsuarioLogado'] = True;
            $_SESSION['CodigoUsuarioLogado'] = $codigoUsuario;
            $nomeCompletoUsuario = explode(' ', $nomeUsuario);
            $_SESSION['nomeUsuarioLogado'] = $nomeCompletoUsuario[0];

            header('Location: main.php');
        }
        else {
            header("location: index.php?codMSG=002");
        }
    }else{
        header("location: index.php?codMSG=001");
    }
?>