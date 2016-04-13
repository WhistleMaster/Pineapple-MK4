var occupineapple_auto_refresh;
var occupineapple_showDots;
var occupineapple_small_showDots;

var occupineapple_showLoadingDots = function() {
    clearInterval(occupineapple_showDots);
	if (!$("#occupineapple_loadingDots").length>0) return false;
    occupineapple_showDots = setInterval(function(){            
        var d = $("#occupineapple_loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

var occupineapple_small_showLoadingDots = function() {
    clearInterval(occupineapple_small_showDots);
	if (!$("#occupineapple_small_loadingDots").length>0) return false;
    occupineapple_small_showDots = setInterval(function(){            
        var d = $("#occupineapple_small_loadingDots");
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

function occupineapple_myajaxStart()
{
	if(occupineapple_auto_refresh == null)
	{
		$("#occupineapple.refresh_text").html('<em>Loading<span id="occupineapple_loadingDots"></span></em>'); 
		occupineapple_showLoadingDots();
	
		$("#occupineapple_small.refresh_text").html('<em>Loading<span id="occupineapple_small_loadingDots"></span></em>'); 
		occupineapple_small_showLoadingDots();
	}
}

function occupineapple_myajaxStop(msg)
{
	if(occupineapple_auto_refresh == null)
	{
		$("#occupineapple.refresh_text").html(msg); 
		clearInterval(occupineapple_showDots);
	
		$("#occupineapple_small.refresh_text").html(msg); 
		clearInterval(occupineapple_small_showDots);
	}
}

function occupineapple_init_small() {
	
	occupineapple_refresh_tile();
	
}

function occupineapple_init() {
	
	occupineapple_refresh();
	occupineapple_refresh_config();
	
	$("#tabs ul").idTabs();

	$('#list_editor').change(function() { occupineapple_show_list() });
	
	$("#occupineapple_auto_refresh").toggleClick(function() {
			$("#occupineapple_auto_refresh").html('<font color="lime">On</font>');
			$('#occupineapple_auto_time').attr('disabled', 'disabled');
			
			occupineapple_auto_refresh = setInterval(
			function ()
			{
				occupineapple_refresh();
			},
			$("#occupineapple_auto_time").val());
		}, function() {
			$("#occupineapple_auto_refresh").html('<font color="red">Off</font>');
			$('#occupineapple_auto_time').removeAttr('disabled');
				
            clearInterval(occupineapple_auto_refresh);
			occupineapple_auto_refresh = null;
		});
}

function occupineapple_refresh() {
	$.ajax({
		type: "GET",
		data: "log",
		beforeSend: occupineapple_myajaxStart(),
		url: "/components/infusions/occupineapple/includes/data.php",
		success: function(msg){
			occupineapple_myajaxStop('');
			$("#occupineapple_output").val(msg).scrollTop($("#occupineapple_output")[0].scrollHeight - $("#occupineapple_output").height());
		}
	});
}

function occupineapple_mdk3_toggle(action) {
    $.get('/components/infusions/occupineapple/includes/actions.php?mdk3&'+action, {list: $("#list").val(), int: $("#interfaces").val(), mon: $("#monitorInterfaces").val()}, function() {
		refresh_small('occupineapple','infusions');
	});

	if(action == 'stop') {
		$("#mdk3_link").html('<strong>Start</strong>');
		$("#mdk3_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#mdk3_link").attr("href", "javascript:occupineapple_mdk3_toggle('start');");
		$("#occupineapple_output").val('mdk3 has been stopped...');
		
		$('#list').removeAttr('disabled');
	}
	else {
		$("#mdk3_link").html('<strong>Stop</strong>');
		$("#mdk3_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#mdk3_link").attr("href", "javascript:occupineapple_mdk3_toggle('stop');");
		
		if ($("#list").val() == '--')
			$("#occupineapple_output").val('mdk3 is running with random AP list...');
		else
			$("#occupineapple_output").val('mdk3 is running with list '+$("#list").val()+'...');
		
		$('#list').attr('disabled', 'disabled');
	}
}

function occupineapple_mdk3_toggle_small(action) {
    $.get('/components/infusions/occupineapple/includes/actions.php?mdk3&'+action, {list: $("#list_small").val(), int: $("#interfaces").val(), mon: $("#monitorInterfaces").val()});

	if(action == 'stop') {
		$("#mdk3_link_small").html('<strong>Start</strong>');
		$("#mdk3_occupineapple_small").html('<font color="red"><strong>disabled</strong></font>');
		$("#mdk3_link_small").attr("href", "javascript:occupineapple_mdk3_toggle_small('start');");
		$("#occupineapple_output_small").val('mdk3 has been stopped...');
		
		$('#list_small').removeAttr('disabled');
	}
	else {
		$("#mdk3_link_small").html('<strong>Stop</strong>');
		$("#mdk3_occupineapple_small").html('<font color="lime"><strong>enabled</strong></font>');
		$("#mdk3_link_small").attr("href", "javascript:occupineapple_mdk3_toggle_small('stop');");
		
		if ($("#list_small").val() == '--')
			$("#occupineapple_output_small").val('mdk3 is running with random AP list...');
		else
			$("#occupineapple_output_small").val('mdk3 is running with list '+$("#list_small").val()+'...');
		
		$('#list_small').attr('disabled', 'disabled');
	}
}

function occupineapple_show_list() {
	if($("#list_editor").val() != "--")
	{
		$('#list_name').val($("#list_editor").val());
		
		$.ajax({
			type: "GET",
			data: "show_list&which=" + $("#list_editor").val(),
			beforeSend: occupineapple_myajaxStart(),
			url: "/components/infusions/occupineapple/includes/lists.php",
			success: function(msg){
				$("#list_content").val(msg);
				occupineapple_myajaxStop('');
			}
		});
	}
	else
	{
		$('#list_name').val("");
		$('#list_content').val("");
	}
}

function occupineapple_delete_list() {	
	if($("#list_editor").val() != "--")
	{
		$.ajax({
			type: "GET",
			data: "delete_list&which=" + $("#list_editor").val(),
			beforeSend: occupineapple_myajaxStart(),
			url: "/components/infusions/occupineapple/includes/lists.php",
			success: function(msg){
				$("#list_editor option:selected").remove();
				$('#list_name').val("");
				$('#list_content').val("");
				
				occupineapple_myajaxStop(msg);
				
				occupineapple_refresh_list();
			}
		});
	}
}

function occupineapple_save_list() {	
	if($("#list_content").val() != "")
	{
		$.ajax({
			type: "POST",
			data: "save_list=1&which="+$("#list_editor").val()+"&newdata="+escape($("#list_content").val()),
			beforeSend: occupineapple_myajaxStart(),
			url: "/components/infusions/occupineapple/includes/lists.php",
			success: function(msg){
				occupineapple_myajaxStop(msg);
			}
		});
	}
}

function occupineapple_new_list() {		
	if($("#list_name").val() != "" && ( $("#list_name").val().search(".list") != -1 || $("#list_name").val().search(".mlist") != -1 ) && $("#list_name").val() != $("#list_editor").val())
	{
		$("#error_text").html('<font color="lime">OK</font>');
		
		$.ajax({
			type: "POST",
			data: "new_list=1&which="+$("#list_name").val()+"&newdata="+escape($("#list_content").val()),
			beforeSend: occupineapple_myajaxStart(),
			url: "/components/infusions/occupineapple/includes/lists.php",
			success: function(msg){
				$('#list_editor').append($("<option></option>").attr("value",$("#list_name").val()).text($("#list_name").val()));
				$('#list_editor').val($("#list_name").val());
				
				occupineapple_myajaxStop(msg);
				
				occupineapple_refresh_list();
			}
		});
	}
	else
	{
		$("#error_text").html('<font color="red">Name cannot be empty and must be <em>*.list</em> or <em>*.mlist</em>.</font>');
	}
}

function occupineapple_refresh_list() {
	
	var previous_val = $('#list option:selected').text();
	
	$.ajax({
		type: "GET",
		data: "list_list",
		beforeSend: occupineapple_myajaxStart(),
		url: "/components/infusions/occupineapple/includes/lists.php",
		success: function(msg){
			occupineapple_myajaxStop('');
			
			$('#list').html(msg);
			$('#list').val(previous_val);
		}
	});
}

function occupineapple_boot_toggle(action) {	
	$.get('/components/infusions/occupineapple/includes/actions.php?boot', {action: action});
	
	if(action == 'disable') {
		$("#boot_link").html('<strong>Enable</strong>');
		$("#boot_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#boot_link").attr("href", "javascript:occupineapple_boot_toggle('enable');");
	}
	else {
		$("#boot_link").html('<strong>Disable</strong>');
		$("#boot_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#boot_link").attr("href", "javascript:occupineapple_boot_toggle('disable');");
	}
}

function occupineapple_monitor_toggle(action) {
	$.ajax({
		type: "GET",
		data: "monitor&"+action+"&int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val(),
		beforeSend: occupineapple_myajaxStart(),
		url: "/components/infusions/occupineapple/includes/actions.php",
		success: function(msg){
			occupineapple_myajaxStop(msg);
			
			if(action == "stop")
				$("#occupineapple_output").val(action+" monitor "+$("#monitorInterfaces").val()+"...");
			else
				$("#occupineapple_output").val(action+" monitor on "+$("#interfaces").val()+"...");
				
			occupineapple_refresh_monitors();
		}
	});
}

function occupineapple_interface_toggle(action) {
	$.ajax({
		type: "GET",
		data: "interface&"+action+"&int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val(),
		beforeSend: occupineapple_myajaxStart(),
		url: "/components/infusions/occupineapple/includes/actions.php",
		success: function(msg){
			occupineapple_myajaxStop(msg);
			
			$("#occupineapple_output").val(action+" "+$("#interfaces").val()+"...");
			occupineapple_refresh_interfaces();
		}
	});
}

function occupineapple_auto_toggle() {
	$.ajax({
		type: "GET",
		data: "auto&int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val(),
		beforeSend: occupineapple_myajaxStart(),
		url: "/components/infusions/occupineapple/includes/actions.php",
		success: function(msg){
			occupineapple_myajaxStop(msg);
			
			$("#occupineapple_output").val("toggle "+$("#interfaces").val()+"...");
		}
	});
}

function occupineapple_refresh_interfaces() {
	$('#interfaces_l').load('/components/infusions/occupineapple/includes/interfaces.php?interface');
}

function occupineapple_refresh_monitors() {
	$('#monitorInterface_l').load('/components/infusions/occupineapple/includes/interfaces.php?monitor');
}

function occupineapple_install(where) {	
	$.ajax({
		type: "GET",
		data: "install&where=" + where,
		beforeSend: occupineapple_myajaxStart(),
		url: "/components/infusions/occupineapple/includes/actions.php",
		success: function(msg){
			$("#occupineapple_output").val(msg);
			
			occupineapple_myajaxStop('');
			
			draw_large_tile('occupineapple', 'infusions');
		}
	});
}

function occupineapple_refresh_config() {	
	$.ajax({
		type: "GET",
		data: "get_conf",
		beforeSend: occupineapple_myajaxStart(),
		url: "/components/infusions/occupineapple/includes/conf.php",
		success: function(msg){
			$("#occupineapple_content_conf").html(msg);
			
			occupineapple_myajaxStop('');
		}
	});
}

function occupineapple_set_config() {	
	$.ajax({
		type: "POST",
		data: $("#occupineapple_form_conf").serialize(),
		beforeSend: occupineapple_myajaxStart(),
		url: "/components/infusions/occupineapple/includes/conf.php",
		success: function(msg){
			occupineapple_myajaxStop(msg);
			
			$("#occupineapple_output").val('Configuration has been saved.');
		}
	});
}

function occupineapple_refresh_tile() {
	$.ajax({
		type: "GET",
		data: "log",
		beforeSend: occupineapple_myajaxStart(),
		url: "/components/infusions/occupineapple/includes/data.php",
		success: function(msg){
			occupineapple_myajaxStop('');
			$("#occupineapple_output_small").val(msg).scrollTop($("#occupineapple_output_small")[0].scrollHeight - $("#occupineapple_output_small").height());
		}
	});
}