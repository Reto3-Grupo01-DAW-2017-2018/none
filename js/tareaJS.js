
$(document).ready(eventos);

function eventos() {
    modal = new Modal("");
    $(".eliminarButton").bind('click', { param: $(this) }, confirmModal);
    $("#myModal>div>span").click(esconderModal,modal.ocultarModal);
    $(".editarButton").bind('click', { param: $(this) }, mostrarFormEditar);
    $('#nuevaTarea').click(validarTarea);
    $("#editarTarea").click(validarTarea);
    $("#cancelarEditarTarea").click(cancelarEdicion); 
    
    //finalizarTarea();
    /*$('.finalizada').prop('checked')(finalizarTarea);
    $('.finalizada').click(finalizarTarea);*/
}

function validarTarea() {
    
    var fechaActual = new Date();
    var ayer = fechaActual.getDate() - 1;
    var fechaAyer = fechaActual.setDate(ayer);
    var recogerFechaInicio = $('.fit').val();
    var fechaInicioTarea = new Date(recogerFechaInicio);
    var recogerFechaFin = $('.fft').val();
    var fechaFinTarea = new Date(recogerFechaFin);
    
    if(fechaInicioTarea < fechaAyer) {
        alert('fecha inicio menor que la actual');
        //PONER UN MODAL
    }
    else {
        if(fechaFinTarea < fechaInicioTarea) {
            alert('fecha fin menor que la de inicio');
            //PONER UN MODAL
        }
        else {
            if(($(".tipoForm").attr("value") == 'nuevo')) {
                $("#formNuevaTarea").attr("action","/../nonecollab/index.php?controller=tarea&action=nuevaTarea");
                $("#formNuevaTarea").submit();
            }
            else {
                if($(".tipoForm").attr("value") == 'editando') {
                    $("#formTarea").attr("action","index.php?controller=tarea&action=modificarTarea");
                    $("#formTarea").submit();
                }
            }
            
        }
    }
}

function mostrarFormEditar() {
    $("#nueTarea").hide();
    $("#ediTarea").show();
    var contadorComentario=event.target.value;
    var idTarea = $("input[name=hiddenIdTarea"+contadorComentario).val();
    var proyecto = $("#ip").val();
    var nombreTarea = $("#nombreViejaTarea"+contadorComentario+" > strong").text();    
    var fechaInicioTarea = $("#fechaInicioViejaTarea"+contadorComentario+" > strong").text();
    var fechaFinTarea = $("#fechaFinViejaTarea"+contadorComentario+" > strong").text();
    var urgente = $("#urgenteViejaTarea"+contadorComentario+" > strong").text();
    //var nombreTarea = $("#editadaViejaTarea"+contadorComentario).text();
    
    //var participantes = $("input[name=participantes]");
    
    $("#ent").attr("value", nombreTarea);
    $("#efit").attr("value", fechaInicioTarea);
    $("#efft").attr("value", fechaFinTarea);
    $("#idTaEd").attr("value", idTarea);
    
    /*alert('idTarea--> ' + idTarea + '\n' +
            'proyecto--> ' + proyecto + '\n' +
            'nombreTarea--> ' + nombreTarea + '\n' +
            'fechaInicioTarea--> ' + fechaInicioTarea + '\n' +
            'fechaFinTarea--> ' + fechaFinTarea + '\n' +
            'urgente--> ' + urgente) + '\n' +
            'participante--> ' + participantes;*/
    
    //asignada a
    //var nombreTarea = $("#nombreViejaTarea"+contadorComentario).text();
    
    /*$("#formsTarea").html('\
        <div id="ediTarea">\n\
            <h4><strong>Editar Tarea:</strong></h4>\n\
            <form id="formEditarTarea" method="post"\n\
                Nuevo nombre Tarea:\n\
                <input type="text" id="ent" name="editandoNombreTarea" class="form-control" value="'+nombreTarea+'" required />\n\
                <br>\n\
                Nueva Fecha Inicio:\n\
                <input type="date" name="editandoFechaInicioTarea" class="form-control fit" required />\n\
                <br>\n\
                Nueva Fecha Finalización:\n\
                <input type="date" name="editandoFechaFinTarea" class="form-control fft" required />\n\
                <br>\n\
                Urgente:\n\
                <span class="form-check form-check-inline">\n\
                    <strong>Si</strong>&nbsp;<input class="form-check-input" type="radio" name="editandoUrgente" id="eu1" value="si" checked />\n\
                </span>\n\
                &nbsp;&nbsp;&nbsp;\n\
                <span class="form-check form-check-inline">\n\
                    <strong>No</strong>&nbsp;<input class="form-check-input" type="radio" name="editandoUrgente" id="eu2" value="no" />\n\
                </span>\n\
                <br><br>\n\
                Reasignar tarea a:&nbsp;&nbsp;\n\
                <select class="form-control-sm" id="et" name="encargadoTarea" required>\n\
                    <option></option>\n\
                    {% for participante in participantes %}\n\
                        <option value="{{participante.idParticipante}}"><strong>{{participante.username}}</strong></option>\n\
                    {% endfor %}\n\
                </select> \n\
                <br><br>\n\
                <input type="hidden" id="eip" name="proyecto" value="' + proyecto + '" />\n\
                <input type="hidden" id="e" class="tipoForm" name="tipo" value="editando" />\n\
                <input type="submit" id="editarTarea" class="col-xs-12 btn btn-warning btn-sm" title="Modificar Tarea" value="Modificar"/>\n\
                <input type="submit" id="cancelarEditarTarea" class="col-xs-12 btn btn-secondary btn-sm" title="Cancelar Editar Tarea" value="Cancelar Editar Tarea"/>\n\
            </form>\n\
            <br>\n\
        </div>');*/
    //$("#ent").val(nombreTarea);
}


























function cancelarEdicion() {
    $("#ediTarea").hide();
    $("#nueTarea").show();
}

function finalizarTarea() {
    /*$('.finalizada').click(function(){
        $(".btnEditarTarea").disabled = true;
    });*/
    $(".btnEditarTarea").disabled = true;
}

function esconderModal(){
    $(".modal").css("display","none");
    $(".modal").html('');
}

function confirmModal(event){
    event.preventDefault();
    var ruta=event.target.parentElement.href;
    let text="El comentario se eliminara por completo,<br>¿Estas seguro?";
    modal.setText(text);
    modal.setPath(ruta);
    modal.getModalConfirm();
}
