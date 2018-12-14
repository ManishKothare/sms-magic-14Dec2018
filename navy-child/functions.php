<?php
add_action( 'wp_enqueue_scripts', 'navy_parent_theme_styles' );
function navy_parent_theme_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_script('smsmagicscript', get_stylesheet_directory_uri() . '/js/smsmagic-navy-script.js', array('jquery'), null, true );
    //wp_enqueue_script('mktofreeemailcheck', get_stylesheet_directory_uri() . '/js/mkto_prevent_free_email.js', array('jquery'), null, true );

}

//for enabling custom sidebar for custom post type nurture
function navy_additional_widgets_init() {
	register_sidebar(array(
		'name'			=>	esc_html__( 'Nurture Sidebar', 'navy'),
		'id'			=>	'sidebar-nurture',
		'description'	=>	esc_html__( 'Widgets in this area will be shown into the sidebar on nurture posts.', 'navy'),
		'before_widget' =>	'<div class="col-md-12 blog_widget">',
		'after_widget'	=>	'</div>',
		'before_title'	=>	'<h4>',
		'after_title'	=>	'</h4>',
	) );
}
add_action( 'widgets_init', 'navy_additional_widgets_init' );

//for resizing the image for nurture post sidebar
add_theme_support( 'post-thumbnails' );
function sidebar_image_thumb() {
   add_theme_support( 'post-thumbnails' );
   add_image_size( 'sidebar-thumb', 370, 166, true );
}
add_action( 'after_setup_theme', 'sidebar_image_thumb' );

//for adding body class 'single-post' for custom post type 'nurture'
function et_custom_body_classes( $classes ) {
	global $post, $current_user, $template;
	if ( is_singular() && ($post->post_type=='messaging') ) {
		$classes[] = 'single-post';
	}
	if ( is_archive() && ($post->post_type=='messaging') ) {
		$classes[] = 'blog';
	}
	return $classes;
}
add_filter( 'body_class', 'et_custom_body_classes' );

//for customizing output 'Custom Related Posts' plugin's on nurture post sidebar
//this function adds thumbnail image of post item in output
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'custom-related-posts/custom-related-posts.php' ) ) {
	$plugindirpath=plugin_dir_url( __FILE__ );
	function smsmagic_nurture_sidebar_crp_customization($listhtml, $post_id, $relation){
		$relation_id=$relation['id'];
		$relation_title=$relation['title'];
		$relation_link=$relation['permalink'];
		$relation_desc=get_post_meta($relation_id,'_aioseop_description', true);
		
		$listhtml='';
		$listhtml='<li>';
		if( has_post_thumbnail($relation_id) ){
			$thumb_image=get_the_post_thumbnail($relation_id, 'sidebar-thumb', array( 'alt' ));
			$listhtml.='<div class="crp_item_thumb">'.$thumb_image.'</div>';
		}
		$listhtml.='<div class="crp_item_title"><h6><a href="'.$relation_link.'">'.$relation_title.'</a></h6></div>';
		$listhtml.='<div class="crp_item_desc"><p>'.$relation_desc.'</p></div>';
		$listhtml.='<div class="clearfix"></div>';
		$listhtml.='</li>';
		
		return $listhtml;
	}
	add_filter('crp_output_list_item', 'smsmagic_nurture_sidebar_crp_customization', 10, 3);
}

//For login functionality via web form and Salesforce OAuth
include_once(get_stylesheet_directory().'/includes/sms-magic-login-process.php');

