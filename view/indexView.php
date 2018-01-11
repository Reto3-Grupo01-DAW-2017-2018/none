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
        <form action="index.php?controller=bodega&action=alta" method="post" class="col-lg-5">
            <h3>Añadir bodega</h3>
            <hr/>
            Nombre: <input type="text" name="nombre" class="form-control"/>
            Dirección: <input type="text" name="direccion" class="form-control"/>
            Email: <input type="text" name="email" class="form-control"/>
            Telefono: <input type="text" name="telefono" class="form-control"/>
            Contacto: <input type="text" name="contacto" class="form-control"/>
            Fecha fundación: <input type="text" name="fechafundacion" class="form-control"/>
            Restaurante:<br>Si: <input type="radio" name="restaurante" value="si" checked/>
            No: <input type="radio" name="restaurante" value="no" /><br>
            Hotel:<br> Si: <input type="radio" name="hotel" value="si"  checked/>
            No: <input type="radio" name="hotel" value="no" />
            <br>
            <input type="submit" value="enviar" class="btn btn-success"/>
        </form>
        
        <div class="col-lg-7">
            <h3>Bodegas</h3>
            <hr/>
        </div>
        <section class="col-lg-7 usuario" style="height:400px;overflow-y:scroll;">
            <?php foreach($data as $bodega) {?>
                <?php echo $bodega["idbodega"]; ?> -
                <?php echo $bodega["nombre"]; ?> -
                <?php echo $bodega["email"]; ?> -
                <?php echo $bodega["telefono"]; ?>
                <a href="index.php?controller=bodega&action=detalle&idbodega=<?php echo $bodega["idbodega"]; ?>">Detalle bodega</a>
                <a href="index.php?controller=bodega&action=baja&idbodega=<?php echo $bodega["idbodega"]; ?>">Borrar bodega</a>
                <hr/>
            <?php } ?>
        </section>
		
        <footer class="col-lg-12">
            <hr/>Copyright &copy; <?php echo  date("Y"); ?>
        </footer>
    </body>
</html>