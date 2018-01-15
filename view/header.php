<ul id="navPrincipal">
    <li class="menu">
        <a href="#">Home</a>
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
    <?php if(!isset($_SESSION["user"])){ ?>
    <li class="menu">
        <a href="#">Login</a>
    </li>
    <?php }else{ ?>
    <li class="menu">
        <a href="#">Perfil</a>
    </li>
    <?php }?>
</ul>