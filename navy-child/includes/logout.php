<?php
$userProfileKeys = array( 'accountId', 'accountType', 'account_status', 'address', 'api_key', 'billing_currency', 'billing_external_eid', 'billing_external_id', 'billing_subscription_details', 'billing_usage_enabled', 'expires_on', 'id_core', 'invoice_frequency', 'order_eid', 'campaign_hide', 'city', 'companyName', 'country', 'countryOfOperation', 'country_id', 'customer_id', 'emailId', 'expiry_date', 'gmt_offset', 'industry', 'industry_name', 'isUpgrade_in_progress_sms_disabled', 'is_child', 'is_core_subscribed', 'is_kyc_completed', 'is_tract_card_added', 'is_verified', 'name', 'org_type', 'parent_account_id', 'phoneNumber', 'salesforce_package_version', 'sandbox_key', 'source', 'state', 'subscription_plan', 'subscription_plan_name', 'time_zone_name', 'userType', 'user_id', 'username', 'zipCode', 'login_intent', 'checkout_complete', 'order_status', 'billingaccount_complete', 'paymentinfo_complete' );

$webCheckoutCookies = array( 'corepack_users', 'corepack_phones', 'corepack_credits', 'corepack_country', 'user_currency', 'user_currency_symbol', 'AdditionalUsers', 'IncludedCredits', 'AdditionalPhones', 'AdditionalCredits' );

function destroy_webcheckout_cookie($cookieName){
	$currentdomain = $_SERVER['SERVER_NAME'];
	unset($_COOKIE[$cookieName]);
	setcookie($cookieName, '', time()-7200, '/', $currentdomain, TRUE);
}

session_start();

unset($_SESSION['LoggedIn']);
unset($_SESSION['access_token']);
unset($_SESSION['loggedin_time']);
unset($_SESSION['industry_name']);

//unset all user profile values stored in session
foreach( $userProfileKeys as $key=>$value ){
	unset($_SESSION[$value]);
}
//destroy/unset all cookies related to webcheckout flow
foreach( $webCheckoutCookies as $key=>$value ){
	if( isset($_COOKIE[$value]) ){
		destroy_webcheckout_cookie($value);
	}
}

/*$page=$_SERVER['REQUEST_URI'];
if( strpos($page,'/messaging-guides-and-overviews/')!==0 ){
	header("Location: https://launch.sms-magic.com/");
}else{
	header("Location: {$_SERVER['HTTP_REFERER']}");
}*/

//redirect to the page from where logout was initiated
header("Location: {$_SERVER['HTTP_REFERER']}");

?>