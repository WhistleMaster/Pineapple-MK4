var auto_refresh;
var showDots;

var showLoadingDots = function() {
    clearInterval(showDots);

	if (!$("#loadingDots").length>0) return false;
    showDots = setInterval(function(){            
        var d = $("#loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

function init() {
	
	refresh();
	refresh_config();
	
	$("#tabs ul").idTabs();
	
	$("#auto_refresh").toggle(function() {
			$("#auto_refresh").html('<font color="lime">On</font>');
			$('#auto_time').attr('disabled', 'disabled');
			
			auto_refresh = setInterval(
			function ()
			{
				refresh();
			},
			$("#auto_time").val());
		}, function() {
			$("#auto_refresh").html('<font color="red">Off</font>');
			$('#auto_time').removeAttr('disabled');
				
            clearInterval(auto_refresh);
			auto_refresh = null;
		});
}

function showTab()
{
	$("#Conf").show(); 
	
	$("#Output").hide();
	$("#Output_link").removeClass("selected"); 
	$("#Help").hide();
	$("#Help_link").removeClass("selected"); 
	
	$("#Configuration_link").addClass("selected");
}

function clean() {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}

	$.ajax({
		type: "GET",
		data: "clean",
		url: "karmastats_data.php",
		success: function(msg){
			$("#refresh_text").html(''); clearInterval(showDots);
			$("#output").val(msg).scrollTop($("#output")[0].scrollHeight - $("#output").height());
		}
	});
}


function refresh() {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}

	$.ajax({
		type: "GET",
		data: "log",
		url: "karmastats_data.php",
		success: function(msg){
			$("#refresh_text").html(''); clearInterval(showDots);
			$("#output").val(msg).scrollTop($("#output")[0].scrollHeight - $("#output").height());
		}
	});
}

function getPosition() {
    if (navigator.geolocation) 
	{
		navigator.geolocation.getCurrentPosition(showPosition);
	}
    else 
	{
		alert("Geolocation is not supported by this browser.")
	}
}

function showPosition(position) {
	$("#pineLatitude").val(position.coords.latitude)
	$("#pineLongitude").val(position.coords.longitude)
}

function testRemote(server) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "test_Remote&server="+server,
		url: "karmastats_actions.php",
		success: function(msg){
			$("#refresh_text").html(''); clearInterval(showDots);
			$("#testRemote").html(msg);
		}
	});
	
}

function refresh_config() {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "get_conf",
		url: "karmastats_conf.php",
		success: function(msg){
			$("#content_conf").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function set_config() {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "POST",
		data: $("#form_conf").serialize(),
		url: "karmastats_conf.php",
		success: function(msg){
			$("#refresh_text").html('<font color="lime"><b>saved</b></font>'); clearInterval(showDots);
			$('#output').val('Configuration has been saved.');
			
			daemon_toggle('disable');
			watchdog_toggle('disable');
			
			setTimeout(function() { reload(); }, 1500);
		}
	});
}

function watchdog_toggle(action) {
	$('#output').load('karmastats_actions.php?watchdog&action='+action);
	if(action == 'enable'){
		$('#watchdog_link').html('<strong>Uninstall</strong>');
		$('#watchdog_status').html('<font color="lime"><strong>installed</strong></font>');
		$('#watchdog_link').attr("href", "javascript:watchdog_toggle('disable');");
	}
	else{
		$('#watchdog_link').html('<strong>Install</strong>');
		$('#watchdog_status').html('<font color="red"><strong>not installed</strong></font>');
		$('#watchdog_link').attr("href", "javascript:watchdog_toggle('enable');");
	}
}

function boot_toggle(action) {
	$('#output').load('karmastats_actions.php?boot&action='+action);
	if(action == 'disable') {
		$("#boot_link").html('<strong>Enable</strong>');
		$("#boot_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#boot_link").attr("href", "javascript:boot_toggle('enable');");
	}
	else {
		$("#boot_link").html('<strong>Disable</strong>');
		$("#boot_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#boot_link").attr("href", "javascript:boot_toggle('disable');");
	}
}

function daemon_toggle(action) {
	$('#output').load('karmastats_actions.php?daemon&'+action);

	if(action == 'stop') {
		$("#daemon_link").html('<strong>Start</strong>');
		$("#daemon_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#daemon_link").attr("href", "javascript:daemon_toggle('start');");
		$('#output').val('daemon has been stopped...');	
	}
	else {
		$("#daemon_link").html('<strong>Stop</strong>');
		$("#daemon_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#daemon_link").attr("href", "javascript:daemon_toggle('stop');");
		$('#output').val('daemon is running...');
	}
}

function reload() {
	location.reload(true);
}

function install() {
	$("#refresh_text").html('<em>Installing<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "install_dep",
		url: "karmastats_actions.php",
		cache: false,
		success: function(msg){
		}
	});

    var loop=self.setInterval(
	function ()
	{
	    $.ajax({
			url: 'status.php',
			cache: false,
			success: function(msg){
				if(msg != 'working')
				{
					reload();
				}
			}
		});
	}
	,5000);
}