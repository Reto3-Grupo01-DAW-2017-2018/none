{% extends "layout.html" %}
{% block container%}
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
{% endblock %}