//For AppExchange Leads form submit
if( !function_exists('smsmagic_appex_leads') ){
	function smsmagic_appex_leads(){
		check_ajax_referer( 'smsmagic-appex-leads', 'security' );
		if( !empty($_POST) ){
			global $wpdb;
			$result = array();
			$appex_leads_table=$wpdb->prefix.'appexchange_leads';
			/*collect post data*/
			$fname = $_POST['FirstName'];
			$lname = $_POST['LastName'];
			$email = $_POST['Email'];
			$mobile = $_POST['MobilePhone'];
			$title = $_POST['Title'];
			$jobfunction = $_POST['JobFunction'];
			$company = $_POST['Company'];
			$numofemployees = $_POST['NumberOfEmployees'];
			$country = $_POST['Country'];
			$state = $_POST['State'];
			$usecase = $_POST['UseCase'];
			$termstime = $_POST['TermsAcceptance'];
			$consent = $_POST['EmailConsent'];
			$consenttime = $_POST['EmailConsentTime'];
			$createdtime = $_POST['CreatedTime'];
			$mktosubmitted = $_POST['MktoSubmitted'];
			//create fields array for insert query
			$fields_data = array(
				'fname' => $fname,
				'lname' => $lname,
				'email' => $email,
				'mobile' => $mobile,
				'title' => $title,
				'jobfunction' => $jobfunction,
				'company' => $company,
				'numofemployees' => $numofemployees,
				'country' => $country,
				'state' => $state,
				'usecase' => $usecase,
				'termstime' => $termstime,
				'consent' => $consent,
				'consenttime' => $consenttime,
				'createdtime' => $createdtime,
				'mktosubmitted' => $mktosubmitted,
			);
			//insert data into db
			$insert_query=$wpdb->insert($appex_leads_table,$fields_data);

			if($insert_query===false){
				$result['dbresult'] = 'Error';
			}else{
				$result['dbresult'] = 'Success';
			}
		}
		die(json_encode($result));
	}
	add_action('wp_ajax_smsmagic_appex_leads', 'smsmagic_appex_leads');
	add_action('wp_ajax_nopriv_smsmagic_appex_leads', 'smsmagic_appex_leads');
}

