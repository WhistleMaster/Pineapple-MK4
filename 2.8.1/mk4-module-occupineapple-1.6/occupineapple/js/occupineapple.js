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
		url: "occupineapple_data.php",
		success: function(msg){
			$("#refresh_text").html(''); clearInterval(showDots);
			$("#output").val(msg).scrollTop($("#output")[0].scrollHeight - $("#output").height());
		}
	});
}

function mdk3_toggle(action) {
	
	$('#output').load('occupineapple_actions.php?mdk3&'+action+'&list='+$("#list").val()+'&int='+$("#interfaces").val()+'&mon='+$("#monitorInterfaces").val());

	if(action == 'stop') {
		$("#mdk3_link").html('<strong>Start</strong>');
		$("#mdk3_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#mdk3_link").attr("href", "javascript:mdk3_toggle('start');");
		$('#output').val('mdk3 has been stopped...');
		
		$('#list').removeAttr('disabled');
	}
	else {
		$("#mdk3_link").html('<strong>Stop</strong>');
		$("#mdk3_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#mdk3_link").attr("href", "javascript:mdk3_toggle('stop');");
		
		if ($("#list").val() == '--')
			$('#output').val('mdk3 is running with random AP list...');
		else
			$('#output').val('mdk3 is running with list '+$("#list").val()+'...');
		
		$('#list').attr('disabled', 'disabled');
	}
}

function show_list() {
	
	if($("#list_editor").val() != "--")
	{
		if(auto_refresh == null) {
			$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
			showLoadingDots();
		}
		
		$('#list_name').val($("#list_editor").val());
		
		$.ajax({
			type: "GET",
			data: "show_list&which=" + $("#list_editor").val(),
			url: "occupineapple_lists.php",
			success: function(msg){
				$("#list_content").val(msg);
				$("#refresh_text").html(''); clearInterval(showDots);
			}
		});
	}
	else
	{
		$('#list_name').val("");
		$('#list_content').val("");
	}
}

function delete_list() {	
	if($("#list_editor").val() != "--")
	{
		if(auto_refresh == null) {
			$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
			showLoadingDots();
		}
		
		$.ajax({
			type: "GET",
			data: "delete_list&which=" + $("#list_editor").val(),
			url: "occupineapple_lists.php",
			success: function(msg){
				$("#list_editor option:selected").remove();
				$('#list_name').val("");
				$('#list_content').val("");
				
				$("#refresh_text").html('<font color="lime"><b>done</b></font>'); clearInterval(showDots);
				
				refresh_list();
			}
		});
	}
}

function save_list() {	
	if($("#list_content").val() != "")
	{
		if(auto_refresh == null) {
			$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
			showLoadingDots();
		}
		
		$.ajax({
			type: "POST",
			data: "save_list=1&which="+$("#list_editor").val()+"&newdata="+escape($("#list_content").val()),
			url: "occupineapple_lists.php",
			success: function(msg){
				$("#refresh_text").html('<font color="lime"><b>saved</b></font>'); clearInterval(showDots);
			}
		});
	}
}

function new_list() {		
	if($("#list_name").val() != "" && ( $("#list_name").val().search(".list") != -1 || $("#list_name").val().search(".mlist") != -1 ) && $("#list_name").val() != $("#list_editor").val())
	{
		$("#error_text").html('<font color="lime">OK</font>');
		
		if(auto_refresh == null) {
			$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
			showLoadingDots();
		}
		
		$.ajax({
			type: "POST",
			data: "new_list=1&which="+$("#list_name").val()+"&newdata="+escape($("#list_content").val()),
			url: "occupineapple_lists.php",
			success: function(msg){
				$('#list_editor').append($("<option></option>").attr("value",$("#list_name").val()).text($("#list_name").val()));
				$('#list_editor').val($("#list_name").val());
				
				$("#refresh_text").html('<font color="lime"><b>done</b></font>'); clearInterval(showDots);
				
				refresh_list();
			}
		});
	}
	else
	{
		$("#error_text").html('<font color="red">Name cannot be empty and must be <em>*.list</em> or <em>*.mlist</em>.</font>');
	}
}

function refresh_list() {
	
	var previous_val = $('#list option:selected').text();
	
	$.ajax({
		type: "GET",
		data: "list_list",
		url: "occupineapple_lists.php",
		success: function(msg){
			$('#list').html(msg);
			$('#list').val(previous_val);
		}
	});
}

function boot_toggle(action) {
	$('#output').load('occupineapple_actions.php?boot&action='+action);
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

function monitor_toggle(action) {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}
	
	$.ajax({
		type: "GET",
		data: "monitor&"+action+"&int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val(),
		url: "occupineapple_actions.php",
		success: function(msg){
			$("#refresh_text").html('<font color="lime"><b>done</b></font>'); clearInterval(showDots);
			if(action == "stop")
				$('#output').val(action+" monitor "+$("#monitorInterfaces").val()+"...");
			else
				$('#output').val(action+" monitor on "+$("#interfaces").val()+"...");
			refresh_monitors();
		}
	});
}

function interface_toggle(action) {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}
	
	$.ajax({
		type: "GET",
		data: "interface&"+action+"&int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val(),
		url: "occupineapple_actions.php",
		success: function(msg){
			$("#refresh_text").html('<font color="lime"><b>done</b></font>'); clearInterval(showDots);
			$('#output').val(action+" "+$("#interfaces").val()+"...");
			refresh_interfaces();
		}
	});
}

function auto_toggle() {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}
	
	$.ajax({
		type: "GET",
		data: "auto&int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val(),
		url: "occupineapple_actions.php",
		success: function(msg){
			$("#refresh_text").html('<font color="lime"><b>done</b></font>'); clearInterval(showDots);
			$('#output').val("toggle "+$("#interfaces").val()+"...");
		}
	});
}

function refresh_interfaces() {
	$('#interfaces_l').load('occupineapple_interfaces.php?interface');
}

function refresh_monitors() {
	$('#monitorInterface_l').load('occupineapple_interfaces.php?monitor');
}

function install(where) {
	$("#refresh_text").html('<em>Installing<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "install&where=" + where,
		url: "occupineapple_actions.php",
		success: function(msg){
			$("#output").val(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
			location.reload(true);
		}
	});
}

function refresh_config() {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "get_conf",
		url: "occupineapple_conf.php",
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
		url: "occupineapple_conf.php",
		success: function(msg){
			$("#refresh_text").html('<font color="lime"><b>saved</b></font>'); clearInterval(showDots);
			$('#output').val('Configuration has been saved.');
		}
	});
}