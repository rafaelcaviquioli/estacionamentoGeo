<div class="navbar-collapse collapse">
    <ul class="nav navbar-nav">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Estacionamentos<b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="?acao=AdmCategoria"><span class="glyphicon glyphicon-plus-sign"></span> Adicionar</a></li>
                <li><a href="?acao=AdmCategoria&list"><span class="glyphicon glyphicon-search"></span> Consultar</a>
                </li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="?acao=AdmVeiculo" class="dropdown-toggle">Veículos</a>
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
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo $this->session->get("usuario")->getLogin(); ?>
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