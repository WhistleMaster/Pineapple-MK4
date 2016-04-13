var sitesurvey_auto_refresh;
var sitesurvey_small_auto_refresh;
var sitesurvey_showDots;
var sitesurvey_small_showDots;

var sitesurvey_showLoadingDots = function() {
    clearInterval(sitesurvey_showDots);
	if (!$("#sitesurvey_loadingDots").length>0) return false;
    sitesurvey_showDots = setInterval(function(){            
        var d = $("#sitesurvey_loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

var sitesurvey_small_showLoadingDots = function() {
    clearInterval(sitesurvey_small_showDots);
	if (!$("#sitesurvey_small_loadingDots").length>0) return false;
    sitesurvey_small_showDots = setInterval(function(){            
        var d = $("#sitesurvey_small_loadingDots");
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

function sitesurvey_myajaxStart()
{
	if(sitesurvey_auto_refresh == null && sitesurvey_small_auto_refresh == null)
	{
		$("#sitesurvey.refresh_text").html('<em>Loading<span id="sitesurvey_loadingDots"></span></em>'); 
		sitesurvey_showLoadingDots();
	
		$("#sitesurvey_small.refresh_text").html('<em>Loading<span id="sitesurvey_small_loadingDots"></span></em>'); 
		sitesurvey_small_showLoadingDots();
	}
}

function sitesurvey_myajaxStop(msg)
{
	if(sitesurvey_auto_refresh == null && sitesurvey_small_auto_refresh == null)
	{
		$("#sitesurvey.refresh_text").html(msg); 
		clearInterval(sitesurvey_showDots);
	
		$("#sitesurvey_small.refresh_text").html(msg); 
		clearInterval(sitesurvey_small_showDots);
	}
}

function sitesurvey_init_small() {
	sitesurvey_refresh_available_ap();
	
	$("#sitesurvey_small_auto_refresh").toggleClick(function() {
			$("#sitesurvey_small_auto_refresh").html('<font color="lime">On</font>');
			$('#sitesurvey_small_auto_time').attr('disabled', 'disabled');
								
			sitesurvey_small_auto_refresh = setInterval(
			function ()
			{
				sitesurvey_refresh_available_ap();
			},
			$("#sitesurvey_small_auto_time").val());
		}, function() {
			$("#sitesurvey_small_auto_refresh").html('<font color="red">Off</font>');
			$('#sitesurvey_small_auto_time').removeAttr('disabled');
									
            clearInterval(sitesurvey_small_auto_refresh);
			sitesurvey_small_auto_refresh = null;
	});
}

function sitesurvey_init() {
	
	sitesurvey_refresh(0);
	sitesurvey_refresh_history();
	sitesurvey_refresh_captures();
	sitesurvey_refresh_config();
	
	$("#tabs2 ul").idTabs();

	$("#sitesurvey_auto_refresh").toggleClick(function() {
			$("#sitesurvey_auto_refresh").html('<font color="lime">On</font>');
			$('#sitesurvey_auto_time').attr('disabled', 'disabled');
			$('#sitesurvey_auto_what').attr('disabled', 'disabled');
						
			if($("#sitesurvey_auto_what").val() == 1)
				$('#sitesurvey_output').load('/components/infusions/sitesurvey/includes/actions.php?background_refresh=start&int='+$("#interfaces").val()+'&mon='+$("#monitorInterfaces").val());
			
			sitesurvey_auto_refresh = setInterval(
			function ()
			{
				sitesurvey_refresh(0);
			},
			$("#sitesurvey_auto_time").val());
		}, function() {
			$("#sitesurvey_auto_refresh").html('<font color="red">Off</font>');
			$('#sitesurvey_auto_time').removeAttr('disabled');
			$('#sitesurvey_auto_what').removeAttr('disabled');
			
			if($("#sitesurvey_auto_what").val() == 1)		
				$('#sitesurvey_output').load('/components/infusions/sitesurvey/includes/actions.php?background_refresh=stop');
							
            clearInterval(sitesurvey_auto_refresh);
			sitesurvey_auto_refresh = null;
	});
}

function sitesurvey_monitor_toggle(action) {
	$.ajax({
		type: "GET",
		data: "monitor&"+action+"&int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val(),
		beforeSend: sitesurvey_myajaxStart(),
		url: "/components/infusions/sitesurvey/includes/actions.php",
		success: function(msg){
			sitesurvey_myajaxStop(msg);
			
			if(action == "stop")
				$('#sitesurvey_output').val(action+" monitor "+$("#monitorInterfaces").val()+"...");
			else
				$('#sitesurvey_output').val(action+" monitor on "+$("#interfaces").val()+"...");
			sitesurvey_refresh_monitors();
		}
	});
}

function sitesurvey_interface_toggle(action) {
	$.ajax({
		type: "GET",
		data: "interface&"+action+"&int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val(),
		beforeSend: sitesurvey_myajaxStart(),
		url: "/components/infusions/sitesurvey/includes/actions.php",
		success: function(msg){
			sitesurvey_myajaxStop(msg);
			
			$('#sitesurvey_output').val(action+" "+$("#interfaces").val()+"...");
			sitesurvey_refresh_interfaces();
		}
	});
}

function sitesurvey_auto_toggle() {
	$.ajax({
		type: "GET",
		data: "auto&int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val(),
		beforeSend: sitesurvey_myajaxStart(),
		url: "/components/infusions/sitesurvey/includes/actions.php",
		success: function(msg){
			sitesurvey_myajaxStop(msg);
			
			$('#sitesurvey_output').val("toggle "+$("#interfaces").val()+"...");
		}
	});
}

function sitesurvey_refresh_interfaces() {
	$('#interfaces_l').load('/components/infusions/sitesurvey/includes/interfaces.php?interface');
}

function sitesurvey_refresh_monitors() {
	$('#monitorInterface_l').load('/components/infusions/sitesurvey/includes/interfaces.php?monitor');
}

function sitesurvey_refresh(clients) {
	$.ajax({
		type: "POST",
		data: "mon="+$("#monitorInterfaces").val()+"&int="+$("#interfaces").val()+"&clients="+clients,
		beforeSend: sitesurvey_myajaxStart(),
		url: "/components/infusions/sitesurvey/includes/data.php",
		success: function(msg){
			$("#content").html(msg);
			sitesurvey_myajaxStop('');
		}
	});
}

function sitesurvey_refresh_captures() {	
	$.ajax({
		type: "GET",
		data: "captures",
		beforeSend: sitesurvey_myajaxStart(),
		url: "/components/infusions/sitesurvey/includes/attacks.php",
		success: function(msg){
			$("#content_captures").html(msg);
			sitesurvey_myajaxStop('');
		}
	});
}

function sitesurvey_refresh_history() {	
	$.ajax({
		type: "GET",
		data: "history",
		beforeSend: sitesurvey_myajaxStart(),
		url: "/components/infusions/sitesurvey/includes/attacks.php",
		success: function(msg){
			$("#content_history").html(msg);
			sitesurvey_myajaxStop('');
		}
	});
}

function sitesurvey_refresh_config() {	
	$.ajax({
		type: "GET",
		data: "get_conf",
		beforeSend: sitesurvey_myajaxStart(),
		url: "/components/infusions/sitesurvey/includes/conf.php",
		success: function(msg){
			$("#content_conf").html(msg);
			sitesurvey_myajaxStop('');
		}
	});
}

function sitesurvey_set_config() {	
	$.ajax({
		type: "POST",
		data: $("#sitesurvey_form_conf").serialize(),
		beforeSend: sitesurvey_myajaxStart(),
		url: "/components/infusions/sitesurvey/includes/conf.php",
		success: function(msg){
			sitesurvey_myajaxStop('');
			sitesurvey_refresh(0);
			sitesurvey_refresh_captures();
		}
	});
}

function execute_custom_script(cmd) {	
	$.ajax({
		type: "GET",
		data: "execute&int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val()+"&cmd="+cmd,
		beforeSend: sitesurvey_myajaxStart(),
		url: "/components/infusions/sitesurvey/includes/actions.php",
		success: function(msg){
			$("#sitesurvey_output").val(msg);
			$('#sitesurvey_output').val('Custom script is running...');
			sitesurvey_myajaxStop('');
			sitesurvey_refresh(0);
			sitesurvey_refresh_history();
		}
	});
}

function sitesurvey_cancel_custom_script() {	
	$.ajax({
		type: "GET",
		data: "cancel&int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val(),
		beforeSend: sitesurvey_myajaxStart(),
		url: "/components/infusions/sitesurvey/includes/actions.php",
		success: function(msg){
			$("#sitesurvey_output").val(msg);
			$('#sitesurvey_output').val('Custom script has been stopped...');
			sitesurvey_myajaxStop('');
			sitesurvey_refresh(0);
			sitesurvey_refresh_history();
		}
	});
}

function sitesurvey_showTab() {
	$("#Output").show(); 
	$("#History").hide();
	$("#History_link").removeClass("selected"); 
	$("#Output_link").addClass("selected");
}

function sitesurvey_load_file(what) {	
	$.ajax({
		type: "GET",
		data: "load&file=" + what,
		beforeSend: sitesurvey_myajaxStart(),
		url: "/components/infusions/sitesurvey/includes/actions.php",
		success: function(msg){
			$("#sitesurvey_output").val(msg);
			sitesurvey_showTab();		
			sitesurvey_myajaxStop('');
		}
	});
}

function sitesurvey_delete_file(what, which) {	
	$.ajax({
		type: "GET",
		data: "delete&file=" + which + "&" + what,
		beforeSend: sitesurvey_myajaxStart(),
		url: "/components/infusions/sitesurvey/includes/actions.php",
		success: function(msg){
			$("#content_history").html(msg);
			sitesurvey_myajaxStop('');
			sitesurvey_refresh_history();
			sitesurvey_refresh_captures();
		}
	});
}

function sitesurvey_deauth(ap, client, time) {	
	$.ajax({
		type: "GET",
		data: "int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val()+"&deauthtarget="+ap+"&deauthtargetClient="+client+"&deauthtimes="+time,
		beforeSend: sitesurvey_myajaxStart(),
		url: "/components/infusions/sitesurvey/includes/attacks.php",
		success: function(msg){
			$("#sitesurvey_output").val(msg);
			sitesurvey_myajaxStop('');
		}
	});
}

function sitesurvey_capture(ap, channel) {	
	$.ajax({
		type: "GET",
		data: "int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val()+"&ap="+ap+"&channel="+channel,
		beforeSend: sitesurvey_myajaxStart(),
		url: "/components/infusions/sitesurvey/includes/attacks.php",
		success: function(msg){
			$("#sitesurvey_output").val(msg);
			$('#sitesurvey_output').val('Capture is running...');
			sitesurvey_myajaxStop('');
			sitesurvey_refresh(0);
			sitesurvey_refresh_captures();
		}
	});
}

function sitesurvey_cancel_capture() {	
	$.ajax({
		type: "GET",
		data: "cancel",
		beforeSend: sitesurvey_myajaxStart(),
		url: "/components/infusions/sitesurvey/includes/attacks.php",
		success: function(msg){
			$("#sitesurvey_output").val(msg);
			$('#sitesurvey_output').val('Capture has been stopped...');
			sitesurvey_myajaxStop('');
			sitesurvey_refresh(0);
			sitesurvey_refresh_captures();
		}
	});
}


function sitesurvey_refresh_available_ap() {
	$.ajax({
		type: "GET",
		data: "available_ap&int="+$("#sitesurvey_interfaces").val(),
		beforeSend: sitesurvey_myajaxStart(),
		url: "/components/infusions/sitesurvey/includes/interfaces.php",
		success: function(msg){
			$("#sitesurvey_list_ap").html(msg);
			sitesurvey_myajaxStop('');
		}
	});
}

function sitesurvey_getOUIFromMAC(mac) {

	var top = 30;
	var left = Math.floor(screen.availWidth * .66) - 10;
	var width = 700
	var height = 400
	var tab = new Array();

	tab = mac.split(mac.substr(2,1));

	var win = window.open("http://standards.ieee.org/cgi-bin/ouisearch?" + tab[0] + '-' + tab[1] + '-' + tab[2], 'OUI From MAC', 'width=' + width + ',height=' + height + ",resizable=yes,scrollbars=yes,statusbar=no");
	win.focus();
}