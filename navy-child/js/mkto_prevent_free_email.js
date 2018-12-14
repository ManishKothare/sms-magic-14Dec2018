//This script prevents Marketo form submission if a user enters non-business email (Gmail, Hotmail, Yahoo, etc.)
//It will work on any page with a Marketo form, and runs when the form is ready 
//For further info, please see Marketo form documentation, http://developers.marketo.com/documentation/websites/forms-2-0/
//Prepared by Ian Taylor and Murtza Manzur on 9/9/2014
(function ($){
	var stylesheetdirpath=mktofreeemailcheck.stylesheetdir;
	var invalidDomains; //container variable to store free email provider domains
	$(document).ready(function() {
		$.ajax({
			type: 'GET',
			url: stylesheetdirpath+'/datafiles/freeemaildomains.txt',
			dataType: 'text',
			success: function(data){
				invalidDomains=data.split(/\r\n|\n/); //store data in container variable
			}
		});
	});
	MktoForms2.whenReady(function (form){
			//console.log(form.getId());
		form.onValidate(function(){
			var email = form.vals().Email;
			if(email){
				if(!isEmailGood(email)){
					form.submittable(false);
					var emailElem = form.getFormElem().find("#Email");
					form.showErrorMessage("Please enter a company email id.", emailElem);
					ga('send', 'event', 'MktoForm', 'Validation', 'FreeEmail', 1); //GA event send
				}else{
					form.submittable(true);
				}
			}
		});
	});
	function isEmailGood(email){
		for(var i=0; i < invalidDomains.length; i++){
			var domain = invalidDomains[i];
			if (email.indexOf(domain) != -1){
				return false;
			}
		}
		return true;
	}
})(jQuery);