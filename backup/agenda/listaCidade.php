<?php
    include "conectaBanco.php";
    if (isset($_GET['codigoEstado'])) {
        $codigoEstado = $_GET['codigoEstado'];
        
        $sqlCidade = "SELECT codigoCidade, nomeCidade FROM cidades WHERE codigoEstado =:codigoEstado;";

        $stmtCidade = $conexao->prepare($sqlCidade);
        $stmtCidade->bindValue(':codigoEstado', $codigoEstado);
        $stmtCidade->execute();
        $resultadoCidade = $stmtCidade->fetchAll();
        echo "<option value=\"\">Escolha sua Cidade</option>";

        foreach ($resultadoCidade as list($codigoCidade, $nomeCidade)) {
            echo "<option value='$codigoCidade'>$nomeCidade</option>";

        }
    }else {
        header("location: cadastroContato.php");
    }
?>