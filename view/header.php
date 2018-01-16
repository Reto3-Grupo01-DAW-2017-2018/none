<ul id="navPrincipal">
    <li class="menu">
        <a href="index.php?controller=usuario&action=index">Inicio</a>
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
    <?php
    if (!isset($_SESSION['user'])){
    ?>
        <li class="menu"><a href="#"><span class="glyphicon glyphicon-log-in"></span> Acceder</a></li>
        <li class="menu"><a href="#"><span class="glyphicon glyphicon-user"></span> Registro</a></li>
    <?php
    }else{
        $usuario=unserialize($_SESSION['user']);
    ?>
        <li class="menu dropdown" id="dropdownPerfil">
            <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown"  role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="glyphicon glyphicon-user"> </span> <?php echo $usuario->username." "; ?><span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#"><span class="glyphicon glyphicon-cog"></span> Perfil</a></li>
                <li><a class="dropdown-item" href="/../nonecollab/index.php?controller=usuario&action=custom"><span class="glyphicon glyphicon-wrench"></span> Personalizar</a></li>
                <div class="dropdown-divider"></div>
                <li><a class="dropdown-item" href="/../nonecollab/index.php?controller=usuario&action=logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
        </li>
    <?php
    }
    ?>
</ul>