arrayBufferToBase64 = ( buffer ) => {
    var binary = '';
    var bytes = new Uint8Array( buffer );
    var len = bytes.byteLength;
    for (var i = 0; i < len; i++) {
        binary += String.fromCharCode( bytes[ i ] );
    }
    return window.btoa( binary );
}
visorPdfVerificado = (pdfFile, url, aou, name) => {        
    objPdFirm = pdfFile;
    pdf_bol = aou;
    url_g = url;
    nombre_doc = name;    
    window.open('../../../lib/lib_vista/visorpdf/visorpdf.html',
    'popUpWindow',
    'height=720,width=945,left=200,top=50,resizable=no scrollbars=no,toolbar=no,status=no,location=no');      
}
servicioValidadorFirmaDigital = (base64, url ,name) => {    
    return axios({
        method: 'post',                                    
        url:'http://192.168.17.50:4000/api/v1/firmas?formato=base64',            
        data: {
            documento: `${base64}`
        } 
    }).then(function(k){
        if(k.data){
            var fs = k;
            visorPdfVerificado(fs, url, true, name);
            console.log('ser',fs);
        }else{
            throw new Error("negativo");
        }
    }).catch(console.error);
}