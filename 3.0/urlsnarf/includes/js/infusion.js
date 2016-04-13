var urlsnarf_auto_refresh;
var urlsnarf_showDots;
var urlsnarf_small_showDots;

var urlsnarf_showLoadingDots = function() {
    clearInterval(urlsnarf_showDots);
	if (!$("#urlsnarf_loadingDots").length>0) return false;
    urlsnarf_showDots = setInterval(function(){            
        var d = $("#urlsnarf_loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

var urlsnarf_small_showLoadingDots = function() {
    clearInterval(urlsnarf_small_showDots);
	if (!$("#urlsnarf_small_loadingDots").length>0) return false;
    urlsnarf_small_showDots = setInterval(function(){            
        var d = $("#urlsnarf_small_loadingDots");
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

function urlsnarf_myajaxStart()
{
	if(urlsnarf_auto_refresh == null)
	{
		$("#urlsnarf.refresh_text").html('<em>Loading<span id="urlsnarf_loadingDots"></span></em>'); 
		urlsnarf_showLoadingDots();
	
		$("#urlsnarf_small.refresh_text").html('<em>Loading<span id="urlsnarf_small_loadingDots"></span></em>'); 
		urlsnarf_small_showLoadingDots();
	}
}

function urlsnarf_myajaxStop(msg)
{
	if(urlsnarf_auto_refresh == null)
	{
		$("#urlsnarf.refresh_text").html(msg); 
		clearInterval(urlsnarf_showDots);
	
		$("#urlsnarf_small.refresh_text").html(msg); 
		clearInterval(urlsnarf_small_showDots);
	}
}

function urlsnarf_init_small() {
	
	urlsnarf_refresh_tile();
}

function urlsnarf_init() {

	urlsnarf_refresh();
	urlsnarf_refresh_history();
	urlsnarf_refresh_custom();
	urlsnarf_refresh_config();
	
	$("#tabs ul").idTabs();
				
    $("#urlsnarf_auto_refresh").toggleClick(function() {
			$("#urlsnarf_auto_refresh").html('<font color="lime">On</font>');
			$('#auto_time').attr('disabled', 'disabled');
			
			urlsnarf_auto_refresh = setInterval(
			function ()
			{
				urlsnarf_refresh();
			},
			$("#auto_time").val());
		}, function() {
			$("#urlsnarf_auto_refresh").html('<font color="red">Off</font>');
			$('#auto_time').removeAttr('disabled');
				
            clearInterval(urlsnarf_auto_refresh);
			urlsnarf_auto_refresh = null;
		});	
}

function urlsnarf_toggle(action) {
	$.get('/components/infusions/urlsnarf/includes/actions.php?urlsnarf&'+action, {int: $("#interface").val()} , function() { refresh_small('urlsnarf','infusions'); });

	if(action == 'stop') {
		$("#urlsnarf_link").html('<strong>Start</strong>');
		$("#urlsnarf_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#urlsnarf_link").attr("href", "javascript:urlsnarf_toggle('start');");
		$('#urlsnarf_output').val('urlsnarf has been stopped...');
		
		$('#interface').removeAttr('disabled');	
				
		urlsnarf_refresh_history();
	}
	else {
		$("#urlsnarf_link").html('<strong>Stop</strong>');
		$("#urlsnarf_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#urlsnarf_link").attr("href", "javascript:urlsnarf_toggle('stop');");
		$('#urlsnarf_output').val('urlsnarf is running...');
		
		$('#interface').attr('disabled', 'disabled');
				
		urlsnarf_refresh_history();
	}
}

function urlsnarf_toggle_small(action) {
	$.get('/components/infusions/urlsnarf/includes/actions.php?urlsnarf&'+action, {int: $("#interface").val()});

	if(action == 'stop') {
		$("#urlsnarf_link_small").html('<strong>Start</strong>');
		$("#urlsnarf_small").html('<font color="red"><strong>disabled</strong></font>');
		$("#urlsnarf_link_small").attr("href", "javascript:urlsnarf_toggle_small('start');");
		$('#urlsnarf_output_small').val('urlsnarf has been stopped...');
		
		$('#urlsnarf_interface_small').removeAttr('disabled');
	}
	else {
		$("#urlsnarf_link_small").html('<strong>Stop</strong>');
		$("#urlsnarf_small").html('<font color="lime"><strong>enabled</strong></font>');
		$("#urlsnarf_link_small").attr("href", "javascript:urlsnarf_toggle_small('stop');");
		$('#urlsnarf_output_small').val('urlsnarf is running...');
		
		$('#urlsnarf_interface_small').attr('disabled', 'disabled');
	}
}

function urlsnarf_refresh() {
	$.ajax({
		type: "GET",
		data: "lastlog&filter="+$("#filter").val(),
		beforeSend: urlsnarf_myajaxStart(),
		url: "/components/infusions/urlsnarf/includes/data.php",
		success: function(msg){
			$("#urlsnarf_output").val(msg).scrollTop($("#urlsnarf_output")[0].scrollHeight - $("#urlsnarf_output").height());
			
			urlsnarf_myajaxStop('');
		}
	});
}

function urlsnarf_refresh_history() {
	$.ajax({
		type: "GET",
		data: "history",
		beforeSend: urlsnarf_myajaxStart(),
		url: "/components/infusions/urlsnarf/includes/data.php",
		success: function(msg){
			$("#content_history").html(msg);
			urlsnarf_myajaxStop('');
		}
	});
}

function urlsnarf_refresh_custom() {
	$.ajax({
		type: "GET",
		data: "custom",
		beforeSend: urlsnarf_myajaxStart(),
		url: "/components/infusions/urlsnarf/includes/data.php",
		success: function(msg){
			$("#content_custom").html(msg);
			urlsnarf_myajaxStop('');
		}
	});
}

function urlsnarf_showTab()
{
	$("#Output").show(); 
	$("#History").hide();
	$("#History_link").removeClass("selected"); 
	$("#Custom").hide();
	$("#Custom_link").removeClass("selected"); 
	$("#Output_link").addClass("selected");
}

function urlsnarf_load_file(what, which) {
	$.ajax({
		type: "GET",
		data: "load&file=" + which + "&" + what,
		beforeSend: urlsnarf_myajaxStart(),
		url: "/components/infusions/urlsnarf/includes/actions.php",
		success: function(msg){
			$("#urlsnarf_output").val(msg);
			urlsnarf_showTab();		
			urlsnarf_myajaxStop('');
		}
	});
}

function urlsnarf_delete_file(what, which) {
	$.ajax({
		type: "GET",
		data: "delete&file=" + which + "&" + what,
		beforeSend: urlsnarf_myajaxStart(),
		url: "/components/infusions/urlsnarf/includes/actions.php",
		success: function(msg){
			$("#content_history").html(msg);
			urlsnarf_myajaxStop('');
			urlsnarf_refresh_history();
			urlsnarf_refresh_custom();
		}
	});
}

function urlsnarf_boot_toggle(action) {
	$.get('/components/infusions/urlsnarf/includes/actions.php?boot', {action: action});
	
	if(action == 'disable') {
		$("#boot_link").html('<strong>Enable</strong>');
		$("#boot_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#boot_link").attr("href", "javascript:urlsnarf_boot_toggle('enable');");
	}
	else {
		$("#boot_link").html('<strong>Disable</strong>');
		$("#boot_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#boot_link").attr("href", "javascript:urlsnarf_boot_toggle('disable');");
	}
}

function urlsnarf_execute_custom_script(cmd) {
	$.ajax({
		type: "GET",
		data: "execute&cmd="+cmd,
		beforeSend: urlsnarf_myajaxStart(),
		url: "/components/infusions/urlsnarf/includes/actions.php",
		success: function(msg){
			$("#urlsnarf_output").val(msg);
			$('#urlsnarf_output').val('Custom script is running...');
			
			urlsnarf_myajaxStop('');
			
			urlsnarf_refresh_history();
			urlsnarf_refresh_custom();
		}
	});
}

function urlsnarf_refresh_config() {
	$.ajax({
		type: "GET",
		data: "get_conf",
		beforeSend: urlsnarf_myajaxStart(),
		url: "/components/infusions/urlsnarf/includes/conf.php",
		success: function(msg){
			$("#content_conf").html(msg);
			urlsnarf_myajaxStop('');
		}
	});
}

function urlsnarf_set_config() {
	$.ajax({
		type: "POST",
		data: "set_conf=1&commands="+$.base64.encode($("#command_File").val()),
		beforeSend: urlsnarf_myajaxStart(),
		url: "/components/infusions/urlsnarf/includes/conf.php",
		success: function(msg){
			urlsnarf_myajaxStop(msg);
		}
	});
}

function urlsnarf_refresh_tile() {
	$.ajax({
		type: "GET",
		data: "lastlog",
		beforeSend: urlsnarf_myajaxStart(),
		url: "/components/infusions/urlsnarf/includes/data.php",
		success: function(msg){
			urlsnarf_myajaxStop('');
			$("#urlsnarf_output_small").val(msg).scrollTop($("#urlsnarf_output_small")[0].scrollHeight - $("#urlsnarf_output_small").height());
		}
	});
}