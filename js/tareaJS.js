
$(document).ready(inicio);

function inicio()
{
    $('#nuevaTarea').click(validarNuevaTarea);  
}

function validarNuevaTarea() {
    
    var fechaActual = new Date();
    var ayer = fechaActual.getDate() - 1;
    var fechaAyer = fechaActual.setDate(ayer);
    var recogerFechaInicio = $('#fit').val();
    var fechaInicioTarea = new Date(recogerFechaInicio);
    var recogerFechaFin = $('#fft').val();
    var fechaFinTarea = new Date(recogerFechaFin);
    
    if(fechaInicioTarea < fechaAyer) {
        //modal aqui
        alert('fecha inicio menor que la actual');
    }
    else {
        if(fechaFinTarea < fechaInicioTarea) {
            //modal aqui
            alert('fecha fin menor que la de inicio');
        }
        else {
            $("#formTarea").attr("action","index.php?controller=tarea&action=nuevaTarea");
            $("#formTarea").submit();
        }
    }
}

