var logcheck_auto_refresh;
var logcheck_showDots;
var logcheck_small_showDots;

var logcheck_showLoadingDots = function() {
    clearInterval(logcheck_showDots);
	if (!$("#logcheck_loadingDots").length>0) return false;
    logcheck_showDots = setInterval(function(){            
        var d = $("#logcheck_loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

var logcheck_small_showLoadingDots = function() {
    clearInterval(logcheck_small_showDots);
	if (!$("#logcheck_small_loadingDots").length>0) return false;
    logcheck_small_showDots = setInterval(function(){            
        var d = $("#logcheck_small_loadingDots");
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

function logcheck_myajaxStart()
{
	if(logcheck_auto_refresh == null)
	{
		$("#logcheck.refresh_text").html('<em>Loading<span id="logcheck_loadingDots"></span></em>'); 
		logcheck_showLoadingDots();
	
		$("#logcheck_small.refresh_text").html('<em>Loading<span id="logcheck_small_loadingDots"></span></em>'); 
		logcheck_small_showLoadingDots();
	}
}

function logcheck_myajaxStop(msg)
{
	if(logcheck_auto_refresh == null)
	{
		$("#logcheck.refresh_text").html(msg); 
		clearInterval(logcheck_showDots);
	
		$("#logcheck_small.refresh_text").html(msg); 
		clearInterval(logcheck_small_showDots);
	}
}

function logcheck_init_small() {
	
	logcheck_refresh_tile();
}

function logcheck_init() {
	
	logcheck_refresh();
	
	$("#tabs ul").idTabs();
	
	$("#logcheck_auto_refresh").toggleClick(function() {
			$("#logcheck_auto_refresh").html('<font color="lime">On</font>');
			$('#auto_time').attr('disabled', 'disabled');
			
			logcheck_auto_refresh = setInterval(
			function ()
			{
				logcheck_refresh();
			},
			$("#auto_time").val());
		}, function() {
			$("#logcheck_auto_refresh").html('<font color="red">Off</font>');
			$('#auto_time').removeAttr('disabled');
							
            clearInterval(logcheck_auto_refresh);
			logcheck_auto_refresh = null;
	});
}

function logcheck_refresh() {
	$.ajax({
		type: "POST",
		beforeSend: logcheck_myajaxStart(),
		url: "/components/infusions/logcheck/includes/data.php",
		success: function(msg){
			$("#logcheck_output").val(msg).scrollTop($("#logcheck_output")[0].scrollHeight - $("#logcheck_output").height());
			
			logcheck_myajaxStop('');
		}
	});
}

function logcheck_test_email() {
	$.ajax({
		type: "GET",
		data: "test_email",
		beforeSend: logcheck_myajaxStart(),
		url: "/components/infusions/logcheck/includes/actions.php",
		success: function(msg){
			logcheck_myajaxStop(msg);
		}
	});
}

function logcheck_update_conf(data, what) {
	$.ajax({
		type: "POST",
		data: "set_conf="+what+"&newdata="+data,
		beforeSend: logcheck_myajaxStart(),
		url: "/components/infusions/logcheck/includes/conf.php",
		success: function(msg){
			logcheck_myajaxStop(msg);
		}
	});
}

function logcheck_update_settings() {
	$.ajax({
		type: "POST",
		data: $("#logcheck_form_conf").serialize(),
		beforeSend: logcheck_myajaxStart(),
		url: "/components/infusions/logcheck/includes/conf.php",
		success: function(msg){
			logcheck_myajaxStop(msg);
			
			$('#logcheck_output').val('Configuration has been saved.');
		}
	});
}

function logcheck_toggle(action) {
	$.get('/components/infusions/logcheck/includes/actions.php?logcheck', {action: action}, function() { refresh_small('logcheck','infusions'); });
	
	if(action == 'stop') {
		$("#logcheck_link").html('<strong>Start</strong>');
		$("#logcheck_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#logcheck_link").attr("href", "javascript:logcheck_toggle('start');");
	}
	else {
		$("#logcheck_link").html('<strong>Stop</strong>');
		$("#logcheck_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#logcheck_link").attr("href", "javascript:logcheck_toggle('stop');");
	}
}

function logcheck_toggle_small(action) {
	$.get('/components/infusions/logcheck/includes/actions.php?logcheck', {action: action});
	
	if(action == 'stop') {
		$("#logcheck_link_small").html('<strong>Start</strong>');
		$("#logcheck_status_small").html('<font color="red"><strong>disabled</strong></font>');
		$("#logcheck_link_small").attr("href", "javascript:logcheck_toggle_small('start');");
	}
	else {
		$("#logcheck_link_small").html('<strong>Stop</strong>');
		$("#logcheck_status_small").html('<font color="lime"><strong>enabled</strong></font>');
		$("#logcheck_link_small").attr("href", "javascript:logcheck_toggle_small('stop');");
	}
}

function logcheck_boot_toggle(action) {
	$.get('/components/infusions/logcheck/includes/actions.php?boot', {action: action});
	
	if(action == 'disable') {
		$("#boot_link").html('<strong>Enable</strong>');
		$("#boot_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#boot_link").attr("href", "javascript:logcheck_boot_toggle('enable');");
	}
	else {
		$("#boot_link").html('<strong>Disable</strong>');
		$("#boot_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#boot_link").attr("href", "javascript:logcheck_boot_toggle('disable');");
	}
}

function logcheck_daemon_toggle(action) {
	$.get('/components/infusions/logcheck/includes/actions.php?daemon', {action: action});
	
	if(action == 'enable'){
		$('#cron_link').html('<strong>Uninstall</strong>');
		$('#cron_status').html('<font color="lime"><strong>installed</strong></font>');
		$('#cron_link').attr("href", "javascript:logcheck_daemon_toggle('disable');");
	}
	else{
		$('#cron_link').html('<strong>Install</strong>');
		$('#cron_status').html('<font color="red"><strong>not installed</strong></font>');
		$('#cron_link').attr("href", "javascript:logcheck_daemon_toggle('enable');");
	}
}

function logcheck_install(where) {
	$.ajax({
		type: "GET",
		data: "install&where=" + where,
		beforeSend: logcheck_myajaxStart(),
		url: "/components/infusions/logcheck/includes/actions.php",
		success: function(msg){
			$("#logcheck_output").val(msg);
			
			logcheck_myajaxStop('');
			
			draw_large_tile('logcheck', 'infusions');
			
		}
	});
}

function logcheck_refresh_tile() {
	$.ajax({
		type: "GET",
		beforeSend: logcheck_myajaxStart(),
		url: "/components/infusions/logcheck/includes/data.php",
		success: function(msg){
			logcheck_myajaxStop('');
			$("#logcheck_output_small").val(msg).scrollTop($("#logcheck_output_small")[0].scrollHeight - $("#logcheck_output_small").height());
		}
	});
}