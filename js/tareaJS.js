
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
}

//Función para las validaciones necesarias para los formularios de tareasProyectoView
function validarTarea(param) {
    var tipoForm = param.target.value;
    var fechaActual = new Date();
    var ayer = fechaActual.getDate() - 1;
    var fechaAyer = fechaActual.setDate(ayer);

    //Si el formulario es el de 'crear tarea'
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
        //Si el formulario es el de 'editar tarea'
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

//Función que oculta el formulario de 'crear tarea' y muestra el de 'editar tarea'
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

    
    var urgente = $("#urgenteViejaTarea"+contadorTarea+" > strong").text();    

    
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

//Función para cambiar el formato de las fechas y poderlo cargar como value en el formulario 'editar tarea'
function cambiarFormatoFechas(fecha) {
    var fechaFormateada = fecha.split("/").reverse().join("-");
    return fechaFormateada;
}

//Función para guardar la tarea marcada como finalizada
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
                modal = new Modal("Error: No se ha podido actualizar la tarea");
                modal.getModal();
            }
        },
        error: function (error)
        {
            modal = new Modal("Error en la conexión");
            modal.getModal();
        }
    });
}

//Función para el botón 'cancelar editar tarea'
function cancelarEdicion() {
    $("#ediTarea").hide();
    $("#nueTarea").show();
}

//Función que marca como finalizada la tarea en la vista
function finalizarTarea(contadorParaFinalizada) {

    if($("#final"+contadorParaFinalizada).is(':checked')){
        $("#btnedi"+contadorParaFinalizada).prop( "disabled", true);
    }
    else{
        $("#btnedi"+contadorParaFinalizada).prop( "disabled", false);
    }
}
