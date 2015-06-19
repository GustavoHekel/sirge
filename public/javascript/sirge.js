function ucfirst (str) {
	str += '';
	var f = str.charAt(0).toUpperCase();
	return f + str.substr(1);
}

function pad(n, width, z) {
	z = z || '0';
	n = n + '';
	return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
}

function mostrar_loading () {
	$.blockUI({ 
		message : '<img src="public/img/ajax-modal-loading.gif" /><h3>Procesando, por favor espere ...</h3>' ,
		css : {
			border : '' ,
			backgroundColor : '' ,
			color : '#FFFFFF' ,
			fontWeight : 900 
			}
	});
}

function bloquear_elemento (elemento) {
	$(elemento).block({ 
		message: '<img src="public/img/ajax-modal-loading.gif" /><h3>Actualice la página...</h3>' ,
		css : {
			border : '' ,
			backgroundColor : '' ,
			color : '#FFFFFF' ,
			fontWeight : 900 
		}
	}); 
}

function bloquear_elemento_small (elemento) {
	$(elemento).block({ 
		message: '<img src="public/img/ajax-modal-loading.gif" />' ,
		css : {
			border : '' ,
			backgroundColor : '' ,
			color : '#FFFFFF' ,
			fontWeight : 900 
		}
	}); 
}

function ocultar_loading_small (elemento) {
	$(elemento).unblock(); 
}

function ocultar_loading () {
	$.unblockUI();
}

function dialog_error_modificacion (id, texto) {
		$("#"+id).html(texto);
		$("#"+id).dialog({
			title		: "Atención!" ,
			buttons 	: [{
				text : "Aceptar"  ,
				class : 'btn green ' ,
				click : function(){
					$(this).dialog("close").empty();
				}
			}]
		});
	}

function estilo_dialog (color) {
	$.extend($.ui.dialog.prototype.options, { 
		show 		: 'fade' , 
		hide 		: 'fade' , 
		modal		: true , 
		width		: 650 , 
		create 		: function (event , ui) { $(this).parent('div').addClass('ui-dialog-' + color ); } ,
		close 		: function () {
			$(this).dialog('destroy');
		} ,
		resizable	: false 
	});
}

$.extend( true , $.fn.dataTable.defaults,{
		bDestroy 		: true,
		bLengthChange	: false,
		bAutoWidth		: true,
		bInfo			: false,
		bProcessing		: true,
		bFilter			: false,
		bSort			: false,
		language		: {
			emptyTable		: "No se encontraron datos para mostrar" ,
			search			: "Búsqueda : " ,
			info			: "Mostrando registros _START_ a _END_ de un total de _TOTAL_" ,
			loadingRecords	: "Espere un momento por favor..." ,
			infoFiltered	: " - filtrando de  _MAX_ registros" ,
			infoEmpty		: "No se encontraron datos para mostrar",
			paginate		: {
				first			: "Primera",
				previous		: "Anterior",
				next			: "Siguiente",
				last			: "&Uacute;ltima"
			}
		}
});

TableTools.DEFAULTS.aButtons = 	[
	{
		"sExtends"				: "csv",
		"sButtonText"			: "Guardar"
	} , {
		"sExtends"				: "pdf",
		"sButtonText"			: "PDF"
	}];

TableTools.DEFAULTS.sSwfPath = "public/javascript/plugins/data-tables/table_tools/media/swf/copy_csv_xls_pdf.swf";

$.datepicker.setDefaults ({
	dateFormat	: "yy-mm-dd" ,
	maxDate 	: '2016-12-31' ,
	monthNames  : ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"] ,
	dayNames	: [ "Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado" ] ,
	dayNamesMin	: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ] ,
	prevText	: "Anterior" ,
	nextText	: "Siguiente" 
});
