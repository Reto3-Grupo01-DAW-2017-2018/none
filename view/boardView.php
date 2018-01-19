<!DOCTYPE html>

<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <title>PROYECTOS EN DESARROLLO</title>
        <link href="/../reto3/css/main.css" rel="stylesheet" type="text/css" />
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <style>
            .ftr{
                margin-bottom: 0;
            }
            input{
                margin-top:5px;
                margin-bottom:5px;
            }
            .right{
                float:right;
            }
            section a{
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <?php include_once "head.php"?>
        <div class="container">
            <?php include_once "header.php"?>
            <div class="col-lg-9">
                <h3>Proyectos del usuario</h3>
                <hr/>            
                <section class="col-lg-12 usuario" style="height:300px;overflow-y:scroll;">
                    <?php foreach($data["proyectos"] as $proyecto) {?>            
                        <?php echo $proyecto["idProyecto"]; ?> -
                        <?php echo $proyecto["nombre"]; ?> -
                        <?php echo $proyecto["fechaInicioProyecto"]; ?>&nbsp;&nbsp;&nbsp;&nbsp;         

                        <!-- Enlace para el boton Gestionar para mostrar los datos del proyecto (se pasa como action -> verDetalle) -->
                        <a href="index.php?controller=proyectos&action=verDetalle&proyecto=<?php echo $proyecto['idProyecto'] ?>" style="text-decoration: none">
                            <button type="button" class="btn btn-info btn-sm">Gestionar</button>
                        </a>

                        <!-- Enlace para el boton borrar para eliminar el proyecto -->
                        <a href="index.php?controller=proyectos&action=eliminar&idProyecto=<?php echo $proyecto['idProyecto'] ?>" style="text-decoration: none">   
                            <button type="button" class="btn btn-danger btn-sm">Borrar</button>
                        </a>             

                        <hr/>
                    <?php } ?>
                </section>
                <hr style="border: 1px solid black !important"/>
                <h3>Participando en proyectos</h3>
                <hr/>            
                <section class="col-lg-12 usuario" style="height:300px;overflow-y:scroll;">
                    <?php foreach($data["participando"] as $proyectoParticipado => $participacion) { ?>            
                            <?php echo $participacion->idProyecto; ?> -
                            <?php echo $participacion->nombre; ?> -
                            <?php echo $participacion->fechaInicioProyecto; ?>&nbsp;&nbsp;&nbsp;&nbsp;                        

                            <!-- Enlace para el boton Gestionar para mostrar los datos del proyecto (se pasa como action -> verDetalle) -->
                            <a href="index.php?controller=proyectos&action=verDetalle&proyecto=<?php echo $participacion->idProyecto ?>" style="text-decoration: none">
                                <button type="button" class="btn btn-info btn-sm">Entrar</button>
                            </a>

                            <!-- Enlace para el boton borrar para eliminar el proyecto -->
                            <a href="index.php?controller=proyectos&action=eliminar&idProyecto=<?php echo $participado->idProyecto ?>" style="text-decoration: none">   
                                <button type="button" class="btn btn-danger btn-sm">Dejar Proyecto</button>
                            </a>
                            <hr/>                        
                    <?php } ?>
                </section>
            </div>
            <aside class="col-lg-3" style="border-left: 1px solid black !important; margin-top: 30px; height: 750px">
                <h4><strong>Usuario:</strong></h4>
                Username: 
                <br>
                <input type="text" name="nombreUsuario" class="form-control" value="<?php  ?>" disabled />
                <br>
                Email:
                <br>
                <input type="text" name="nombreUsuario" class="form-control" value="<?php  ?>" disabled />
                <br>
                <a href="" class="btn btn-info btn-sm" title="Ver perfil" style="width: 252px !important">Ver Perfil</a> <!-- antes era 180px -->
                <br>
                <hr style="border:1px solid black;"/>
                <h4><strong>Acciones:</strong></h4>
                <a href="index.php?controller=tarea&action=tareasUsuario&usuario=<?php echo 1; //AQUI IRÁ EL USUARIO EN SESIÓN ?>" class="btn btn-info btn-sm" title="Ver Tareas del Usuario" style="width: 252px !important">Tareas del Usuario</a>
                <br><br>
                <a href="index.php?controller=archivo&action=archivosUsuario&usuario=<?php echo 1; //AQUI IRÁ EL USUARIO EN SESIÓN ?>" class="btn btn-info btn-sm" title="Ver Archivos del Usuario" style="width: 252px !important">Archivos del Usuario</a>
                <br><br>
                <a href="" class="btn btn-info btn-sm" title="Ver Comentarios del Usuario" style="width: 252px !important">Comentarios del Usuario</a>
                <br><br>
                <a href="" class="btn btn-primary btn-sm" title="Salir del Programa" style="width: 252px !important">Salir del Programa</a>
                <br><br>
            </aside>
        </div>
        
        <?php include_once "footer.php"?>
       
    </body>
</html>
