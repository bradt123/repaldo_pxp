let objRecpFirm;
let objResponse = {};
let objdata;
let url;
const estado = false;
let bol;
let html='';
let name_doc;
function miFuncion() {                   
    bol = opener.pdf_bol;
    url = opener.url_g;
    name_doc = opener.name_file;    
    color = 'red';
    msg = 'DOCUMENTO NO FIRMADO';
    console.log(opener);
    if (bol) {
        objResponse = opener.objPdFirm.data.data;
        const data_sign = JSON.parse(JSON.stringify(objResponse));
        if (data_sign[0] != undefined) {
            data_sign.forEach(item => {
                html += `                                
        <div class="ui vertical accordion menu" style="width:300px;font-size:12px;">
        <div class="item">
            <a class="active title">
                <i class="dropdown icon" onclick="cargaQjery()"></i>
                <b>Firmado por:</b> <span>${item.nombreComunSubject}</span><br>
                <b>Fecha Firma:</b> <span>${item.fechaFirma}</span>
            </a>                    
            <div class="content">
                <div class="ui form">
                    <div class="grouped fields">
                        <label>Origen Certificado:</label>
                        <div class="field">                                                                            
                                Entidad ADSIB Certficadora
                        </div>
                        <label>Observaciones:</label>
                        <div class="field">
                                ninguna
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>                
        `;
            });
            color = 'green';
            msg = 'DOCUMENTO FIRMADO';            
        } else {
            html = `
    <div class="ui info message" style="width:280px;">
        <!-- <i class="close icon"></i> -->
        <div class="header">
            Qué ventajas se tienen al firmar digitalmente frente a la firma manuscrita?
        </div>
        <ul class="list">
            <li>Mayor seguridad e integridad de los documentos. El contenido del documento electrónico firmado no puede ser alterado, por lo que se se garantiza la autenticación del mismo y la identidad del firmante.</li>
            <li>Se garantiza la confidencialidad, el contenido del mensaje solo será conocido por quienes estén autorizados a ello.</li>
        </ul>
    </div>
    `;
        }
    } else {
        html = `
    <div class="ui info message" style="width:280px;">
            <!-- <i class="close icon"></i> -->
        <div class="header">
            Qué ventajas se tienen al firmar digitalmente frente a la firma manuscrita?
        </div>
        <ul class="list">
            <li>Mayor seguridad e integridad de los documentos. El contenido del documento electrónico firmado no puede ser alterado, por lo que se se garantiza la autenticación del mismo y la identidad del firmante.</li>
            <li>Se garantiza la confidencialidad, el contenido del mensaje solo será conocido por quienes estén autorizados a ello.</li>
        </ul>
    </div>
    `;
    }    
    document.getElementById('root-users').innerHTML = html;
    document.getElementById('name_doc').innerHTML = `<h4><a class="header">${name_doc}</a></h4>`;
    etiquetaValidacion = `<a id="marca" class="ui ${color} ribbon label">${msg}</a>`;            
    document.getElementById('ifram').innerHTML = `${etiquetaValidacion} <iframe src=${url} frameborder="0" width="600" height="300" ></iframe>`;
}
window.onload = miFuncion;
function cargaQjery() {
    $('.ui.accordion')
        .accordion({
            selector: {
                trigger: '.title .icon'
            }
        });
}