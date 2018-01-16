<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>DETALLE PROYECTO</title>
        <link href="/../reto3/css/main.css" rel="stylesheet" type="text/css" />
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    </head>
    <body>
        <?php include_once "head.php";?>
        <div class="container">
            <?php include_once "header.php"; ?>
            <div class="col-lg-9">
            <?php 
            foreach($data['proyecto'] as $key => $valor) {
                echo 'a ver--> '. $key. ' - '. $valor. '<br>';
            }
            ?>
            </div>

            <aside class="col-lg-3">
                hola
            </aside>
        </div>		
        <?php include_once "footer.php"?>
    </body>
</html>
