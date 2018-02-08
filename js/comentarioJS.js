
$(document).ready(eventos);

function eventos(){
    modal = new Modal("");
    $(".editarButton").bind('click', { param: $(this) }, mostrarFormEditar);
    $("#escribirComentario").click(comprobarComentario);    
    $("#editarComentario").click(comprobarComentario);
    $("#cancelarEditarComentario").click(cancelarEdicion);    
    $("#myModal>div>span").click(esconderModal,modal.ocultarModal);
    $(".eliminarButton").bind('click', { param: $(this) }, confirmModal);
}

//Funcion que hace las validaciones necesarias de los formularios en comentariosProyectoView
function comprobarComentario() {
    let idComentario = $("input[name=idComentario]").attr("value");

    
    let proyecto = $("input[name=proyecto]").val();
    let nombreProyecto = $("input[name=nombreProyecto]").val();
    let responsable = $("input[name=responsable]").val();
    let participante = $("input[name=participante]").val();

    event.preventDefault();
    //Si el formulario es el de 'nuevo comentario'
    if(($(".tipoForm").attr("value") == 'nuevo')) {
        if($("#contenidoNuevoCom").val().length > 300) {
            modal = new Modal("El texto del comentario es demasiado largo");
            modal.getModal();
        }
        else {
            $("#formNuevoComentario").attr("action","index.php?controller=comentario&action=nuevoComentario&proyecto="+proyecto+"&nombreProyecto="+nombreProyecto+"&responsable="+responsable+"&participante="+participante);
            $("#formNuevoComentario").submit();
        }            
    }
    else {
        //Si el formulario es el de editar comentario
        if($(".tipoForm").attr("value") == 'editando') {
            if($("#contenidoEditandoCom").val().length > 300) {
                modal = new Modal("El texto del comentario es demasiado largo");
                modal.getModal();
            }
            else {
                if($("#contenidoViejoCom"+$("#comentarioHidden").val()).text().localeCompare($("#contenidoEditandoCom").val()) != 0) {
                    $("#formEditarComentario").attr("action","index.php?controller=comentario&action=modificarComentario&comentario="+idComentario+"&proyecto="+proyecto+"&nombreProyecto="+nombreProyecto+"&responsable="+responsable+"&participante="+participante);
                    $("#formEditarComentario").submit();
                }
                else {
                    modal = new Modal("No se ha modificado el texto del comentario");
                    modal.getModal();
                }
            }
        }
    }
}

//Función que carga el formulario para editar el comentario
function mostrarFormEditar(event) {  
    $("#nuevoComentario").hide();
    $("#editarComentario").show();
    var contadorComentario=event.target.value;
    var idComentario = $("input[name=hiddenIdComentario"+contadorComentario).val();
    var contenido = $("#contenidoViejoCom"+contadorComentario).text();
    var participante = $("#idParticipante").val();;
    var proyecto = $("#idProyecto").val();
   
    $("#formsComentario").html('\
        <div id="editarComentario">\n\
            <h4 id="edi"><strong>Editar Comentario:</strong></h4>\n\
            <form id="formEditarComentario" method="post">\n\
                Nuevo texto Comentario:\n\
                <textarea class="form-control" id="contenidoEditandoCom" name="nuevoContenido" rows="3" placeholder="(max. 300 caracteres)" required>'+ contenido +'</textarea>\n\
                <br>\n\
                <input type="hidden" id="idParticipante" name="idComentario" value="'+ idComentario +'" />\n\
                <input type="hidden" id="idParticipante" name="participante" value="'+ participante +'" />\n\
                <input type="hidden" id="idProyecto" name="proyecto" value="'+ proyecto +'" />\n\
                <input type="hidden" class="tipoForm" name="tipo" value="editando" />\n\
                <input type="hidden" id="comentarioHidden" value="idComentario" />\n\
                <input type="submit" id="editarComentario" onclick="comprobarComentario()" class="col-xs-12 btn btn-warning btn-sm" title="Editar Comentario" value="Modificar"/>\n\
                <input type="submit" id="cancelarEditarComentario" class="col-xs-12 btn btn-secondary btn-sm" title="Cancelar Editar Comentario" value="Cancelar Editar Comentario"/>\n\
            </form>\n\
            <br>\n\
        </div>');
}

//Funcion para el boton de cancelar la edición del comentario
function cancelarEdicion() {
    $("#editarComentario").hide();
    $("#nuevoComentario").show();    
}
