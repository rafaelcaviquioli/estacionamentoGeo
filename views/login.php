<!DOCTYPE html>
<html lang="pt_BR">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>GeoPark</title>

        <!-- Bootstrap core CSS -->
        <link href="bootstrap/dist/css/bootstrap.css" rel="stylesheet">
        <link href="css/signin.css" rel="stylesheet">


        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>

    <body <?php echo (isset($_POST['login']) ? "onLoad=\"document.getElementById('senha').focus();\"" : NULL)  ?>>
        
        <div class="container">
            <form class="form-signin" method="POST" action="?acao=Login">
                <h2 class="form-signin-heading">Login</h2>
                <input type="text" class="form-control" placeholder="Login" id="login" name="login" value="<?php echo isset($_POST['login']) ? $_POST['login'] : NULL ?>" required autofocus>
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
            </form>
                <?php
                $error = $this->session->getFlashBag()->get('error');
                Tool::alert("error", $error);
                        
                ?>
        </div> 

        <script src="bootstrap/dist/js/jquery-1.10.2.min.js"></script>
        <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    </body>
</html>
