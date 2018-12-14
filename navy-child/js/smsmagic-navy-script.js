(function($){
	$(document).ready(function(){
		if( currentPageSlug()=='use-cases' ){
			$('.casestudyWrapper a').click(function(){
				var usecaseDocPath=$(this).attr('href');
				var industryHeading=$(this).closest('.wpb_text_column').prev().find('h2').text();
				var industryName = 'UseCase-'+( industryHeading.replace(' ','-') );
				var usecaseName = usecaseDocPath.substring( (usecaseDocPath.lastIndexOf("/")+1), usecaseDocPath.lastIndexOf(".") );
				console.log('UseCase-Download'+' | '+industryName+' | '+'UseCase_'+usecaseName);
				//ga('send', 'event', 'UseCase-Download', industryName, 'UseCase_'+usecaseName, 1);
			});
		}
		if( $('.vc_btn3-container a').length ){
			$('.vc_btn3-container a').click(function(){
				console.log( $(this).text() );
			});
		}
	});
	function currentPageSlug(){
		var currentURL = $(location).attr('href');
		var pagePath = currentURL.split('/').splice(-2);
		var pageSlug = pagePath[0];
		return pageSlug;
	}
	/*
	function checkIfAnalyticsLoaded(){
		if(window._gaq && window._gaq._getTracker){
			//Do tracking with new-style analytics
			console.log(window._gaq._getTracker);
		}else if(window.urchinTracker){
			//Do tracking with old-style analytics
			console.log(window.urchinTracker);
		}else{
			//Retry. Probably want to cap the total number of times you call this.
			setTimeout(checkIfAnalyticsLoaded(), 500);
		}
	}
	*/
})(jQuery);