
$(document).ready(eventos);

function eventos() {
    modal = new Modal("");
    $(".eliminarButton").bind('click', { param: $(this) }, confirmModal);
    $("#myModal>div>span").click(esconderModal,modal.ocultarModal);
    $(".editarButton").bind('click', { param: $(this) }, mostrarFormEditar);
    $('#nuevaTarea').bind('click', { param: $(this) }, validarTarea);
    $("#editarTarea").bind('click', { param: $(this) }, validarTarea);
    $("#cancelarEditarTarea").click(cancelarEdicion); 
    $(".finalizadaViejaTarea").bind('click', { param: $(this) }, guardarFinalizada);
    /*$("#formNuevaTarea").submit(function(event){
        event.preventDefault()
        validarTarea();
    });*/
    
    //finalizarTarea();
    /*$('.finalizada').prop('checked')(finalizarTarea);
    $('.finalizada').click(finalizarTarea);*/
}

function validarTarea(param) {
    var tipoForm = param.target.value;
    var fechaActual = new Date();
    var ayer = fechaActual.getDate() - 1;
    var fechaAyer = fechaActual.setDate(ayer);

    if(tipoForm == 'Crear Tarea') {

        var recogerFechaInicio = $('#fit').text();
        var fechaInicioTarea = new Date(recogerFechaInicio);
        var recogerFechaFin = $('#fft').text();
        var fechaFinTarea = new Date(recogerFechaFin);
        var nombreTarea= $('#nnt').val();
        var asignado=$('#et option:selected').val();
        if(fechaInicioTarea==null||fechaFinTarea==null||nombreTarea==null||asignado==""){
            modal = new Modal("Campos vacios");
            modal.getModal();
        }else {
            if (fechaInicioTarea < fechaAyer) {
                modal = new Modal("Error: Fecha inicio menor que la actual");
                modal.getModal();
            }
            else {
                if (fechaFinTarea < fechaInicioTarea) {
                    modal = new Modal("Error: Fecha fin menor que la de inicio");
                    modal.getModal();
                }
                else {
                    $("#formNuevaTarea").attr("action", "/../nonecollab/index.php?controller=tarea&action=nuevaTarea");
                    $("#formNuevaTarea").submit();
                }
            }
        }
    }
    else {
        if(tipoForm == 'Modificar') {
            var recogerEditandoFechaInicio = $('#efit').text();
            var fechaInicioTareaEdit = new Date(recogerEditandoFechaInicio);
            var recogerEditandoFechaFin = $('#efft').text();
            var fechaFinTareaEdit = new Date(recogerEditandoFechaFin);
            var nombreTarea= $('#ent').val();
            var asignado=$('#eet option:selected').val();

            if(fechaInicioTareaEdit==null||fechaFinTareaEdit==null||nombreTarea==null||asignado==null){
                modal = new Modal("Campos vacios");
                modal.getModal();
            }else {
                if (fechaInicioTarea < fechaAyer) {
                    modal = new Modal("Error: Fecha inicio menor que la actual");
                    modal.getModal();
                }
                else {
                    if (fechaFinTarea < fechaInicioTarea) {
                        modal = new Modal("Error: Fecha fin menor que la de inicio");
                        modal.getModal();
                    }
                    else {
                        $("#formEditarTarea").attr("action", "index.php?controller=tarea&action=modificarTarea");
                        $("#formEditarTarea").submit();
                    }
                }
            }
        }
    }
}

