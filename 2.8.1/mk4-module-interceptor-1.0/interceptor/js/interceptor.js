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
	
	$("#tabs ul").idTabs();

	$('#list_editor').change(function() { show_list() });
	
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

function refresh() {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}

	$.ajax({
		type: "GET",
		data: "log",
		url: "interceptor_data.php",
		success: function(msg){
			$("#refresh_text").html(''); clearInterval(showDots);
			$("#output").val(msg).scrollTop($("#output")[0].scrollHeight - $("#output").height());
		}
	});
}

function interceptor_toggle(action) {
	
	if($('#8021X:checkbox:checked').val() == "8021X")
		$('#output').load('interceptor_actions.php?interceptor&8021X&'+action);
	else
		$('#output').load('interceptor_actions.php?interceptor&'+action);
	
	if(action == 'stop') {
		$("#interceptor_link").html('<strong>Start</strong>');
		$("#interceptor_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#interceptor_link").attr("href", "javascript:interceptor_toggle('start');");
		
		$('#8021X').removeAttr('disabled');
		
		$('#output').val('Interceptor has been stopped...');
	}
	else if(action == 'start'){
		$("#interceptor_link").html('<strong>Stop</strong>');
		$("#interceptor_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#interceptor_link").attr("href", "javascript:interceptor_toggle('stop');");
		
		$('#8021X').attr('disabled', 'disabled');
		
		$('#output').val('Interceptor is running...');
	}
	else if(action == 'install'){
		$("#interceptor2_link").html('<strong>Uninstall</strong>');
		$("#interceptor2_status").html('<font color="lime"><strong>installed</strong></font>');
		$("#interceptor2_link").attr("href", "javascript:interceptor_toggle('uninstall');");
		
		$('#output').val('Interceptor is installed...');
	}
	else if(action == 'uninstall'){
		$("#interceptor2_link").html('<strong>Install</strong>');
		$("#interceptor2_status").html('<font color="red"><strong>not installed</strong></font>');
		$("#interceptor2_link").attr("href", "javascript:interceptor_toggle('install');");
		
		$('#output').val('Interceptor has been uninstalled...');
	}
}

function boot_toggle(action) {
	
	if($('#8021X_onboot:checkbox:checked').val() == "8021X")
		$('#output').load('interceptor_actions.php?boot&8021X&action='+action);
	else
		$('#output').load('interceptor_actions.php?boot&action='+action);

	if(action == 'disable') {
		$("#boot_link").html('<strong>Enable</strong>');
		$("#boot_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#boot_link").attr("href", "javascript:boot_toggle('enable');");
		
		$('#8021X_onboot').removeAttr('disabled');
	}
	else {
		$("#boot_link").html('<strong>Disable</strong>');
		$("#boot_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#boot_link").attr("href", "javascript:boot_toggle('disable');");
		
		$('#8021X_onboot').attr('disabled', 'disabled');
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
		url: "interceptor_actions.php",
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