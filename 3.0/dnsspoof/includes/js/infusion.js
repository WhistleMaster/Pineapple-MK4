var dnsspoof_auto_refresh;
var dnsspoof_showDots;
var dnsspoof_small_showDots;

var dnsspoof_showLoadingDots = function() {
    clearInterval(dnsspoof_showDots);
	if (!$("#dnsspoof_loadingDots").length>0) return false;
    dnsspoof_showDots = setInterval(function(){            
        var d = $("#dnsspoof_loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

var dnsspoof_small_showLoadingDots = function() {
    clearInterval(dnsspoof_small_showDots);
	if (!$("#dnsspoof_small_loadingDots").length>0) return false;
    dnsspoof_small_showDots = setInterval(function(){            
        var d = $("#dnsspoof_small_loadingDots");
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

function dnsspoof_myajaxStart()
{
	if(dnsspoof_auto_refresh == null)
	{
		$("#dnsspoof.refresh_text").html('<em>Loading<span id="dnsspoof_loadingDots"></span></em>'); 
		dnsspoof_showLoadingDots();
	
		$("#dnsspoof_small.refresh_text").html('<em>Loading<span id="dnsspoof_small_loadingDots"></span></em>'); 
		dnsspoof_small_showLoadingDots();
	}
}

function dnsspoof_myajaxStop(msg)
{
	if(dnsspoof_auto_refresh == null)
	{
		$("#dnsspoof.refresh_text").html(msg); 
		clearInterval(dnsspoof_showDots);
	
		$("#dnsspoof_small.refresh_text").html(msg); 
		clearInterval(dnsspoof_small_showDots);
	}
}

function dnsspoof_init_small() {
	
	dnsspoof_refresh_tile();
}

function dnsspoof_init() {

	dnsspoof_refresh();
	dnsspoof_refresh_history();
	
	$("#tabs ul").idTabs();
				
    $("#dnsspoof_auto_refresh").toggleClick(function() {
			$("#dnsspoof_auto_refresh").html('<font color="lime">On</font>');
			$('#auto_time').attr('disabled', 'disabled');
			
			dnsspoof_auto_refresh = setInterval(
			function ()
			{
				dnsspoof_refresh();
			},
			$("#auto_time").val());
		}, function() {
			$("#dnsspoof_auto_refresh").html('<font color="red">Off</font>');
			$('#auto_time').removeAttr('disabled');
				
            clearInterval(dnsspoof_auto_refresh);
			dnsspoof_auto_refresh = null;
		});	
}

function dnsspoof_toggle(action) {
	$.get('/components/infusions/dnsspoof/includes/actions.php?dnsspoof&'+action, function() { refresh_small('dnsspoof','infusions'); });

	if(action == 'stop') {
		$("#dnsspoof_link").html('<strong>Start</strong>');
		$("#dnsspoof_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#dnsspoof_link").attr("href", "javascript:dnsspoof_toggle('start');");
		$('dnsspoof_output').val('dnsspoof has been stopped...');	
				
		dnsspoof_refresh_history();
	}
	else {
		$("#dnsspoof_link").html('<strong>Stop</strong>');
		$("#dnsspoof_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#dnsspoof_link").attr("href", "javascript:dnsspoof_toggle('stop');");
		$('dnsspoof_output').val('dnsspoof is running...');
				
		dnsspoof_refresh_history();
	}
}

function dnsspoof_toggle_small(action) {
	$.get('/components/infusions/dnsspoof/includes/actions.php?dnsspoof&'+action);

	if(action == 'stop') {
		$("#dnsspoof_link_small").html('<strong>Start</strong>');
		$("#dnsspoof_small").html('<font color="red"><strong>disabled</strong></font>');
		$("#dnsspoof_link_small").attr("href", "javascript:dnsspoof_toggle('start');");
		$('dnsspoof_output_small').val('dnsspoof has been stopped...');	
	}
	else {
		$("#dnsspoof_link_small").html('<strong>Stop</strong>');
		$("#dnsspoof_small").html('<font color="lime"><strong>enabled</strong></font>');
		$("#dnsspoof_link_small").attr("href", "javascript:dnsspoof_toggle('stop');");
		$('dnsspoof_output_small').val('dnsspoof is running...');
	}
}

function dnsspoof_refresh() {
	$.ajax({
		type: "GET",
		data: "lastlog",
		beforeSend: dnsspoof_myajaxStart(),
		url: "/components/infusions/dnsspoof/includes/data.php",
		success: function(msg){
			$("#dnsspoof_output").val(msg).scrollTop($("#dnsspoof_output")[0].scrollHeight - $("#dnsspoof_output").height());
			
			dnsspoof_myajaxStop('');
		}
	});
}

function dnsspoof_refresh_history() {
	$.ajax({
		type: "GET",
		data: "history",
		beforeSend: dnsspoof_myajaxStart(),
		url: "/components/infusions/dnsspoof/includes/data.php",
		success: function(msg){
			$("#dnsspoof_content_history").html(msg);
			dnsspoof_myajaxStop('');
		}
	});
}

function dnsspoof_showTab()
{
	$("#Output").show(); 
	$("#History").hide();
	$("#History_link").removeClass("selected"); 
	$("#Output_link").addClass("selected");
}

function dnsspoof_load_file(what) {
	$.ajax({
		type: "GET",
		data: "load&file=" + what,
		beforeSend: dnsspoof_myajaxStart(),
		url: "/components/infusions/dnsspoof/includes/actions.php",
		success: function(msg){
			$("#dnsspoof_output").val(msg);
			dnsspoof_showTab();		
			dnsspoof_myajaxStop('');
		}
	});
}

function dnsspoof_delete_file(what, which) {
	$.ajax({
		type: "GET",
		data: "delete&file=" + which + "&" + what,
		beforeSend: dnsspoof_myajaxStart(),
		url: "/components/infusions/dnsspoof/includes/actions.php",
		success: function(msg){
			$("#dnsspoof_content_history").html(msg);
			dnsspoof_myajaxStop('');
			dnsspoof_refresh_history();
		}
	});
}

function dnsspoof_fake_toggle(action) {
	$.get('/components/infusions/dnsspoof/includes/actions.php?fake', {action: action});
	
	if(action == 'install'){
		$('#fake_link').html('<strong>Uninstall</strong>');
		$('#fake_status').html('<font color="lime"><strong>installed</strong></font>');
		$('#fake_link').attr("href", "javascript:dnsspoof_fake_toggle('uninstall');");
	}
	else{
		$('#fake_link').html('<strong>Install</strong>');
		$('#fake_status').html('<font color="red"><strong>not installed</strong></font>');
		$('#fake_link').attr("href", "javascript:dnsspoof_fake_toggle('install');");
	}
}

function dnsspoof_boot_toggle(action) {	
	$.get('/components/infusions/dnsspoof/includes/actions.php?boot', {action: action});
	
	if(action == 'disable') {
		$("#dnsspoof_boot_link").html('<strong>Enable</strong>');
		$("#dnsspoof_boot_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#dnsspoof_boot_link").attr("href", "javascript:dnsspoof_boot_toggle('enable');");
	}
	else {
		$("#dnsspoof_boot_link").html('<strong>Disable</strong>');
		$("#dnsspoof_boot_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#dnsspoof_boot_link").attr("href", "javascript:dnsspoof_boot_toggle('disable');");
	}
}

function dnsspoof_update_conf(data, what) {
	$.ajax({
		type: "POST",
		data: "set_conf="+what+"&newdata="+data,
		beforeSend: dnsspoof_myajaxStart(),
		url: "/components/infusions/dnsspoof/includes/conf.php",
		success: function(msg){
			dnsspoof_myajaxStop(msg);
		}
	});
}

function dnsspoof_refresh_tile() {
	$.ajax({
		type: "GET",
		data: "lastlog",
		beforeSend: dnsspoof_myajaxStart(),
		url: "/components/infusions/dnsspoof/includes/data.php",
		success: function(msg){
			dnsspoof_myajaxStop('');
			$("#dnsspoof_output_small").val(msg).scrollTop($("#dnsspoof_output_small")[0].scrollHeight - $("#dnsspoof_output_small").height());
		}
	});
}