<?php
    session_start();
    if (!isset($_SESSION['verificaUsuarioLogado'])) {
        header("Location: index.php?codMSG=003");
    }
    else {
        $codigoUsuarioLogado = $_SESSION['codigoUsuarioLogado'];
        $nomeUsuarioLogado = $_SESSION['nomeUsuarioLogado'];
        include 'conectaBanco.php';  
    }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <script src="js/jquery-3-3-1.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
    <script src="js/jquery.validate.js"></script>
    <script src="js/messages_pt_BR.js"></script>
    <script src="js/pwstrength-bootstrap.js"></script>
    <script src="js/dateITA.js"></script>
    <script src="js/jquery.mask.js"></script>
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
    </style>
</head>

<body>
<?php 
    include "menu.php";
?>
    <div class="row h-100 align-items-center">
        <div class="container my-5">
            <div class="row">
                <div class="col-sm"></div>
                <div class="col-sm-10">
                <?php
                            $flagErro = False;
                            $flagSucesso = False;
                            $mostrarMensagem = False;

                            $dadosContato = array('codigoContato', 'nomeContato', 'nascimentoContato', 'sexoContato', 'mailContato', 'fotoContato', 'fotoAtualContato', 'telefone1Contato', 'telefone2Contato', 'telefone3Contato', 'telefone4Contato', 'logradouroContato', 'complementoContato', 'bairroContato', 'estadoContato', 'cidadeContato');

                            foreach($dadosContato as $campo){
                                $$campo = "";
                            }

                        if (isset($_POST['codigoContato'])) { //forme submetido (salvar)
                            $codigoContato = $_POST['codigoContato'];
                            $nomeContato = addslashes($_POST['nomeContato']);
                            $nascimentoContato = $_POST['nascimentoContato'];

                            if (isset($_POST['sexoContato'])) {
                                $sexoContato = $_POST['sexoContato'];
                            } else {
                                $sexoContato = "";
                            }

                            $mailContato = $_POST['mailContato'];
                            $fotoContato = $_FILES['fotoContato'];
                            $fotoAtualContato = $_POST['fotoAtualContato'];
                            $telefone1Contato = $_POST['telefone1Contato'];
                            $telefone2Contato = $_POST['telefone2Contato'];
                            $telefone3Contato = $_POST['telefone3Contato'];
                            $telefone4Contato = $_POST['telefone4Contato'];
                            $logradouroContato = addslashes( $_POST['logradouroContato']);
                            $complementoContato = addslashes( $_POST['complementoContato']);
                            $bairroContato = addslashes( $_POST['bairroContato']);
                            $estadoContato = $_POST['estadoContato'];
                            $cidadeContato  = $_POST['cidadeContato'];


                            $telefonesContato = array($telefone1Contato, $telefone2Contato, $telefone3Contato, $telefone4Contato);

                            $telefonesFiltradosContato = array_filter($telefonesContato);
                            $telefonesValidadosContato = preg_grep('/^\(\d{2}\)\s\d{4,5}\-\d{4}$/', $telefonesContato);

                            if ($telefonesFiltradosContato === $telefonesValidadosContato){
                                $erroTelefones = False;
                            } else {
                                $erroTelefones = True;
                            }

                            if (empty($nomeContato) || empty($sexoContato) || empty($mailContato) || empty($telefone1Contato) || 
                                empty($logradouroContato) || empty($complementoContato) || empty($bairroContato) || empty($cidadeContato) || 
                                empty($estadoContato)) {
                            
                                $flagErro = True;
                                $mensagemAcao = "Preencha todos os campos obrigatórios (*). ";

                            } else if (strlen($nomeContato) < 5) {
                                $flagErro = True;
                                $mensagemAcao = "Informe a quantidade mínima de caracteres para cada campo: Nome (5).";
                            } else if (!empty($nascimentoContato) && !preg_match('/^(0?[1-9]|[1,2][0-9]|[3[0,1])[\/](0?[1-9]|1[0,1,2])[\/]\d{4}$/', $nascimentoContato)) { //validação do nascimento
                                $flagErro = True;
                                $mensagemAcao = "A data de nascimento do contato deve ser no formato DD/MM/AAAA.";                               
                            } else if (!preg_match("/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/", $mailContato)){
                                $flagErro = True;
                                $mensagemAcao = "Verifique o e-mail informado.";

                            } else if ($fotoContato['error'] != 4) {
                                if (!in_array($fotoContato['type'], array('image/jpg', 'image/jpeg', 'image/png', )) || $fotoContato['size'] > 2000000) {

                                    $flagErro = True;
                                    $mensagemAcao = "A foto do contato deve ser nos formatos JPG, JPEG ou PNG e ter no máximo 2MB.";

                                } else {
                                   list($larguraFoto, $alturaFoto) = getimagesize($fotoContato['tmp_name']);

                                   if ($larguraFoto > 500 || $alturaFoto > 200) {
                                    $flagErro = True;
                                    $mensagemAcao = "As dimensões da foto devem ser no máximo 500x200 pixels.";
                                   }
                                }

                            } else if ($erroTelefones) {
                                $flagErro = True;
                                $mensagemAcao = "Os campos de telefone devem ser no formato (xx) xxxxx-xxxx. ";
                            
                            }


                            if (!$flagErro) {
                                if (empty($codigoContato)) { // inclusão de contato
                                    $sqlContato = "INSERT INTO contatos (codigoUsuario, nomeContato, nascimentoContato, sexoContato, mailContato, fotoContato, telefone1Contato, telefone2Contato, telefone3Contato, telefone4Contato, logradouroContato, complementoContato, bairroContato, cidadeContato, estadoContato) VALUES (:codigoUsuario, :nomeContato, :nascimentoContato, :sexoContato, :mailContato, :fotoContato, :telefone1Contato, :telefone2Contato, :telefone3Contato, :telefone4Contato, :logradouroContato, :complementoContato, :bairroContato, :cidadeContato, :estadoContato)";

                                    $sqlContatoST = $conexao->prepare($sqlContato);

                                    $sqlContatoST->bindValue(':codigoUsuario', $codigoUsuarioLogado);
                                    $sqlContatoST->bindValue(':nomeContato', $nomeContato);

                                    $nascimentoContato = formataData($nascimentoContato);
                                    $sqlContatoST->bindValue(':nascimentoContato', $nascimentoContato);


                                    $sqlContatoST->bindValue(':sexoContato', $sexoContato);
                                    $sqlContatoST->bindValue(':mailContato', $mailContato);
                                    $sqlContatoST->bindValue(':telefone1Contato', $telefone1Contato);
                                    $sqlContatoST->bindValue(':telefone2Contato', $telefone2Contato);
                                    $sqlContatoST->bindValue(':telefone3Contato', $telefone3Contato);
                                    $sqlContatoST->bindValue(':telefone4Contato', $telefone4Contato);
                                    $sqlContatoST->bindValue(':logradouroContato', $logradouroContato);
                                    $sqlContatoST->bindValue(':complementoContato', $complementoContato);
                                    $sqlContatoST->bindValue(':bairroContato', $bairroContato);
                                    $sqlContatoST->bindValue(':cidadeContato', $cidadeContato);
                                    $sqlContatoST->bindValue('estadoContato:', $estadoContato);

                                    if ($fotoContato['error'] == 0) {
                                        $extensaoFoto = pathinfo($fotoContato['name'], PATHINFO_EXTENSION);
                                        $nomeFoto = "fotos/" . strtotime(date("Y-m-d H:i:s")) . $codigoUsuarioLogado . '.' . $extensaoFoto;

                                        if (copy($fotoContato['tmp_name'], $nomeFoto)) {
                                            $fotoEnviada = True;
                                        } else {
                                            $fotoEnviada = False;
                                        }

                                        $sqlContatoST->bindValue(':fotoContato', $nomeFoto);
                                    } else {
                                        $sqlContatoST->bindValue(':fotoContato', '');
                                        $fotoEnviada = False;
                                    }
                                    if ($sqlContatoST->execute()) {
                                        $flagSucesso = True;
                                        $mensagemAcao = "Novo contato cadastrado com sucesso.";
                                    } else {
                                        $flagErro = True;
                                        $mensagemAcao = "Erro ao cadastrar o novo contato. Código do erro: $sqlContatoST->errorCode( ).";

                                        $nascimentoContato = formataData($nascimentoContato);

                                        if ($fotoEnviada) {
                                            unlink($nomeFoto);
                                        }
                                    }
                                    
                                    
                                    }

                                } else { //edição de contato existente

                            }

                        } else { //carregar dados
                            if(isset($_GET['codigoContato'])) { // abrir contato existente

                            } 
                        }

                        if ($flagErro) {
                            $classeMensagem = "alert-danger";
                            $mostrarMensagem = True;
                        }   else if ($flagSucesso) {
                            $classeMensagem = "alert-success";
                            $mostrarMensagem = True;
                        }

                        if ($mostrarMensagem) {
                            echo "<div class=\"alert $classeMensagem alert-dismissible fade show my-5\" role=\"alert\">
                                    $mensagemAcao
                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\">
                                        <span aria-hidden=\"true\">&times;</span>
                                    </button>
                                </div>";
                        }
                    ?>
                    <div class="card border-primary my-5">
                    <input type ="hidden" name="codigoContato" value="">
                    <input type ="hidden" name="fotoAtualContato" value="">
                        <div class="card-header bg-primary text-white">
                            <h5> Cadastro contato</h5>
                        </div>
                        <div class="card-body">
                            <div>
                                <h4><span style="color: blue;"> Dados pessoais </span></h4>
                                <hr>
                            </div>
                            <form id="cadastroContato" method="post" action="novoUsuario.php">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="nomeContato">Nome*</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="bi-people-fill"></i></div>
                                                </div>
                                                <input id="nomeContato" type="text" size="60" class="form-control"
                                                    name="nomeContato" placeholder="Digite o seu nome" value="<?= $nomeContato ?>"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                                    <label for="fotoContato">Foto</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="bi-file-earmark-person"></i>
                                                            </div>
                                                        </div>
                                                        <div class="custom-file">
                                                            <input class="custom-file-input" type="file"
                                                                name="fotoContato" id="fotoContato">
                                                            <label class="custom-file-label" for="fotoContato">
                                                                Escolha a foto...
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="nascimentoContato">Data de nascimento</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="bi-at"></i></div>
                                                </div>
                                                <input id="nascimentoContato" type="text" class="form-control date"
                                                    name="nascimentoContato" placeholder="DD/MM/AAAA" value="<?= $nascimentoContato ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label form="sexoContato">Sexo*</label>
                                            <div class="input-group">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="sexoContato"
                                                        id="sexoMasculino" value="masculino">
                                                    <label class="form-check-label" for="sexoMasculino">
                                                        Masculino
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="sexoContato"
                                                        id="sexoFeminino" value="feminino">
                                                    <label class="form-check-label" for="sexoFeminino">
                                                        Feminino
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group mt-3">
                                            <label for="mailContato">E-mail*</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="bi-at"></i></div>
                                                </div>
                                                <input id="mailContato" type="email" class="form-control"
                                                    name="mailContato" placeholder="Digite o seu email" value=""
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4>
                                        Telefones
                                    </h4>
                                    <hr>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="telefone1Contato">Telefone*</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="bi-phone"></i></div>
                                                </div>
                                                <input id="telefone1Contato" type="tel" class="form-control mascara-telefone"
                                                    name="telefone1Contato" placeholder="(xx) xxxx-xxxx" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="telefone2Contato">Telefone</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="bi-phone"></i></div>
                                                </div>
                                                <input id="telefone2Contato" type="tel" class="form-control mascara-telefone"
                                                    name="telefone2Contato" placeholder="(xx) xxxx-xxxx" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="telefone3Contato">Telefone</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="bi-phone"></i></div>
                                                </div>
                                                <input id="telefone3Contato" type="tel" class="form-control mascara-telefone"
                                                    name="telefone3Contato" placeholder="(xx) xxxx-xxxx" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="telefone4Contato">Telefone</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="bi-phone"></i></div>
                                                </div>
                                                <input id="telefone4Contato" type="tel" class="form-control mascara-telefone"
                                                    name="telefone4Contato" placeholder="(xx) xxxx-xxxx" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4>
                                        Endereço
                                    </h4>
                                    <hr>
                                </div>

                                <div class="row">
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label for="logradouroContato">Logradouro*</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="bi-map"></i></div>
                                                </div>
                                                <input id="logradouroContato" type="text" class="form-control"
                                                    name="logradouroContato"
                                                    placeholder="Rua, avenida, travessa e outros" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="complementoContato">Complemento*</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="bi-map"></i></div>
                                                </div>
                                                <input id="complementoContato" type="text" class="form-control"
                                                    name="complementoContato"
                                                    placeholder="Número, quadra, lote e outros" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="bairroContato">Bairro*</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="bi-map"></i></div>
                                                </div>
                                                <input id="bairroContato" type="text" class="form-control"
                                                    name="bairroContato" placeholder="Digite o bairro" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="estadoContato">Estado*</label>
                                            <div>
                                                <select class="form-control" aria-label="Default select example"
                                                    id="estadoContato" name="estadoContato" required>
                                                    <?php
                                                       $sqlEstado = "SELECT codigoEstado, nomeEstado FROM estados";
                                                       $resultadoEstado = $conexao->query($sqlEstado)->fetchAll();
                                                       foreach ($resultadoEstado as list($codigoEstado, $nomeEstado)) {
                                                           echo "<option value='$codigoEstado'>$nomeEstado</option>";
                                                       }
                                                    ?>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="bairro">Cidade*</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="bi-globe"></i></div>
                                                </div>
                                                <select class="form-control" name="cidadeContato" id="cidadeContato"
                                                    required>
                                                    
                                                    <option value="">Escolha sua Cidade</option>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm text-right">
                                        <button class="btn btn-primary" type="submit">Cadastrar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSobreAplicacao" tabindex="-1" role="dialog" aria-labelledby="sobreAplicacao"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sobreAplicacao">Sobre</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img src="img/logo.jpg" alt="logo">
                    <hr>
                    <p>Agenda de contatos</p>
                    <p>Versão 1.0</p>
                    <p>Todos os direitos reservados &copy: 2021 </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <?php include "modalApp.php";?>
</body>
<script>
    jQuery.validator.setDefaults({
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },

        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }

    });
    $(document).ready(() => {
        $("#cadastroContato").validate({
            rules: {
                
                nomeContato: {
                    minlength: 5,
                    required: true
                },
                nascimentoContato: {
                    dateITA: true
                },
                sexoContato: {
                    required: true
                }
            }
        });
       
        $("#nascimentoContato").mask("00/00/0000");

        var SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
            spOptions = {
                onKeyPress: function (val, e, field, options) {
                    field.mask(SPMaskBehavior.apply({}, arguments), options);
                }
            };
        $('.mascara-telefone').mask(SPMaskBehavior, spOptions);

        $('#estadoContato').change(function() {
            $("#cidadeContato").html('<option>Carregando... </option>');
            $("#cidadeContato").load('listaCidade.php?codigoEstado='+$("#estadoContato").val());
            
            
        });
    });
</script>

</html>