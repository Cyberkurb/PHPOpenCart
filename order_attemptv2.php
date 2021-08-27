<?php
$apiurl = 'https://newcrm.dansonscorp.com/Api/';
$servername = "dansons-cluster-1.cluster-cjocz1mn6ubo.us-west-2.rds.amazonaws.com";
$username = "integration_user";
$password = '<H6b2n;9?wD"eR@5';
$dbname = "suitecrm_new";
$dbname1 = "dansonsns";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$conn2 = new mysqli($servername, $username, $password, $dbname1);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$order_id = $_GET['order_id'];
$i = 0;
$current_case_update_q = "SELECT o.order_id, ";
$current_case_update_q .= "o.customer_id, ";
$current_case_update_q .= "o.store_url, ";
$current_case_update_q .= "o.firstname, ";
$current_case_update_q .= "o.lastname, ";
$current_case_update_q .= "o.email, ";
$current_case_update_q .= "o.telephone, ";
$current_case_update_q .= "o.payment_firstname, ";
$current_case_update_q .= "o.payment_lastname, ";
$current_case_update_q .= "o.payment_address_1, ";
$current_case_update_q .= "o.payment_address_2, ";
$current_case_update_q .= "o.payment_city, ";
$current_case_update_q .= "o.payment_zone, ";
$current_case_update_q .= "o.payment_postcode, ";
$current_case_update_q .= "o.payment_country, ";
$current_case_update_q .= "o.payment_method, ";
$current_case_update_q .= "o.shipping_firstname, ";
$current_case_update_q .= "o.shipping_lastname, ";
$current_case_update_q .= "o.shipping_address_1, ";
$current_case_update_q .= "o.shipping_address_2, ";
$current_case_update_q .= "o.shipping_city, ";
$current_case_update_q .= "o.shipping_country, ";
$current_case_update_q .= "o.shipping_zone, ";
$current_case_update_q .= "o.shipping_postcode, ";
$current_case_update_q .= "o.shipping_method, ";
$current_case_update_q .= "o.total, ";
$current_case_update_q .= "o.order_status_id, ";
$current_case_update_q .= "os.name AS order_status, ";
$current_case_update_q .= "pft.Message AS cc_message, ";
$current_case_update_q .= "pft.Status AS cc_status, ";
$current_case_update_q .= "o.username ";
$current_case_update_q .= "FROM oc_order o ";
$current_case_update_q .= "LEFT JOIN oc_order_status os ON os.order_status_id = o.order_status_id ";
$current_case_update_q .= "LEFT JOIN oc_payfabric_transaction pft ON pft.order_id = o.order_id ";
$current_case_update_q .= "WHERE o.order_id = " . $order_id .";";
$current_case_updateq = $conn2->query($current_case_update_q);
$current_case_update = $current_case_updateq->fetch_assoc();
//while ($current_case_update = $current_case_updateq->fetch_assoc()) {

    $current_contact_update_q = "SELECT count(id) AS records, id AS id FROM oratt_online_order_attempts WHERE id = 'DWS" . $current_case_update['order_id'] ."' GROUP BY id;";
    $current_contact_updateq = $conn->query($current_contact_update_q);
    $current_contact_update = $current_contact_updateq->fetch_assoc();

    $contact_q = "SELECT id_c AS id FROM contacts_cstm WHERE integration_id_c = 'DWS" . $current_case_update['customer_id']."' GROUP BY id_c;";
    $contactq = $conn->query($contact_q);
    $contact = $contactq->fetch_assoc();

    if($current_case_update['order_status_id'] == 0){
        $current_case_update['order_status'] = 'Incomplete';
    }
    else{
        $current_case_update['order_status'] = $current_case_update['order_status'];
    }

