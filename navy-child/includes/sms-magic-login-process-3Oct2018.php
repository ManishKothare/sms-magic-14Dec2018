<?php

/* Enqueue scripts and styles for the front end. */
function navy_child_login_dependencies(){
	wp_enqueue_style('login-styles', get_stylesheet_directory_uri().'/css/login-styles.css', '', null, 'all');
	wp_enqueue_script('jquery-validate', get_stylesheet_directory_uri().'/js/jquery.validate.min.js', array('jquery'), '1.17.0', true );
	wp_enqueue_script('loginscript', get_stylesheet_directory_uri() . '/js/login-script.js', array('jquery'), '1.0.0', true );
	wp_localize_script('loginscript', 'loginscript', 
		array(
			'adminajax' => admin_url( 'admin-ajax.php' ),
			'stylesheetdir' => get_stylesheet_directory_uri(),
			'localizedajaxnonce' => wp_create_nonce('smsmagic_nonce_localized'),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'navy_child_login_dependencies' );

//Initiate sesssion
if (!function_exists('initiate_session')) {
	function initiate_session(){
		if( !isset($_SESSION) || session_id() == '' || !session_id() ){
			session_start(); //echo( session_id() );
		}
		$expireSessionAfter = 60;
		if( isset($_SESSION['loggedin_time']) ){
			//check how much time has passed since user has loggedin
			$timeSinceLogin = time() - $_SESSION['loggedin_time'];
			$expireySeconds = $expireSessionAfter * 60;
			//Check if time since loggedin is more than expiry time
			if($timeSinceLogin >= $expireySeconds){
				if( isset($_SESSION['LoggedIn']) && $_SESSION['LoggedIn']==TRUE ){
					$_SESSION['LoggedIn']=FALSE;
				}
				if( isset($_SESSION['access_token']) && $_SESSION['access_token']!==NULL ){
					$_SESSION['access_token']=NULL;
				}
			}
		}
	}
}
add_action('init', 'initiate_session', 1);

//Custom function to get current server name
if (!function_exists('currentServer')) {
	function currentServer(){
		return $_SERVER['SERVER_NAME'];
	}
}

//For creating coockies
if( !function_exists('smsmagic_create_cookie') ){
	function smsmagic_create_cookie($cookieName,$cookieValue){
		$expiry=time()+7140; //1:59 min
		$deletetime=time()-7200; //2 hrs before
		$currentdomain=currentServer();
		if(!isset($_COOKIE[$cookieName])){
			$cookiecreatesuccess=setcookie( $cookieName, $cookieValue, $expiry, '/', $currentdomain, TRUE );
		}else{
			unset($_COOKIE[$cookieName]);
			if(true===setcookie($cookieName, '', $deletetime, '/', $currentdomain, TRUE)){
				$cookieupdatesuccess=setcookie( $cookieName, $cookieValue, $expiry, '/', $currentdomain, TRUE );
			}
			$cookieupdatesuccess=setcookie( $cookieName, $cookieValue, $expiry, '/', $currentdomain, TRUE );
		}
		$result = (FALSE===$cookiecreatesuccess && (FALSE===$cookieupdatesuccess || NULL===$cookieupdatesuccess)) ? FALSE : TRUE;
		return $result;
	}
}

//For destroying coockies
if( !function_exists('smsmagic_destroy_cookie') ){
	function smsmagic_destroy_cookie($cookieName){
		$currentdomain=currentServer();
		unset($_COOKIE[$cookieName]);
		setcookie($cookieName, '', time()-7200, '/', $currentdomain, TRUE);
	}
}

$currentdomain=currentServer();
if( (currentServer()=='localhost') || (FALSE!==strpos(currentServer(), 'launch')) ){
	//this is localhost or staging/development website
	$api_server='app.txtbox.in';
	$sf_client_id = '3MVG9ZL0ppGP5UrB_BcrBsdC01G3PDZXCun.bNaD.kYkENMvz4U3B7YKpY0e9S1n51phTGbzqieGcir7i4EbN'; //STAGING instance
}else{
	//this is live website
	$api_server='app.sms-magic.com';
	$sf_client_id = '3MVG9CEn_O3jvv0wZKoKHoEQZrbJ4jL_8nmY0JHKOhSy8iK1RrWGG.SwPdQ3G8WNnIUsIFtJACvcTI_WE0m3q'; //LIVE instance
}
/*# SMS-Magic Shortcode for rendering Login Form #*/
if (!function_exists('smsmagic_login_form')){
	function smsmagic_login_form($atts){
		global $api_server, $sf_client_id;
		$wp_admin_post_url=esc_url( admin_url('admin-post.php') );
		$output_html='';
		
		$default_args = array(
            'intent' => NULL,
			'message' => NULL,
			'cpblock' => NULL,
			'sfloginbtn' => NULL,
			'country' => 'GLOBAL'
        );
		//possible values for intent argument are: webcheckout | webrecharge | resourceaccess
		//webcheckout - to used when rendering login form on web checkout page
		//webrecharge - to used when rendering login form on recharge page
		//resourceaccess - to be used when rendering login form on support or resource section pages where content is login protected
		//cpblock - values to be (show/hide) this is identify whether to show or not the core pack includes values below login block. like: user count, phones count and credit count
		//this is required in popup block on pricing page
		
		$atts=shortcode_atts($default_args, $atts, 'smsmagic_show_login_form');
		
		if( isset($atts['intent']) && ($atts['intent']!==null) && (strlen($atts['intent'])>0) ){
			$intent_identifier='<input id="login_intent" type="hidden" name="intent" value="'.$atts['intent'].'">';
			$intent=$atts['intent'];
		}else{
			$intent_identifier=NULL;
			$intent=NULL;
		}
		if( isset($atts['message']) && (strlen($atts['message'])>0) ){
			switch($atts['message']){
				case 'error':
					$retry_message='Security Error. Please try again.';
					break;
				case 'BAD-USERNAME';
					$retry_message='Username doest not exist.';
					break;
				case 'WRONG-PASSWORD';
					$retry_message='Wrong Password.';
					break;
				default:
					$retry_message=NULL;
			}
		}
		
		$output_html.='<div class="loginblock">';
			if($retry_message!==NULL){
				$output_html.='<p style="color:red;text-align:center;font-size:90%;margin:5px auto 5px auto;">'.$retry_message.'</p>';
			}
			$output_html.='<h3>Login</h3>';
			$output_html.='<p class="signupline">Don\'t have an account? <a href="https://'.$api_server.'/app/#/signup" target="_blank">Sign Up</a></p>';
			$output_html.='<div class="form_wrapper">';
				$output_html.='<form action="'.$wp_admin_post_url.'" method="POST" id="loginForm" name="loginForm">';
					$output_html.=$intent_identifier;
					$output_html.=wp_nonce_field( '_wp_nounce_customer_app_login', 'login', true, true );
					$output_html.='<input id="pre_referrer" type="hidden" name="pre_referrer" value="'.$_SERVER["HTTP_REFERER"].'">';
					$output_html.='<input id="formaction" type="hidden" name="action" value="customer_app_login">';
					$output_html.='<div class="form-group">';
						$output_html.='<input type="email" class="form-control" id="username" name="username" placeholder="Email" required>';
					$output_html.='</div>';
					$output_html.='<div class="form-group">';
						$output_html.='<input type="password" class="form-control" id="password" name="password" placeholder="Password" required>';
					$output_html.='</div>';
					$output_html.='<input id="loginFormBtn" type="submit" name="submit_portallogin" value="Login" class="login_form_button portallogin" />';
					//$output_html.='<input id="loginFormBtn" type="button" name="submit" value="Login" class="login_form_button portallogin" />';
				$output_html.='</form>';
				$output_html.='<p class="passwordline"><a href="https://'.$api_server.'/app/#/forgot-password" target="_blank">Forgot Password?</a></p>';
			$output_html.='</div>';
			if( isset($atts['sfloginbtn']) && $atts['sfloginbtn']=='show' ){
				$output_html.='<div class="sf_btn_wrapper">';
					$output_html.='<input id="LoginWithSF" type="button" name="submit" value="Login with Salesforce" class="login_form_button sflogin" />';
				$output_html.='</div>';
			}
		$output_html.='</div>';
		//enable output block for core pack. this is used on pricing landing page
		$globalflagbg='style="background-image:url(\''.get_stylesheet_directory_uri().'/img/flags/Flag-GLOBAL.png\');"';
		if( isset($atts['cpblock']) && $atts['cpblock']=='show' ){		
			$output_html.='<div class="loginform_cpblock">';
				$output_html.='<div class="cpblock_wrapper">';
					$output_html.='<div class="cpblock_title" '.$globalflagbg.'>Summary <span class="totalcost">$1000</span></div>';
					$output_html.='<div class="cpblock_table_wrapper">';
						$output_html.='<div class="cpblock_table">';
							$output_html.='<div class="cpblock_tablecol cpblock_users"><p><strong>5</strong> Users</p></div>';
							$output_html.='<div class="cpblock_tablecol cpblock_phones"><p><strong>0</strong> Phone Lines</p></div>';
							$output_html.='<div class="cpblock_tablecol cpblock_credits"><p><strong>$180</strong> Credits</p></div>';
						$output_html.='</div>';
							$output_html.=$_COOKIE['corepack_country'];
					$output_html.='</div>';
				$output_html.='</div>';
			$output_html.='</div>';
		}
		//output javascript for enabling login with salesforce functionality
		$output_html.='<script type="text/javascript">';
			$output_html.='jQuery(document).ready(function($){';
				$output_html.='$("#LoginWithSF").click(function(){';
					if( NULL!==$intent && ($intent=='webcheckout' || $intent=='webrecharge' || $intent=='resourceaccess') ){
						//add a javascript line to set cookie with value as current page where the form is rendered.
						$exptime=time()+7200;
						$output_html.='document.cookie = "original_page='.$_SERVER["REQUEST_URI"].'; expires='.$exptime.'; path=/";';
					}else{
						$exptime=time()+7200;
						$output_html.='document.cookie = "original_page='.$_SERVER["HTTP_REFERER"].'; expires='.$exptime.'; path=/";';
					}
					$output_html.='var SALESFORCE_AUTHORIZE_URL = "https://login.salesforce.com/services/oauth2/authorize";';
					$output_html.='var CLIENT_ID = "'.$sf_client_id.'";';
					if(NULL==$intent){
						$output_html.='var REDIRECT_URI = "'.$wp_admin_post_url.'?action=sfor&";';
					}else{
						$output_html.='var REDIRECT_URI = "'.$wp_admin_post_url.'?action=sfor&intent='.$intent.'&";';
					}
					$output_html.='var RS_TYPE = "response_type=code";';
					$output_html.='var uri_paras = RS_TYPE+"&client_id="+CLIENT_ID+"&redirect_uri="+encodeURIComponent(REDIRECT_URI);';
					$output_html.='var complete_redirect_uri = SALESFORCE_AUTHORIZE_URL +"?"+ uri_paras;';
					$output_html.='window.location.replace(complete_redirect_uri);';
				$output_html.='});';
			$output_html.='});';
		$output_html.='</script>';
		
		if( (!isset($_SESSION['LoggedIn'])) || (isset($_SESSION['LoggedIn']) && $_SESSION['LoggedIn']==FALSE) ){
			return $output_html;
		}else{
			$logoutBtn.='<div class="vc_btn3-container vc_btn3-center"><a class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-classic vc_btn3-color-turquoise" href="'.get_stylesheet_directory_uri().'/includes/logout.php">Logout</a></div>';
			return $logoutBtn;
		}
	}
	add_shortcode('smsmagic_show_login_form', 'smsmagic_login_form');
	//useage as follows
	//[smsmagic_show_login_form intent="web_checkout" message="'.$_GET['retry'].'"] | $_GET['retry'] is content from query string
}

//For handling Salesfoce OAuth Response
if( !function_exists('salesforce_oauth_response') ){
	function salesforce_oauth_response(){
		global $api_server;
		$intent=null;
		$social_signup_url='https://'.$api_server.'/api/v2/social/signup';
		$state=urlencode('orgType=production&provider=salesforce_production&redirectUrl=landing_page&redirectionType=signin');
		
		if( isset($_GET) ){//check for the GET values exit
			
			if( isset($_GET['action']) && $_GET['action']=='sfor' ){
				//get response code from Salesforce
				if( isset($_GET['code']) && ($_GET['code']!=='' || $_GET['code']!==null) ){
					$code=$_GET['code'];
				}
				//get intent value
				if( isset($_GET['intent']) && ($_GET['intent']!==null) && (strlen($_GET['intent'])>0) ){
					$intent=$_GET['intent'];
				}
				//create social signup URL with received code from Salesforce and state parameters
				$smsmagic_login_url=$social_signup_url.'?code='.$code.'&state='.$state;
				
				$social_signup_curl = curl_init();
				curl_setopt_array($social_signup_curl, array(
					CURLOPT_URL => $smsmagic_login_url,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'GET',
					CURLOPT_HTTPHEADER => array('cache-control: no-cache'),
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_SSL_VERIFYHOST => false
				));
				$social_signup_json_response = curl_exec($social_signup_curl);
				$err = curl_error($social_signup_curl);
				curl_close($social_signup_curl);
				if ($err) {
					/*"cURL Error #:" . $err;
					if( isset($_COOKIE['original_page']) ){
						$redirect_on_error = $_COOKIE['original_page'];
					}else{
						$redirect_on_error = get_site_url();
					}
					wp_safe_redirect( $redirect_on_error );
					exit;*/
				} else {
					$sf_response=json_decode($social_signup_json_response, true);
					//if response contains 'message' instead of access_token (which means error)
					if( array_key_exists('message',$sf_response) ){
						if( isset($_COOKIE['original_page']) ){
							$redirect = $_COOKIE['original_page'];
						}else{
							$redirect = get_site_url();
						}
						wp_safe_redirect( $redirect );
						exit;
					}
					$access_token = $sf_response['access_token'];
					
					if(session_id()!=='' || session_id()!==NULL){
						$_SESSION['LoggedIn']=TRUE;
						$_SESSION['loggedin_time']=time(); //set time when user has logged in.
						$_SESSION['access_token']=$access_token;
					}
				}
				
				if( !empty($access_token) && strlen($access_token)>0 ){
					//get profile info via api call
					$user_profile_info=get_user_profile_info($access_token);
					
					//if intention is webrecharge, redirect to recharge page
					if( $intent=='webrecharge' ){
						$recharge_page='/recharge/';
						wp_safe_redirect( $recharge_page );
						exit;
					}
					//if intention is resource access, redirect to resource page
					if( $intent=='resourceaccess' ){
						if( isset($_COOKIE['original_page']) ){
							$resource_page=$_COOKIE['original_page'];
						}else{
							$resource_page='/';
						}
						//Check for payee customer with salesforce version
						payee_customer_checks($user_profile_info);
						//wp_safe_redirect( $resource_page );
						//exit;
					}
					//if intention is web checkout, run user profile checks function
					if( $intent=='webcheckout' ){
						user_profile_checks($user_profile_info);
					}
					if( $intent==NULL ){
						if( isset($_COOKIE['original_page']) ){
							$redirect_page=$_COOKIE['original_page'];
						}else{
							$redirect_page=get_site_url();
						}
						wp_safe_redirect( $redirect_page );
						exit;
					}
				}else{
					//$append=null;
				}
			}//action check ends
			
		}//check for GET values ends
	}
}
add_action( 'admin_post_sfor', 'salesforce_oauth_response' );
add_action( 'admin_post_nopriv_sfor', 'salesforce_oauth_response' );

/*For login form*/
if( !function_exists('login_form_process') ){
	function login_form_process(){
		global $api_server;
		if(isset($_POST) && !empty($_POST)){
			$nonce = $_POST['login'];
			$referer = $_POST['_wp_http_referer'];
			$pre_referrer = $_POST['pre_referrer'];
			$action = $_POST['action'];
			if(isset($_POST['intent']) && $_POST['intent']!==''){
				$login_intent=$_POST['intent'];
			}
			//security check
			$security_check=wp_verify_nonce( $nonce, '_wp_nounce_customer_app_login' );
			
			if(false === $security_check || !$security_check ){ //back to original page with error if security check failed.
				$backto=$referer.'?retry=error';
				wp_safe_redirect( $backto );
				exit;
			}
			$useremail=$_POST['username'];
			$password=$_POST['password'];
			$postdata=array('username'=>$useremail, 'password'=>$password);
			
			$login_api_url='https://'.$api_server.'/api/v2/login';
			$content=json_encode($postdata);
			
			$curl=curl_init($login_api_url);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			$json_response = curl_exec($curl);
			$status=curl_getinfo($curl, CURLINFO_HTTP_CODE);
			if ($status !== 200 ){
				$error_message=json_decode($json_response, true);
				$backto=$referer.'?retry='.$error_message['message'];
				wp_safe_redirect( $backto );
				exit;
				//die("Error: call to URL $login_api_url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
			}
			curl_close($curl);
			$response=json_decode($json_response, true); //var_dump($response);
			
			if( !empty($response) && count($response)>0 ){
				if( array_key_exists('access_token', $response) ){
					$access_token=$response['access_token'];
				}
				if( array_key_exists('refresh_token', $response) ){
					$refresh_token=$response['refresh_token'];
				}
				
				//this means login is valid and successful
				if( isset($access_token) && strlen($access_token)>0 ){ //set login successful
					$currentdomain=currentServer();
					//store access_token in cookie, valid for 1hr 59min.
					//setcookie( 'access_token', $access_token, (time()+7140),'/', $currentdomain, TRUE );
					//OR for security reason instead of cookie, store access_token in session
					if(session_id()!=='' || session_id()!==NULL){
						$_SESSION['LoggedIn']=TRUE;
						$_SESSION['loggedin_time']=time(); //set time when user has logged in.
						$_SESSION['access_token']=$access_token;
					}
					
					//get user profile info. This also stores profile values in session
					$user_profile_info=get_user_profile_info($access_token);
					
					//check for login intention
					if( isset($login_intent) && ($login_intent!=='' || strlen($login_intent)>0) ){
						$_SESSION['login_intent']=$login_intent;
						//if login intent is for resource access, redirect to resource page from where user logged in
						if($login_intent=='resourceaccess'){
							//$referer=$referer.'?LoggedIn=True'; //##Delete after test
							//Check for payee customer with salesforce version
							payee_customer_checks($user_profile_info);
							//wp_safe_redirect( $referer );
							//exit;
						}
						//if login intent is for web recharge, redirect to rechage page
						if($login_intent=='webrecharge'){
							//$referer=$referer.'?LoggedIn=True'; //##Delete after test
							get_user_profile_info($access_token);
							//check for required parameters of existing customer
							//'billing_external_eid' not null
							//'is_core_subscribed' is true
							//'is_tract_card_added' is true
							if( ($_SESSION['billing_external_eid']!==null) && ($_SESSION['is_core_subscribed']===true) && ($_SESSION['is_tract_card_added']===true) ){
								wp_safe_redirect( $referer );
								exit;
							}else{
								$sendto = '/pricing/';
								wp_safe_redirect( $sendto );
								exit;
							}
						}
						//for resource access ends
						//if login intent is for web checkout, continue with user profile checks with respect to web checkout flow
						if($login_intent=='webcheckout'){
							//call profile info checking function
							user_profile_checks($user_profile_info);
						}
						//for web checkout ends
					}else{
						wp_safe_redirect( $pre_referrer );
						exit;
					}
				} //condition for setting login successful ends
			}
		}
	}
}
add_action( 'admin_post_nopriv_customer_app_login', 'login_form_process' );
add_action( 'admin_post_customer_app_login', 'login_form_process' );

/*For billing info form submit action*/
if( !function_exists('login_credentials_check_via_ajax') ){
	function login_credentials_check_via_ajax(){
		global $api_server;
		if(isset($_POST) && !empty($_POST)){
			$nonce = $_POST['security'];
			$referer = $_POST['_wp_http_referer'];
			//security check
			check_ajax_referer( 'smsmagic_nonce_localized', 'security' ); //security check
			
			if( isset($_POST['payload']) && ($_POST['payload']!=='' || strlen($_POST['payload'])>0) ){
				if(strpos($_POST['payload'],'\\')!==false){
					$payloadstring=str_replace('\\','',$_POST['payload']);
				}else{
					$payloadstring=$_POST['payload'];
				}
				$payload=json_decode($payloadstring,true); //convert payload string into array
				//username and pasword both available
				if( (isset($payload['username']) && strlen($payload['username'])>0) && (isset($payload['password']) && strlen($payload['password'])>0) ){
					
					$useremail=$payload['username'];
					$password=$payload['password'];
					$postdata=array('username'=>$useremail, 'password'=>$password);
					
					$login_api_url='https://'.$api_server.'/api/v2/login';
					$logincredentials=json_encode($postdata);
					
					$curl=curl_init($login_api_url);
					curl_setopt($curl, CURLOPT_HEADER, false);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
					curl_setopt($curl, CURLOPT_POST, true);
					curl_setopt($curl, CURLOPT_POSTFIELDS, $logincredentials);
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
					$json_response = curl_exec($curl);
					$status=curl_getinfo($curl, CURLINFO_HTTP_CODE);
					if ($status !== 200 ){
						$error_message=json_decode($json_response, true);
						$output['login']=FALSE;
						$output['error_msg']=$error_message['message'];
					}
					curl_close($curl);
					$response=json_decode($json_response, true);
					
					if( !empty($response) && count($response)>0 ){
						if( array_key_exists('access_token', $response) ){
							$access_token=$response['access_token'];
							$output['login']=TRUE;
						}
					}
					
					die(json_encode($output));
				}//username and pasword check ends
			}//payload check ends
		}
	}
}
add_action( 'wp_ajax_login_credentials_check', 'login_credentials_check_via_ajax' );
add_action( 'wp_ajax_nopriv_login_credentials_check', 'login_credentials_check_via_ajax' );

if(!function_exists('user_profile_checks')){
	function user_profile_checks($user_info){ //pass profile information as an array to this function ($profile_info)
		global $api_server;
		$customer_id=$user_info['customer_id'];
		$core_subscribed=$user_info['is_core_subscribed'];
		$billing_eid=$user_info['billing_external_eid'];
		$billing_currency=$user_info['billing_currency'];
		$billing_cardadded=$user_info['is_tract_card_added'];
		
		//check 'customer_id'
		if( $user_info['customer_id']==null ){
			//customer is on old pricing. //redirect to comparision page.
			$redirect_comparision_url='https://'.$api_server.'/pricing/topup/';
			wp_redirect($redirect_comparision_url);
			exit;
		}else{
			//customer is on new pricing, proceed to further check (is core subscribed)
			if( false===$user_info['is_core_subscribed'] ){
				//customer has not subscribed core. //eligible for core pack subscription.
				//check for billing system related - billing_external_eid and billing_currency
				if( ($user_info['billing_external_eid']===null || $user_info['billing_external_eid']=='null') || ($user_info['billing_currency']===null || $user_info['billing_currency']=='null') ){
					//either not set. start billing account registration flow. Redirect to endpoint for billing account registration
					$billingaccountEP=get_site_url().'/pricing/billingaccount/';
					wp_safe_redirect( $billingaccountEP );
					exit;
				}elseif( (null!==$user_info['billing_external_eid'] || $user_info['billing_external_eid']!=='null') && ($user_info['billing_currency']!==null || $user_info['billing_currency']!=='null') ){
					//customer has both set, proceed to further check (credit card)
					if( false===$user_info['is_tract_card_added'] || $user_info['is_tract_card_added']==false ){
						//credit card not added on tract. start add credit card flow
						$paymentinfoEP=get_site_url().'/pricing/paymentinfo/';
						wp_safe_redirect( $paymentinfoEP );
						exit;
					}elseif(true===$user_info['is_tract_card_added'] || $user_info['is_tract_card_added']=='true'){
						//credit card is added on tract. send to checkout page
						$checkoutEP=get_site_url().'/pricing/checkout/';
						wp_safe_redirect( $checkoutEP );
						exit;
					}
				}
			}else{
				//customer has subscribed core. //redirect to recharge page.
				$redirect_recharge_url=get_site_url().'/recharge/';
				wp_safe_redirect( $redirect_recharge_url );
				exit;
			}
		}
	}
}

if( !function_exists('get_user_profile_info') ){
	function get_user_profile_info($access_token){
		global $api_server;
		//$profile_api_url='https://dev-app.txtbox.in/api/v1/profile';
		$profile_api_url='https://'.$api_server.'/api/v1/profile';
		$profile_curl_headers=array(
			'Content-type: application/json',
			'Authorization: JWT '.$access_token
		);
		$profile_curl=curl_init($profile_api_url);
		curl_setopt($profile_curl, CURLOPT_URL, $profile_api_url);
		curl_setopt($profile_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($profile_curl, CURLOPT_HTTPHEADER, $profile_curl_headers);
		curl_setopt($profile_curl, CURLOPT_POST, false);
		curl_setopt($profile_curl, CURLOPT_HTTPGET, true);
		curl_setopt($profile_curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($profile_curl, CURLOPT_SSL_VERIFYHOST, false);
		$json_profile_response = curl_exec($profile_curl);
		$profile_status=curl_getinfo($profile_curl, CURLINFO_HTTP_CODE);
		$profile_response=json_decode($json_profile_response, true); //convert JSON response into PHP Array
		if ($profile_status !== 200 ){
			die("Error: call to URL $profile_api_url failed with status $profile_status, response $json_profile_response, curl_error " . curl_error($profile_curl) . ", curl_errno " . curl_errno($profile_curl));
			//send back to pricing/checkout page with respective error response
		}
		curl_close($profile_curl);
		$profile_info=$profile_response[0];
		//loop throught the profile fields and store them in session
		if(session_id()!=='' || session_id()!==NULL){
			foreach($profile_info as $profileKey => $profileKeyValue) {
				if( is_array($profileKeyValue) ){
					foreach($profileKeyValue as $pkvKey => $pkvValue) {
						$profileSubKey=$profileKeyValue.'_'.$pkvKey;
						$_SESSION[$profileSubKey]=$pkvValue;
					}
				}else{
					$_SESSION[$profileKey]=$profileKeyValue;
				}
			}
		}
		return $profile_info;
	}	
}


if(!function_exists('payee_customer_checks')){
	function payee_customer_checks($user_profile){		
		$account_status=$user_profile['account_status'];
		$sf_version=$user_profile['salesforce_package_version'];		
		
		//converting SF package version string value to float
		$version_num=floatval($sf_version);		
		
		if($account_status == 'payee'){ 
			//its a paying customer grant access to tech resources
			If( $version_num >= 1.49){
				//wp_safe_redirect( $pre_referrer );
				wp_safe_redirect( get_site_url().'/technical-resource-center/converse-user-and-technical-guides/' );
				exit;
			}elseif( $version_num <= 1.48){				
				//wp_safe_redirect( $pre_referrer );
				wp_safe_redirect( get_site_url().'/technical-resource-center/interact-user-and-technical-guides/' );
				exit;	
			}else{
				wp_safe_redirect( get_site_url().'/technical-resource-center/messaging-guides-and-overviews/' );
				exit;
			}
		}else{
			wp_safe_redirect( get_site_url().'/technical-resource-center/messaging-guides-and-overviews/' );
			exit;
		}
	}
}

if( !function_exists('techdocs_access_check') ){
	function techdocs_access_check($page){	
		$account_status=$_SESSION['account_status'];
		$sf_version=$_SESSION['salesforce_package_version'];
		$version_num=floatval($sf_version);
		//$emailId=$_SESSION['emailId'];
		$account_id=$_SESSION['accountId'];
		
		if( (isset($_SESSION['LoggedIn'])) && ($_SESSION['LoggedIn']==TRUE) ){ //loggedin check			
			if( $account_id !== 1 ){
				if( (strpos($page,'converse')!==FALSE) && ($version_num<=1.48) ){  
					wp_safe_redirect( get_site_url().'/technical-resource-center/interact-user-and-technical-guides/' );
					exit;
				}
			}
		}
	}
}

if (!function_exists('smsmagic_sfdc_oauth_btn')){
	function smsmagic_sfdc_oauth_btn($atts){
		global $api_server, $sf_client_id;
		$wp_admin_post_url=esc_url( admin_url('admin-post.php') );
		$output_script='';

		$default_args = array(
			'checkboxid' => NULL,
			'buttonid' => NULL,
		);

		$atts=shortcode_atts($default_args, $atts, 'smsmagic_signin_with_sfdc');

		//output javascript for enabling login with salesforce functionality
		$output_script.='<script type="text/javascript">';
			$output_script.='jQuery(document).ready(function($){';
				if( isset($atts['checkboxid']) && $atts['checkboxid']!==NULL ){
					$output_script.='$("#'.$atts['checkboxid'].'").click(function(){';
						$exptime=time()+7200;
					$output_script.='});';
				}
				$output_script.='$("#'.$atts['buttonid'].'").click(function(){';
					$exptime=time()+7200;
					//code for date and time
					$output_script.='var newdate = new Date();';
					$output_script.='var dd = newdate.getDate();';
					$output_script.='var mm = newdate.getMonth()+1;';
					$output_script.='var yyyy = newdate.getFullYear();';
					$output_script.='if(dd<10){dd = "0"+dd;}';
					$output_script.='if(mm<10){mm = "0"+mm;}';
					$output_script.='var today = yyyy+"-"+dd+"-"+mm;';
					$output_script.='var timehrs = newdate.getHours();';
					$output_script.='var timemin = newdate.getMinutes();';
					$output_script.='var timesec = newdate.getSeconds();';
					$output_script.='if(timehrs<10){timehrs = "0"+timehrs;}';
					$output_script.='if(timemin<10){timemin = "0"+timemin;}';
					$output_script.='if(timesec<10){timesec = "0"+timesec;}';
					$output_script.='var timenow = timehrs+":"+timemin+":"+timesec;';
					$output_script.='var nowDateTime = today+" "+timenow;';
					//code for date and time end
					$output_script.='document.cookie = "tandc_accepted_value=true; expires='.$exptime.'; path=/";';
					$output_script.='document.cookie = "tandc_accepted_datetime="+nowDateTime+"; expires='.$exptime.'; path=/";';
					$output_script.='document.cookie = "original_page='.$_SERVER["REQUEST_URI"].'; expires='.$exptime.'; path=/";';
					$output_script.='var SALESFORCE_AUTHORIZE_URL = "https://login.salesforce.com/services/oauth2/authorize";';
					$output_script.='var CLIENT_ID = "'.$sf_client_id.'";';
					$output_script.='var REDIRECT_URI = "'.$wp_admin_post_url.'?action=sfortrialorg&";';
					$output_script.='var RS_TYPE = "response_type=code";';
					$output_script.='var uri_paras = RS_TYPE+"&client_id="+CLIENT_ID+"&redirect_uri="+encodeURIComponent(REDIRECT_URI);';
					$output_script.='var complete_redirect_uri = SALESFORCE_AUTHORIZE_URL +"?"+ uri_paras;';
					$output_script.='window.location.replace(complete_redirect_uri);';
				$output_script.='});';
			$output_script.='});';
		$output_script.='</script>';

		return $output_script;
	}
	add_shortcode('smsmagic_signin_with_sfdc', 'smsmagic_sfdc_oauth_btn');
	//useage as follows
	//[smsmagic_signin_with_sfdc buttonid=""] | targetid is id of the target click element, without '#'
}

//For handling Salesfoce OAuth Response (used for sms-magic app install request flow)
if( !function_exists('smsmagic_sfdc_oauth_trialorg') ){
	function smsmagic_sfdc_oauth_trialorg(){
		global $api_server;
		$social_signup_url='https://'.$api_server.'/api/v2/social/signup';
		$state=urlencode('orgType=production&provider=salesforce_production&redirectUrl=landing_page&redirectionType=signin');
		
		if( isset($_GET) ){//check for the GET values exist
			//get response code from Salesforce
			if( isset($_GET['code']) && ($_GET['code']!=='' || $_GET['code']!==null) ){
				$code=$_GET['code'];
			}
			//create social signup URL with received code from Salesforce and state parameters
			$smsmagic_socialsignup_api=$social_signup_url.'?code='.$code.'&state='.$state;

			$social_signup_curl = curl_init();
			curl_setopt_array($social_signup_curl, array(
				CURLOPT_URL => $smsmagic_socialsignup_api,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_HTTPHEADER => array('cache-control: no-cache'),
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_SSL_VERIFYHOST => false
			));
			$social_signup_json_response = curl_exec($social_signup_curl);
			$err = curl_error($social_signup_curl);
			curl_close($social_signup_curl);

			if ($err) {

			} else {
				$sf_response=json_decode($social_signup_json_response, true);
				//if response contains 'message' instead of access_token (which means error)
				if( array_key_exists('message',$sf_response) ){
					if( isset($_COOKIE['original_page']) ){
						$redirect = $_COOKIE['original_page'];
					}else{
						$redirect = get_site_url();
					}
					wp_safe_redirect( $redirect );
					exit;
				}
				$access_token = $sf_response['access_token'];
				
				if(session_id()!=='' || session_id()!==NULL){
					$_SESSION['LoggedIn']=TRUE;
					$_SESSION['loggedin_time']=time(); //set time when user has logged in.
					$_SESSION['access_token']=$access_token;
				}
			}

			if( !empty($access_token) && strlen($access_token)>0 ){
				//get profile info via api call
				$user_profile_info=get_user_profile_info($access_token);
				//https://launch.sms-magic.com/appexchange-select-option/
				wp_safe_redirect( '/appexchange-converse-trial/select-option/' );
				exit;
			}

		}//check for GET values ends
	}
}
add_action( 'admin_post_sfortrialorg', 'smsmagic_sfdc_oauth_trialorg' );
add_action( 'admin_post_nopriv_sfortrialorg', 'smsmagic_sfdc_oauth_trialorg' );

?>