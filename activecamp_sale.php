<?php

$servername = "dansons-cluster-1.cluster-cjocz1mn6ubo.us-west-2.rds.amazonaws.com";
$username = "integration_user";
$password = '<H6b2n;9?wD"eR@5';
$dbname = "dansonsns";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


$customer_id = $_GET['customer_id'];
$order_id = $_GET['order_id'];

$current_case_update_q = "SELECT firstname, lastname, email, telephone, store_id FROM oc_customer WHERE customer_id = " . (int)$customer_id .";";
$current_case_updateq = $conn->query($current_case_update_q);
$current_case_update = $current_case_updateq->fetch_assoc();

$current_sale_update_q = "SELECT order_product_id, model FROM oc_order_product WHERE order_id = " . (int)$order_id .";";
$current_sale_updateq = $conn->query($current_sale_update_q);
$current_sale_update = $current_sale_updateq->fetch_assoc();

$firstname = $current_case_update['firstname'];
$lastname = $current_case_update['lastname'];
$email = $current_case_update['email'];
$store_id = $current_case_update['store_id'];
$phone = preg_replace('/[^A-Za-z0-9]/', '', $current_case_update['telephone']);

if($store_id == 0){
    $list_id = 3;
}
elseif($store_id == 9){
    $list_id = 3;
}
elseif($store_id == 10){
    $list_id = 3;
}
elseif($store_id == 1){
    $list_id = 2;
}
elseif($store_id == 11){
    $list_id = 2;
}
elseif($store_id == 4){
    $list_id = 2;
}
elseif($store_id == 2){
    $list_id = 4;
}
elseif($store_id == 15){
    $list_id = 11;
}
elseif($store_id == 13){
    $list_id = 5;
}
else{
    $list_id = 3;
}
// By default, this sample code is designed to get the result from your ActiveCampaign installation and print out the result
$url = 'https://dansons.api-us1.com';


$params = array(

    // the API Key can be found on the "Your Settings" page under the "API" tab.
    // replace this with your API Key
    'api_key'      => 'e982a80b8204db92df0667e0222c942601ebdd0e73d0474b152d9934853467eefe3b1865',

    // this is the action that adds a contact
    'api_action'   => 'contact_tag_add',

    // define the type of output you wish to get back
    // possible values:
    // - 'xml'  :      you have to write your own XML parser
    // - 'json' :      data is returned in JSON format and can be decoded with
    //                 json_decode() function (included in PHP since 5.2.0)
    // - 'serialize' : data is returned in a serialized format and can be decoded with
    //                 a native unserialize() function
    'api_output'   => 'serialize',
);

// here we define the data we are posting in order to perform an update
    $post = array(
        'email' => $email, // contact email address (pass this OR the contact ID)
        //'id' => 12, // contact ID (pass this OR the contact email address)
        'tags' => 'Status - Online Purchase',
        // or multiple tags
        //'tags[]' => 'tag1',
        //'tags[]' => 'tag2',
    );
    
    // This section takes the input fields and converts them to the proper format
    $query = "";
    foreach( $params as $key => $value ) $query .= urlencode($key) . '=' . urlencode($value) . '&';
    $query = rtrim($query, '& ');
    
    // This section takes the input data and converts it to the proper format
    $data = "";
    foreach( $post as $key => $value ) $data .= urlencode($key) . '=' . urlencode($value) . '&';
    $data = rtrim($data, '& ');
    
    // clean up the url
    $url = rtrim($url, '/ ');
    
    // This sample code uses the CURL library for php to establish a connection,
    // submit your request, and show (print out) the response.
    if ( !function_exists('curl_init') ) die('CURL not supported. (introduced in PHP 4.0.2)');
    
    // If JSON is used, check if json_decode is present (PHP 5.2.0+)
    if ( $params['api_output'] == 'json' && !function_exists('json_decode') ) {
        die('JSON not supported. (introduced in PHP 5.2.0)');
    }
    
    // define a final API request - GET
    $api = $url . '/admin/api.php?' . $query;
    
    $request = curl_init($api); // initiate curl object
    curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
    curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
    curl_setopt($request, CURLOPT_POSTFIELDS, $data); // use HTTP POST to send form data
    //curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment if you get no gateway response and are using HTTPS
    curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
    
    $response = (string)curl_exec($request); // execute curl post and store results in $response
    
    // additional options may be required depending upon your server configuration
    // you can find documentation on curl options at http://www.php.net/curl_setopt
    curl_close($request); // close curl object
    usleep(150000);
    while($order_product = $current_sale_update){
	
// here we define the data we are posting in order to perform an update
    $post = array(
        'email' => $email, // contact email address (pass this OR the contact ID)
        'tags' => 'Sale Item - ' . $order_product['model'] . ' ',
    );
    
    // This section takes the input fields and converts them to the proper format
    $query = "";
    foreach( $params as $key => $value ) $query .= urlencode($key) . '=' . urlencode($value) . '&';
    $query = rtrim($query, '& ');
    
    // This section takes the input data and converts it to the proper format
    $data = "";
    foreach( $post as $key => $value ) $data .= urlencode($key) . '=' . urlencode($value) . '&';
    $data = rtrim($data, '& ');
    
    // clean up the url
    $url = rtrim($url, '/ ');
    
    // This sample code uses the CURL library for php to establish a connection,
    // submit your request, and show (print out) the response.
    if ( !function_exists('curl_init') ) die('CURL not supported. (introduced in PHP 4.0.2)');
    
    // If JSON is used, check if json_decode is present (PHP 5.2.0+)
    if ( $params['api_output'] == 'json' && !function_exists('json_decode') ) {
        die('JSON not supported. (introduced in PHP 5.2.0)');
    }
    
    // define a final API request - GET
    $api = $url . '/admin/api.php?' . $query;
    
    $request = curl_init($api); // initiate curl object
    curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
    curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
    curl_setopt($request, CURLOPT_POSTFIELDS, $data); // use HTTP POST to send form data
    //curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment if you get no gateway response and are using HTTPS
    curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
    
    $response = (string)curl_exec($request); // execute curl post and store results in $response
    
    // additional options may be required depending upon your server configuration
    // you can find documentation on curl options at http://www.php.net/curl_setopt
    curl_close($request); // close curl object
    usleep(150000);
}
?>