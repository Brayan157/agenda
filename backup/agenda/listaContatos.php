

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de contatos</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <script src="js/jquery-3.3.1.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
    <style>
        html {
            height: 100%;
        }

        body {
            background: url('img/dark-blue-background.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100%;
            overflow-x: hidden;
        }

        .custom-file-input~.custom-file-label::after {
            content: "Selecionar";
        }
    </style>
</head>

<body>
    <?php include "main.php"; 
        include "conectaBanco.php";
        $codigoUsuarioLogado = $_SESSION['codigoUsuarioLogado'];
    ?>

    <div class="h-100 row align-items-center pt-5">
        <div class="container">
            <div class="row">
                <div class="col-sm"></div>
                <div class="col-sm-12">
                    <div class="card border-primary my-5">
                        <div class="card-header bg-primary text-white">
                            <h5>Lista de contatos</h5>

                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Telefone</th>
                                        <th scope="col">E-mail</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if (isset($_GET['busca'])){
                                            $busca = '%' .  $_GET['busca'] . '%';
                                        } else {
                                            $busca = '%%';
                                        }

                                        $sqlContatos = "SELECT codigoContato, nomeContato, mailContato, telefone1Contato FROM contatos WHERE    codigoUsuario=:codigoUsuario AND nomeContato LIKE :busca ORDER BY nomeContato";

                                        $sqlContatosST = $conexao->prepare($sqlContatos);
                                        $sqlContatosST->bindValue(':codigoUsuario', $codigoUsuarioLogado);
                                        $sqlContatosST->bindValue(':busca', $busca);

                                        $sqlContatosST->execute();
                                        $quantidadeContatos = $sqlContatosST->rowCount();

                                        if ($quantidadeContatos > 0) {
                                            $resultadoContatos = $sqlContatosST->fetchALL();

                                            foreach($resultadoContatos as list($codigoContato, $nomeContato, $mailContato, $telefone1Contato)){
                                            echo "<tr>
                                            <th scope=\"row\">$codigoContato</th>
                                            <td>$nomeContato</td>
                                            <td>$telefone1Contato</td>
                                            <td>$mailContato</td>
                                            <td>
                                                <div class=\"dropdown\">
                                                    <a class=\"btn btn-secondary dropdown-toggle btn-sm\" href=\"#\"
                                                        role=\"button\" id=\"{id}\" data-toggle=\"dropdown\" aria-haspopup=\"true\"
                                                        aria-expanded=\"false\">
                                                        Ações
                                                    </a>
    
                                                    <div class=\"dropdown-menu\" aria-labelledby=\"{id}\">
                                                        <a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\"
                                                            data-target=\"#visualizarContato\" data-whatever=\"$codigoContato\">
                                                            <i class=\"bi-eye\"></i> Visualizar
                                                        </a>
                                                        <a class=\"dropdown-item\" href=\"cadastroContato.php\">
                                                            <i class=\"bi-pencil\"></i> Editar
                                                        </a>
                                                        <a class=\"dropdown-item\" href=\"#\">
                                                            <i class=\"bi-trash\"></i> Excluir
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>";
                                            }
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm"></div>
            </div>
        </div>
    </div>
    <div class="modal  fade" id="modalSobreAplicacao" tabindex="-1" role="dialog" aria-labelledby="sobreAplicacao"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sobreAplicacao">Sobre</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img src="img/logo.jpg" alt="">
                    <hr>
                    <p>Agenda de contatos</p>
                    <p>Versão 1.0</p>
                    <p>Todos os direitos reservados &copy; 2022</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="visualizarContato" tabindex="-1" role="dialog" aria-labelledby="visualizarDadosContatos"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="visualizarDadosContatos">Dados do contato</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="modal-body" id="dadosContato">

                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php include "modalApp.php";?>
</body>
<script>
    $(document).ready(function() {
        $('#visualizarContato').on('show.bs.modal', function (event) {
            var origemContato = $(event.relatedTarget);
            var codigoContato = origemContato.data('whatever');

            $('#dadosContato').load('visualizaContato.php?codigoContato=' + codigoContato);
        });
    });
</script>

</html>