function button_init() {
	$("#tabs ul").idTabs();
}

function button_update_message(data) {
  $("#button.refresh_text").html(data); 
}