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
        // add assoc key values, this will be posts values
        formData.append("archivos", this.file);
        //formData.append("upload_file", true);
        let idProyecto= $("#idProyecto").val();
        let nombreProyecto= $("#nombreProyecto").val();
        $.ajax({
            type: "POST",
            url: "/../nonecollab/index.php?controller=archivo&action=nuevoArchivo&idProyecto="+idProyecto+"&nombreProyecto="+nombreProyecto,
            dataType: "text",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            timeout: 60000,
            /*xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', that.progressHandling, false);
                }
                return myXhr;
            },*/
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
            },
            error: function (error) {
                let texto="Error, no se ha podido conectar con el servidor";
                modal.setText(texto);
                modal.getModal();
            }
        });
    };

    progressHandling(event){
        var percent = 0;
        var position = event.loaded || event.position;
        var total = event.total;
        var progress_bar_id = "#progress-wrp";
        if (event.lengthComputable) {
            percent = Math.ceil(position / total * 100);
        }
        // update progressbars classes so it fits your code
        $(progress_bar_id + " .progress-bar").css("width", +percent + "%");
        $(progress_bar_id + " .status").text(percent + "%");
    };
}