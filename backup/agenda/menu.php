<nav class="navbar navbar-expand-sm navbar-dark bg-dark fixed-top">
        <div class="container">
            <a href="main.php" class="navbar-brand"><img src="img/icone.svg" width="30" height="30"
               alt="agenda de contatos">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar"> <span
                    class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false" id="menuCadastros">
                            <i class="bi-card-list"></i> Cadastros
                        </a>
                        <div class="dropdown-menu" aria-labelledby="menuCadastros">
                            <a class="dropdown-item" href="cadastroContato.php">
                                <i class="bi-person-fill"></i> Novo Contato
                            </a>
                            <a class="dropdown-item" href="listaContatos.php">
                                <i class="bi-list-ul"></i> Lista de contatos
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false" id="menuConta">
                            <i class="bi-gear-fill"></i> Minha Conta
                        </a>
                        <div class="dropdown-menu" aria-labelledby="menuConta">
                            <a class="dropdown-item" href="alterarDados.php">
                                <i class="bi-pencil-square"></i> Alterar dados
                            </a>
                            <a class="dropdown-item" href="logout.php">
                                <i class="bi-door-open-fill"></i> Sair
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="" data-toggle="modal" data-target="#modalSobreAplicacao">
                            <i class="bi-info-circle"></i> Sobre
                        </a>
                    </li>
                </ul>
                <form action="listaContatos.php" class="form-inline my-2 my-lg-0" method="get">
                    <input class="form-control mr-sm-2" type="search" name="busca" id="busca" placeholder="Pesquisar">
                    <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Enviar</button>
                </form>
                <?php
                if (isset($_SESSION['verificaUsuarioLogado'])) {
                    $nomeUsuarioLogado = $_SESSION['nomeUsuarioLogado'];
                ?>
                    <span class="navbar-text ml-4">Olá <b><?=$nomeUsuarioLogado?></b>, Seja bem-vindo(a)!</span>
                 <?php   
                }
                ?>
            </div>
        </div>
    </nav>
    