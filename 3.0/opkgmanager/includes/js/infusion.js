var opkgmanager_auto_refresh;
var opkgmanager_showDots;
var opkgmanager_small_showDots;

var opkgmanager_showLoadingDots = function() {
    clearInterval(opkgmanager_showDots);
	if (!$("#opkgmanager_loadingDots").length>0) return false;
    opkgmanager_showDots = setInterval(function(){            
        var d = $("#opkgmanager_loadingDots");
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

function opkgmanager_myajaxStart()
{
	if(opkgmanager_auto_refresh == null)
	{
		$("#opkgmanager.refresh_text").html('<em>Loading<span id="opkgmanager_loadingDots"></span></em>'); 
		opkgmanager_showLoadingDots();
	}
}

function opkgmanager_myajaxStop(msg)
{
	if(opkgmanager_auto_refresh == null)
	{
		$("#opkgmanager.refresh_text").html(msg); 
		clearInterval(opkgmanager_showDots);
	}
}

function opkgmanager_init() {
	
	opkgmanager_refresh();
}

function opkgmanager_update() {
	$("#pack").html('...');
	$.ajax({
		type: "POST",
		data: "update=1",
		beforeSend: opkgmanager_myajaxStart(),
		url: "/components/infusions/opkgmanager/includes/actions.php",
		success: function(msg){
			$("#pack").html(msg);
			opkgmanager_myajaxStop('');
			
			refresh_small('opkgmanager','infusions');
		}
	});
}

function opkgmanager_refresh() {
	$.ajax({
		type: "POST",
		data: "refresh",
		beforeSend: opkgmanager_myajaxStart(),
		url: "/components/infusions/opkgmanager/includes/data.php",
		success: function(msg){
			$("#opkgmanager_content").html(msg);
			
			opkgmanager_myajaxStop('');
		}
	});
}

function opkgmanager_show_actions(package) {
	$.ajax({
		type: "POST",
		data: "show_actions_popup=1&package="+package,
		beforeSend: opkgmanager_myajaxStart(),
		url: "/components/infusions/opkgmanager/includes/actions.php",
		success: function(msg){
			
		    $('.popup_content').html(msg);
		    $('.popup').css('visibility', 'visible');
			
			opkgmanager_myajaxStop('');
		}
	});
}

function opkgmanager_perf_action(package, action) {
	$.ajax({
		type: "POST",
		data: "package="+package+"&action="+action,
		beforeSend: opkgmanager_myajaxStart(),
		url: "/components/infusions/opkgmanager/includes/actions.php",
		success: function(msg){
			
		    $('.popup_content').html(msg);
		    $('.popup').css('visibility', 'visible');
			
			opkgmanager_myajaxStop('');
		}
	});
}