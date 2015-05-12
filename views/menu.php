<div class="navbar-collapse collapse">
    <ul class="nav navbar-nav">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Paginas <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <a href="?acao=AdmPagina&list&idIdioma=2"><span class="glyphicon glyphicon-search"></span> Português</a>
                </li>
                <li>
                    <a href="?acao=AdmPagina&list&idIdioma=1"><span class="glyphicon glyphicon-search"></span> Inglês</a>
                </li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Categorias de Produtos<b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="?acao=AdmCategoria"><span class="glyphicon glyphicon-plus-sign"></span> Adicionar</a></li>
                <li><a href="?acao=AdmCategoria&list"><span class="glyphicon glyphicon-search"></span> Consultar</a>
                </li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Produtos <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="?acao=AdmProduto"><span class="glyphicon glyphicon-plus-sign"></span> Adicionar</a></li>
                <li><a href="?acao=AdmProduto&list"><span class="glyphicon glyphicon-search"></span> Consultar</a>
                </li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Categorias de Serviços<b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="?acao=AdmCategoriaServicos"><span class="glyphicon glyphicon-plus-sign"></span> Adicionar</a></li>
                <li><a href="?acao=AdmCategoriaServicos&list"><span class="glyphicon glyphicon-search"></span> Consultar</a>
                </li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Serviços <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="?acao=AdmServico"><span class="glyphicon glyphicon-plus-sign"></span> Adicionar</a></li>
                <li><a href="?acao=AdmServico&list"><span class="glyphicon glyphicon-search"></span> Consultar</a>
                </li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Estrutura <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="?acao=AdmEstrutura"><span class="glyphicon glyphicon-plus-sign"></span> Adicionar</a></li>
                <li><a href="?acao=AdmEstrutura&list"><span class="glyphicon glyphicon-search"></span> Consultar</a>
                </li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Certificações <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="?acao=AdmCertificacao"><span class="glyphicon glyphicon-plus-sign"></span> Adicionar</a>
                </li>
                <li><a href="?acao=AdmCertificacao&list"><span class="glyphicon glyphicon-search"></span> Consultar</a>
                </li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Usuários <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="?acao=AdmUsuario"><span class="glyphicon glyphicon-plus-sign"></span> Adicionar</a></li>
                <li><a href="?acao=AdmUsuario&list"><span class="glyphicon glyphicon-search"></span> Consultar</a></li>
            </ul>
        </li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo $this->session->get("usuario")->getNomeCompleto(); ?>
                <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <a href="?acao=MeuUsuario"><span class="glyphicon glyphicon-cog"></span> Meu Dados</a>
                </li>
                <li>
                    <a href="?acao=Logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
                </li>
            </ul>
        </li>
    </ul>
</div>