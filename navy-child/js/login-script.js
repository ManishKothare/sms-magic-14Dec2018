(function($){
	var stylesheetdirpath=loginscript.stylesheetdir;
	var adminajax=loginscript.adminajax;	//console.log(adminajax);//#####
	var localized_nonce=loginscript.localizedajaxnonce;	//console.log(localized_nonce);//#####
	//##For login form - check valid login credentials via ajax submit
	$('#loginForm').validate({
		rules:{
			'username':{
				required:true,
				email: true
			},
			'password':'required'
		},
		submitHandler: function(form){
			var loginsubmitBtn=$(form).find('#loginFormBtn');
			var loginusername=$(form).find('#username').val();
			var loginpassword=$(form).find('#password').val();
			var dataJSON={'username':loginusername, 'password':loginpassword};
			var loginDataJSON=JSON.stringify(dataJSON);
			var ajax_progress_loader_img = stylesheetdirpath+'/img/progress.gif';
			$(loginsubmitBtn).parent().append('<div class="temp" style="display:table; margin:10px auto 0px auto;"><img src="'+ ajax_progress_loader_img +'" style="display:table; margin:0px auto; max-width:60%;"></div>');
			$.ajax({
				type:'POST',
				url:adminajax,
				data:{
					action:'login_credentials_check',
					security:localized_nonce,
					payload:loginDataJSON
				},
				success:function(data){
					var jsonObj=$.parseJSON(data);		
					if( (jsonObj.login==false) || (jsonObj.login=='false') ){
						var err=jsonObj.error_msg;
						if(err=='BAD-USERNAME'){
							var errmsg='<span class="badcredentials">Username does not exist</span>';
							$(errmsg).appendTo($('#username').parent()).delay(3000).fadeOut(1000, function(){$(this).remove();});
						}
						if(err=='WRONG-PASSWORD'){
							var errmsg='<span class="badcredentials">Wrong password</span>';
							$(errmsg).appendTo($('#password').parent()).delay(3000).fadeOut(1000, function(){$(this).remove();});
						}
						$('.temp').remove();
						return false;
					}
					if( (jsonObj.login==true) || (jsonObj.login=='true') ){
						$(loginsubmitBtn).prop('disabled',true);
						console.log('Credentials correct. Submit form.');//#####
						form.submit();
					}
				}
			});
			return false;
		}
	});
	//##For login form end
})(jQuery);