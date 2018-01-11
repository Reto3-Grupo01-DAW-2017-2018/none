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
    <form action="index.php?controller=bodega&action=update" method="post" class="col-lg-5">
        <h3>Editar Bodega</h3>
        <hr/>
        <input type="hidden" name="idbodega" class="form-control" value="<?php echo $data->idbodega; ?>"/>
        Nombre: <input type="text" name="nombre" class="form-control" value="<?php echo $data->nombre; ?>"/>
        Dirección: <input type="text" name="direccion" class="form-control" value="<?php echo $data->direccion; ?>"/>
        Email: <input type="text" name="email" class="form-control" value="<?php echo $data->email; ?>"/>
        Telefono: <input type="text" name="telefono" class="form-control" value="<?php echo $data->telefono; ?>"/>
        Contacto: <input type="text" name="contacto" class="form-control" value="<?php echo $data->contacto; ?>"/>
        Fecha fundación: <input type="text" name="fechafundacion" class="form-control" value="<?php echo $data->fechafundacion; ?>"/>
        Restaurante:
        <?php
        if ($data->restaurante == "si"){
            ?><br>Si: <input type="radio" name="restaurante" value="si" checked/>
            No: <input type="radio" name="restaurante" value="no" /><br><?php
        }
        else{
            ?><br>Si: <input type="radio" name="restaurante" value="si"/>
            No: <input type="radio" name="restaurante" value="no" checked/><br><?php
        }
        ?>
        Hotel:
        <?php
        if ($data->hotel == "si"){
            ?><br>Si: <input type="radio" name="hotel" value="si" checked/>
             No: <input type="radio" name="hotel" value="no" /><br><?php
        }
        else{
            ?><br>Si: <input type="radio" name="hotel" value="si"/>
            No: <input type="radio" name="hotel" value="no" checked/><br><?php
        }
        ?>
        <input type="submit" value="enviar" class="btn btn-success"/>
    </form>


    <div class="col-lg-7">
        <hr/>
        <a href="./index.php?controller=vino&action=index&idbodega=<?php echo $data->idbodega; ?>">Lista vinos</a>
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