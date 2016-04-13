var pineapplestats_auto_refresh;
var pineapplestats_showDots;

var pineapplestats_showLoadingDots = function() {
    clearInterval(pineapplestats_showDots);
	if (!$("#pineapplestats_loadingDots").length>0) return false;
    pineapplestats_showDots = setInterval(function(){            
        var d = $("#pineapplestats_loadingDots");
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

function pineapplestats_myajaxStart()
{
	if(pineapplestats_auto_refresh == null)
	{
		$("#pineapplestats.refresh_text").html('<em>Loading<span id="pineapplestats_loadingDots"></span></em>'); 
		pineapplestats_showLoadingDots();
	}
}

function pineapplestats_myajaxStop(msg)
{
	if(pineapplestats_auto_refresh == null)
	{
		$("#pineapplestats.refresh_text").html(msg); 
		clearInterval(pineapplestats_showDots);
	}
}

function pineapplestats_init() {
	
	pineapplestats_refresh();
	pineapplestats_refresh_config();
	
	$("#tabs ul").idTabs();
	
	$("#pineapplestats_auto_refresh").toggleClick(function() {
			$("#pineapplestats_auto_refresh").html('<font color="lime">On</font>');
			$('#pineapplestats_auto_time').attr('disabled', 'disabled');
			
			pineapplestats_auto_refresh = setInterval(
			function ()
			{
				pineapplestats_refresh();
			},
			$("#pineapplestats_auto_time").val());
		}, function() {
			$("#pineapplestats_auto_refresh").html('<font color="red">Off</font>');
			$('#pineapplestats_auto_time').removeAttr('disabled');
				
            clearInterval(pineapplestats_auto_refresh);
			pineapplestats_auto_refresh = null;
		});
}

function pineapplestats_clean() {
	$.ajax({
		type: "GET",
		data: "clean",
		beforeSend: pineapplestats_myajaxStart(),
		url: "/components/infusions/pineapplestats/includes/data.php",
		success: function(msg){
			pineapplestats_myajaxStop('');
			$("#pineapplestats_output").val(msg).scrollTop($("#pineapplestats_output")[0].scrollHeight - $("#pineapplestats_output").height());
		}
	});
}

function pineapplestats_showTab() {
	$("#Conf").show(); 
	$("#Help").hide();
	$("#Output").hide();
	$("#Help_link").removeClass("selected"); 
	$("#Output_link").removeClass("selected"); 
	$("#Configuration_link").addClass("selected");
}

function pineapplestats_refresh() {
	$.ajax({
		type: "GET",
		data: "log",
		beforeSend: pineapplestats_myajaxStart(),
		url: "/components/infusions/pineapplestats/includes/data.php",
		success: function(msg){
			pineapplestats_myajaxStop('');
			$("#pineapplestats_output").val(msg).scrollTop($("#pineapplestats_output")[0].scrollHeight - $("#pineapplestats_output").height());
		}
	});
}

function pineapplestats_getPosition() {
    if (navigator.geolocation) 
	{
		navigator.geolocation.getCurrentPosition(
		        function(position) {
					$("#pineLatitude").val(position.coords.latitude)
					$("#pineLongitude").val(position.coords.longitude)
		        },
		        function errorCallback(error) {
					switch(error.code)
					    {
					    case error.PERMISSION_DENIED:
					      alert("User denied the request for Geolocation.")
					      break;
					    case error.POSITION_UNAVAILABLE:
					      alert("Location information is unavailable.")
					      break;
					    case error.TIMEOUT:
					      alert("The request to get user location timed out.")
					      break;
					    case error.UNKNOWN_ERROR:
					      alert("An unknown error occurred.")
					      break;
					    }
		        },
		        {
					maximumAge:60000, 
					timeout:5000, 
					enableHighAccuracy:true
				}
			);
	}
    else 
	{
		alert("Geolocation is not supported by this browser.")
	}
}

function pineapplestats_testRemote(server) {
	$.ajax({
		type: "GET",
		data: "test_Remote&server="+server,
		beforeSend: pineapplestats_myajaxStart(),
		url: "/components/infusions/pineapplestats/includes/actions.php",
		success: function(msg){
			pineapplestats_myajaxStop('');
			$("#testRemote").html(msg);
		}
	});
}

function pineapplestats_addToken() {
	$.ajax({
		type: "GET",
		data: "addToken",
		beforeSend: pineapplestats_myajaxStart(),
		url: "/components/infusions/pineapplestats/includes/actions.php",
		success: function(msg){
			pineapplestats_myajaxStop('');
			$("#addToken").html(msg);
		}
	});
}

function pineapplestats_save_remote_public_key(data) {
	$.ajax({
		type: "POST",
		data: "remote_public_key=1&newdata="+data,
		beforeSend: pineapplestats_myajaxStart(),
		url: "/components/infusions/pineapplestats/includes/conf.php",
		success: function(msg){
			pineapplestats_myajaxStop(msg);
		}
	});
}

function pineapplestats_refresh_config() {
	$.ajax({
		type: "GET",
		data: "get_conf",
		beforeSend: pineapplestats_myajaxStart(),
		url: "/components/infusions/pineapplestats/includes/conf.php",
		success: function(msg){
			pineapplestats_myajaxStop('');
			$("#pineapplestats_content_conf").html(msg);
		}
	});
}

function pineapplestats_set_config() {
	$.ajax({
		type: "POST",
		data: $("#pineapplestats_form_conf").serialize(),
		beforeSend: pineapplestats_myajaxStart(),
		url: "/components/infusions/pineapplestats/includes/conf.php",
		success: function(msg){
			pineapplestats_myajaxStop(msg);
			
			$('#pineapplestats_output').val('Configuration has been saved.');
			
			pineapplestats_daemon_toggle('stop');
			pineapplestats_watchdog_toggle('disable');
			pineapplestats_reboot_toggle('disable');
			
			setTimeout(function() { pineapplestats_reload(); }, 1500);
		}
	});
}

function pineapplestats_watchdog_toggle(action) {
	$.get('/components/infusions/pineapplestats/includes/actions.php?watchdog', {action: action});
	
	if(action == 'enable'){
		$('#watchdog_link').html('<strong>Uninstall</strong>');
		$('#watchdog_status').html('<font color="lime"><strong>installed</strong></font>');
		$('#watchdog_link').attr("href", "javascript:pineapplestats_watchdog_toggle('disable');");
	}
	else{
		$('#watchdog_link').html('<strong>Install</strong>');
		$('#watchdog_status').html('<font color="red"><strong>not installed</strong></font>');
		$('#watchdog_link').attr("href", "javascript:pineapplestats_watchdog_toggle('enable');");
	}
}

function pineapplestats_reboot_toggle(action) {
	$.get('/components/infusions/pineapplestats/includes/actions.php?reboot', {action: action});
	
	if(action == 'enable'){
		$('#reboot_link').html('<strong>Uninstall</strong>');
		$('#reboot_status').html('<font color="lime"><strong>installed</strong></font>');
		$('#reboot_link').attr("href", "javascript:pineapplestats_reboot_toggle('disable');");
	}
	else{
		$('#reboot_link').html('<strong>Install</strong>');
		$('#reboot_status').html('<font color="red"><strong>not installed</strong></font>');
		$('#reboot_link').attr("href", "javascript:pineapplestats_reboot_toggle('enable');");
	}
}

function pineapplestats_boot_toggle(action) {
	$.get('/components/infusions/pineapplestats/includes/actions.php?boot', {action: action});

	if(action == 'disable') {
		$("#boot_link").html('<strong>Enable</strong>');
		$("#boot_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#boot_link").attr("href", "javascript:pineapplestats_boot_toggle('enable');");
	}
	else {
		$("#boot_link").html('<strong>Disable</strong>');
		$("#boot_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#boot_link").attr("href", "javascript:pineapplestats_boot_toggle('disable');");
	}
}

function pineapplestats_autossh_toggle(action) {
	$.get('/components/infusions/pineapplestats/includes/actions.php?autossh', {action: action});

	if(action == 'stop') {
		$("#autossh_link").html('<strong>Connect</strong>');
		$("#autossh_status").html('<font color="red"><strong>disconnected</strong></font>');
		$("#autossh_link").attr("href", "javascript:pineapplestats_autossh_toggle('start');");
	}
	else {
		$("#autossh_link").html('<strong>Disconnect</strong>');
		$("#autossh_status").html('<font color="lime"><strong>connected</strong></font>');
		$("#autossh_link").attr("href", "javascript:pineapplestats_autossh_toggle('stop');");
	}
}

function pineapplestats_daemon_toggle(action) {
	$.get('/components/infusions/pineapplestats/includes/actions.php?daemon', {action: action});

	if(action == 'stop') {
		$("#daemon_link").html('<strong>Start</strong>');
		$("#daemon_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#daemon_link").attr("href", "javascript:pineapplestats_daemon_toggle('start');");
		$('#pineapplestats_output').val('daemon has been stopped...');	
	}
	else {
		$("#daemon_link").html('<strong>Stop</strong>');
		$("#daemon_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#daemon_link").attr("href", "javascript:pineapplestats_daemon_toggle('stop');");
		$('#pineapplestats_output').val('daemon is running...');
	}
}

function pineapplestats_reload() {
	draw_large_tile('pineapplestats', 'infusions');
}

function pineapplestats_install() {
	$.ajax({
		type: "GET",
		data: "install_dep",
		beforeSend: pineapplestats_myajaxStart(),
		url: "/components/infusions/pineapplestats/includes/actions.php",
		cache: false,
		success: function(msg){
		}
	});

    var loop=self.setInterval(
	function ()
	{
	    $.ajax({
			url: '/components/infusions/pineapplestats/includes/status.php',
			cache: false,
			success: function(msg){
				if(msg != 'working')
				{
					pineapplestats_reload();
					clearInterval(loop);
				}
			}
		});
	}
	,5000);
}