function mostrarFormEditar(event) {
    $("#nueTarea").hide();
    $("#ediTarea").show();
    var contadorTarea=event.target.value;
    var idTarea = $("input[name=hiddenIdTarea"+contadorTarea).val();
    var proyecto = $("#ip").val();
    var nombreTarea = $("#nombreViejaTarea"+contadorTarea+" > strong").text();  
    
    var fechaInicioTarea = $("#fechaInicioViejaTarea"+contadorTarea+" > strong").text();
    var fechaInicioTareaFormateada = cambiarFormatoFechas(fechaInicioTarea);    
    
    var fechaFinTarea = $("#fechaFinViejaTarea"+contadorTarea+" > strong").text();
    var fechaFinTareaFormateada = cambiarFormatoFechas(fechaFinTarea);
    
    //AKI COMPROBAR LOS QUE ESTAN SELECCIONADOS DE URGENTE Y ASIGNADA A
    
    var urgente = $("#urgenteViejaTarea"+contadorTarea+" > strong").text();    
    
    //var editada = $("#editadaViejaTarea"+contadorTarea+" > strong").text();
    //EDITADA, AL MODIFICAR, SIEMPRE VA A SER SI (donde modificamos esto, aki o en controller??)
   //var editada = 'si';
    
    var asignada = $("#idUsuarioAsignado"+contadorTarea).val();
    var creador = $("#idUsuarioCreador"+contadorTarea).val();
    

    //Asignamos los valores a los campos del formulario 'editar'
    $("#idTaEd").attr("value", idTarea);
    $("#ent").attr("value", nombreTarea);
    $("#efit").attr("value", fechaInicioTareaFormateada);
    $("#efft").attr("value", fechaFinTareaFormateada);

    if(urgente == 'si') {        
        $('input:radio[name="editandoUrgente"][value="no"]').prop('checked', false);
        $('input:radio[name="editandoUrgente"][value="si"]').prop('checked', true);
       
    }
    else {        
        $('input:radio[name="editandoUrgente"][value="si"]').prop('checked', false);
        $('input:radio[name="editandoUrgente"][value="no"]').prop('checked', true);
    }
    
    var arrayParticipantes = $("option[name=opcionesSelect]");
    for(var i = 0; i < arrayParticipantes.length; i++) {
        if(arrayParticipantes[i].value == asignada) {
            arrayParticipantes[i].selected='selected';
        }
    }
    $("#ect").attr("value", creador); // creadortarea = participante que creó la tarea
}

function cambiarFormatoFechas(fecha) {
    //var fechaFormateada = fecha.replace(/\//g, "-").split("-").reverse().join("-");
    var fechaFormateada = fecha.split("/").reverse().join("-");
    /*var fechaFormateada2 = fechaFormateada.split("-");
    var fechaFormateada3 = fechaFormateada2.reverse();
    var fechaFormateadaFinal = fechaFormateada3.join('-');*/
    
    //return fechaFormateadaFinal;
    return fechaFormateada;
}

function guardarFinalizada(event)
{
    var finalizada;
    var idTarea=event.target.value;
    var contadorTareaPinchada = event.target.id.substring(5);
    if(event.target.checked){
        finalizada = "si";
    }else{
        finalizada = "no";
    }
    
   
    var datos= JSON.parse('{ "idTarea":'+idTarea+', "finalizada":'+'"'+finalizada+'"'+'}');
    
    $.ajax
    ({
        type: 'POST',
        url: '/../nonecollab/index.php?controller=tarea&action=guardarFinalizada',
        data: datos,
        dataType: 'text',
        success: function (data)
        {
            resultado=data;
            if(resultado==1)
            {
                finalizarTarea(contadorTareaPinchada);
            }
            else
            {
                //modal error
            }
        },
        error: function (error)
        {
            alert("error del servidor");
            console.log('Llamada Oo--> '+error);
        }
    });
}

function cancelarEdicion() {
    $("#ediTarea").hide();
    $("#nueTarea").show();
}

function finalizarTarea(contadorParaFinalizada) {

    if($("#final"+contadorParaFinalizada).is(':checked')){
        $("#btnedi"+contadorParaFinalizada).prop( "disabled", true);
    }
    else{
        $("#btnedi"+contadorParaFinalizada).prop( "disabled", false);
    }
}

function esconderModal(){
    $(".modal").css("display","none");
    $(".modal").html('');
}

function confirmModal(event){
    event.preventDefault();
    var ruta=event.target.parentElement.href;
    let text="La Tarea se eliminará por completo,<br>¿Estas seguro?";
    modal.setText(text);
    modal.setPath(ruta);
    modal.getModalConfirm();
}
