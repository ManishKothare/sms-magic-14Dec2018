<?php

$servername = "localhost";
$username = "smsmagic_convers";
$password = "h#xUzz=GW=j^";
$db_name = "smsmagic_conversetest1";

global $mysqli;



$mysqli = new mysqli($servername, $username, $password, $db_name);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

//$access_token = get_mkto_access_token($mysqli);

//get leads from db
$leads_query = "SELECT `id`, `fname`, `lname`, `email`, `mobile`, `title`, `jobfunction`, `company`, `numofemployees`, `country`, `state`, `usecase`, `termstime`, `consent`, `consenttime`, `createdtime` FROM `wp_appexchange_leads` WHERE mktosubmitted=0";
$leads = $mysqli->query($leads_query);

echo("<p>Start of records processing: ".$leads->num_rows."</p>");//#####
if($leads->num_rows > 0){
    echo('<p>Records found, continue</p>');//#####
    $leadsInput = array();
    $leadIds = array();
    while ($lead = $leads->fetch_assoc()) {
        echo('<p>Processing while loop, '.$lead['id'].'</p>');//#####
        $createdTimeStamp = strtotime($lead['createdtime']);
        $now = gmmktime();
        //record's creation time is older than 10 min. 600 = 10 x 60 sec | for testing use 300 (5 min)
        if( ($now - $createdTimeStamp) >= 300 ){
            echo('<p>'.$lead['id'].' - qualified for post</p>');//#####
            $leadData = array(
                "FirstName"=>$lead['fname'],
                "LastName"=>$lead['lname'],
                "Email"=>$lead['email'],
                "MobilePhone"=>$lead['mobile'],
                "Title"=>$lead['title'],
                "Job_Function__c"=>$lead['jobfunction'],
                "Company"=>$lead['company'],
                "NumberOfEmployees"=>$lead['numofemployees'],
                "Country"=>$lead['country'],
                "State"=>$lead['state'],
                "Trial_Org_Usecase__c"=>$lead['usecase'],
                "Terms_Acceptance__c"=>$lead['termstime'],
                "Email_Consent__c"=>$lead['consent'],
                "Email_Consent_Timestamp__c"=>$lead['consenttime']
            );
            array_push($leadsInput,$leadData);
            array_push($leadIds,$lead['id']);
        }//created time comparison check ends
    }//while loop ends
    //create array of qualified leads data
    $responsebodyarray = array (
        "action"=>"createOrUpdate",
        "asyncProcessing"=>false,
        "input"=>$leadsInput,
        "lookupField"=>"Email"
    );
    $leadsPostData = json_encode($responsebodyarray);
        echo('<pre>');//#####
        var_dump($leadsPostData);//#####
        echo('</pre>');//#####
    //post it to mkto (cURL)
    $mktoAccessToken = get_mkto_access_token($mysqli);
        echo('<p>Got access token: '.$mktoAccessToken.'</p>');//#####
    $mkto_rest_ep='https://707-UFB-065.mktorest.com/rest/v1/leads.json?access_token='.$mktoAccessToken;
    $mkto_curl_headers=array(
        'Content-type: application/json',
        'Accept: application/json'
    );
        echo('<p>cURL start</p>');//#####
    $mkto_curl=curl_init();
    curl_setopt_array($mkto_curl, array(
        CURLOPT_URL => $mkto_rest_ep,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $leadsPostData,
        CURLOPT_HTTPHEADER => $mkto_curl_headers
    ));
    $json_response = curl_exec($mkto_curl);
    $error = curl_error($mkto_curl);
    $status = curl_getinfo($mkto_curl, CURLINFO_HTTP_CODE);
    curl_close($mkto_curl);
    //cURL end
        echo('<p>cURL close</p>');//#####
    if ($status !== 200 ){
        $error_message=json_decode($json_response, true);
            var_dump($error_message);//#####
    }else{
        $response = json_decode($json_response, true);
        $requestId = $response['requestId'];
        $result = $response['result'];
        if( array_key_exists("success",$response) && ($response['success']==true) ){
        
            //email variables
            $to=array('kundan@screen-magic.com');
            $subject='Wordpress CronJob Run Result';
            $headers=array('Content-Type: text/html; charset=UTF-8');
            $headers[]='From: Kundan Shukla <kundan@sms-magic.com>';
            $headers[]='MIME-Version: 1.0';
            $message='<p>Cron processed leads.</p>';
            //when post to mkto is success
            foreach($result as $key=>$row){
                $ltableid=$leadIds[$key];
                //when lead record updated/created in marketo
                if( ($row['status'] == 'updated') || $row['status'] == 'created' ){
                    //update this record in wp db as posted to marketo
                    $message.='<p>Lead table id: '.$ltableid.'<br/>Marketo status: '.$row['status'].'</p>';
                    $updated = $mysqli->query("UPDATE `wp_appexchange_leads` SET `mktosubmitted`=1 WHERE `id`='".$ltableid."'");
                    if($updated !== FALSE){
                        echo('<p>'.$ltableid.' updated</p>');//#####
                    }else{
                        echo('<p>updated failed</p>');//#####
                    }
                }
                //when lead record i skipped
                if($row['status']=='skipped'){
                    $skipreason=$row['reasons']['message'];
                    $message.='<p>Lead table id: '.$ltableid.'<br/>';
                    $message.='Marketo status: '.$row['status'].'<br/>';
                    $message.='Status reason: '.$row['reasons']['message'].'</p>';
                }
            }
            $message.='<p>Check details in marketo and backend</p>';
            //send mail
            $emailresult = mail($to, $subject, $message, $headers);
            if($emailresult !== FALSE){
                echo('<p>Email sent.</p>');//#####
            }
            
        }//response success check ends
    }
    
}
echo("<p>End of records processing: ".$leads->num_rows."</p>");


function get_mkto_access_token($mysqli){
    $refTime = gmmktime()-3600;
    $getmktotoken = $mysqli->query("SELECT 'mktoaccesstoken', 'mktoexpiresin' FROM 'wp_mkto_access_token' WHERE 'mktotokentime'>($refTime)");
    
    if(!$getmktotoken || $getmktotoken==NULL){
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
            
            $insert = $mysqli->query("INSERT INTO `wp_mkto_access_token` (`id`, `mktoaccesstoken`, `mktoexpiresin`, `mktotokentime`) VALUES (NULL, '".$mkto_access_token."', '".$mkto_expires_in."', CURRENT_TIMESTAMP)");
            if($mysqli->error){
                echo($mysqli->error);
            }

            return $mkto_access_token;
        }

    }else{
        return $getmktotoken->mktoaccesstoken;
    }
}


$mysqli->close();

?>