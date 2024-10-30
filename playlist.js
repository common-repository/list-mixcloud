jQuery(function ($) {
	console.log("Plugin List Mixcloud : Do you want help to translate plugin, share an improvement or issue ? Go to https://wordpress.org/plugins/list-mixcloud/");
	$ = jQuery;
	dom = $("#uniqueWidget");
	if(dom.length) {
		srcRoot = $("#uniqueWidget").attr('src').split("&feed=")[0];
		li = $('#plList li').on('click', function () {
				widget = Mixcloud.PlayerWidget(document.getElementById("uniqueWidget"));
				var id = $(this).find(".plItem>.plTitle").attr('idtrack');
				widget.ready.then(function() {
					widget.pause();
					widget.load(decodeURI(id),true).then(function() {
						//widget.ready.then(function() {
							console.log(decodeURI(id));
							widget.getCurrentKey().then(function(key) {srcRoot = $("#uniqueWidget").attr('src').split("&feed=")[0]; dom.attr('src',srcRoot + "&feed="+id); console.log(key);});
							widget.play();
						//});
					});
					$('.plSel').removeClass('plSel');
					$(this).addClass('plSel');
				});
				
			});
	}
});