<?php
$servername = "dansons-cluster-1.cluster-cjocz1mn6ubo.us-west-2.rds.amazonaws.com";
$username = "integration_user";
$password = '<H6b2n;9?wD"eR@5';
$dbname = "suitecrm_new";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$casenumber = $_GET['casenumber'];
$conact_id = $_GET['contact_id'];

$current_contact_update_q = "SELECT count(id_c) AS records, id_c AS id FROM contacts_cstm WHERE integration_id_c = 'DWS" . $conact_id ."' GROUP BY id_c;";
$current_contact_updateq = $conn->query($current_contact_update_q);
$current_contact_update = $current_contact_updateq->fetch_assoc();


$current_case_q = "SELECT id AS id FROM cases WHERE case_number = '" . $casenumber ."';";
$current_caseq = $conn->query($current_case_q);
$current_case = $current_caseq->fetch_assoc();


$current_q = "SELECT count(id) AS records FROM contacts_cases WHERE contact_id = '" . $current_contact_update['id'] . "' AND case_id = '" . $current_case['id'] . "';";
$currentq = $conn->query($current_q);
$current = $currentq->fetch_assoc();

if($current['records'] > 0){
    
}
else{
    $query_1 = "INSERT INTO contacts_cases (";
    $query_1 .= "id, date_modified, ";
    $query_1 .= "contact_id, case_id) VALUES (";
    $query_1 .= "'DWS".$conact_id."-". $casenumber . "', now(), ";
    $query_1 .= "'".$current_contact_update['id']."', '".$current_case['id']."');";
    $add = $conn->query($query_1);
}

$conn->close();
?>