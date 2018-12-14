(function($){
	
	$('#TrialFormBtn').click(function(){
		
		$('#trialForm').validate({
			rules: {
				FirstName: {
					required: true
				},
				LastName: {
					required: true
				},
				Email: {
					required: true,
					email: true
				},
				Phone: {
					required: true
				},
				Title: {
					required: true
				},
				Company: {
					required: true
				},
			}
		});

	}); //Trial Form Submit Ends

})(jQuery);