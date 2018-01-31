function Mask(){
    $(document.body).append("<div class = 'mask'></div>");
    $('.mask').css({
            'width': $(document).width(), 
            'height': $(document).height(), 
            'position': 'absolute', 
            'top': 0,  
            'left': 0, 
            'background-color': 'black', 
            'opacity': 0.2, 
            'z-index': 9999
    });
}

function Loading(){	
    
    if($('#myModal').is(':visible') && !$('#myModal').is('[closing]')){
        var x = $('.modal-body').width();
        var y = $('.modal-body').height();
        $('.modal-body').children().hide();
        if($('.fa-spin').length == 0)
            $('.modal-body').append("<i class = 'fa fa-cog fa-spin' style = 'font-size: 50pt; margin: " + (y/2-50) + "px " + (x/2-25) + "px'></i>");

    }else{
        Mask();
        $(document.body).append("<div class = 'loading'><i class = 'fa fa-cog fa-spin'></i></div>");
        $('.loading').css({
            'position': 'absolute', 
            'top': 200,
            'left': $(document).width() / 2 - 50, 
            'z-index': 9999, 
            'font-size': '72pt', 
            'color': 'white'
        });
    }
   
}

function Dialog(title, w, h, data){
    $(document.body).append("<div class = 'dialog' title = '" + title + "'><center>" + data + "</center></div>");
    $('.dialog').dialog({width: w, height: h, modal: true, close: $('.mask').remove()});
}

