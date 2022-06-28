<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <script src="js/booststrap.bundle.js"></script>
    <script src="js/jquery-3-3-1.js"></script>
    <script src="js/jquery.validate.js"></script>
    <script src="js/messages_pt_BR.js"></script>
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
    <div class="row h-100 align-items-center">
        <div class="container">
                <?php
                    if (isset($_GET['codMSG'])) {
                        $codmsg = $_GET['codMSG'];
                        switch ($codmsg) {
                            case '001':
                                $classMensagem = "alert-danger";
                                $textoMensagem = "Informe usúario e senha para acessar o sistema.";
                                break;
                            
                            case '002':
                                $classMensagem = "alert-danger";
                                $textoMensagem = "Usúario ou senha incorretos.";
                                break;
                            case '003':
                                $classMensagem = "alert-danger";
                                $textoMensagem = "Usúario não logado.";
                                break;
                            case '004':
                                $classMensagem = "alert-danger";
                                $textoMensagem = "Informe o e-mail do usuário cadastrado no sistema.";
                                break;
                            case '005':
                                $classMensagem = "alert-danger";
                                $textoMensagem = "Usuário não cadastrado no sistema.";
                                break;
                            case '006':
                                $classMensagem = "alert-danger";
                                $textoMensagem = "Ocorreu um erro ao gerar a nova senha.";
                                break;
                            case '007':
                                $classMensagem = "alert-danger";
                                $textoMensagem = "Ocorreu um erro ao enviar a nova senha para o e-mail.";
                                break;
                            case '008':
                                $classMensagem = 'alert-success';
                                $textoMensagem = "Sua nova senha foi enviada para o e-mail cadastrado.";
                                break;
                            case '009':
                                $classMensagem = 'alert-success';
                                $textoMensagem = "Sua sessão no sistema foi encerrada com sucesso.";
                        }
                        if (!empty($textoMensagem)) {
                            echo "<div class=\"alert $classMensagem alert-dismissible fade show\" role=\"alert\">
                                $textoMensagem
                                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
                                <span aria-hidden=\"true\">&times;</span>
                                </button>
                            </div>";
                        }
                    }
                ?>
            <div class="row">
                <div class="col-sm">
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        
                        <div class="card-header bg-white">
                            <img style="width: 100%" src="img/logo.jpg" alt="Agenda de Contatos">
                        </div>

                        <div class="card-body">
                            <form id="login" method="post" action="login.php">
                                <div class="form-group">
                                    <label for="mailUsuario">E-mail</label>
                                    <input type="email" class="form-control" id="mailUsuario"
                                        placeholder="Digite seu e-mail" name = "mailUsuario">
                                </div>
                                <div class="form-group">
                                    <label for="senhaUsuario">Senha</label>
                                    <input type="password" class="form-control" id="senhaUsuario"
                                        placeholder="Digite sua senha" name = "senhaUsuario">
                                </div>
                                <button id="entrarLogin" type="submit" class="btn btn-primary btn-block btn-lg">Entrar</button>
                            </form>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm">
                                    <a class="btn btn-success btn-block" href="novoUsuario.php">Não sou cadastrado</a>
                                </div>
                                <div class="col-sm">
                                    <button id="esqueciSenha" class="btn btn-warning btn-block">Esqueci a Senha</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-sm">
                </div>
            </div>
        </div>
    </div>
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
    
    $(document).ready(function () {
            $("#login").validate({
                rules: {
                    mailUsuario: {
                        required: true
                    },
                    senhaUsuario: {
                        required: true
                    }
                }
            });

            $('#esqueciSenha').click(function() {
                $('#senhaUsuario').rules("remove", "required");

                $('#login').attr("action", "recuperarSenha.php");
                $('#login').submit();
            });

            $('#entrarLogin').click(function() {
                $('#senhaUsuario').rules("add", "required");

                $('#login').attr("action", "login.php");
                $('#login').submit();
            });
        });
</script>

</html>