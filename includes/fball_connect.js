// JavaScript Document
jQuery.noConflict();
(function($) { 
	$(function() {
		
	var fball_facebook_connect = function() {
			var facebook_auth = $('#fball_facebook_auth');
			var client_id = facebook_auth.find('input[type=hidden][name=client_id]').val();
			var redirect_uri = facebook_auth.find('input[type=hidden][name=redirect_uri]').val();

			if(client_id == "") {
				alert("You have not configure facebook api settings.")
			} else {
				window.open('https://graph.facebook.com/oauth/authorize?client_id=' + client_id + '&redirect_uri=' + redirect_uri + '&scope=email,user_birthday,user_hometown,user_location,user_work_history,user_website,publish_stream',
				'','scrollbars=no,menubar=no,height=400,width=800,resizable=yes,toolbar=no,status=no');
			}
		};

		$(".fball_login_facebook").click(function() {
			fball_facebook_connect();
		});
   });
})(jQuery);
