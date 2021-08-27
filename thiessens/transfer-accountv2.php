<?php
$apiurl = 'https://newcrm.dansonscorp.com/Api/';
$servername = "dansons-cluster-1.cluster-cjocz1mn6ubo.us-west-2.rds.amazonaws.com";
$username = "integration_user";
$password = '<H6b2n;9?wD"eR@5';
$dbname = "suitecrm_new";
$dbname1 = "thiessens_dev";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$conn2 = new mysqli($servername, $username, $password, $dbname1);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$httpHeaderln = array('Content-type: application/vnd.api+json', 'Accept: application/vnd.api+json');
    $loginstr = array("grant_type" => "client_credentials", "client_id" => "5e960e1d-93bd-8dbe-7d39-5e0a8238f747", "client_secret" => "A!exander249143");
    $loginstrjson = json_encode($loginstr, TRUE);

    $spch = curl_init();
        curl_setopt($spch, CURLOPT_URL, $apiurl.'access_token');
        curl_setopt($spch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($spch, CURLOPT_POSTFIELDS, $loginstrjson);
        curl_setopt($spch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($spch, CURLOPT_HTTPHEADER, $httpHeaderln);
    $spLoginResponse2 = curl_exec($spch);
    //$httpResponseCode = curl_getinfo($spch);
    curl_close($spch);
    //var_dump($spLoginResponse2);
    // Convert the JSON into a multi-dimensional array.
    $LoginresponseArray = json_decode($spLoginResponse2, TRUE);
    $access_token = $LoginresponseArray['access_token'];
    $token_type = $LoginresponseArray['token_type'];

$customer_id = $_GET['customer_id'];
$i = 0;
$current_case_update_q = "SELECT store_id, customer_id, firstname, lastname, email, telephone, oc_customer.safe FROM oc_customer WHERE customer_id = " . $customer_id .";";
//$current_case_update_q = "SELECT store_id, customer_id, firstname, lastname, email, telephone, oc_customer.safe FROM oc_customer;";
$current_case_updateq = $conn2->query($current_case_update_q);
$current_case_update = $current_case_updateq->fetch_assoc();
//while ($current_case_update = $current_case_updateq->fetch_assoc()) {
    /*
    $current_access_token_q = "SELECT * FROM api_access_tokens WHERE token = '5e960e1d-93bd-8dbe-7d39-5e0a8238f747';";
    $current_access_tokenq = $conn->query($current_access_token_q);
    $current_access_token = $current_access_tokenq->fetch_assoc();
    
    $access_token = $current_access_token['access_token'];
    $token_type = $current_access_token['token_type'];
*/
    $customer_id = $current_case_update['customer_id'];
    $firstname = $current_case_update['firstname'];
    $lastname = $current_case_update['lastname'];
    $email = $current_case_update['email'];
    $safe = $current_case_update['safe'];
    $store_id = $current_case_update['store_id'];

    if($store_id == 0){
        $assigned_account = '6c428eb8-8355-18a1-4c7e-5e15fe39dd9d';
    }
    else{
        $assigned_account = '6c428eb8-8355-18a1-4c7e-5e15fe39dd9d';
    }

    if($safe == 1){
        $safe_code = 0;
    }
    else{
        $safe_code = 1;
    }

    $phone = preg_replace('/[^A-Za-z0-9]/', '', $current_case_update['telephone']);

    $address1_cust_q = "SELECT *, oc_zone.code AS state_code FROM oc_address JOIN oc_zone ON oc_zone.zone_id = oc_address.zone_id JOIN oc_country ON oc_country.country_id = oc_address.country_id WHERE customer_id = " . $customer_id ." ORDER BY address_id LIMIT 1;";
    $address1_custq = $conn2->query($address1_cust_q);
    $address1_cust = $address1_custq->fetch_assoc();

    $address2_cust_q = "SELECT *, oc_zone.code AS state_code FROM oc_address JOIN oc_zone ON oc_zone.zone_id = oc_address.zone_id JOIN oc_country ON oc_country.country_id = oc_address.country_id WHERE customer_id = " . $customer_id ." ORDER BY address_id DESC LIMIT 1;";
    $address2_custq = $conn2->query($address2_cust_q);
    $address2_cust = $address2_custq->fetch_assoc();

$current_contact_update_q = "SELECT count(id_c) AS records, id_c AS id FROM contacts_cstm WHERE integration_id_c = 'TWS" . $customer_id ."' GROUP BY id_c;";
$current_contact_updateq = $conn->query($current_contact_update_q);
$current_contact_update = $current_contact_updateq->fetch_assoc();

if($current_contact_update['records'] > 0){
    
    ${"postdata" . $i} = array(
                "data" => array(
                    "type" => "Contact",
                    "id" => $current_contact_update['id'],
                    "attributes" => array(
                        "first_name" => htmlspecialchars($firstname, ENT_QUOTES),
                        "last_name" => htmlspecialchars($lastname, ENT_QUOTES),
                        "email1" => strtolower($email),
                        "phone_home" => htmlspecialchars($phone, ENT_QUOTES), 
                        "phone_mobile" => htmlspecialchars($phone, ENT_QUOTES),
                        "primary_address_street" => htmlspecialchars($address1_cust['address_1'], ENT_QUOTES), 
                        "primary_address_city" => htmlspecialchars($address1_cust['city'], ENT_QUOTES), 
                        "primary_address_postalcode" => htmlspecialchars($address1_cust['state_code'], ENT_QUOTES), 
                        "primary_address_state" => htmlspecialchars($address1_cust['state_code'], ENT_QUOTES), 
                        "primary_address_country" => htmlspecialchars($address1_cust['iso_code_3'], ENT_QUOTES),
                        "alt_address_street" => htmlspecialchars($address2_cust['address_1'], ENT_QUOTES), 
                        "alt_address_city" => htmlspecialchars($address2_cust['city'], ENT_QUOTES), 
                        "alt_address_postalcode" => htmlspecialchars($address2_cust['state_code'], ENT_QUOTES), 
                        "alt_address_state" => htmlspecialchars($address2_cust['state_code'], ENT_QUOTES), 
                        "alt_address_country" => htmlspecialchars($address2_cust['iso_code_3'], ENT_QUOTES),
                        "integration_id_c" => 'TWS'.$customer_id, 
                        "description" => $email, 
                        "lead_source" => 'Web Site',
                        "invalid_email" => $safe_code
                        )
                    )
                );
        
            ${"httpheader" . $i} = array(
                'Content-type: application/vnd.api+json',
                'Accept: application/vnd.api+json',
                'Authorization: '.$token_type . ' ' . $access_token,
            );
            ${"postdata2" . $i} = json_encode(${"postdata" . $i});
            ${"cus" . $i} = curl_init($apiurl.'V8/module');
            curl_setopt(${"cus" . $i}, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt(${"cus" . $i}, CURLOPT_POSTFIELDS, ${"postdata2" . $i});
            curl_setopt(${"cus" . $i}, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt(${"cus" . $i}, CURLOPT_HTTPHEADER, ${"httpheader" . $i});
            //print "detials:".$ch;
            ${"curlbody" . $i} = curl_exec(${"cus" . $i});
            ${"curlerror" . $i} = curl_getinfo(${"cus" . $i}, CURLINFO_HTTP_CODE);
            curl_close(${"cus" . $i});
            //var_dump(${"curlerror" . $i});
            //sleep(2);

            $contact_2_account_q1 = "SELECT id_c AS id FROM contacts_cstm WHERE integration_id_c = 'TWS" . $customer_id ."' GROUP BY id_c;";
            $contact_2_account_q1q = $conn->query($contact_2_account_q1);
            $contact_2_account = $contact_2_account_q1q->fetch_assoc();

            $contact_2_account_q2 = "INSERT INTO accounts_contacts SET contact_id = '" . $contact_2_account['id'] . "', account_id = '" . $assigned_account . "', id = 'TWS" . $customer_id . "-" . $i ."';";
            $contact_2_account_q2q = $conn->query($contact_2_account_q2);

            $contact_2_account_q2 = "UPDATE accounts_contacts SET contact_id = '" . $contact_2_account['id'] . "', account_id = '" . $assigned_account . "' WHERE id = 'TWS" . $customer_id . "-" . $i ."';";
            $contact_2_account_q2q = $conn->query($contact_2_account_q2);
}
else{
    ${"postdata" . $i} = array(
                "data" => array(
                    "type" => "Contact",
                    "attributes" => array(
                        "first_name" => htmlspecialchars($firstname, ENT_QUOTES),
                        "last_name" => htmlspecialchars($lastname, ENT_QUOTES),
                        "email1" => strtolower($email),
                        "phone_home" => htmlspecialchars($phone, ENT_QUOTES), 
                        "phone_mobile" => htmlspecialchars($phone, ENT_QUOTES),
                        "primary_address_street" => htmlspecialchars($address1_cust['address_1'], ENT_QUOTES), 
                        "primary_address_city" => htmlspecialchars($address1_cust['city'], ENT_QUOTES), 
                        "primary_address_postalcode" => htmlspecialchars($address1_cust['state_code'], ENT_QUOTES), 
                        "primary_address_state" => htmlspecialchars($address1_cust['state_code'], ENT_QUOTES), 
                        "primary_address_country" => htmlspecialchars($address1_cust['iso_code_3'], ENT_QUOTES),
                        "alt_address_street" => htmlspecialchars($address2_cust['address_1'], ENT_QUOTES), 
                        "alt_address_city" => htmlspecialchars($address2_cust['city'], ENT_QUOTES), 
                        "alt_address_postalcode" => htmlspecialchars($address2_cust['state_code'], ENT_QUOTES), 
                        "alt_address_state" => htmlspecialchars($address2_cust['state_code'], ENT_QUOTES), 
                        "alt_address_country" => htmlspecialchars($address2_cust['iso_code_3'], ENT_QUOTES),
                        "integration_id_c" => 'TWS'.$customer_id, 
                        "description" => $email, 
                        "lead_source" => 'Web Site',
                        "invalid_email" => $safe_code
                        )
                    )
                );
        
            ${"httpheader" . $i} = array(
                'Content-type: application/vnd.api+json',
                'Accept: application/vnd.api+json',
                'Authorization: '.$token_type . ' ' . $access_token,
            );
            ${"postdata2" . $i} = json_encode(${"postdata" . $i});
            ${"cus" . $i} = curl_init($apiurl.'V8/module');
            curl_setopt(${"cus" . $i}, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt(${"cus" . $i}, CURLOPT_POSTFIELDS, ${"postdata2" . $i});
            curl_setopt(${"cus" . $i}, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt(${"cus" . $i}, CURLOPT_HTTPHEADER, ${"httpheader" . $i});
            //print "detials:".$ch;
            ${"curlbody" . $i} = curl_exec(${"cus" . $i});
            ${"curlerror" . $i} = curl_getinfo(${"cus" . $i}, CURLINFO_HTTP_CODE);
            curl_close(${"cus" . $i});
            //var_dump(${"curlerror" . $i});
            sleep(1);
            $contact_2_account_q1 = "SELECT id_c AS id FROM contacts_cstm WHERE integration_id_c = 'TWS" . $customer_id ."' GROUP BY id_c;";
            $contact_2_account_q1q = $conn->query($contact_2_account_q1);
            $contact_2_account = $contact_2_account_q1q->fetch_assoc();

            $contact_2_account_q2 = "INSERT INTO accounts_contacts SET contact_id = '" . $contact_2_account['id'] . "', account_id = '" . $assigned_account . "', id = 'TWS" . $customer_id . "-" . $i ."';";
            $contact_2_account_q2q = $conn->query($contact_2_account_q2);
}

//$i++;
//}
$conn->close();
$conn2->close();
?>