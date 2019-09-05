(function ($) {
    $.fn.tab = function (fraction) {
    	var parent = $(this);
    	var tab_name = parent.attr("id");
    	var c=1;
    	$(this).find("> div").each(function(){
    		var counter = c;
    		$(this).attr("class","allied_tabs_build").hide();
    		$(this).attr("id",tab_name+"_tabContent_"+counter);
    		var heading = $(this).find("h1:first").hide().text();
    		parent.prepend("<span style='padding:10px;-moz-border-radius:5px;cursor:pointer;background-color:#013336;color:#fff;display:inline-block;margin-right:10px;' id='"+tab_name+"_tabBtn_"+counter+"'>"+heading+"</span>");
    		$("#"+tab_name+"_tabBtn_"+counter).bind("click",function(){
    			$(".allied_tabs_build").hide();
    			$("#"+tab_name+"_tabContent_"+counter).show();
    		});
    		c++;
    	});
    };
})(jQuery);
