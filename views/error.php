<?php $error = $this->session->getFlashBag()->get('error'); ?>
<!DOCTYPE html>
<html lang="pt_BR">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MEDSY</title>

        <!-- Bootstrap core CSS -->
        <link href="bootstrap/dist/css/bootstrap.css" rel="stylesheet">


        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <style>
            .container {margin-top: 50px;}
        </style>
    </head>
    <body>
        <div class="container">
            <?php
            Tool::alert("error", $error, "<br /><a href=\"?\">Voltar para pagina inicial</a>");
            ?>
        </div>
    </body>
</html>