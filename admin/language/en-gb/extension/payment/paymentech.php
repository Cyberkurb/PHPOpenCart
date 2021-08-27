<?php
// Heading
$_['heading_title']      = 'Chase Paymentech Orbital';

// Text 
$_['text_payment']       = 'Payment';
$_['text_success']       = 'Success: You have modified Paymentech Checkout account details!';
$_['text_paymentech']        = '<a href="http://www.chasepaymentech.com/" target="_blank"><img src="view/image/payment/chase-paymentech.jpg" alt="Paymentech" title="Paymentech" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_authorization'] = 'Authorization';
$_['text_sale']          = 'Sale';

// Entry
$_['entry_test_merchant']     = 'Test Merchant ID:';
$_['entry_pr_merchant']     = 'Production Merchant ID:';
$_['entry_payment_trace']    = 'Paymentech Trace ID:';

$_['entry_payment_bin']    = 'Paymentech BIN:<br /><i class="help">000001 = Salem 000002 = Tampa(default)</i>';
$_['entry_payment_msgtype']    = 'Message Type Request:<br /><i class="help">Authorization OR Authorization/Capture request AC(Default)</i>';

$_['entry_payment_tz']    = 'TzCode (timezone):<br /><i class="help">705 = Eastern<br />105 = Indiana<br />706 = Central<br />707 = Mountain <br />107 = Arizona <br />708 = Pacific<br />709 = Alaska<br /> 110 = Hawaii</i>';
$_['entry_debug']   = 'Debug:<br /><i class="help">This will create API response log file in system/log/error.txt folder.</i>';

$_['entry_test']         = 'Test Mode:<br /><i class="help">Use the live or testing (sandbox) gateway server to process transactions?</i>';
$_['entry_transaction']  = 'Transaction Method:';
$_['entry_total']        = 'Total:<br /><i class="help">The checkout total the order must reach before this payment method becomes active.</i>';
$_['entry_order_status'] = 'Order Status:';
$_['entry_geo_zone']     = 'Geo Zone:';
$_['entry_status']       = 'Status:';
$_['entry_sort_order']   = 'Sort Order:';
$_['entry_username']   = 'Username:';
$_['entry_password']   = 'Password:';


// Error
$_['error_permission']   = 'Warning: You do not have permission to modify payment Paymentech Checkout!';
$_['error_username']     = 'API Username Required!'; 
$_['error_password']     = 'API Password Required!'; 
$_['error_signature']    = 'API Signature Required!'; 
?>