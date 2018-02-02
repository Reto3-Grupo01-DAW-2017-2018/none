$(document).ready(eventos);
function eventos() {
    $(document).on('click', '.close,#cancelButton', esconderModal);
    $(document).on('click', '.eliminarButton',{ param: $(this) }, confirmModal);
}
function esconderModal(){
    $(".modal").css("display","none");
    $(".modal").html('');
}
function confirmModal(event){
    event.preventDefault();
    var ruta=event.target.parentElement.href;
    let text="¿Confirmar la acción?";
    modal.setText(text);
    modal.setPath(ruta);
    modal.getModalConfirm();
}
class Modal{
    constructor (texto){
        this.texto=texto;
        this.element=$(".modal");/*Aqui va el elemento modal que cogemos mediante su clase */
    }
    setText(texto){
        /*Cambiamos el texto del modal para reutilizarlo*/
        this.texto=texto;
    }
    setPath(ruta){
        /*Cambiamos la ruta del modal para reutilizarlo*/
        this.path=ruta;
    }
    getModal(){
        /*Cambiamos el texto del modal y lo mostramos*/
        this.element.html('' +
            '    <div class="modal-content">\n' +
            '        <span class="close glyphicon glyphicon-remove"></span>\n' +
            '        <p>'+this.texto+'</p>\n' +
            '    </div>');
        this.element.css("display","block");
    }
    getModalConfirm(){
        /*Cambiamos el texto del modal y lo mostramos*/
        this.element.html('' +
            '    <div class="modal-content">\n' +
            '        <span class="close glyphicon glyphicon-remove""></span>\n' +
            '        <p>'+this.texto+'</p>' +
            '        <div class="modal-footer">'+
                '        <a type="button" href="'+this.path+'" id="confirmButtom" class="btn btn-danger">CONFIRMAR</a>'+
                '        <a type="button" id="cancelButton" class="btn btn-secondary" data-dismiss="modal" >CANCELAR</a>'+
                '    </div>'+
            '    </div>');
        this.element.css("display","block");
    }
}