$(document).ready(function() {
	$(document).ajaxStart(function(){
        timer = setTimeout("$('#ajax-preloader').show()",1000);
	});
	$(document).ajaxStop(function(){
		clearTimeout(timer);
	    $('#ajax-preloader').hide();
	}); 
});
//action on video remove click
$('.broadcasting_main').on("click", ".videoremove", function(){
	entity = $(this).attr("value");
	broadcasting = $('#bctitle').text();
	$(".broadcasting_main").load("youtvfunctions.php", { entity: entity, action: "remove_video", broadcasting: broadcasting });
});
//action on video edit button click
$('.broadcasting_main').on("click", ".videoedit", function(){
	target = $(this).attr("value");
	value = $('#'+target+' a').attr('href');
	$(this).closest('.videoentry').load("youtvfunctions.php", { target: target, value: value, action: "edit_video_press" });
});
// action on video edit save button click
$('.broadcasting_main').on("click", ".videoentry .save", function(){
	value = "#text" + $(this).val();
	newurl = $(value).val();
	entity = $(this).val();
	broadcasting = $('#bctitle').text();
	$('.broadcasting_main').load("youtvfunctions.php", { newurl: newurl, entity: entity, action: "change_video", broadcasting: broadcasting});
});
// action on add video button click
$('.broadcasting_main').on("click", ".add", function(){
	target = $(this).val();
	$(this).closest('.videoadd').load("youtvfunctions.php", { target: target, action: "add_video" });
});
// action on add video save button click
$('.broadcasting_main').on("click", ".videoadd .save", function(){
	value = "#textadd" + $(this).val();
	newurl = $(value).val();
	entity = $(this).val();
	broadcasting = $('#bctitle').text();
	$('.broadcasting_main').load("youtvfunctions.php", { newurl: newurl, entity: entity, action: "add_video_save", broadcasting: broadcasting});
});
//action on move video up button click
$('.broadcasting_main').on("click", ".videoup", function(){
	entity = $(this).val();
	broadcasting = $('#bctitle').text();
	$('.broadcasting_main').load("youtvfunctions.php", { entity: entity, action: "moveup_video", broadcasting: broadcasting});
});
//action on move video down button click
$('.broadcasting_main').on("click", ".videodown", function(){
	entity = $(this).val();
	broadcasting = $('#bctitle').text();
	$('.broadcasting_main').load("youtvfunctions.php", { entity: entity, action: "movedown_video", broadcasting: broadcasting });
});
//action on settings button click
$('.broadcasting_main').on("click", ".bcsettings", function(){
	broadcasting = $(this).val();
	$('.broadcasting_main').load("youtvfunctions.php", { broadcasting: broadcasting, action: "load_bc_settings" }, function(){
		$('input:checked').prev('label').css("background-color" , "#555");
		$('.daysblock, #pre_loop').on("click", "label", function(){
			if ($(this).next('input').is(':checked')) {
				$(this).css("background-color" , "white");
			}
	 		else {
	 			$(this).css("background-color" , "#555");
			};
		});
		$('.daysblock, #pre_controls').on("click", "label", function(){
			if ($(this).next('input').is(':checked')) {
				$(this).css("background-color" , "white");
			}
	 		else {
	 			$(this).css("background-color" , "#555");
			};
		});
	});
});
// action on delete broadcasting button click
$('.broadcasting_main').on("click", ".bcdelete", function(){
	broadcasting = $(this).val();
	$('.broadcasting_main').load("youtvfunctions.php", { broadcasting: broadcasting, action: "delete_broadcasting" }, function(){
		$("[id='"+broadcasting+"']").remove();
	});
});
//action on import playlist button click
$('.broadcasting_main').on("click", ".bcimport", function(){
	broadcasting = $(this).val();
	$('.broadcasting_main').load("youtvfunctions.php", { broadcasting: broadcasting, action: "import_playlist" });
});
//action on save settings button click
$('.broadcasting_main').on("click", ".bcsavesettings", function(){
	value = $("#editbroadcasting").serializeArray();
	$('.broadcasting_main').load("youtvfunctions.php", value, function(){
		$('.broadcasting_list').load("youtvfunctions.php", {action: "add_broadcasting"});
	});
});
//action on import playlist save button click
$('.broadcasting_main').on("click", ".bcadd", function(){
	broadcasting = $(this).val();
	playlistsave = $('#playlist').val();
	$('.broadcasting_main').load("youtvfunctions.php", { broadcasting: broadcasting, action: "import_playlist_save", playlist: playlistsave });
});
//action on select broadcasting from list
$('.broadcasting_list').on("click", ".bcselector", function(){
	broadcasting = this.id;
	$('.broadcasting_main').load("youtvfunctions.php", { broadcasting: broadcasting, action: "select_broadcasting" });
});
$('.daysblock, #pre_loop').on("click", "label", function(){
	if ($(this).next('input').is(':checked')) {
		$(this).css("background-color" , "white");
	}
	 else {
	 	$(this).css("background-color" , "#555");
	};
});
$('.daysblock, #pre_controls').on("click", "label", function(){
	if ($(this).next('input').is(':checked')) {
		$(this).css("background-color" , "white");
	}
	 else {
	 	$(this).css("background-color" , "#555");
	};
});
// action on add broadcasting button click
$('.broadcasting_new').on("click", ".bcadd", function(){
	value = $("#addbroadcasting").serializeArray();
	$('.broadcasting_list').load("youtvfunctions.php", value);
});