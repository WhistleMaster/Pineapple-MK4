var auto_refresh;
var showDots;

var showLoadingDots = function() {
    if (!$("#loadingDots").length>0) return false;
    showDots = setInterval(function(){            
        var d = $("#loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

function init() {
	
	refresh();
}

function update() {
	$("#pack").html('...');
	$.ajax({
		type: "POST",
		data: "update=1",
		url: "opkg_manager_actions.php",
		success: function(msg){
			$("#pack").html(msg)
		}
	});
}

function cache_status() {
	$("#cache").html('...');
	$.ajax({
		type: "POST",
		data: "cache_status=1",
		url: "opkg_manager_actions.php",
		success: function(msg){
			$("#cache").html(msg)
		}
	});
}

function update_cache() {
	$("#refresh_text").html('<em>Running<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "POST",
		data: "update_cache=1",
		url: "opkg_manager_actions.php",
		success: function(msg){
			$("#output").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
			cache_status();
			
			refresh();
		}
	});
}

function refresh() {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 

	showLoadingDots();

	$.ajax({
		type: "POST",
		data: "refresh",
		url: "opkg_manager_data.php",
		success: function(msg){
			$("#content").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function perf_action(package, action) {
	$("#refresh_text").html('<em>Running<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "POST",
		data: "package="+package+"&action="+action,
		url: "opkg_manager_actions.php",
		success: function(msg){
			$("#output").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}