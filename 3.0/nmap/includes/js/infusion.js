var nmap_auto_refresh;
var nmap_showDots;
var nmap_small_showDots;

var nmap_showLoadingDots = function() {
    clearInterval(nmap_showDots);
	if (!$("#nmap_loadingDots").length>0) return false;
    nmap_showDots = setInterval(function(){            
        var d = $("#nmap_loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

var nmap_small_showLoadingDots = function() {
    clearInterval(nmap_small_showDots);
	if (!$("#nmap_small_loadingDots").length>0) return false;
    nmap_small_showDots = setInterval(function(){            
        var d = $("#nmap_small_loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

var nmap_showOutput = function() {
    clearInterval(nmap_auto_refresh);

    nmap_auto_refresh = setInterval(function(){            
		nmap_refresh_control();
		refresh_small('nmap','infusions');
    },10000);
}

var nmap_showOutput_small = function() {
    clearInterval(nmap_auto_refresh);

    nmap_auto_refresh = setInterval(function(){            
		nmap_refresh_control_small();
    },10000);
}

$.fn.toggleClick=function() {
	var functions=arguments, iteration=0
		return this.click(function(){
			functions[iteration].apply(this,arguments)
			iteration= (iteration+1) %functions.length
		})
}

function nmap_myajaxStart()
{
	if(nmap_auto_refresh == null)
	{
		$("#nmap.refresh_text").html('<em>Loading<span id="nmap_loadingDots"></span></em>'); 
		nmap_showLoadingDots();
	
		$("#nmap_small.refresh_text").html('<em>Loading<span id="nmap_small_loadingDots"></span></em>'); 
		nmap_small_showLoadingDots();
	}
}

function nmap_myajaxStop(msg)
{
	if(nmap_auto_refresh == null)
	{
		$("#nmap.refresh_text").html(msg); 
		clearInterval(nmap_showDots);
	
		$("#nmap_small.refresh_text").html(msg); 
		clearInterval(nmap_small_showDots);
	}
}

function nmap_init_small() {
		
	$('#profile_small').change(function() { nmap_update_small() });
	$('#target_small').keyup(function() { nmap_update_small() });
}

function nmap_init() {
	
	nmap_refresh_history();
	
	$("#tabs ul").idTabs();
	$("#tabs2 ul").idTabs();
	
	$('#profile').change(function() { nmap_update() });
	$('#timing').change(function() { nmap_update() });
	$('#tcp').change(function() { nmap_update() });
	$('#nontcp').change(function() { nmap_update() });
	
	$(':checkbox').click(function() { nmap_update() });
	
	$('#target').keyup(function() { nmap_update() });
}

function nmap_refresh_control() {
	$('#nmap_control').load('/components/infusions/nmap/includes/data.php?control'); 
}

function nmap_refresh_control_small() {
	$('#nmap_control_small').load('/components/infusions/nmap/includes/data.php?control_small'); 
}

function nmap_refresh_output() {
	$.ajax({
		type: "GET",
		data: "lastscan",
		url: "/components/infusions/nmap/includes/data.php",
		success: function(msg){
			$("#nmap_output").val(msg).scrollTop($("#nmap_output")[0].scrollHeight - $("#nmap_output").height());
		}
	});
}

function nmap_refresh_output_small() {
	$.ajax({
		type: "GET",
		data: "lastscan",
		url: "/components/infusions/nmap/includes/data.php",
		success: function(msg){
			$("#nmap_output_small").val(msg).scrollTop($("#nmap_output_small")[0].scrollHeight - $("#nmap_output_small").height());
		}
	});
}

function nmap_refresh_history() {
	$.ajax({
		type: "GET",
		data: "history",
		beforeSend: nmap_myajaxStart(),
		url: "/components/infusions/nmap/includes/data.php",
		success: function(msg){
			$("#nmap_content").html(msg);
			nmap_myajaxStop('');
		}
	});
}

function nmap_scan_toggle(action) {
	if(action == 'scan') {
		nmap_scan(); nmap_showOutput();
		$("#scan").html('<font color="red"><strong>Cancel</strong></font>');
		$("#scan").attr("href", "javascript:nmap_scan_toggle('cancel');");
		$('#nmap_output').val('Scan is running...');
	}
	else {
		nmap_cancel();
		$("#scan").html('<font color="lime"><strong>Scan</strong></font>');
		$("#scan").attr("href", "javascript:nmap_scan_toggle('scan');");
	}
}

function nmap_scan_toggle_small(action) {
	if(action == 'scan') {
		nmap_scan_small(); nmap_showOutput_small();
		$("#scan_small").html('<font color="red"><strong>Cancel</strong></font>');
		$("#scan_small").attr("href", "javascript:nmap_scan_toggle('cancel');");
		$('#nmap_output_small').val($('#profile_small').find(":selected").text()+' is running...');
	}
	else {
		nmap_cancel_small();
		$("#scan_small").html('<font color="lime"><strong>Scan</strong></font>');
		$("#scan_small").attr("href", "javascript:nmap_scan_toggle('scan');");
	}
}

function nmap_update() {
	if(nmap_profile() != "")
		$('#command').val("nmap " + nmap_profile() + nmap_target());
	else
		$('#command').val("nmap " + nmap_timing() + nmap_tcp() + nmap_nontcp() +nmap_options() + nmap_target());
}

function nmap_update_small() {
	$('#nmap_command_small').val("nmap " + nmap_profile_small() + nmap_target_small());
}

function nmap_cancel() {
	$.ajax({
		type: "GET",
		data: "cancel",
		beforeSend: nmap_myajaxStart(),
		url: "/components/infusions/nmap/includes/actions.php",
		success: function(msg){
			nmap_refresh_history();
			nmap_myajaxStop('');
			
			clearInterval(nmap_auto_refresh);
			
			refresh_small('nmap','infusions');
		}
	});
}

function nmap_cancel_small() {
	$.ajax({
		type: "GET",
		data: "cancel",
		beforeSend: nmap_myajaxStart(),
		url: "/components/infusions/nmap/includes/actions.php",
		success: function(msg){
			nmap_refresh_history();
			nmap_myajaxStop('');
			
			clearInterval(nmap_auto_refresh);
		}
	});
}

function nmap_showTab() {
	$("#Output").show(); 
	$("#History").hide();
	$("#History_link").removeClass("selected"); 
	$("#Output_link").addClass("selected");
}

function nmap_load_file(what) {
	$.ajax({
		type: "GET",
		data: "load&file=" + what,
		beforeSend: nmap_myajaxStart(),
		url: "/components/infusions/nmap/includes/actions.php",
		success: function(msg){
			$("#nmap_output").val(msg);
			nmap_showTab();		
			nmap_myajaxStop('');
		}
	});
}

function nmap_delete_file(what) {
	$.ajax({
		type: "GET",
		data: "delete&file=" + what,
		beforeSend: nmap_myajaxStart(),
		url: "/components/infusions/nmap/includes/actions.php",
		success: function(msg){
			$("#nmap_content").html(msg);
			nmap_refresh_history();
			nmap_myajaxStop('');
		}
	});
}

function nmap_scan() {
	$.ajax({
		type: "GET",
		data: "scan&target="+$("#target").val()+"&profile="+$('#profile').find(":selected").text()+"&cmd="+$('#command').val(),
		url: "/components/infusions/nmap/includes/actions.php",
		success: function(msg){
			nmap_myajaxStop('');
			
			refresh_small('nmap','infusions');
		}
	});
}

function nmap_scan_small() {
	$.ajax({
		type: "GET",
		data: "scan&target="+$("#target_small").val()+"&profile="+$('#profile_small').find(":selected").text()+"&cmd="+$('#nmap_command_small').val(),
		url: "/components/infusions/nmap/includes/actions.php",
		success: function(msg){
			nmap_myajaxStop('');
		}
	});
}

function nmap_target_small() {
	var return_value = "";
		
	if($("#target_small").val() != "--")
		return_value = $("#target_small").val() + " ";
	
	return return_value;
}

function nmap_profile_small() {
    var return_value = "";
	
	if($("#profile_small").val() != "--")
		return_value = $("#profile_small").val() + " ";
	
	return return_value;
}

function nmap_options(which) {
	var return_value = "";

    $('input:checked').each(function() {
      return_value += $(this).val() + " ";
    });
	
	return return_value;
}

function nmap_target() {
	var return_value = "";
		
	if($("#target").val() != "--")
		return_value = $("#target").val() + " ";
	
	return return_value;
}

function nmap_profile() {
    var return_value = "";
	
	if($("#profile").val() != "--")
		return_value = $("#profile").val() + " ";
	
	return return_value;
}

function nmap_timing() {
    var return_value = "";
	
	if($("#timing").val() != "--")
		return_value = $("#timing").val() + " ";
	
	return return_value;
}

function nmap_tcp() {
    var return_value = "";
	
	if($("#tcp").val() != "--")
		return_value = $("#tcp").val() + " ";
	
	return return_value;
}

function nmap_nontcp() {
    var return_value = "";
	
	if($("#nontcp").val() != "--")
		return_value = $("#nontcp").val() + " ";
	
	return return_value;
}

function nmap_install(where) {
	$("#refresh_text").html('<em>Installing<span id="nmap_loadingDots"></span></em>'); 
	nmap_showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "install&where=" + where,
		url: "/components/infusions/nmap/includes/actions.php",
		success: function(msg){
			$("#nmap_output").val(msg);
			nmap_myajaxStop('');
			
			draw_large_tile('nmap', 'infusions');
		}
	});
}