/* 
Author: Breydi vasquez pacheco
fecha: 19-06-2019
Descripcion: uso de la libreria  de Firefox PDF.js para codificar el 
archivo pdf en imagen  */

arrayBufferToBase64 = ( buffer ) => {
    var binary = '';
    var bytes = new Uint8Array( buffer );
    var len = bytes.byteLength;
    for (var i = 0; i < len; i++) {
        binary += String.fromCharCode( bytes[ i ] );
    }
    return window.btoa( binary );
}
pdfSinFirma = (resp) => {    
    let url_sifirm = `../../../kerp_breydi/lib/lib_control/Intermediario.php?r=${resp}&t=${new Date().toLocaleTimeString()}`
    const pdf_gener = fetch(url_sifirm).then(response => response.arrayBuffer()).then( (data) => {
        var buferpdf = arrayBufferToBase64(data);
        //console.log(buferpdf);
        servicioValidadorFirmaDigital(buferpdf);
    })
}  

let html="";  

function servicioValidadorFirmaDigital(base64){
    return axios({
        method: "post",
        url: "http://192.168.17.50:4000/api/v1/firmas?formato=base64",
        data: {
            documento: base64
        }
    }).then(function (k) {
        if (k.data) {
            var fs = k.data;            
            ObjFirm(fs, k.config.data);
        } else {
            throw new Error("El Documento No Presenta Niguna Firma");
        }
    }).catch(console.error);
}

function ObjFirm(data, base64){    
    const data_sign = JSON.parse(JSON.stringify(data.data));
    const base64pdf = JSON.parse(base64);    
    name_doc = "Certificado De Trabajo";
    color = "red";
    msg = "DOCUMENTO NO FIRMADO";
    if (data_sign.length > 0) {
        console.log("uno");
        data_sign.forEach(item => {
        html += `
        <div class="ui vertical accordion menu" style="width:auto; font-size:4px;">
            <div class="item" style="font-size:4px;">
                <a class="title">
                    <i class="dropdown icon" onclick="cargaQjery()"></i>
                    <b>Firmado por:</b> <span>${item.nombreComunSubject}</span><br>
                    <b>Fecha Firma:</b> <span>${item.fechaFirma}</span>
                </a>
                <div class="content">
                    <div class="ui form">
                        <div class="grouped fields" style="font-size:4px;">
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
                color = "green";
                msg = "DOCUMENTO FIRMADO";
            } else {
        console.log("dos");       
            html = `
        <div class="ui info message" style="width:auto; font-size:4px;">
            <div class="header">
                Qué ventajas se tienen al firmar digitalmente frente a la firma manuscrita?
            </div>
            <ul class="list">
                <li>Mayor seguridad e integridad de los documentos. El contenido del documento electrónico firmado nopuede ser alterado, por lo que se se garantiza la autenticación del mismo y la identidad del firmante.</li>
                <li>Se garantiza la confidencialidad, el contenido del mensaje solo será conocido por quienes esténautorizados a ello.</li>
            </ul>
        </div>
        `;
    }
        document.getElementById("root-users").innerHTML = html;
        document.getElementById("name_doc").innerHTML = `<p><span>Documento: </span>${name_doc}</p>`;
        etiquetaValidacion = `<a id="marca" class="ui mini ${color} ribbon label"><i class="file pdf outline icon"></i> ${msg}</a>`;
        document.getElementById("ifram").innerHTML = `${etiquetaValidacion} <canvas id="the-canvas"></canvas>`;
        convertPdfToImg(base64pdf.documento);           
}

if(pdf != ""){    
    servicioValidadorFirmaDigital(pdf);
}else if(pdf == ''){    
    if(action_req != ''){
        pdfSinFirma(action_req.ROOT.detalle.archivo_generado);
    }  
}else{ 
    document.getElementById("root-users").innerHTML = `
        <div class="ui info message" style="width:auto; font-size:4px;">
            <div class="header"><br>
                Qué ventajas se tienen al firmar digitalmente frente a la firma manuscrita?
            </div>
            <ul class="list">
                <li>Mayor seguridad e integridad de los documentos. El contenido del documento electrónico firmado nopuede ser alterado, por lo que se se garantiza la autenticación del mismo y la identidad del firmante.</li>
                <li>Se garantiza la confidencialidad, el contenido del mensaje solo será conocido por quienes esténautorizados a ello.</li>
            </ul>
        </div>
        `;
}

function cargaQjery() {
    $(".ui.accordion")
        .accordion({
            selector: {
                trigger: ".title .icon"
            }
        });
}              
/**********************************
 * convert to  pdf into img for to view en navigator
 * ****************************************************** */
let pdfGeneral;
let renderTask;
convertPdfToImg = (pdfi) => {
    document.getElementById("buttons").innerHTML = `
    <div id="bto-pages" class="ui mini buttons">
        <button id="prev" class="ui mini button blue basic">
            <i class="left chevron icon" ></i>
        </button>
        <button class="ui mini button black basic">
            Pagina &nbsp;&nbsp;&nbsp;
            <span id="page_num"></span> / <span id="page_count"></span>
        </button>
        <button id="next" class="ui right mini button blue basic">            
            <i class="right chevron icon"></i>
        </button>
    </div>`;
    var pdfjsLib = window['pdfjs-dist/build/pdf'];
  
    // The workerSrc property shall be specified.
    pdfjsLib.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.js';
    
  
    var pdfDoc = null,
      pageNum = 1,
      pageRendering = false,
      pageNumPending = null,
      scale = 1.5,
      canvas = document.getElementById('the-canvas'),
      ctx = canvas.getContext('2d');
  
  function renderPage(num) {
    pageRendering = true;  
    pdfDoc.getPage(num).then(function(page) {
      var viewport = page.getViewport({scale: scale});
      canvas.height = viewport.height;
      canvas.width = viewport.width;
  
      // renderizar pdf en en canvas
      var renderContext = {
        canvasContext: ctx,
        viewport: viewport
      };
      var renderTask = page.render(renderContext);
  
      //espera para renderizado
      renderTask.promise.then(function() {
        pageRendering = false;
        if (pageNumPending !== null) {
          // renderisando nueva pagina
          renderPage(pageNumPending);
          pageNumPending = null;
        }
      });
    });
  
    // actulalzar pagina actual
    document.getElementById('page_num').textContent = num;
  }
  
    function queueRenderPage(num) {
      if (pageRendering) {
        pageNumPending = num;
      } else {
        renderPage(num);
      }
    }
    
    /**
     * pagina anterior.
     */
    function onPrevPage() {        
      if (pageNum <= 1) {
        return;
      }
      pageNum--;
      queueRenderPage(pageNum);
    }
    document.getElementById('prev').addEventListener('click', onPrevPage);
    
    /**
     * pagina siguiente.
     */
    function onNextPage() {        
      if (pageNum >= pdfDoc.numPages) {
        return;
      }
      pageNum++;
      queueRenderPage(pageNum);
    }       
    
    pdfjsLib.getDocument({data: atob(pdfi)}).promise.then(function(pdfDoc_) {
      pdfDoc = pdfDoc_;
      document.getElementById('page_count').textContent = pdfDoc.numPages;    
      renderPage(pageNum);
    });
    document.getElementById('next').addEventListener('click', onNextPage); 
}

