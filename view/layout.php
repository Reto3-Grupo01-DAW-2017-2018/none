<!DOCTYPE HTML>
<html lang="es">
<head>
    {% block head %}
        <meta charset="utf-8"/>
        <title>Ejemplo PHP+PDO+POO+MVC</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    {% endblock %}
</head>
<body>
{% block header %}
    <ul id="navPrincipal">
        <li class="menu">
            <a href="#">N</a>
        </li>
        <li class="menu">
            <a href="#">Trayectoria</a>
        </li>
        <li class="menu">
            <a href="#">Proyectos</a>
        </li>
        <li class="menu">
            <a href="#">Testimonios</a>
        </li>
        <li class="menu">
            <a href="#">hola</a>
        </li>
        <li class="menu">
            <a href="#">prueba</a>
        </li>
        <li class="menu">
            <a href="#">Contacto</a>
        </li>
    </ul>
{% endblock %}
{% block container %}

{% endblock %}
<footer class="col-lg-12">
    {% block footer %}
        <hr/>Copyright &copy; <?php echo  date("Y"); ?>
    {% endblock %}
</footer>
</body>
</html>