<?php

$servername = "localhost";
$username = "uljohmmy_oldsite";
$password = "2y6GQzNpD&~M[Y=S";
$db_name = "uljohmmy_old_smsmagic";

// $servername = "localhost";
// $username = "smsmagic_convers";
// $password = "h#xUzz=GW=j^";
// $db_name = "smsmagic_conversetest1";

global $mysqli;

$mysqli = new mysqli($servername, $username, $password, $db_name);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

//get leads from db
$leads_query = "SELECT `id`, `fname`, `lname`, `email`, `mobile`, `title`, `jobfunction`, `company`, `numofemployees`, `country`, `state`, `usecase`, `termstime`, `consent`, `consenttime`, `createdtime` FROM `wp_appexchange_leads` WHERE `mktosubmitted`=0";
$leads = $mysqli->query($leads_query);
if($mysqli->error){
    echo($mysqli->error);
    error_log($mysqli->error,0);
}

//when leads query from website db has results
if($leads->num_rows > 0){
    while ($lead = $leads->fetch_assoc()) {
        $createdTimeStamp = strtotime($lead['createdtime']);
        $now = gmmktime();
        //record's creation time is older than 10 min. 600 = 10 x 60 sec | for testing use 300 (5 min)
        if( ($now - $createdTimeStamp) >= 600 ){
            if($lead['consent']==1){
                $lead['consent']='Yes';
            }
            //create leads data array to post to Marketo
            $leadData = array(
                "firstName"=>$lead['fname'],
                "lastName"=>$lead['lname'],
                "email"=>$lead['email'],
                "mobilePhone"=>$lead['mobile'],
                "title"=>$lead['title'],
                "Job_Function__c"=>$lead['jobfunction'],
                "company"=>$lead['company'],
                "numberOfEmployees"=>$lead['numofemployees'],
                "country"=>$lead['country'],
                "state"=>$lead['state'],
                "Trial_Org_Usecase__c"=>$lead['usecase'],
                "Terms_Acceptance__c"=>$lead['termstime'],
                "Email_Consent__c"=>$lead['consent'],
                "Email_Consent_Timestamp__c"=>$lead['consenttime']
            );
            //get Marketo record Id for given email (email id of current record being processed)
            $lead_mkto_id = get_Lead_Mkto_ID($lead['email'],$mysqli);
            //post lead data to Marketo
            post_leadData_Marketo($lead['id'],$lead_mkto_id,$leadData,$mysqli);
        } //lead created time comparison check ends
    } //while loop ends
}
//leads query result row count check ends

//function to check if lead with the current email exists in Marketo
//and if it exists, get Marketo record id of lates lead record (in case of multiple records exist)
function get_Lead_Mkto_ID($leadEmail,$mysqli){
    $mktoAccessToken = get_mkto_access_token($mysqli);
    $mkto_curl_headers=array(
        'Content-type: application/json',
        "Cache-Control: no-cache",
        'Accept: application/json'
    );
    //cURL get request to Marketo for current lead, to see if multiple records exist for the email address
    $mkto_get_lead_ep = "https://707-UFB-065.mktorest.com/rest/v1/leads.json?access_token=".$mktoAccessToken."&filterType=Email&filterValues=".$leadEmail."&fields=id,FirstName,LastName,Email";
    $mkto_get_leads_curl = curl_init();
    curl_setopt_array($mkto_get_leads_curl, array(
        CURLOPT_URL => $mkto_get_lead_ep,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $mkto_curl_headers,
    ));
    $getResponse = curl_exec($mkto_get_leads_curl);
    $getErr = curl_error($mkto_get_leads_curl);
    curl_close($mkto_get_leads_curl);
    if ($getErr) {
        echo "Marketo Get Lead Data cURL Error #:" . $getErr;
        error_log($getErr,0);
    } else {
        //when Marketo cURL get request is succesfull
        $mkto_leadsData_array = json_decode($getResponse,TRUE);
        //check for received lead data from Marketo
        //following condition means; lead data received; atleast one lead record with given email exist in Marketo
        if( ($mkto_leadsData_array["success"]==true) && (count($mkto_leadsData_array["result"])>0) ){
            //check for received records count; single/multiple
            if( count($mkto_leadsData_array["result"])>1 ){
                //multiple records found for given email
                $targetKey = count($mkto_leadsData_array['result'])-1;
                $leadMktoId = $mkto_leadsData_array["result"][$targetKey]["id"];
            }elseif( count($mkto_leadsData_array["result"])==1 ){
                //single record found for given email
                $leadMktoId = $mkto_leadsData_array["result"][0]["id"];
            }
            return $leadMktoId;
        }
        //following condition meands lead record with given email address does not exist in Marketo
        elseif( ($mkto_leadsData_array["success"]==true) && (count($mkto_leadsData_array["result"])==0) ){
            return null;
        }
        //check for received lead data from Marketo ends
    }
}

