<?php
    session_start();
    if (!isset($_SESSION['verificaUsuarioLogado'])) {
        header("Location: index.php?codMSG=003");
    }
    elseif(!isset($_GET['excluir'])){
        header("Location: listaContatos.php");
    }
    else {
        $codigoContato = $_GET['excluir'];
        $codigoUsuarioLogado = $_SESSION['CodigoUsuarioLogado'];
        include 'conectaBanco.php';  
        $sql = "DELETE FROM contatos WHERE codigoContato=:codigoContato AND codigoUsuario=:codigoUsuario";
        $sqlST = $conexao -> prepare($sql);
        $sqlST->bindValue(":codigoContato", $codigoContato);
        $sqlST->bindValue(":codigoUsuario", $codigoUsuarioLogado);
        
        if ($sqlST->execute()) {
            header("Location: listaContatos.php?codMSG=002");
        }
        else {
            header("Location: listaContatos.php?codMSG=001");
        }

    }

?>