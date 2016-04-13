var sslstrip_auto_refresh;
var sslstrip_showDots;
var sslstrip_small_showDots;

var sslstrip_showLoadingDots = function() {
    clearInterval(sslstrip_showDots);
	if (!$("#sslstrip_loadingDots").length>0) return false;
    sslstrip_showDots = setInterval(function(){            
        var d = $("#sslstrip_loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

var sslstrip_small_showLoadingDots = function() {
    clearInterval(sslstrip_small_showDots);
	if (!$("#sslstrip_small_loadingDots").length>0) return false;
    sslstrip_small_showDots = setInterval(function(){            
        var d = $("#sslstrip_small_loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

$.fn.toggleClick=function() {
	var functions=arguments, iteration=0
		return this.click(function(){
			functions[iteration].apply(this,arguments)
			iteration= (iteration+1) %functions.length
		})
}

function sslstrip_myajaxStart()
{
	if(sslstrip_auto_refresh == null)
	{
		$("#sslstrip.refresh_text").html('<em>Loading<span id="sslstrip_loadingDots"></span></em>'); 
		sslstrip_showLoadingDots();
	
		$("#sslstrip_small.refresh_text").html('<em>Loading<span id="sslstrip_small_loadingDots"></span></em>'); 
		sslstrip_small_showLoadingDots();
	}
}

function sslstrip_myajaxStop(msg)
{
	if(sslstrip_auto_refresh == null)
	{
		$("#sslstrip.refresh_text").html(msg); 
		clearInterval(sslstrip_showDots);
	
		$("#sslstrip_small.refresh_text").html(msg); 
		clearInterval(sslstrip_small_showDots);
	}
}

function sslstrip_init_small() {
	
	sslstrip_refresh_tile();
}

function sslstrip_init() {

	sslstrip_refresh();
	sslstrip_refresh_history();
	sslstrip_refresh_custom();
	sslstrip_refresh_config();
	
	$("#tabs ul").idTabs();
				
    $("#sslstrip_auto_refresh").toggleClick(function() {
			$("#sslstrip_auto_refresh").html('<font color="lime">On</font>');
			$('#auto_time').attr('disabled', 'disabled');
			
			sslstrip_auto_refresh = setInterval(
			function ()
			{
				sslstrip_refresh();
			},
			$("#auto_time").val());
		}, function() {
			$("#sslstrip_auto_refresh").html('<font color="red">Off</font>');
			$('#auto_time').removeAttr('disabled');
				
            clearInterval(sslstrip_auto_refresh);
			sslstrip_auto_refresh = null;
		});	
}

function sslstrip_toggle(action) {
	
	if($('#verbose:checkbox:checked').val() == "verbose")
		$.get('/components/infusions/sslstrip/includes/actions.php?sslstrip&verbose&'+action, function() { refresh_small('sslstrip','infusions'); });
	else
		$.get('/components/infusions/sslstrip/includes/actions.php?sslstrip&'+action, function() { refresh_small('sslstrip','infusions'); });

	if(action == 'stop') {
		$("#sslstrip_link").html('<strong>Start</strong>');
		$("#sslstrip_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#sslstrip_link").attr("href", "javascript:sslstrip_toggle('start');");
		$('#sslstrip_output').val('sslstrip has been stopped...');	
		
		$('#verbose').removeAttr('disabled');
		
		sslstrip_refresh_history();
	}
	else {
		$("#sslstrip_link").html('<strong>Stop</strong>');
		$("#sslstrip_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#sslstrip_link").attr("href", "javascript:sslstrip_toggle('stop');");
		$('#sslstrip_output').val('sslstrip is running...');
		
		$('#verbose').attr('disabled', 'disabled');
		
		sslstrip_refresh_history();
	}
}

function sslstrip_toggle_small(action) {
	
	if($('#verbose_small:checkbox:checked').val() == "verbose")
		$.get('/components/infusions/sslstrip/includes/actions.php?sslstrip&verbose&'+action);
	else
		$.get('/components/infusions/sslstrip/includes/actions.php?sslstrip&'+action);

	if(action == 'stop') {
		$("#sslstrip_link_small").html('<strong>Start</strong>');
		$("#sslstrip_small").html('<font color="red"><strong>disabled</strong></font>');
		$("#sslstrip_link_small").attr("href", "javascript:sslstrip_toggle_small('start');");
		$('#sslstrip_output_small').val('sslstrip has been stopped...');	
		
		$('#verbose_small').removeAttr('disabled');
	}
	else {
		$("#sslstrip_link_small").html('<strong>Stop</strong>');
		$("#sslstrip_small").html('<font color="lime"><strong>enabled</strong></font>');
		$("#sslstrip_link_small").attr("href", "javascript:sslstrip_toggle_small('stop');");
		$('#sslstrip_output_small').val('sslstrip is running...');
		
		$('#verbose_small').attr('disabled', 'disabled');
	}
}

function sslstrip_refresh() {	
	$.ajax({
		type: "GET",
		data: "lastlog&filter="+$("#filter").val(),
		beforeSend: sslstrip_myajaxStart(),
		url: "/components/infusions/sslstrip/includes/data.php",
		success: function(msg){
			$("#sslstrip_output").val(msg).scrollTop($("#sslstrip_output")[0].scrollHeight - $("#sslstrip_output").height());
			
			sslstrip_myajaxStop('');
		}
	});
}

function sslstrip_refresh_history() {
	$.ajax({
		type: "GET",
		data: "history",
		beforeSend: sslstrip_myajaxStart(),
		url: "/components/infusions/sslstrip/includes/data.php",
		success: function(msg){
			$("#sslstrip_content_history").html(msg);
			sslstrip_myajaxStop('');
		}
	});
}

function sslstrip_refresh_custom() {
	$.ajax({
		type: "GET",
		data: "custom",
		beforeSend: sslstrip_myajaxStart(),
		url: "/components/infusions/sslstrip/includes/data.php",
		success: function(msg){
			$("#sslstrip_content_custom").html(msg);
			sslstrip_myajaxStop('');
		}
	});
}

function sslstrip_showTab()
{
	$("#Output").show(); 
	$("#History").hide();
	$("#History_link").removeClass("selected"); 
	$("#Custom").hide();
	$("#Custom_link").removeClass("selected"); 
	$("#Output_link").addClass("selected");
}

function sslstrip_load_file(what, which) {
	$.ajax({
		type: "GET",
		data: "load&file=" + which + "&" + what,
		beforeSend: sslstrip_myajaxStart(),
		url: "/components/infusions/sslstrip/includes/actions.php",
		success: function(msg){
			$("#sslstrip_output").val(msg);
			sslstrip_showTab();		
			sslstrip_myajaxStop('');
		}
	});
}

function sslstrip_delete_file(what, which) {
	$.ajax({
		type: "GET",
		data: "delete&file=" + which + "&" + what,
		beforeSend: sslstrip_myajaxStart(),
		url: "/components/infusions/sslstrip/includes/actions.php",
		success: function(msg){
			$("#sslstrip_content_history").html(msg);
			sslstrip_myajaxStop('');
			sslstrip_refresh_history();
			sslstrip_refresh_custom();
		}
	});
}

function sslstrip_boot_toggle(action) {
	$.get('/components/infusions/sslstrip/includes/actions.php?boot', {action: action});

	if(action == 'disable') {
		$("#boot_link").html('<strong>Enable</strong>');
		$("#boot_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#boot_link").attr("href", "javascript:sslstrip_boot_toggle('enable');");
	}
	else {
		$("#boot_link").html('<strong>Disable</strong>');
		$("#boot_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#boot_link").attr("href", "javascript:sslstrip_boot_toggle('disable');");
	}
}

function sslstrip_install(where) {
	$.ajax({
		type: "GET",
		data: "install&where=" + where,
		beforeSend: sslstrip_myajaxStart(),
		url: "/components/infusions/sslstrip/includes/actions.php",
		success: function(msg){
			$("#sslstrip_output").val(msg);
			sslstrip_myajaxStop('');
			
			draw_large_tile('sslstrip', 'infusions');
		}
	});
}

function sslstrip_execute_custom_script(cmd) {
	$.ajax({
		type: "GET",
		data: "execute&cmd="+cmd,
		beforeSend: sslstrip_myajaxStart(),
		url: "/components/infusions/sslstrip/includes/actions.php",
		success: function(msg){
			$("#sslstrip_output").val(msg);
			$('#sslstrip_output').val('Custom script is running...');
			sslstrip_myajaxStop('');
			sslstrip_refresh_history();
			sslstrip_refresh_custom();
		}
	});
}

function sslstrip_refresh_config() {
	$.ajax({
		type: "GET",
		data: "get_conf",
		beforeSend: sslstrip_myajaxStart(),
		url: "/components/infusions/sslstrip/includes/conf.php",
		success: function(msg){
			$("#sslstrip_content_conf").html(msg);
			sslstrip_myajaxStop('');
		}
	});
}

function sslstrip_set_config() {
	$.ajax({
		type: "POST",
		data: "set_conf=1&commands="+$.base64.encode($("#command_File").val()),
		beforeSend: sslstrip_myajaxStart(),
		url: "/components/infusions/sslstrip/includes/conf.php",
		success: function(msg){
			sslstrip_myajaxStop(msg);
			sslstrip_refresh();
			sslstrip_refresh_history();
		}
	});
}

function sslstrip_refresh_tile() {
	$.ajax({
		type: "GET",
		data: "lastlog",
		beforeSend: sslstrip_myajaxStart(),
		url: "/components/infusions/sslstrip/includes/data.php",
		success: function(msg){
			sslstrip_myajaxStop('');
			$("#sslstrip_output_small").val(msg).scrollTop($("#sslstrip_output_small")[0].scrollHeight - $("#sslstrip_output_small").height());
		}
	});
}