<!DOCTYPE html>
<html lang="pt_BR">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>GeoPark</title>

        <!-- Bootstrap core CSS -->
        <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/base.css" rel="stylesheet">


        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <script src="bootstrap/dist/js/jquery-1.10.2.min.js"></script>
        <script src="bootstrap/dist/js/jquery.mask.min.js"></script>
        <script src="bootstrap/dist/js/jquery.price.js"></script>
        <script src="bootstrap/dist/js/jquery.validate.min.js"></script>
        <script src="bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="bootstrap/dist/js/jquery.uploadfile.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#back').click(function () {
                    history.back();
                });
            });
        </script>
    </head>

    <body>

        <!-- Static navbar -->
        <div class="navbar navbar-default navbar-static-top" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="?"><span class="glyphicon glyphicon-home"></span> GeoPark</a>
            </div>
            <!-- Menu -->
            <?php include('views/menu.php'); ?>
            <!-- Fim Menu -->
        </div>


        <div class="container">
            <?php
            $viewContainer = $request->query->get('viewContainer');
            if (isset($viewContainer) AND !empty($viewContainer)) {
                include($viewContainer);
            }
            ?>
        </div>
    </body>
</html>
