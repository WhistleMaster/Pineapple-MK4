var status_showDots;
var status_small_showDots;

var status_showLoadingDots = function() {
    clearInterval(status_showDots);
	if (!$("#status_loadingDots").length>0) return false;
    status_showDots = setInterval(function(){            
        var d = $("#status_loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

var status_small_showLoadingDots = function() {
    clearInterval(status_small_showDots);
	if (!$("#status_small_loadingDots").length>0) return false;
    status_small_showDots = setInterval(function(){            
        var d = $("#status_small_loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

function status_myajaxStart()
{
	$("#status.refresh_text").html('<em>Loading<span id="status_loadingDots"></span></em>'); 
	status_showLoadingDots();
	
	$("#status_small.refresh_text").html('<em>Loading<span id="status_small_loadingDots"></span></em>'); 
	status_small_showLoadingDots();
}

function status_myajaxStop(msg)
{
	$("#status.refresh_text").html(msg); 
	clearInterval(status_showDots);
	
	$("#status_small.refresh_text").html(msg); 
	clearInterval(status_small_showDots);
}

function status_init_small() {

	status_refresh_tile();
}

function status_init() {

	status_refresh();
}

function status_refresh() {
	$.ajax({
		type: "POST",
		beforeSend: status_myajaxStart(),
		url: "/components/infusions/status/includes/data.php",
		success: function(msg){
			$("#status_content").html(msg);
			status_myajaxStop('');
		}
	});
}

function status_getOUIFromMAC(mac) {

	var top = 30;
	var left = Math.floor(screen.availWidth * .66) - 10;
	var width = 700
	var height = 400
	var tab = new Array();

	tab = mac.split(mac.substr(2,1));

	var win = window.open("http://standards.ieee.org/cgi-bin/ouisearch?" + tab[0] + '-' + tab[1] + '-' + tab[2], 'OUI From MAC', 'width=' + width + ',height=' + height + ",resizable=yes,scrollbars=yes,statusbar=no");
	win.focus();
}

function status_graph(what) {
    $.get('/components/infusions/status/includes/graph.php', {w: what}, function(data){
	    $('.popup_content').html(data);
	    $('.popup').css('visibility', 'visible');
    });
}

function status_execute(what) {
    $.get('/components/infusions/status/includes/execute.php', {cmd: what}, function(data){
	    $('.popup_content').html(data);
	    $('.popup').css('visibility', 'visible');
    });
}

function status_refresh_tile() {
	$.ajax({
		type: "GET",
		data: "interface",
		beforeSend: status_myajaxStart(),
		url: "/components/infusions/status/includes/data_small.php",
		success: function(msg){
			$("#status_content_small").html(msg);
			status_myajaxStop('');
		}
	});
}