var wifimanager_showDots;
var wifimanager_small_showDots;

var wifimanager_showLoadingDots = function() {
    clearInterval(wifimanager_showDots);
	if (!$("#wifimanager_loadingDots").length>0) return false;
    wifimanager_showDots = setInterval(function(){            
        var d = $("#wifimanager_loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

var wifimanager_small_showLoadingDots = function() {
    clearInterval(wifimanager_small_showDots);
	if (!$("#wifimanager_small_loadingDots").length>0) return false;
    wifimanager_small_showDots = setInterval(function(){            
        var d = $("#wifimanager_small_loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

function wifimanager_init_small() {
	wifimanager_refresh_tile();
}

function wifimanager_init() {
	wifimanager_refresh();
	wifimanager_refresh_interfaces();
}

function wifimanager_myajaxStart()
{
	$("#wifimanager.refresh_text").html('<em>Loading<span id="wifimanager_loadingDots"></span></em>'); 
	wifimanager_showLoadingDots();
	
	$("#wifimanager_small.refresh_text").html('<em>Loading<span id="wifimanager_small_loadingDots"></span></em>'); 
	wifimanager_small_showLoadingDots();
}

function wifimanager_myajaxStop(msg)
{
	$("#wifimanager.refresh_text").html(msg); 
	clearInterval(wifimanager_showDots);
	
	$("#wifimanager_small.refresh_text").html(msg); 
	clearInterval(wifimanager_small_showDots);
}

function wifimanager_toggle_options(interface) {
	
	// Channel
	if($('#'+interface+'_mode').val() == "ap")
	{
		$("#"+interface+"_channel option[value=auto]").attr('disabled','disabled')
		if($("#"+interface+"_channel").val() == "auto")
			$("#"+interface+"_channel").val(1);
	}
	else if($('#'+interface+'_mode').val() == "sta")
	{
		$("#"+interface+"_channel option[value=auto]").removeAttr('disabled');
		$("#"+interface+"_channel").val(0);
	}
	
	// Security
	switch($('#'+interface+'_security_mode').val()) {
		case 'wep':
			$('#'+interface+'_key_div').show();
			$('#'+interface+'_shared_key_div').hide();
			$('#'+interface+'_encryption_div').hide();
			$('#'+interface+'_wep_mode_div').show();
			$('#'+interface+'_eap_type_div').hide();
			$('#'+interface+'_identity_div').hide();
			$('#'+interface+'_password_div').hide();
			$('#'+interface+'_server_div').hide();
			$('#'+interface+'_port_div').hide();
			$('#'+interface+'_shared_div').hide();
		break;
		case 'psk': 
			$('#'+interface+'_key_div').hide();
			$('#'+interface+'_shared_key_div').show();
			$('#'+interface+'_encryption_div').show();
			$('#'+interface+'_wep_mode_div').hide();
			$('#'+interface+'_eap_type_div').hide();
			$('#'+interface+'_identity_div').hide();
			$('#'+interface+'_password_div').hide();
			$('#'+interface+'_server_div').hide();
			$('#'+interface+'_port_div').hide();
			$('#'+interface+'_shared_div').hide();
		break;
		case 'wpa': 
			$('#'+interface+'_key_div').hide();
			$('#'+interface+'_shared_key_div').hide();
			$('#'+interface+'_encryption_div').show();
			$('#'+interface+'_wep_mode_div').hide();
			if($('#'+interface+'_mode').val() == "sta")
			{
				$('#'+interface+'_eap_type_div').show();
				$('#'+interface+'_identity_div').show();
				$('#'+interface+'_password_div').show();
				$('#'+interface+'_server_div').hide();
				$('#'+interface+'_port_div').hide();
				$('#'+interface+'_shared_div').hide();
			}
			else if($('#'+interface+'_mode').val() == "ap")
			{
				$('#'+interface+'_server_div').show();
				$('#'+interface+'_port_div').show();
				$('#'+interface+'_shared_div').show();
				$('#'+interface+'_eap_type_div').hide();
				$('#'+interface+'_identity_div').hide();
				$('#'+interface+'_password_div').hide();
			}
		break;
		case 'psk2':
			$('#'+interface+'_key_div').hide();
			$('#'+interface+'_shared_key_div').show();
			$('#'+interface+'_encryption_div').show();
			$('#'+interface+'_wep_mode_div').hide();
			$('#'+interface+'_eap_type_div').hide();
			$('#'+interface+'_identity_div').hide();
			$('#'+interface+'_password_div').hide();
			$('#'+interface+'_server_div').hide();
			$('#'+interface+'_port_div').hide();
			$('#'+interface+'_shared_div').hide();
		break;
		case 'wpa2': 
			$('#'+interface+'_key_div').hide();
			$('#'+interface+'_shared_key_div').hide();
			$('#'+interface+'_encryption_div').show();
			$('#'+interface+'_wep_mode_div').hide(); 
			if($('#'+interface+'_mode').val() == "sta")
			{
				$('#'+interface+'_eap_type_div').show();
				$('#'+interface+'_identity_div').show();
				$('#'+interface+'_password_div').show();
				$('#'+interface+'_server_div').hide();
				$('#'+interface+'_port_div').hide();
				$('#'+interface+'_shared_div').hide();
			}
			else if($('#'+interface+'_mode').val() == "ap")
			{
				$('#'+interface+'_server_div').show();
				$('#'+interface+'_port_div').show();
				$('#'+interface+'_shared_div').show();
				$('#'+interface+'_eap_type_div').hide();
				$('#'+interface+'_identity_div').hide();
				$('#'+interface+'_password_div').hide();
			}
		break;
		case 'mixed-psk':
			$('#'+interface+'_key_div').hide();
			$('#'+interface+'_shared_key_div').show();
			$('#'+interface+'_encryption_div').show();
			$('#'+interface+'_wep_mode_div').hide();
			$('#'+interface+'_eap_type_div').hide();
			$('#'+interface+'_identity_div').hide();
			$('#'+interface+'_password_div').hide();
			$('#'+interface+'_server_div').hide();
			$('#'+interface+'_port_div').hide();
			$('#'+interface+'_shared_div').hide();
		break;
		case 'mixed-wpa':
			$('#'+interface+'_key_div').hide();
			$('#'+interface+'_shared_key_div').hide();
			$('#'+interface+'_encryption_div').show();
			$('#'+interface+'_wep_mode_div').hide();
			if($('#'+interface+'_mode').val() == "sta")
			{
				$('#'+interface+'_eap_type_div').show();
				$('#'+interface+'_identity_div').show();
				$('#'+interface+'_password_div').show();
				$('#'+interface+'_server_div').hide();
				$('#'+interface+'_port_div').hide();
				$('#'+interface+'_shared_div').hide();
			}
			else if($('#'+interface+'_mode').val() == "ap")
			{
				$('#'+interface+'_server_div').show();
				$('#'+interface+'_port_div').show();
				$('#'+interface+'_shared_div').show();
				$('#'+interface+'_eap_type_div').hide();
				$('#'+interface+'_identity_div').hide();
				$('#'+interface+'_password_div').hide();
			}
		break;
		case 'none': 
			$('#'+interface+'_key_div').hide();
			$('#'+interface+'_shared_key_div').hide();
			$('#'+interface+'_encryption_div').hide();
			$('#'+interface+'_wep_mode_div').hide();
			$('#'+interface+'_eap_type_div').hide();
			$('#'+interface+'_identity_div').hide();
			$('#'+interface+'_password_div').hide();
			$('#'+interface+'_server_div').hide();
			$('#'+interface+'_port_div').hide();
			$('#'+interface+'_shared_div').hide();
		break;
	}
}

function wifimanager_refresh() {
	$.ajax({
		type: "POST",
		async: false,
		beforeSend: wifimanager_myajaxStart(),
		url: "/components/infusions/wifimanager/includes/data.php",
		success: function(msg){
			$("#content").html(msg);
			
			wifimanager_refresh_bck();
			
			wifimanager_myajaxStop('');
		}
	});
}

function wifimanager_refresh_interfaces() {
	$('#sidePanelContent').load('/components/infusions/wifimanager/includes/interfaces.php?interface');
}

function wifimanager_interface_toggle(interface, action) {		
	$.ajax({
		type: "POST",
		data: "interface=1&action="+action+"&int="+interface,
		beforeSend: wifimanager_myajaxStart(),
		url: "/components/infusions/wifimanager/includes/actions.php",
		success: function(msg){
			wifimanager_myajaxStop(msg);
			
			wifimanager_refresh(); wifimanager_refresh_interfaces();
						
			wifimanager_refresh_tile();
		}
	});

}

function wifimanager_monitor_toggle(interface, monitor, action) {	
	$.ajax({
		type: "POST",
		data: "monitor=1&action="+action+"&int="+interface+"&mon="+monitor,
		beforeSend: wifimanager_myajaxStart(),
		url: "/components/infusions/wifimanager/includes/actions.php",
		success: function(msg){
			wifimanager_myajaxStop(msg);
			
			wifimanager_refresh(); wifimanager_refresh_interfaces();
						
			wifimanager_refresh_tile();
		}
	});
}

function wifimanager_save(data) {
	$.ajax({
		type: "POST",
		data: $("#"+data).serialize(),
		beforeSend: wifimanager_myajaxStart(),
		url: "/components/infusions/wifimanager/includes/actions.php",
		success: function(msg){
			wifimanager_myajaxStop(msg);
			
			if(data != 'ics_conf')
			{
				$("#commit").css("color","green");
				$("#revert").css("color","red");
			}
		}
	});
}

function wifimanager_detect() {		
	$.ajax({
		type: "POST",
		data: "detect=1",
		beforeSend: wifimanager_myajaxStart(),
		url: "/components/infusions/wifimanager/includes/actions.php",
		success: function(msg){
			wifimanager_myajaxStop(msg);
			
			wifimanager_refresh(); wifimanager_refresh_interfaces();
		}
	});
}

function wifimanager_release(interface) {		
	$.ajax({
		type: "POST",
		data: "release=1&int="+interface,
		beforeSend: wifimanager_myajaxStart(),
		url: "/components/infusions/wifimanager/includes/actions.php",
		success: function(msg){
			wifimanager_myajaxStop(msg);
			
			wifimanager_refresh(); wifimanager_refresh_interfaces();
			
			wifimanager_refresh_tile();
		}
	});
}

function wifimanager_connect(interface) {		
	$.ajax({
		type: "POST",
		data: "connect=1&int="+interface,
		beforeSend: wifimanager_myajaxStart(),
		url: "/components/infusions/wifimanager/includes/actions.php",
		success: function(msg){
			wifimanager_myajaxStop(msg);
			
			wifimanager_refresh(); wifimanager_refresh_interfaces();
			
			wifimanager_refresh_tile();
		}
	});
}

function wifimanager_macchanger(interface, radio) {		
	$.ajax({
		type: "POST",
		data: "macchanger=1&int="+interface+"&phy="+radio,
		beforeSend: wifimanager_myajaxStart(),
		url: "/components/infusions/wifimanager/includes/actions.php",
		success: function(msg){
			wifimanager_myajaxStop(msg);
			
			wifimanager_refresh(); wifimanager_refresh_interfaces();
			
			$("#commit").css("color","green");
			$("#revert").css("color","red");
			
			rwifimanager_refresh_tile();
		}
	});
}

function wifimanager_remove(interface) {		
	$.ajax({
		type: "POST",
		data: "remove=1&phy="+interface,
		beforeSend: wifimanager_myajaxStart(),
		url: "/components/infusions/wifimanager/includes/actions.php",
		success: function(msg){
			wifimanager_myajaxStop(msg);
			
			wifimanager_refresh(); wifimanager_refresh_interfaces();
			
			$("#commit").css("color","green");
			$("#revert").css("color","red");
		}
	});
}

function wifimanager_commit() {		
	$.ajax({
		type: "POST",
		data: "commit=1",
		beforeSend: wifimanager_myajaxStart(),
		url: "/components/infusions/wifimanager/includes/actions.php",
		success: function(msg){
			wifimanager_myajaxStop(msg);
			
			wifimanager_refresh(); wifimanager_refresh_interfaces();
			
			$("#commit").css("color","black");
			$("#revert").css("color","black");
		}
	});
}

function wifimanager_revert() {		
	$.ajax({
		type: "POST",
		data: "revert=1",
		beforeSend: wifimanager_myajaxStart(),
		url: "/components/infusions/wifimanager/includes/actions.php",
		success: function(msg){
			wifimanager_myajaxStop(msg);
			
			wifimanager_refresh(); wifimanager_refresh_interfaces();
			
			$("#commit").css("color","black");
			$("#revert").css("color","black");
		}
	});
}

function wifimanager_show_ap(what) {
    $.get('/components/infusions/wifimanager/includes/ap.php', {w: what}, function(data){
	    $('.popup_content').html(data);
	    $('.popup').css('visibility', 'visible');
    });
}

function wifimanager_refresh_bck() {
	$.ajax({
		type: "GET",
		data: "bck",
		beforeSend: wifimanager_myajaxStart(),
		url: "/components/infusions/wifimanager/includes/backup.php",
		success: function(msg){
			$("#content_bck").html(msg);
			wifimanager_myajaxStop('');
		}
	});
}

function wifimanager_new_bck() {
	$.ajax({
		type: "GET",
		data: "new",
		beforeSend: wifimanager_myajaxStart(),
		url: "/components/infusions/wifimanager/includes/backup.php",
		success: function(msg){
			wifimanager_refresh_bck();
			
			wifimanager_myajaxStop(msg);
		}
	});
}

function wifimanager_restore_bck(which) {
	$.ajax({
		type: "GET",
		data: "restore&file=" + which,
		beforeSend: wifimanager_myajaxStart(),
		url: "/components/infusions/wifimanager/includes/backup.php",
		success: function(msg){
			
			wifimanager_refresh();
			wifimanager_refresh_interfaces();
			
			wifimanager_myajaxStop(msg);
			
			$("#commit").css("color","green");
			$("#revert").css("color","red");
		}
	});
}

function wifimanager_delete_bck(which) {
	$.ajax({
		type: "GET",
		data: "delete&file=" + which,
		beforeSend: wifimanager_myajaxStart(),
		url: "/components/infusions/wifimanager/includes/backup.php",
		success: function(msg){
			wifimanager_myajaxStop('');
			
			wifimanager_refresh_bck();
		}
	});
}

function wifimanager_view_bck(which) {
    $.get('/components/infusions/wifimanager/includes/backup.php?view', {file: which}, function(data){
	    $('.popup_content').html(data);
	    $('.popup').css('visibility', 'visible');
    });
}

function wifimanager_refresh_tile() {
	$.ajax({
		type: "GET",
		data: "interface",
		beforeSend: wifimanager_myajaxStart(),
		url: "/components/infusions/wifimanager/includes/interfaces.php",
		success: function(msg){
			$("#wifimanager_interfaces_tile").html(msg);
			wifimanager_myajaxStop('');
		}
	});
}