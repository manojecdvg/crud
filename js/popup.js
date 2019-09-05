//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupStatus = 0;

//loading popup with jQuery magic!
function loadPopup() {
	var popupHeight = jQuery("#popupContact").height();
	//loads popup only if it is disabled
	if(popupStatus==0) {
		jQuery("#backgroundPopup").css({
			"opacity": "0.7"
		});
		jQuery("#popupContact").css({
			"height" : popupHeight,
		});
		jQuery("#backgroundPopup").fadeIn("slow");
		jQuery("#popupContact").fadeIn("slow");
		popupStatus = 1;
	}
}

//disabling popup with jQuery magic!
function disablePopup() {
	//disables popup only if it is enabled
	if(popupStatus==1){
		jQuery("#backgroundPopup").fadeOut("slow");
		jQuery("#popupContact").fadeOut("slow");
		popupStatus = 0;
	}
}

//centering popup
function centerPopup() {
	//request data for centering
	var windowWidth = document.documentElement.offsetWidth;
	var windowHeight = document.documentElement.offsetHeight + window.scrollY;
	var popupHeight = jQuery("#popupContact").height();
	var popupWidth = jQuery("#popupContact").width();
	//centering
	jQuery("#popupContact").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	jQuery("#backgroundPopup").css({
		"height": windowHeight
	});
}