//function to post lead data to Marketo.
//this funtion posts lead data with context updateOnly or createOnly based on provided parameter
function post_leadData_Marketo($leadDbId,$leadMtkId,$leadData,$mysqli){
    $mkto_curl_headers=array(
        'Content-type: application/json',
        "Cache-Control: no-cache",
        'Accept: application/json'
    );
    //check if lead's Marketo id value is null or not null
    if($leadMtkId!==null){
        $mktoAction="updateOnly";
        $leadData["id"]=$leadMtkId;
        $responsebodyarray = array(
	    "action"=>$mktoAction,
	    "asyncProcessing"=>false,
	    "input"=>array($leadData),
	    "lookupField"=>"id"
	);
    }elseif($leadMtkId===null){
        //$leadData["id"]=null;
        $mktoAction="createOnly";
        $leadData["leadSource"]="SFDC-IN|SMS-Magic Converse | Intelligent Business Text Messaging";
        $leadData["Primary_Lead_Source__c"]="AppExchange";
        $leadData["leadStatus"]="New";
        $leadData["Lead_Qualification_Stage__c"]="SQL (Sales)";
        $leadData["Lead_Stage__c"]="SQL";
        $responsebodyarray = array(
	    "action"=>$mktoAction,
	    "asyncProcessing"=>false,
	    "input"=>array($leadData)
	);
    }
//     $responsebodyarray = array(
//         "action"=>$mktoAction,
//         "asyncProcessing"=>false,
//         "input"=>array($leadData),
//         "lookupField"=>"id"
//     );
    $leadsPostData = json_encode($responsebodyarray);
    $mktoAccessToken = get_mkto_access_token($mysqli);
    //post it to Marketo (cURL)
    $mkto_post_lead_ep = 'https://707-UFB-065.mktorest.com/rest/v1/leads.json?access_token='.$mktoAccessToken;
    $mkto_post_curl=curl_init();
    curl_setopt_array($mkto_post_curl, array(
        CURLOPT_URL => $mkto_post_lead_ep,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $leadsPostData,
        CURLOPT_HTTPHEADER => $mkto_curl_headers,
    ));
    $json_response = curl_exec($mkto_post_curl);
    $postError = curl_error($mkto_post_curl);
    $status = curl_getinfo($mkto_post_curl, CURLINFO_HTTP_CODE);
    curl_close($mkto_post_curl);
    //check post cURL status
    if ($status !== 200 ){
        $error_message=json_decode($json_response, true);
        error_log($error_message,0);
    }else{
        $response = json_decode($json_response, true);
        $result = $response['result'];
        if( array_key_exists('success',$response) && ($response['success']==true) ){
            foreach($result as $key=>$row){
                //when lead record updated/created in marketo
                if( ($row['status'] == 'updated') || $row['status'] == 'created' ){
                    echo('<p>RecordId '.$leadDbId.': '.$row['status'].' in Marketo via cURL</p>'); //#####
                    $updateDB = $mysqli->query("UPDATE `wp_appexchange_leads` SET `mktosubmitted`=1 WHERE `id`='".$leadDbId."'");
                    if($mysqli->affected_rows==1){
                        echo('<p>Record at id '.$leadDbId.' updated in table wp_appexchange_leads</p>'); //#####
                    }
                }
                //when lead record is skipped
                if($row['status']=='skipped'){
                    $errorCode = $row['reasons'][0]['code'];
                    $skipReason = $row['reasons'][0]['message'];
                    $errormsg = 'Error Code:'.$errorCode.'. Email: '.$leadData['email'].', '.$skipReason;
                    error_log($errormsg,0);
                    echo('<p>'.$errormsg.'</p>');
                }
            } //foreach loop ends
        } //response success check ends
    }
    //post cURL status check ends
}