//For posting AppExchange Leads to Marketo
if( !function_exists('appexleads_to_marketo') ){
	function appexleads_to_marketo(){
		global $wpdb;
		$appex_leads_table = $wpdb->prefix.'appexchange_leads';
		$appexleads = $wpdb->get_results( 
			"SELECT `id`, `fname`, `lname`, `email`, `mobile`, `title`, `jobfunction`, `company`, `numofemployees`, `country`, `state`, `usecase`, `termstime`, `consent`, `consenttime`, `createdtime`
			 FROM $appex_leads_table WHERE mktosubmitted=0" 
		);
		if(count($appexleads)>0){
			$leadsInput = array();
			$leadIds = array();
			foreach($appexleads as $key => $row){
				$createdTimeStamp = strtotime($row['createdtime']);
				$now = gmmktime();
				//record's creation time is older than 10 min. 600 = 10 x 60 sec | for testing use 300 (5 min)
				if( ($now - $createdTimeStamp) >= 300 ){
					$lead = array(
						"FirstName"=>$row['fname'],
						"LastName"=>$row['lname'],
						"Email"=>$row['email'],
						"MobilePhone"=>$row['mobile'],
						"Title"=>$row['title'],
						"Job_Function__c"=>$row['jobfunction'],
						"Company"=>$row['company'],
						"NumberOfEmployees"=>$row['numofemployees'],
						"Country"=>$row['country'],
						"State"=>$row['state'],
						"Trial_Org_Usecase__c"=>$row['usecase'],
						"Terms_Acceptance__c"=>$row['termstime'],
						"Email_Consent__c"=>$row['consent'],
						"Email_Consent_Timestamp__c"=>$row['consenttime']
					);
					array_push($leadsInput,$lead);
					array_push($leadIds,$row['id']);
				}//created time comparison check ends
			}//foreach loop ends
			//create array of qualified leads data
			$responsebodyarray = array (
				"action"=>"createOrUpdate",
				"asyncProcessing"=>false,
				"input"=>$leadsInput,
				"lookupField"=>"Email"
			);
			$leadsData = json_encode($responsebodyarray);
			//post it to mkto (cURL)
			$mktoAccessToken = get_mkto_access_token();
			$mkto_rest_ep='https://707-UFB-065.mktorest.com/rest/v1/leads.json?access_token='.$mktoAccessToken;
			$mkto_curl_headers=array(
				'Content-type: application/json',
				'Accept: application/json'
			);
			$mkto_curl=curl_init($mkto_rest_ep);
			curl_setopt($mkto_curl, CURLOPT_HEADER, false);
			curl_setopt($mkto_curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($mkto_curl, CURLOPT_HTTPHEADER, $mkto_curl_headers);
			curl_setopt($mkto_curl, CURLOPT_POST, true);
			curl_setopt($mkto_curl, CURLOPT_POSTFIELDS, $leadsData);
			curl_setopt($mkto_curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($mkto_curl, CURLOPT_SSL_VERIFYHOST, false);
			$json_response = curl_exec($mkto_curl);
			$status=curl_getinfo($mkto_curl, CURLINFO_HTTP_CODE);
			if ($status !== 200 ){
				$error_message=json_decode($json_response, true);
			}
			curl_close($mkto_curl);
			//cURL end
			$response = json_decode($json_response, true);
			$requestId = $response['requestId'];
			$result = $response['result'];
			$success = $response['success'];
			if($success==true){
				//when post to mkto is success
				foreach($result as $key=>$row){
					if($row['status'] == 'updated'){
						$ltableid=$leadIds[$key];
						$mkto_result = $wpdb->update($appex_leads_table, array(`mktosubmitted`=>1), array(`id`=>$ltableid) );
					}
				}
				
				$message='<p>Cron processed leads';
				foreach($leadIds as $leadkey=>$leadid){
					$message.='Lead table id: '.$leadid.'<br/>';
				}
				$message.='</p>';
			}else{
				$message='<p>Cron result';
				$message.=$result['reasons']['code'];
				$message.=$result['reasons']['message'];
				$message.='</p>';
			}
			$to=array('kundan@screen-magic.com');
			$subject='Wordpress CronJob Run Result';
			$headers=array('Content-Type: text/html; charset=UTF-8');
			$headers[] = 'From: Kundan Shukla <kundan@sms-magic.com>';
			wp_mail( $to, $subject, $message, $headers, $attachments );
		}
	}
	add_action('appexleads_to_marketo', 'appexleads_to_marketo');
}

//function to generate, store and retrive marketo access token
if( !function_exists('get_mkto_access_token') ){
	function get_mkto_access_token(){
		global $wpdb;
		$mkto_token_table = $wpdb->prefix.'mkto_access_token';
		$ClientId = 'f790edc3-cd0e-4a73-8344-e36ead06ae1d';
		$ClientSecret = 'FFbvr8NU7IPba4labbiQAKn85lBogAj5';
		$identiryURL = 'https://707-UFB-065.mktorest.com/identity/oauth/token?grant_type=client_credentials&client_id='.$ClientId.'&client_secret='.$ClientSecret;
		$args = array(  
            'headers' => array(  
                'Accept' => 'application/json',  
                'Content-Type' => 'application/json'  
            )
		);
		$getmktotoken = $wpdb->get_row( "SELECT `mktoaccesstoken`, `mktoexpiresin` FROM $mkto_token_table WHERE `mktotokentime`>(gmtime()-3600)" );
		if(count($getmktotoken)>0){
			return $getmktotoken['mktoaccesstoken'];
		}else{
			$mkto_response_array=json_decode( wp_remote_retrieve_body( wp_remote_get($identiryURL,$args) ), true );
			$mkto_access_token = $mkto_response_array['access_token'];
			$mkto_expires_in = $mkto_response_array['expires_in'];
			$mkto_token_time = gmtime();
			$fields_data = array(
				'mktoaccesstoken' => $mkto_access_token,
				'mktoexpiresin' => $mkto_expires_in,
				'mktotokentime' => $mkto_token_time,
			);
			$insert_token=$wpdb->insert($mkto_token_table,$fields_data);
			return $mkto_access_token;
		}
	}
}

?>