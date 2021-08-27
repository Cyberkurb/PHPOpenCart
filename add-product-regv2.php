<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
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

$product_id = $_GET['product_id'];

$i = 0;

$current_case_update_q = "SELECT cp.product_id, cp.customer_id, p.model, pd.name, cp.serialnumber, cp.purchasedate, cp.purchaselocation, cp.dateregistered, c.code FROM oc_customer_product cp JOIN oc_coupon c ON c.coupon_id = cp.coupon_id LEFT JOIN oc_product p ON p.product_id = cp.purchaseproduct_id LEFT JOIN oc_product_description pd ON pd.product_id = cp.purchaseproduct_id WHERE cp.product_id = '" . $product_id . "' ORDER BY cp.dateregistered DESC LIMIT 1;";
$current_case_updateq = $conn2->query($current_case_update_q);
$current_case_update = $current_case_updateq->fetch_assoc();

//while ($current_case_update = $current_case_updateq->fetch_assoc()) {

    $current_contact_update_q = "SELECT count(id_c) AS records, id_c AS id FROM contacts_cstm WHERE integration_id_c = 'DWS" . $current_case_update['customer_id'] ."' GROUP BY id_c;";
    $current_contact_updateq = $conn->query($current_contact_update_q);
    $current_contact_update = $current_contact_updateq->fetch_assoc();

    $current_product_q = "SELECT id AS id FROM aos_products WHERE part_number = '" . $current_case_update['model'] . "';";
    $current_productq = $conn->query($current_product_q);
    $current_product = $current_productq->fetch_assoc();

    $query_research = "SELECT count(id) AS accounts FROM prn_registeredonlineproducts WHERE id = '" . $current_case_update['product_id'] . "';";
    $q_research = $conn->query($query_research);
    $research = $q_research->fetch_assoc();

    if($research['accounts'] > 0){
        echo "Update";
        $query_1 = "UPDATE prn_registeredonlineproducts SET ";
        $query_1 .= "date_modified = now(), ";
        $query_1 .= "description = '".htmlspecialchars($current_case_update['serialnumber'], ENT_QUOTES)." - ".htmlspecialchars($current_case_update['purchaselocation'], ENT_QUOTES)."', ";
        $query_1 .= "name = '".htmlspecialchars($current_case_update['name'], ENT_QUOTES)."', ";
        $query_1 .= "purchase_date = '".htmlspecialchars($current_case_update['purchasedate'], ENT_QUOTES)."', ";
        $query_1 .= "couponcode = '".htmlspecialchars($current_case_update['name'], ENT_QUOTES)."', ";
        $query_1 .= "product_name = '".htmlspecialchars($current_case_update['code'], ENT_QUOTES)."', ";
        $query_1 .= "serial_number = '".htmlspecialchars($current_case_update['serialnumber'], ENT_QUOTES)."' ";
        $query_1 .= "WHERE id = '".$current_case_update['product_id']."';";
        $add = $conn->query($query_1);
    }
    else{
        echo "add";
        $query_1 = "INSERT INTO prn_registeredonlineproducts (";
        $query_1 .= "id, date_entered, date_modified, modified_user_id, created_by, ";
        $query_1 .= "description, name, purchase_date, product_name, couponcode, serial_number) VALUES (";
        $query_1 .= "'".$current_case_update['product_id']."', now(), now(), 1, 1, ";
        $query_1 .= "'".htmlspecialchars($current_case_update['serialnumber'], ENT_QUOTES)." - ".htmlspecialchars($current_case_update['purchaselocation'], ENT_QUOTES)."', '".htmlspecialchars($current_case_update['name'], ENT_QUOTES)."', ";
        $query_1 .= "'".htmlspecialchars($current_case_update['purchasedate'], ENT_QUOTES)."', '".htmlspecialchars($current_case_update['name'], ENT_QUOTES)."', '".htmlspecialchars($current_case_update['code'], ENT_QUOTES)."', '".htmlspecialchars($current_case_update['serialnumber'], ENT_QUOTES)."');";
        $add = $conn->query($query_1);
    }

    $query_research2 = "SELECT count(id) AS accounts FROM prn_registeredonlineproducts_contacts_c WHERE id = '" . $current_case_update['product_id'] . "';";
    $q_research2 = $conn->query($query_research2);
    $research2 = $q_research2->fetch_assoc();

    if($research2['accounts'] > 0){
        echo "Update";
        $query_2 = "UPDATE prn_registeredonlineproducts_contacts_c SET ";
        $query_2 .= "date_modified = now(), ";
        $query_2 .= "prn_regist9b02roducts_idb = '".$current_case_update['product_id']."', prn_registeredonlineproducts_contactscontacts_ida = '".$current_contact_update['id']."' WHERE id = '" . $current_case_update['product_id'] . "';";
        $add = $conn->query($query_2);
    }
    else{
        echo "Add";
        $query_2 = "INSERT INTO prn_registeredonlineproducts_contacts_c (";
        $query_2 .= "id, date_modified, ";
        $query_2 .= "prn_regist9b02roducts_idb, prn_registeredonlineproducts_contactscontacts_ida) VALUES (";
        $query_2 .= "'".$current_case_update['product_id']."', now(), ";
        $query_2 .= "'".$current_case_update['product_id']."', '".$current_contact_update['id']."');";
        $add = $conn->query($query_2);
    }

    $query_research3 = "SELECT count(id) AS accounts FROM prn_registeredonlineproducts_aos_products_c WHERE id = '" . $current_case_update['product_id'] . "';";
    $q_research3 = $conn->query($query_research3);
    $research3 = $q_research3->fetch_assoc();

    if($research3['accounts'] > 0){
        echo "Update";
        $query_2 = "UPDATE prn_registeredonlineproducts_aos_products_c SET ";
        $query_2 .= "date_modified = now(), ";
        $query_2 .= "prn_registda1aroducts_idb = '".$current_case_update['product_id']."', prn_registeredonlineproducts_aos_productsaos_products_ida = '".$current_contact_update['id']."' WHERE id = '" . $current_case_update['product_id'] . "';";
        $add = $conn->query($query_2);
    }
    else{
        echo "Add";
        $query_2 = "INSERT INTO prn_registeredonlineproducts_aos_products_c (";
        $query_2 .= "id, date_modified, ";
        $query_2 .= "prn_registda1aroducts_idb, prn_registeredonlineproducts_aos_productsaos_products_ida) VALUES (";
        $query_2 .= "'".$current_case_update['product_id']."', now(), ";
        $query_2 .= "'".$current_case_update['product_id']."', '".$current_contact_update['id']."');";
        $add = $conn->query($query_2);
    }

//}
$conn->close();
?>