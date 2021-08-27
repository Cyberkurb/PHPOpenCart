<?php
$servername = "dansons-cluster-1.cluster-cjocz1mn6ubo.us-west-2.rds.amazonaws.com";
$username = "admin";
$password = "AQr7=P6Vk_<dN3+:";
$dbname = "suitecrm";
$dbname1 = "thiessens_dev";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$conn2 = new mysqli($servername, $username, $password, $dbname1);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$customer_id = $_GET['customer_id'];

$current_case_update_q = "SELECT firstname, lastname, email, telephone FROM oc_customer WHERE customer_id = " . $customer_id .";";
//$current_case_update_q = "SELECT customer_id, firstname, lastname, email, telephone FROM oc_customer;";
$current_case_updateq = $conn2->query($current_case_update_q);
$current_case_update = $current_case_updateq->fetch_assoc();
//while ($current_case_update = $current_case_updateq->fetch_assoc()) {
//$customer_id = $current_case_update['customer_id'];
$firstname = $current_case_update['firstname'];
$lastname = $current_case_update['lastname'];
$email = $current_case_update['email'];
$phone = preg_replace('/[^A-Za-z0-9]/', '', $current_case_update['telephone']);

$address1_cust_q = "SELECT *, oc_zone.code AS state_code FROM oc_address JOIN oc_zone ON oc_zone.zone_id = oc_address.zone_id JOIN oc_country ON oc_country.country_id = oc_address.country_id WHERE customer_id = " . $customer_id ." ORDER BY address_id LIMIT 1;";
$address1_custq = $conn2->query($address1_cust_q);
$address1_count = $address1_custq->num_rows;
$address1_cust = $address1_custq->fetch_assoc();

$address2_cust_q = "SELECT *, oc_zone.code AS state_code FROM oc_address JOIN oc_zone ON oc_zone.zone_id = oc_address.zone_id JOIN oc_country ON oc_country.country_id = oc_address.country_id WHERE customer_id = " . $customer_id ." ORDER BY address_id DESC LIMIT 1;";
$address2_custq = $conn2->query($address2_cust_q);
$address2_count = $address2_custq->num_rows;
$address2_cust = $address2_custq->fetch_assoc();

$current_contact_update_q = "SELECT count(id) AS records FROM contacts WHERE id = 'TWS" . $customer_id ."';";
$current_contact_updateq = $conn->query($current_contact_update_q);
$current_contact_update = $current_contact_updateq->fetch_assoc();