function Capital(str){
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function ValidMail(mail){
    mail = $.trim(mail);
    var first = mail.split("@");
    if(first.length == 2){
        var dir = first[0];
        var domain = first[1];
        var second = domain.split(".");
        if(second.length > 1){
            return true;
        }else
            return false;
    }else
        return false;
}

function ColorBox(url, title){
    if($('#ankor-ajax').length == 0)
        $(document.body).append("<a id = 'ankor-ajax'></a>");
    $('#ankor-ajax').attr('href', url);
    $('#ankor-ajax').attr('title', title);
    $('#ankor-ajax').colorbox();
    $('#ankor-ajax').click();
}


function Modal(url, title, width, fn){
    
    $('#myModal').on('show.bs.modal', function (e) {
        $('.modal-body').css({'visibility': 'hidden'});
    });
    $('#myModal').on('shown.bs.modal', function (e) {
        $('.modal-body').css({'visibility': 'visible'});
        Ready();
    });
    $('#myModal').on('hidden.bs.modal', function (e) {
//        $('.summernote').destroy();
        $('.modal-body').empty();
        $(this).removeAttr('closing');
        Dismiss();
    });
    $('#myModalLabel').text(title);
    $('.modal-dialog').animate({'width': width}, 500);
    if($('#myModal').is(':visible')){
        Loading();
        $('.modal-body').load(url, function(){
            if(fn) fn();
        });
    }else{
        Mask();
        $('.modal-body').load(url, function(){
            if(fn) fn();
            $('#myModal').modal('show');
        });
    }    
}

function CloseModal(){
    $('#myModal').modal('hide').attr('closing', '1');
}

function DoSelect(obj, change){
    $(obj).select2({
            width: 'element',
            placeholder: "Seleccione..."
    }).on('change', function(e){ (change?change():null); });
}

function MultiSelect(obj, headerLeft, headerRight, searchable, selected){
    $(obj).multiSelect({
        selectableHeader: (searchable?"<input type='text' class='search-input form-control' autocomplete='off' placeholder='" + headerLeft + "'>":headerLeft),
        selectionHeader: (searchable?"<input type='text' class='search-input form-control' autocomplete='off' placeholder='" + headerRight + "'>":headerRight),
        afterInit: function(ms){
          var that = this,
              $selectableSearch = that.$selectableUl.prev(),
              $selectionSearch = that.$selectionUl.prev(),
              selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
              selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

          that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
          .on('keydown', function(e){
            if (e.which === 40){
              that.$selectableUl.focus();
              return false;
            }
          });

          that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
          .on('keydown', function(e){
            if (e.which == 40){
              that.$selectionUl.focus();
              return false;
            }
          });
        },
        afterSelect: function(){
          this.qs1.cache();
          this.qs2.cache();
          if($(this.qs1).val() != ""){
              $(this.qs1).val('');
              $(obj).multiSelect('refresh');
          }
        },
        afterDeselect: function(){
          this.qs1.cache();
          this.qs2.cache();
          if($(this.qs2).val() != ""){
            $(this.qs2).val('');
            $(obj).multiSelect('refresh');
          }
        }
    });
    if(selected) $(obj).multiSelect('select', selected);
}

function DataTable(obj){
    $(obj).DataTable({
            "pageLength": 50,
            "autoWidth": false,
            "language": {
              "sProcessing":     "Procesando...",
              "sLengthMenu":     "Mostrar _MENU_ registros",
              "sZeroRecords":    "No se encontraron resultados",
              "sEmptyTable":     "Ningún registro encontrado",
              "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
              "sInfoEmpty":      "0 registros",
              "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
              "sInfoPostFix":    "",
              "sSearch":         "Filtrar:",
              "sUrl":            "",
              "sInfoThousands":  ",",
              "sLoadingRecords": "Cargando...",
              "oPaginate": {
                  "sFirst":    "Primero",
                  "sLast":     "Último",
                  "sNext":     "Siguiente",
                  "sPrevious": "Anterior"
              },
              "oAria": {
                  "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                  "sSortDescending": ": Activar para ordenar la columna de manera descendente"
              }
          }
      });
}

function ClearDataFilter(tbl){
    $(tbl).DataTable().search("").draw();
}

function FancyBox(url, title){
    $('#fancybox').attr('href', '');
    $('#fancybox').fancybox({
        href: url, 
        title:  title, 
        openEffect: 'elastic', 
        fitToView: true
    });
    $('#fancybox').click();
}

function toastIt(txt, type, keep, fn){
    $().toastmessage('showToast', {
        text     : txt,
        sticky   : (keep?true:false), 
        position: 'top-right', 
        type: (type?type:'notice'),
        close: (fn?fn:null)
    });
}

function Notif(msg, type, time){
    notif({
        msg: msg,
        type: (type?type:"success"),
        time: (time?time:3000)
    });
}

function Error2(msg, w, hide){
    notif({
        type: "error",
        msg: msg,
        position: "center",
        width: (w?w:$(window).width()-200),
        autohide: (hide?hide:false), 
        multiline: true
    });
}

function Dismiss(){
    $('#ui_notifIt.error').click();
}

function LoadButton(obj){
    var icon = $(obj).find('i').attr('class');
    $(obj).html("<i class = 'fa fa-spinner fa-spin' action = '" + icon + "'></i> " + $(obj).text());
    $(obj).attr('disabled', 'disabled');
    $(obj).addClass('disabled');
}

function DisplayErrors(error){
    var html = "<div class = 'alert alert-danger text-justify'>";
    if(error.responseJSON.errors){
        html += "<ul>";
        $.each(error.responseJSON.errors, function (key, item) {
              //key is the field
              html += "<li><b>" + key + "</b>: " + item[0] + "</li>";
        });
        html += "</ul>";
    }else{
        html += "<p><b>" + error.responseJSON.message + "</b></p>";
    }
    html += "</div>";
    return html;
}

function SweetAlert(type, text, fn, title){
    var mytitle = "";
    switch(type){
        case "confirm": mytitle = "Confirme..."; break;
        case "success": mytitle = "¡Correcto!"; break;
        case "error": mytitle = "¡Error!"; break;
        case "info": mytitle = "¡Aviso!"; break;
        case "warning": mytitle = "Advertencia..."; break;
    }
    swal({
        title: (title?title:mytitle),
        html: text,
        type: (type=="confirm"?"warning":type),
        showCancelButton: (fn?true:false),
        cancelButtonText: "Cancelar",
//        closeOnCancel: true
    }).then((result) => {
        if (result.value && fn) {
          fn();
        }
    });
}

function OK(msg){
    SweetAlert("success", msg);
}

function Error(msg){
    SweetAlert("error", msg);
}

function Warning(msg){
    SweetAlert("warning", msg);
}

function Question(msg, fn){
    SweetAlert("confirm", msg, fn);
}


function ShowAlert(msg){
    vex.defaultOptions.className = 'vex-theme-os';
    vex.dialog.alert(msg);
}

function ShowConfirm(msg, fn){
    vex.defaultOptions.className = 'vex-theme-os';
    vex.dialog.confirm({
        message: msg,
        callback: function(value) {
            if(value) fn();
        }
    });
}

//function ShowAlert(msg, label, fn){
//    bootbox.dialog({
//        message: msg, 
//        buttons: {
//            main: {
//                label: (label?label:"OK"),
//                className: "btn-primary",
//                callback: function() {
//                    if(fn) fn();
//                }
//            }
//        }
//    });
//}
//
//function ShowConfirm(msg, fnOk, labelOk, fnCancel, labelCancel){
//    bootbox.dialog({
//        message: msg, 
//        buttons: {
//            cancel: {
//                label: (labelCancel?labelCancel:"Cancelar"),
//                className: "btn-default",
//                callback: function() {
//                    if(fnCancel) fnCancel();
//                }
//            }, 
//            main: {
//                label: (labelOk?labelOk:"OK"),
//                className: "btn-primary",
//                callback: function() {
//                    fnOk();
//                }
//            }
//        }
//    });
//}

function ShowAlertVex(msg){
    vex.defaultOptions.className = 'vex-theme-os';
    vex.dialog.alert(msg);
}

function ShowConfirmVex(msg, fn){
    vex.defaultOptions.className = 'vex-theme-os';
    vex.dialog.confirm({
        message: msg,
        callback: function(value) {
            if(value) fn();
        }
    });
}

function Full(obj, invisibletoo){
	var full = true;
	$('.emptyfield').removeClass('emptyfield');
	$(obj).find('.require:not(".select2-container, .select2-focusser")').each(function(){
//		if( $.trim($(this).val()) == "" && ( ($(this).is(':visible') && !$(this).hasClass('select2')) || $(this).hasClass('select2-offscreen') || invisibletoo) ){
		if( $.trim($(this).val()) == "" && ( ($(this).is(':visible') || invisibletoo ) || $(this).hasClass('select2-offscreen') ) ){
			full = false;
                        $(this).addClass('emptyfield');
		}
	});
	return full;	
}

function AjaxSend(form, url, fnNoData, fnReturnData){
    var data = new FormData();
    $(form).find('input:not("button"), select, textarea').each(function(){
        var val = null;
        var name = null;
        if($(this).is(':file')){
            var inputFileImage = document.getElementById($(this).attr('id'));
            val = (inputFileImage?inputFileImage.files[0]:null);   
            name = $(this).attr('name');
        }else if($(this).is(':radio') || $(this).is(':checkbox')){
            if($(this).is(':checked')){
                val = $(this).val();
                name = $(this).attr('name');
            }
        }else{ 
            val = $(this).val();
            name = $(this).attr('name');
        }
        if(name) data.append(name, val);
    });
    $.ajax({
         url: url,
         type:'POST',
         contentType:false,
         data: data,
         processData:false,
         cache:false, 
         success: function(msg){
             if(msg){
                 if(fnReturnData)
                     fnReturnData(msg);
                 else{
                    Ready();
                    Error(msg);
                 }
             }else{
                 fnNoData();
             }
         }
    });
}

function removeLegend(){
    $("a:contains('chart by')").remove();
}

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function validateRFC(rfc){	
	for(var i=0; i<rfc.length; i++){
		if(i < 4){
			if(isNumeric(rfc[i]) || rfc[i] == ' ')
				return false;
		}else if(i > 3 && i < 10){
			if(!isNumeric(rfc[i]) || rfc[i] == ' ')
				return false;
		}		
	}
	return true;
}

function ReloadGrid(obj, page, fn){
    obj.clearAll();
    obj.loadXML(page, function(){ 
        obj.filterByAll();
        Count(obj); 
        if(fn) fn();
        Ready(); 
    });
}

function Clear(obj){	
    $('#' + obj).find('.filter').find('input, select').val('').trigger('change');
//        $(obj).find('input, select').each(function(){
//		$(this).val('');
//	});
}

function Count(obj){
    var counter = obj.getRowsNum();
    $(obj.entBox).parents('table').find('.RowCount').text("Total: " + counter);
    return counter;
}

function ExportData(obj, report){
    obj.toExcel('js/dhtmlx/grid-excel-php/generate.php?report=' + (report || ""));
}
            
function DatePicker(obj, min, max, fn){
    var fecha = new Date();
    var year = fecha.getFullYear();
    $.datepicker.regional['es'] = {
                clearText: 'Borra',
                clearStatus: 'Borra fecha actual',
                closeText: 'Cerrar',
                closeStatus: 'Cerrar sin guardar',
                prevText: '<Ant',
                prevBigText: '<<',
                prevStatus: 'Mostrar mes anterior',
                prevBigStatus: 'Mostrar año anterior',
                nextText: 'Sig>',
                nextBigText: '>>',
                nextStatus: 'Mostrar mes siguiente',
                nextBigStatus: 'Mostrar año siguiente',
                currentText: 'Hoy',
                currentStatus: 'Mostrar mes actual',
                monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio', 'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                monthStatus: 'Seleccionar otro mes',
                yearStatus: 'Seleccionar otro año',
                weekHeader: 'Sm',
                weekStatus: 'Semana del año',
                dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
                dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
                dayStatus: 'Set DD as first week day',
                dateStatus: 'Select D, M d',
                dateFormat: 'dd/mm/yy',
                firstDay: 1,
                initStatus: 'Seleccionar fecha',
                isRTL: false, 
                yearRange: (year - 100) + ":" + (year + 1)
        };
    $.datepicker.setDefaults($.datepicker.regional['es']);
    $(obj).datepicker({dateFormat: "dd-M-yy", changeMonth: true, changeYear: true, onSelect: fn, minDate: min, maxDate: max }).attr('readonly', 'true');
}

function miniLoad(obj){
    $(obj).parent().children().hide();
    $(obj).parent().append("<img src ='img/loadingBar.gif' width ='" + $(obj).parent().width() / 2 + "' heigth ='30' style = 'margin: 5px 20px 0px 10px;' class ='miniloading'>");
}

function Ready(){
    var icon = $('.fa-spin').attr('action');
    $('.fa-spin').parents('button').removeAttr('disabled').removeClass('disabled');
    $('.fa-spin').parents('a').removeAttr('disabled').removeClass('disabled');
    if(icon)
        $('.fa-spin').parent().html("<i class = '" + icon + "'></i> " + $('.fa-spin').parent().text()).removeClass('disabled');
    $('.miniloading').parent().children().show();
    $('.mask, .loading, .miniloading, .fa-spin').remove();
    $('.modal-body').children().show();
}

function NumberFormat(num, dec){
    var val = num.toString();
    if(val){
        var exp = val.split(".");
        var stack = new Array();
        var cont = 0;
        for(var i=exp[0].length -1; i>=0; i--){
            if(exp[0][i] != ","){
                stack.push(exp[0][i]);
                cont++;
            }
            if(cont == 3 && exp[0][i-1]){
                cont = 0;
                stack.push(",");
            }
        }
        var final = "";
        for(var j=stack.length -1; j>=0; j--)
            final += (stack[j]);
        if(dec)
            final += "." + (exp[1]?exp[1]:"00");
        return final;
    }
}

$(document).on('keydown', '.numeric', function(e){
//        $(this).numeric();

	var AlfaNum = new Array(48, 49, 50, 51, 52, 53, 54, 55, 56, 57);
	var NumPad = new Array(96, 97, 98, 99, 100, 101, 102, 103, 104, 105);
	var dot = new Array(110, 190);	
	var allow = new Array(8, 9, 13, 46);
	var arrow = new Array(37, 38, 39, 40);
	var n = e.which;
        var k = String.fromCharCode(e.keyCode);

	if(AlfaNum.indexOf(n) > -1 || NumPad.indexOf(n) > -1 || dot.indexOf(n) > -1 || allow.indexOf(n) > -1 || arrow.indexOf(n) > -1)
		return true;
	else
		return false;
});

$(document).on('focusout', 'input.money, input.km', function(){
    var val = $.trim($(this).val());
    if(val){
        var exp = val.split(".");
        var stack = new Array();
        var cont = 0;
        for(var i=exp[0].length -1; i>=0; i--){
            if(exp[0][i] != ","){
                stack.push(exp[0][i]);
                cont++;
            }
            if(cont == 3 && exp[0][i-1]){
                cont = 0;
                stack.push(",");
            }
        }
        var final = "";
        for(var j=stack.length -1; j>=0; j--)
            final += (stack[j]);
        final = final + ($(this).hasClass('money')?"." + (exp[1]?exp[1]:"00"):"");
        $(this).val(final);
    }
});

$(document).on('submit', 'form.avoid', function(){return false;});