if($current_contact_update['records'] > 0){
    $update_attempt_q = "UPDATE oratt_online_order_attempts SET ";
    $update_attempt_q .= "name = '" . $current_case_update['store_url'] . "', ";
    $update_attempt_q .= "date_modified = now() ";
    $update_attempt_q .= "WHERE id = 'DWS" . $current_case_update['order_id'] . "'; ";
    $update_attempt = $conn->query($update_attempt_q);

    $update_attempt_q = "UPDATE oratt_online_order_attempts_cstm SET ";
    $update_attempt_q .= "status_c = '" . $current_case_update['order_status'] . "', ";
    $update_attempt_q .= "entered_by_c = '" . $current_case_update['username'] . "', ";
    $update_attempt_q .= "payment_address_c = '" . htmlspecialchars($current_case_update['payment_address_1'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "payment_address_2_c = '" . htmlspecialchars($current_case_update['payment_address_2'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "payment_city_c = '" . htmlspecialchars($current_case_update['payment_city'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "payment_state_c = '" . htmlspecialchars($current_case_update['payment_zone'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "payment_postcode_c = '" . htmlspecialchars($current_case_update['payment_postcode'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "payment_country_c = '" . htmlspecialchars($current_case_update['payment_country'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "payment_method_c = '" . htmlspecialchars($current_case_update['payment_method'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "shipping_address_c = '" . htmlspecialchars($current_case_update['shipping_address_1'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "shipping_address_2_c = '" . htmlspecialchars($current_case_update['shipping_address_2'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "shipping_city_c = '" . htmlspecialchars($current_case_update['shipping_city'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "shipping_state_c = '" . htmlspecialchars($current_case_update['shipping_zone'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "shipping_postcode_c = '" . htmlspecialchars($current_case_update['shipping_postcode'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "shipping_country_c = '" . htmlspecialchars($current_case_update['shipping_country'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "shipping_method_c = '" . htmlspecialchars($current_case_update['shipping_method'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "id_number_c = '" . htmlspecialchars($current_case_update['order_id'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "total_c = '" . htmlspecialchars($current_case_update['total'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "cc_message_c = '" . htmlspecialchars($current_case_update['cc_message'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "cc_status_c = '" . htmlspecialchars($current_case_update['cc_status'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "payment_firstname_c = '" . htmlspecialchars($current_case_update['payment_firstname'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "payment_lastname_c = '" . htmlspecialchars($current_case_update['payment_lastname'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "shipping_firstname_c = '" . htmlspecialchars($current_case_update['shipping_firstname'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "shipping_lastname_c = '" . htmlspecialchars($current_case_update['shipping_lastname'], ENT_QUOTES) . "' ";
    $update_attempt_q .= "WHERE id_c = 'DWS" . $current_case_update['order_id'] . "'; ";
    $update_attempt = $conn->query($update_attempt_q);

    $update_attempt_q = "UPDATE contacts_oratt_online_order_attempts_1_c SET ";
    $update_attempt_q .= "contacts_oratt_online_order_attempts_1contacts_ida = '" . $contact['id'] . "', ";
    $update_attempt_q .= "contacts_of10cttempts_idb = 'DWS" . $current_case_update['order_id'] . "', ";
    $update_attempt_q .= "date_modified = now() ";
    $update_attempt_q .= "WHERE id = 'DWS" . $current_case_update['order_id'] . "'; ";
    $update_attempt = $conn->query($update_attempt_q);

}
else{
    $update_attempt_q = "INSERT INTO oratt_online_order_attempts SET ";
    $update_attempt_q .= "id = 'DWS" . $current_case_update['order_id'] . "', ";
    $update_attempt_q .= "name = '" . $current_case_update['store_url'] . "', ";
    $update_attempt_q .= "date_entered = now(), ";
    $update_attempt_q .= "date_modified = now() ";
    $update_attempt = $conn->query($update_attempt_q);

    $update_attempt_q = "INSERT INTO oratt_online_order_attempts_cstm SET ";
    $update_attempt_q .= "id_c = 'DWS" . $current_case_update['order_id'] . "', ";
    $update_attempt_q .= "status_c = '" . $current_case_update['order_status'] . "', ";
    $update_attempt_q .= "entered_by_c = '" . $current_case_update['username'] . "', ";
    $update_attempt_q .= "payment_address_c = '" . htmlspecialchars($current_case_update['payment_address_1'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "payment_address_2_c = '" . htmlspecialchars($current_case_update['payment_address_2'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "payment_city_c = '" . htmlspecialchars($current_case_update['payment_city'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "payment_state_c = '" . htmlspecialchars($current_case_update['payment_zone'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "payment_postcode_c = '" . htmlspecialchars($current_case_update['payment_postcode'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "payment_country_c = '" . htmlspecialchars($current_case_update['payment_country'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "payment_method_c = '" . htmlspecialchars($current_case_update['payment_method'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "shipping_address_c = '" . htmlspecialchars($current_case_update['shipping_address_1'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "shipping_address_2_c = '" . htmlspecialchars($current_case_update['shipping_address_2'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "shipping_city_c = '" . htmlspecialchars($current_case_update['shipping_city'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "shipping_state_c = '" . htmlspecialchars($current_case_update['shipping_zone'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "shipping_postcode_c = '" . htmlspecialchars($current_case_update['shipping_postcode'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "shipping_country_c = '" . htmlspecialchars($current_case_update['shipping_country'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "shipping_method_c = '" . htmlspecialchars($current_case_update['shipping_method'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "id_number_c = '" . htmlspecialchars($current_case_update['order_id'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "total_c = '" . htmlspecialchars($current_case_update['total'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "cc_message_c = '" . htmlspecialchars($current_case_update['cc_message'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "cc_status_c = '" . htmlspecialchars($current_case_update['cc_status'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "payment_firstname_c = '" . htmlspecialchars($current_case_update['payment_firstname'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "payment_lastname_c = '" . htmlspecialchars($current_case_update['payment_lastname'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "shipping_firstname_c = '" . htmlspecialchars($current_case_update['shipping_firstname'], ENT_QUOTES) . "', ";
    $update_attempt_q .= "shipping_lastname_c = '" . htmlspecialchars($current_case_update['shipping_lastname'], ENT_QUOTES) . "' ";
    $update_attempt = $conn->query($update_attempt_q);

    $update_attempt_q = "INSERT INTO contacts_oratt_online_order_attempts_1_c SET ";
    $update_attempt_q .= "contacts_oratt_online_order_attempts_1contacts_ida = '" . $contact['id'] . "', ";
    $update_attempt_q .= "contacts_of10cttempts_idb = 'DWS" . $current_case_update['order_id'] . "', ";
    $update_attempt_q .= "date_modified = now(), ";
    $update_attempt_q .= "id = 'DWS" . $current_case_update['order_id'] . "'; ";
    $update_attempt = $conn->query($update_attempt_q);
   
}

//$i++;
//}
$conn->close();
$conn2->close();
?>