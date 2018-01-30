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
            '        <span class="close glyphicon glyphicon-remove" onclick="esconderModal()"></span>\n' +
            '        <p>'+this.texto+'</p>\n' +
            '    </div>');
        this.element.css("display","block");
    }
    getModalConfirm(){
        /*Cambiamos el texto del modal y lo mostramos*/
        this.element.html('' +
            '    <div class="modal-content">\n' +
            '        <span class="close glyphicon glyphicon-remove" onclick="esconderModal()"></span>\n' +
            '        <p>'+this.texto+'</p>' +
            '        <div class="modal-footer">'+
                '        <a type="button" href="'+this.path+'" id="confirmButtom" class="btn btn-danger" onclick="location.replace(/"index.php?controller=archivo&action=eliminar&idArchivo={{archivo.idArchivo}}&origen=usuario/")">ELIMINAR</a>'+
                '        <a type="button" class="btn btn-secondary" data-dismiss="modal" onclick="esconderModal()">CANCELAR</a>'+
                '    </div>'+
            '    </div>');
        this.element.css("display","block");
    }
}