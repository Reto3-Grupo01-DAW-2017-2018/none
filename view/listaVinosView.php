<!DOCTYPE HTML>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <title>Ejemplo PHP+PDO+POO+MVC</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <style>
        input{
            margin-top:5px;
            margin-bottom:5px;
        }
        .right{
            float:right;
        }
    </style>
</head>
<body>
<form action="index.php?controller=vino&action=alta&idbodega=<?php echo $idbodega ?>" method="post" class="col-lg-5">
    <h3>Añadir vino</h3>
    <hr/>
    Nombre: <input type="text" name="nombre" class="form-control"/>
    Descripción: <input type="text" name="descripcion" class="form-control"/>
    Año: <input type="text" name="anyo" class="form-control"/>
    Tipo: <input type="text" name="tipo" class="form-control"/>
    Alcohol: <input type="text" name="alcohol" class="form-control"/>
    <input type="submit" value="enviar" class="btn btn-success"/>
</form>
<section class="col-lg-7 usuario" style="height:400px;overflow-y:scroll;">
    <?php foreach($data as $vino) {?>
        <?php echo $vino["idvino"]; ?> -
        <?php echo $vino["nombre"]; ?> -
        <?php echo $vino["año"]; ?> -
        <?php echo $vino["tipo"]; ?>
        <a href="index.php?controller=vino&action=detalle&idvino=<?php echo $vino["idvino"]; ?>&idbodega=<?php echo $idbodega; ?>">Detalle vino</a>
        <a href="index.php?controller=vino&action=baja&idvino=<?php echo $vino["idvino"]; ?>">Borrar vino</a>
        <hr/>
    <?php } ?>
</section>

<div class="col-lg-7">
    <hr/>
    <a href="./index.php">Volver</a>
</div>
<div class="col-lg-7" style="height:400px;">
    <hr/>
</div>

<footer class="col-lg-12 fixed-bottom">
    <hr/>
    Copyright &copy; <?php echo  date("Y"); ?>
</footer>
</body>
</html>