if($current_contact_update['records'] > 0){
    if($address1_count > 0){
        $query_1 = "UPDATE contacts SET ";
        $query_1 .= "date_modified = now(), description = '".$email."', ";
        $query_1 .= "first_name = '".htmlspecialchars($firstname, ENT_QUOTES)."', last_name = '".htmlspecialchars($lastname, ENT_QUOTES)."', phone_home = '".htmlspecialchars($phone, ENT_QUOTES)."', phone_mobile ='".htmlspecialchars($phone, ENT_QUOTES)."', ";
        $query_1 .= "primary_address_street = '".htmlspecialchars($address1_cust['address_1'], ENT_QUOTES)."', primary_address_city = '".htmlspecialchars($address1_cust['city'], ENT_QUOTES)."', primary_address_postalcode ='".htmlspecialchars($address1_cust['state_code'], ENT_QUOTES)."', primary_address_state ='".htmlspecialchars($address1_cust['state_code'], ENT_QUOTES)."', primary_address_country ='".htmlspecialchars($address1_cust['iso_code_3'], ENT_QUOTES)."', ";
        $query_1 .= "alt_address_street = '".htmlspecialchars($address2_cust['address_1'], ENT_QUOTES)."', alt_address_city = '".htmlspecialchars($address2_cust['city'], ENT_QUOTES)."', alt_address_postalcode ='".htmlspecialchars($address2_cust['state_code'], ENT_QUOTES)."', alt_address_state ='".htmlspecialchars($address2_cust['state_code'], ENT_QUOTES)."', alt_address_country ='".htmlspecialchars($address2_cust['iso_code_3'], ENT_QUOTES)."' ";
        $query_1 .= "WHERE id = 'TWS".$customer_id."';";
    }
    else{
        $query_1 = "UPDATE contacts SET ";
        $query_1 .= "date_modified = now(), description = '".$email."', ";
        $query_1 .= "first_name = '".htmlspecialchars($firstname, ENT_QUOTES)."', last_name = '".htmlspecialchars($lastname, ENT_QUOTES)."', phone_home = '".htmlspecialchars($phone, ENT_QUOTES)."', phone_mobile ='".htmlspecialchars($phone, ENT_QUOTES)."' ";
        $query_1 .= "WHERE id = 'TWS".$customer_id."';";
    }
    $add = $conn->query($query_1);
    //echo("Error description: " . mysqli_error($conn));

    $query_2 = "UPDATE email_addresses SET ";
    $query_2 .= "date_modified = now(), ";
    $query_2 .= "email_address = '".strtolower($email)."', email_address_caps = '".strtoupper($email)."' ";
    $query_2 .= "WHERE id = 'TWSE".$customer_id."';";
    $add = $conn->query($query_2);
    //echo("Error description: " . mysqli_error($conn));
}
else{
    /*
    $query_1 = "INSERT INTO contacts (";
    $query_1 .= "id, date_entered, date_modified, modified_user_id, created_by, ";
    $query_1 .= "description, deleted, assigned_user_id, first_name, last_name, phone_home, phone_mobile, ";
    $query_1 .= "lead_source, reports_to_id, portal_account_disabled, portal_user_type) VALUES (";
    $query_1 .= "'TWS".$customer_id."', now(), now(), 1, 1, ";
    $query_1 .= "'".$email."', 0, 1, '".$firstname."', '".$lastname."', '".$phone."', '".$phone."', ";
    $query_1 .= "'Web Site', '', 0, 'Single');";
    $add = $conn->query($query_1);
*/
    if($address1_count > 0){
        $query_1 = "INSERT INTO contacts SET ";
        $query_1 .= "id = 'TWS".$customer_id."', date_entered = now(), date_modified = now(), modified_user_id = 1, created_by = 1, description = '".$email."', deleted = 0, assigned_user_id = 1, lead_source = 'Web Site', reports_to_id = '', portal_account_disabled = 0, portal_user_type = 'Single', ";
        $query_1 .= "first_name = '".htmlspecialchars($firstname, ENT_QUOTES)."', last_name = '".htmlspecialchars($lastname, ENT_QUOTES)."', phone_home = '".htmlspecialchars($phone, ENT_QUOTES)."', phone_mobile ='".htmlspecialchars($phone, ENT_QUOTES)."', ";
        $query_1 .= "primary_address_street = '".htmlspecialchars($address1_cust['address_1'], ENT_QUOTES)."', primary_address_city = '".htmlspecialchars($address1_cust['city'], ENT_QUOTES)."', primary_address_postalcode ='".htmlspecialchars($address1_cust['state_code'], ENT_QUOTES)."', primary_address_state ='".htmlspecialchars($address1_cust['state_code'], ENT_QUOTES)."', primary_address_country ='".htmlspecialchars($address1_cust['iso_code_3'], ENT_QUOTES)."', ";
        $query_1 .= "alt_address_street = '".htmlspecialchars($address2_cust['address_1'], ENT_QUOTES)."', alt_address_city = '".htmlspecialchars($address2_cust['city'], ENT_QUOTES)."', alt_address_postalcode ='".htmlspecialchars($address2_cust['state_code'], ENT_QUOTES)."', alt_address_state ='".htmlspecialchars($address2_cust['state_code'], ENT_QUOTES)."', alt_address_country ='".htmlspecialchars($address2_cust['iso_code_3'], ENT_QUOTES)."'; ";
        $add = $conn->query($query_1);
        //echo("Error description: " . mysqli_error($conn));
    }
    else{
        $query_1 = "INSERT INTO contacts SET ";
        $query_1 .= "id = 'TWS".$customer_id."', date_entered = now(), date_modified = now(), modified_user_id = 1, created_by = 1, description = '".$email."', deleted = 0, assigned_user_id = 1, lead_source = 'Web Site', reports_to_id = '', portal_account_disabled = 0, portal_user_type = 'Single', ";
        $query_1 .= "first_name = '".htmlspecialchars($firstname, ENT_QUOTES)."', last_name = '".htmlspecialchars($lastname, ENT_QUOTES)."', phone_home = '".htmlspecialchars($phone, ENT_QUOTES)."', phone_mobile ='".htmlspecialchars($phone, ENT_QUOTES)."'; ";
        $add = $conn->query($query_1);
        //echo("Error description: " . mysqli_error($conn));
    }

    $query_2 = "INSERT INTO email_addresses (";
    $query_2 .= "id, date_created, date_modified, ";
    $query_2 .= "email_address, email_address_caps, invalid_email, opt_out, confirm_opt_in) VALUES (";
    $query_2 .= "'TWSE".$customer_id."', now(), now(), ";
    $query_2 .= "'".strtolower($email)."', '".strtoupper($email)."', 0, 0, 'opt-in');";
    $add = $conn->query($query_2);
    //echo("Error description: " . mysqli_error($conn));
    $query_3 = "INSERT INTO email_addr_bean_rel (";
    $query_3 .= "id, date_created, date_modified, ";
    $query_3 .= "email_address_id, bean_id, bean_module, primary_address, reply_to_address) VALUES (";
    $query_3 .= "'TWSBEAN".$customer_id."', now(), now(), ";
    $query_3 .= "'TWSE".$customer_id."', 'TWS".$customer_id."', 'Contacts', 1, 1);";
    $add = $conn->query($query_3);
    //echo("Error description: " . mysqli_error($conn));
    $query_4 = "INSERT INTO accounts_contacts (";
    $query_4 .= "id, date_modified, ";
    $query_4 .= "contact_id, account_id) VALUES (";
    $query_4 .= "'TWSA".$customer_id."', now(), ";
    $query_4 .= "'TWS".$customer_id."', '40000');";
    $add = $conn->query($query_4);//echo("Error description: " . mysqli_error($conn));
}
//}
$conn->close();
?>