//function to retrieve/generate Marketo access token
function get_mkto_access_token($mysqli){
    $refTime = gmmktime()-3600;
    $tokenQuery = "SELECT `mktoaccesstoken`, `mktoexpiresin`, `mktotokentime` FROM `wp_mkto_access_token` ORDER BY `id` DESC LIMIT 1";
    //return $tokenQuery;

    $getmktotoken = $mysqli->query($tokenQuery);
    if($mysqli->error){
        echo($mysqli->error);
        error_log($mysqli->error,0);
    }
    //check for result count of fetch token query
    if( $getmktotoken->num_rows > 0 ){
        while ($token = $getmktotoken->fetch_assoc()) {
            $mktAccessToken = $token['mktoaccesstoken'];
            $mktTokenExpires = $token['mktoexpiresin'];
            $mktTokenTime = $token['mktotokentime'];
        }
        //token expiry check
        //current timesamp (integer) minus token generated timestamp (integer) is less than token expiry time (integer)
        if( intval(gmmktime()) - intval($mktTokenTime) < intval($mktTokenExpires) ){
            //this means token fetched from db is still valid; return the token
            $tokentoReturn = $mktAccessToken; //return access token retrieved from db
        }else{
            //this means result is false; generate new token from Marketo to return
            $tokentoReturn = generate_marketo_token($mysqli);
        }
        //check for token expiry ends
    }else{
        //token not retrieved, generate new token from Marketo
        $tokentoReturn = generate_marketo_token($mysqli);
    }
    //check for fetcch token query result end
    return $tokentoReturn;
    
}//get marketo access token ends

//function to generate Marketo Access token via Marketo API call
function generate_marketo_token($mysqli){
    $ClientId = 'f790edc3-cd0e-4a73-8344-e36ead06ae1d';
    $ClientSecret = 'FFbvr8NU7IPba4labbiQAKn85lBogAj5';
    $identiryURL = 'https://707-UFB-065.mktorest.com/identity/oauth/token?grant_type=client_credentials&client_id='.$ClientId.'&client_secret='.$ClientSecret;
    $curl_headers = array(
        "Accept: application/json",
        "Cache-Control: no-cache",
        "Content-Type: application/json"
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $identiryURL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $curl_headers,
    ));
    $curl_response = curl_exec($curl);
    $curl_error = curl_error($curl);
    curl_close($curl);
    if ($curl_error) {
        echo "cURL Error #:" . $curl_error;
    }else{
        $mkto_response_array = json_decode($curl_response,TRUE);
        $mkto_access_token = $mkto_response_array['access_token'];
        $mkto_expires_in = $mkto_response_array['expires_in'];
        $mkto_token_time = gmmktime();
        //insert newly generated token into db
        $inert_token_query = "INSERT INTO `wp_mkto_access_token` (`id`, `mktoaccesstoken`, `mktoexpiresin`, `mktotokentime`) VALUES (NULL, '".$mkto_access_token."', '".$mkto_expires_in."', '".$mkto_token_time."')";
        $insert = $mysqli->query($inert_token_query);
        if($mysqli->error){
            echo($mysqli->error);
        }
        return $mkto_access_token; //return newly generated token
    }
}

$mysqli->close();

?>