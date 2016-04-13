var trapcookies_auto_refresh;
var trapcookies_showDots;
var trapcookies_small_showDots;

var trapcookies_showLoadingDots = function() {
    clearInterval(trapcookies_showDots);
	if (!$("#trapcookies_loadingDots").length>0) return false;
    trapcookies_showDots = setInterval(function(){            
        var d = $("#trapcookies_loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

var trapcookies_small_showLoadingDots = function() {
    clearInterval(trapcookies_small_showDots);
	if (!$("#trapcookies_small_loadingDots").length>0) return false;
    trapcookies_small_showDots = setInterval(function(){            
        var d = $("#trapcookies_small_loadingDots");
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

function trapcookies_myajaxStart()
{
	if(trapcookies_auto_refresh == null)
	{
		$("#trapcookies.refresh_text").html('<em>Loading<span id="trapcookies_loadingDots"></span></em>'); 
		trapcookies_showLoadingDots();
	
		$("#trapcookies_small.refresh_text").html('<em>Loading<span id="trapcookies_small_loadingDots"></span></em>'); 
		trapcookies_small_showLoadingDots();
	}
}

function trapcookies_myajaxStop(msg)
{
	if(trapcookies_auto_refresh == null)
	{
		$("#trapcookies.refresh_text").html(msg); 
		clearInterval(trapcookies_showDots);
	
		$("#trapcookies_small.refresh_text").html(msg); 
		clearInterval(trapcookies_small_showDots);
	}
}

function trapcookies_init_small() {
	trapcookies_refresh_tile();
}

function trapcookies_init() {

	trapcookies_refresh();
	trapcookies_refresh_history();
	
	$("#tabs ul").idTabs();
				
    $("#trapcookies_auto_refresh").toggleClick(function() {
			$("#trapcookies_auto_refresh").html('<font color="lime">On</font>');
			$('#auto_time').attr('disabled', 'disabled');
			
			trapcookies_auto_refresh = setInterval(
			function ()
			{
				trapcookies_refresh();
			},
			$("#auto_time").val());
		}, function() {
			$("#trapcookies_auto_refresh").html('<font color="red">Off</font>');
			$('#auto_time').removeAttr('disabled');
				
            clearInterval(trapcookies_auto_refresh);
			trapcookies_auto_refresh = null;
		});	
}

function trapcookies_toggle(action) {	
	$.get('/components/infusions/trapcookies/includes/actions.php?trapcookies&'+action, function() { refresh_small('trapcookies','infusions'); });

	if(action == 'stop') {
		$("#trapcookies_link").html('<strong>Start</strong>');
		$("#trapcookies_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#trapcookies_link").attr("href", "javascript:trapcookies_toggle('start');");
		$('#trapcookies_output').val('trapcookies has been stopped...');	
				
		trapcookies_refresh_history();
	}
	else {
		$("#trapcookies_link").html('<strong>Stop</strong>');
		$("#trapcookies_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#trapcookies_link").attr("href", "javascript:trapcookies_toggle('stop');");
		$('#trapcookies_output').val('trapcookies is running...');
				
		trapcookies_refresh_history();
	}
}

function trapcookies_toggle_small(action) {	
	$.get('/components/infusions/trapcookies/includes/actions.php?trapcookies', {action: action});

	if(action == 'stop') {
		$("#trapcookies_link_small").html('<strong>Start</strong>');
		$("#trapcookies_status_small").html('<font color="red"><strong>disabled</strong></font>');
		$("#trapcookies_link_small").attr("href", "javascript:trapcookies_toggle_small('start');");
		$('#trapcookies_output_small').val('trapcookies has been stopped...');	
	}
	else {
		$("#trapcookies_link_small").html('<strong>Stop</strong>');
		$("#trapcookies_status_small").html('<font color="lime"><strong>enabled</strong></font>');
		$("#trapcookies_link_small").attr("href", "javascript:trapcookies_toggle_small('stop');");
		$('#trapcookies_output_small').val('trapcookies is running...');
	}
}


function trapcookies_dnsspoof_toggle(action) {
	$.get('/components/infusions/trapcookies/includes/actions.php?dnsspoof', {action: action});

	if(action == 'stop') {
		$("#dnsspoof_link").html('<strong>Start</strong>');
		$("#dnsspoof_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#dnsspoof_link").attr("href", "javascript:trapcookies_dnsspoof_toggle('start');");
		$('#trapcookies_output').val('dnsspoof has been stopped...');	
	}
	else {
		$("#dnsspoof_link").html('<strong>Stop</strong>');
		$("#dnsspoof_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#dnsspoof_link").attr("href", "javascript:trapcookies_dnsspoof_toggle('stop');");
		$('#trapcookies_output').val('dnsspoof is running...');
	}
}

function trapcookies_refresh() {
	$.ajax({
		type: "GET",
		data: "lastlog",
		beforeSend: trapcookies_myajaxStart(),
		url: "/components/infusions/trapcookies/includes/data.php",
		success: function(msg){
			$("#trapcookies_output").val(msg).scrollTop($("#trapcookies_output")[0].scrollHeight - $("#trapcookies_output").height());
			
			trapcookies_myajaxStop('');
		}
	});
}

function trapcookies_refresh_history() {
	$.ajax({
		type: "GET",
		data: "history",
		beforeSend: trapcookies_myajaxStart(),
		url: "/components/infusions/trapcookies/includes/data.php",
		success: function(msg){
			$("#content_history").html(msg);
			trapcookies_myajaxStop('');
		}
	});
}

function trapcookies_showTab() {
	$("#Output").show(); 
	$("#History").hide();
	$("#History_link").removeClass("selected"); 
	$("#Output_link").addClass("selected");
}

function trapcookies_load_file(what) {
	$.ajax({
		type: "GET",
		data: "load&file=" + what,
		beforeSend: trapcookies_myajaxStart(),
		url: "/components/infusions/trapcookies/includes/actions.php",
		success: function(msg){
			$("#trapcookies_output").val(msg);
			trapcookies_showTab();		
			trapcookies_myajaxStop('');
		}
	});
}

function trapcookies_delete_file(what, which) {
	$.ajax({
		type: "GET",
		data: "delete&file=" + which + "&" + what,
		beforeSend: trapcookies_myajaxStart(),
		url: "/components/infusions/trapcookies/includes/actions.php",
		success: function(msg){
			$("#content_history").html(msg);
			trapcookies_myajaxStop('');
			trapcookies_refresh_history();
		}
	});
}

function trapcookies_landing_toggle(action) {
	$.get('/components/infusions/trapcookies/includes/actions.php?landing', {action: action});
	
	if(action == 'install'){
		$('#landing_link').html('<strong>Uninstall</strong>');
		$('#landing_status').html('<font color="lime"><strong>installed</strong></font>');
		$('#landing_link').attr("href", "javascript:trapcookies_landing_toggle('uninstall');");
	}
	else{
		$('#landing_link').html('<strong>Install</strong>');
		$('#landing_status').html('<font color="red"><strong>not installed</strong></font>');
		$('#landing_link').attr("href", "javascript:trapcookies_landing_toggle('install');");
	}
}

function trapcookies_boot_toggle(action) {
	$.get('/components/infusions/trapcookies/includes/actions.php?boot', {action: action});
	
	if(action == 'disable') {
		$("#boot_link").html('<strong>Enable</strong>');
		$("#boot_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#boot_link").attr("href", "javascript:trapcookies_boot_toggle('enable');");
	}
	else {
		$("#boot_link").html('<strong>Disable</strong>');
		$("#boot_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#boot_link").attr("href", "javascript:trapcookies_boot_toggle('disable');");
	}
}

function trapcookies_update_conf(data, what) {
	$.ajax({
		type: "POST",
		data: "set_conf="+what+"&newdata="+data,
		beforeSend: trapcookies_myajaxStart(),
		url: "/components/infusions/trapcookies/includes/conf.php",
		success: function(msg){
			trapcookies_myajaxStop(msg);
		}
	});
}

function trapcookies_install(where) {
	$.ajax({
		type: "GET",
		data: "install&where=" + where,
		beforeSend: trapcookies_myajaxStart(),
		url: "/components/infusions/trapcookies/includes/actions.php",
		success: function(msg){
			$("#trapcookies_output").val(msg);
			trapcookies_myajaxStop('');
			
			draw_large_tile('trapcookies', 'infusions');
		}
	});
}

function trapcookies_refresh_tile() {
	$.ajax({
		type: "GET",
		data: "lastlog",
		beforeSend: trapcookies_myajaxStart(),
		url: "/components/infusions/trapcookies/includes/data.php",
		success: function(msg){
			trapcookies_myajaxStop('');
			$("#trapcookies_output_small").val(msg).scrollTop($("#trapcookies_output_small")[0].scrollHeight - $("#trapcookies_output_small").height());
		}
	});
}