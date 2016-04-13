var tcpdump_auto_refresh;
var tcpdump_showDots;
var tcpdump_small_showDots;

var tcpdump_showLoadingDots = function() {
    clearInterval(tcpdump_showDots);
	if (!$("#tcpdump_loadingDots").length>0) return false;
    tcpdump_showDots = setInterval(function(){            
        var d = $("#tcpdump_loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

var tcpdump_small_showLoadingDots = function() {
    clearInterval(tcpdump_small_showDots);
	if (!$("#tcpdump_small_loadingDots").length>0) return false;
    tcpdump_small_showDots = setInterval(function(){            
        var d = $("#tcpdump_small_loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

function tcpdump_myajaxStart()
{
	if(tcpdump_auto_refresh == null)
	{
		$("#tcpdump.refresh_text").html('<em>Loading<span id="tcpdump_loadingDots"></span></em>'); 
		tcpdump_showLoadingDots();
	
		$("#tcpdump_small.refresh_text").html('<em>Loading<span id="tcpdump_small_loadingDots"></span></em>'); 
		tcpdump_small_showLoadingDots();
	}
}

function tcpdump_myajaxStop(msg)
{
	if(tcpdump_auto_refresh == null)
	{
		$("#tcpdump.refresh_text").html(msg); 
		clearInterval(tcpdump_showDots);
	
		$("#tcpdump_small.refresh_text").html(msg); 
		clearInterval(tcpdump_small_showDots);
	}
}

function tcpdump_init_small() {
	$('#tcpdump_interface_small').change(function() { tcpdump_update_small() });
}

function tcpdump_init() {
	
	tcpdump_refresh_history();
	
	$("#tabs ul").idTabs();
	$("#tabs2 ul").idTabs();
	
	$('#interface').change(function() { tcpdump_update() });
	$('#verbose').change(function() { tcpdump_update() });
	$('#timestamp').change(function() { tcpdump_update() });
	$('#resolve').change(function() { tcpdump_update() });
	
	$(':checkbox').click(function() { tcpdump_update() });
	
	$('#filter').keyup(function() { tcpdump_update() });
}

function tcpdump_refresh_history() {
	$.ajax({
		type: "GET",
		data: "history",
		beforeSend: tcpdump_myajaxStart(),
		url: "/components/infusions/tcpdump/includes/data.php",
		success: function(msg){
			$("#tcpdump_content").html(msg);
			tcpdump_myajaxStop('');
		}
	});
}

function tcpdump_append_filter(what) {
	if($('#filter').val().substr($('#filter').val().length-1) != ' ' && $('#filter').val().length != 0)	
		$('#filter').val($('#filter').val() + ' ' + what);
	else
		$('#filter').val($('#filter').val() + what);
	
	tcpdump_update();
}

function tcpdump_dump_toggle(action) {
	if(action == 'capture') {
		tcpdump_dump();
		$("#scan").html('<font color="red"><strong>Stop</strong></font>');
		$("#scan").attr("href", "javascript:tcpdump_dump_toggle('stop');");
		$('#tcpdump_output').val('Capture is running...');
	}
	else {
		tcpdump_cancel();
		$("#scan").html('<font color="lime"><strong>Capture</strong></font>');
		$("#scan").attr("href", "javascript:tcpdump_dump_toggle('capture');");
		$('#tcpdump_output').val('Capture has been stopped...');
	}
}

function tcpdump_dump_toggle_small(action) {
	if(action == 'capture') {
		tcpdump_dump_small();
		$("#dump_small").html('<font color="red"><strong>Stop</strong></font>');
		$("#dump_small").attr("href", "javascript:tcpdump_dump_toggle_small('stop');");
		$('#tcpdump_output_small').val('Capture is running...');
		
		$('#tcpdump_interface_small').attr('disabled', 'disabled');
	}
	else {
		tcpdump_cancel_small();
		$("#dump_small").html('<font color="lime"><strong>Capture</strong></font>');
		$("#dump_small").attr("href", "javascript:tcpdump_dump_toggle_small('capture');");
		$('#tcpdump_output_small').val('Capture has been stopped...');
		
		$('#tcpdump_interface_small').removeAttr('disabled');
	}
}

function tcpdump_update() {
	if(tcpdump_filter() != '')
		$('#tcpdump_command').val("tcpdump " + tcpdump_interface() + tcpdump_verbose() + tcpdump_resolve() + tcpdump_timestamp() + tcpdump_options() + '\'' + tcpdump_filter() + '\'');
	else
		$('#tcpdump_command').val("tcpdump " + tcpdump_interface() + tcpdump_verbose() + tcpdump_resolve() + tcpdump_timestamp() + tcpdump_options());
}

function tcpdump_update_small() {
	$('#tcpdump_command_small').val("tcpdump " + tcpdump_interface_small());
}

function tcpdump_cancel() {
	$.ajax({
		type: "GET",
		data: "cancel",
		beforeSend: tcpdump_myajaxStart(),
		url: "/components/infusions/tcpdump/includes/actions.php",
		success: function(msg){
			tcpdump_refresh_history();
			
			tcpdump_load_file('capture.log');
			
			tcpdump_myajaxStop('');
			
			refresh_small('tcpdump','infusions');
		}
	});
}

function tcpdump_cancel_small() {
	$.ajax({
		type: "GET",
		data: "cancel",
		beforeSend: tcpdump_myajaxStart(),
		url: "/components/infusions/tcpdump/includes/actions.php",
		success: function(msg){
			
			tcpdump_load_file_small('capture.log');
			
			tcpdump_myajaxStop('');
		}
	});
}

function tcpdump_delete_file(what) {
	$.ajax({
		type: "GET",
		data: "delete&file=" + what,
		beforeSend: tcpdump_myajaxStart(),
		url: "/components/infusions/tcpdump/includes/actions.php",
		success: function(msg){
			$("#tcpdump_content").html(msg);
			tcpdump_refresh_history();
			
			tcpdump_myajaxStop('');
		}
	});
}

function tcpdump_load_file(what) {
	$.ajax({
		type: "GET",
		data: "load&file=" + what,
		beforeSend: tcpdump_myajaxStart(),
		url: "/components/infusions/tcpdump/includes/actions.php",
		success: function(msg){
			$("#tcpdump_output").val(msg);
			
			tcpdump_myajaxStop('');
		}
	});
}

function tcpdump_load_file_small(what) {
	$.ajax({
		type: "GET",
		data: "load&file=" + what,
		beforeSend: tcpdump_myajaxStart(),
		url: "/components/infusions/tcpdump/includes/actions.php",
		success: function(msg){
			$("#tcpdump_output_small").val(msg);
			
			tcpdump_myajaxStop('');
		}
	});
}

function tcpdump_dump() {
	$.ajax({
		type: "GET",
		data: "scan&int="+$('#interface').find(":selected").text()+"&cmd="+$('#tcpdump_command').val(),
		url: "/components/infusions/tcpdump/includes/actions.php",
		success: function(msg){
			tcpdump_myajaxStop('');
			
			refresh_small('tcpdump','infusions');
		}
	});
}

function tcpdump_dump_small() {
	$.ajax({
		type: "GET",
		data: "scan&int="+$('#tcpdump_interface_small').find(":selected").text()+"&cmd="+$('#tcpdump_command_small').val(),
		url: "/components/infusions/tcpdump/includes/actions.php",
		success: function(msg){			
			tcpdump_myajaxStop('');
		}
	});
}

function tcpdump_options(which) {
	var return_value = "";

    $('input:checked').each(function() {
      return_value += $(this).val() + " ";
    });
	
	return return_value;
}

function tcpdump_verbose() {
	var return_value = "";
		
	if($("#verbose").val() != "--")
		return_value = $("#verbose").val() + " ";
	
	return return_value;
}

function tcpdump_filter() {
	var return_value = "";
		
	if($("#filter").val() != " ")
		return_value = $("#filter").val();
	
	return return_value;
}

function tcpdump_interface() {
    var return_value = "";
	
	if($("#interface").val() != "--")
		return_value = $("#interface").val() + " ";
	
	return return_value;
}

function tcpdump_interface_small() {
    var return_value = "";
	
	if($("#tcpdump_interface_small").val() != "--")
		return_value = $("#tcpdump_interface_small").val() + " ";
	
	return return_value;
}

function tcpdump_timestamp() {
    var return_value = "";
	
	if($("#timestamp").val() != "--")
		return_value = $("#timestamp").val() + " ";
	
	return return_value;
}

function tcpdump_resolve() {
    var return_value = "";
	
	if($("#resolve").val() != "--")
		return_value = $("#resolve").val() + " ";
	
	return return_value;
}

function tcpdump_install(where) {
	$.ajax({
		type: "GET",
		data: "install&where=" + where,
		beforeSend: tcpdump_myajaxStart(),
		url: "/components/infusions/tcpdump/includes/actions.php",
		success: function(msg){
			$("#tcpdump_output").val(msg);
			
			tcpdump_myajaxStop('');
			
			draw_large_tile('tcpdump', 'infusions');
		}
	});
}