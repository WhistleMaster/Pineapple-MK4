var jammer_auto_refresh;
var jammer_showDots;
var jammer_small_showDots;

var jammer_showLoadingDots = function() {
    clearInterval(jammer_showDots);
	if (!$("#jammer_loadingDots").length>0) return false;
    jammer_showDots = setInterval(function(){            
        var d = $("#jammer_loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

var jammer_small_showLoadingDots = function() {
    clearInterval(jammer_small_showDots);
	if (!$("#jammer_small_loadingDots").length>0) return false;
    jammer_small_showDots = setInterval(function(){            
        var d = $("#jammer_small_loadingDots");
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

function jammer_myajaxStart()
{
	if(jammer_auto_refresh == null)
	{
		$("#jammer.refresh_text").html('<em>Loading<span id="jammer_loadingDots"></span></em>'); 
		jammer_showLoadingDots();
	
		$("#jammer_small.refresh_text").html('<em>Loading<span id="jammer_small_loadingDots"></span></em>'); 
		jammer_small_showLoadingDots();
	}
}

function jammer_myajaxStop(msg)
{
	if(jammer_auto_refresh == null)
	{
		$("#jammer.refresh_text").html(msg); 
		clearInterval(jammer_showDots);
	
		$("#jammer_small.refresh_text").html(msg); 
		clearInterval(jammer_small_showDots);
	}
}

function jammer_init_small() {
	
	jammer_refresh_tile();
	
}

function jammer_init() {
	
	jammer_refresh();
	jammer_refresh_available_ap('whitelist');
	jammer_refresh_available_ap('blacklist');
	
	jammer_refresh_config();
	
	$("#tabs ul").idTabs();
	
	$("#jammer_auto_refresh").toggleClick(function() {
			$("#jammer_auto_refresh").html('<font color="lime">On</font>');
			$('#auto_time').attr('disabled', 'disabled');
			
			jammer_auto_refresh = setInterval(
			function ()
			{
				jammer_refresh();
			},
			$("#auto_time").val());
		}, function() {
			$("#jammer_auto_refresh").html('<font color="red">Off</font>');
			$('#auto_time').removeAttr('disabled');
							
            clearInterval(jammer_auto_refresh);
			jammer_auto_refresh = null;
	});
}

function jammer_append(what, which) {
	if($('#'+which).val() != "")
		$('#'+which).val($('#'+which).val() + '\n' + what);
	else
		$('#'+which).val(what);
}

function jammer_refresh() {
	$.ajax({
		type: "GET",
		data: "log",
		beforeSend: jammer_myajaxStart(),
		url: "/components/infusions/jammer/includes/data.php",
		success: function(msg){
			$("#jammer_output").val(msg).scrollTop($("#jammer_output")[0].scrollHeight - $("#jammer_output").height());
			
			jammer_myajaxStop('');
		}
	});
}

function jammer_refresh_available_ap(which) {
	$.ajax({
		type: "GET",
		data: "available_ap&mon="+$("#monitorInterfaces").val()+"&int="+$("#interfaces").val(),
		beforeSend: jammer_myajaxStart(),
		url: "/components/infusions/jammer/includes/data.php",
		success: function(msg){
			$("#list_"+which).html(msg);
			jammer_myajaxStop('');
			$('#list_' + which + ' li').click(function() { 
				var append_value = '# ' + $(this).attr("name") + '\n' + $(this).attr("address");
				jammer_append(append_value,which);
				return false;
			});
		}
	});
}

function jammer_update_conf(data, what) {
	$.ajax({
		type: "POST",
		data: "set_conf="+what+"&newdata="+data,
		beforeSend: jammer_myajaxStart(),
		url: "/components/infusions/jammer/includes/conf.php",
		success: function(msg){
			jammer_myajaxStop(msg);
		}
	});
}

function jammer_toggle(action) {	
	$.get('/components/infusions/jammer/includes/actions.php?jammer&'+action, {int: $("#interfaces").val(), mon: $("#monitorInterfaces").val()}, function() {
		refresh_small('jammer','infusions');
	});
	
	if(action == 'stop') {
		$("#jammer_link").html('<strong>Start</strong>');
		$("#jammer_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#jammer_link").attr("href", "javascript:jammer_toggle('start');");
		$('#jammer_output').val("Stopping WiFi Jammer...");
	}
	else {
		$("#jammer_link").html('<strong>Stop</strong>');
		$("#jammer_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#jammer_link").attr("href", "javascript:jammer_toggle('stop');");
		$('#jammer_output').val("Starting WiFi Jammer...");
	}
}

function jammer_toggle_small(action) {	
	$.get('/components/infusions/jammer/includes/actions.php?jammer&'+action, {int: $("#jammer_interfaces_small").val(), mon: $("#jammer_monitorInterfaces_small").val()});
	
	if(action == 'stop') {
		$("#jammer_link_small").html('<strong>Start</strong>');
		$("#jammer_small").html('<font color="red"><strong>disabled</strong></font>');
		$("#jammer_link_small").attr("href", "javascript:jammer_toggle_small('start');");
		$('#jammer_output_small').val("Stopping WiFi Jammer...");
	}
	else {
		$("#jammer_link_small").html('<strong>Stop</strong>');
		$("#jammer_small").html('<font color="lime"><strong>enabled</strong></font>');
		$("#jammer_link_small").attr("href", "javascript:jammer_toggle_small('stop');");
		$('#jammer_output_small').val("Starting WiFi Jammer...");
	}
}

function jammer_boot_toggle(action) {
	$.get('/components/infusions/jammer/includes/actions.php?boot', {action: action});
	
	if(action == 'disable') {
		$("#boot_link").html('<strong>Enable</strong>');
		$("#boot_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#boot_link").attr("href", "javascript:jammer_boot_toggle('enable');");
	}
	else {
		$("#boot_link").html('<strong>Disable</strong>');
		$("#boot_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#boot_link").attr("href", "javascript:jammer_boot_toggle('disable');");
	}
}

function jammer_monitor_toggle(action) {
	$.ajax({
		type: "GET",
		data: "monitor&"+action+"&int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val(),
		beforeSend: jammer_myajaxStart(),
		url: "/components/infusions/jammer/includes/actions.php",
		success: function(msg){
			jammer_myajaxStop(msg);
			
			if(action == "stop")
				$('#jammer_output').val(action+" monitor "+$("#monitorInterfaces").val()+"...");
			else
				$('#jammer_output').val(action+" monitor on "+$("#interfaces").val()+"...");
			jammer_refresh_monitors();
		}
	});
}

function jammer_interface_toggle(action) {
	$.ajax({
		type: "GET",
		data: "interface&"+action+"&int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val(),
		beforeSend: jammer_myajaxStart(),
		url: "/components/infusions/jammer/includes/actions.php",
		success: function(msg){
			jammer_myajaxStop(msg);
			
			$('#jammer_output').val(action+" "+$("#interfaces").val()+"...");
			jammer_refresh_interfaces();
		}
	});
}

function jammer_auto_toggle() {
	$.ajax({
		type: "GET",
		data: "auto&int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val(),
		beforeSend: jammer_myajaxStart(),
		url: "/components/infusions/jammer/includes/actions.php",
		success: function(msg){
			jammer_myajaxStop(msg);
			
			$('#jammer_output').val("toggle "+$("#interfaces").val()+"...");
		}
	});
}

function jammer_refresh_interfaces() {
	$('#interfaces_l').load('/components/infusions/jammer/includes/interfaces.php?interface');
}

function jammer_refresh_monitors() {
	$('#monitorInterface_l').load('/components/infusions/jammer/includes/interfaces.php?monitor');
}

function jammer_refresh_config() {
	$.ajax({
		type: "GET",
		data: "get_conf",
		beforeSend: jammer_myajaxStart(),
		url: "/components/infusions/jammer/includes/conf.php",
		success: function(msg){
			jammer_myajaxStop('');
			
			$("#jammer_content_conf").html(msg);
		}
	});
}

function jammer_set_config() {
	$.ajax({
		type: "POST",
		data: $("#jammer_form_conf").serialize(),
		beforeSend: jammer_myajaxStart(),
		url: "/components/infusions/jammer/includes/conf.php",
		success: function(msg){
			jammer_myajaxStop(msg);
			
			$('#jammer_output').val('Configuration has been saved.');
		}
	});
}

function jammer_refresh_tile() {
	$.ajax({
		type: "GET",
		data: "log",
		beforeSend: jammer_myajaxStart(),
		url: "/components/infusions/jammer/includes/data.php",
		success: function(msg){
			jammer_myajaxStop('');
			$("#jammer_output_small").val(msg).scrollTop($("#jammer_output_small")[0].scrollHeight - $("#jammer_output_small").height());
		}
	});
}