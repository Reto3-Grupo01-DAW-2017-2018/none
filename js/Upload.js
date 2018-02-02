class Upload{
    constructor (files){
        this.file=files
    }

    getType(){
        return this.file.type;
    };
    getSize(){
        return this.file.size;
    };
    getName(){
        return this.file.name;
    };
    doUpload(){
        var that = this;
        var formData = new FormData();
        let modal = new Modal();
        formData.append("archivos", this.file);
        let idProyecto= $("#idProyecto").val();
        let nombreProyecto= $("#nombreProyecto").val();
        $.ajax({
            type: "POST",
            url: "/../nonecollab/index.php?controller=archivo&action=nuevoArchivo&idProyecto="+idProyecto,
            dataType: "text",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            timeout: 60000,
            async: false,
            success: function (data) {
                // your callback here
                alert(data);
                if(data == "false"){
                    let texto="Error, no se ha podido completar la subida del archivo o ya existe un archivo con ese nombre en el servidor.";
                    modal.setText(texto);
                    modal.getModal();
                }else{
                    if(data == "true"){
                        let texto="Archivo subido con exito!";
                        modal.setText(texto);
                        modal.getModal();
                    }else{
                        alert(data);
                        let texto="Error desconocido";
                        modal.setText(texto);
                        modal.getModal();
                    }
                }
            },/*
            Este codigo es para sacar una barra de progreso, quitado por razones de usabilidad
            xhr: function (data) {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', that.progressHandling, false);
                }
                return myXhr;
            },*/
            error: function (error) {
                let texto="Error, no se ha podido conectar con el servidor "+error;
                modal.setText(texto);
                modal.getModal();
            }
        });
    };
    /*
    Este codigo es para sacar una barra de progreso, quitado por razones de usabilidad
    progressHandling(event){
        var percent = 0;
        var position = event.loaded;// || event.position;
        var total = event.total;
        var progress_bar_id = "#progress-bar";
        if (event.lengthComputable) {
            percent = Math.ceil(position / total * 100);
        }
        // update progressbars classes so it fits your code
        $(".progress-bar").prop("aria-valuenow", percent);
        $(".progress-bar").css("width", percent + "%");
        $(".status").text(percent + "%");
    };
    */
}