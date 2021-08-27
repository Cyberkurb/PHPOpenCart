<?php
/**
 * @package		AWS
 * @author		Justin Lonsway
*/

include_once(DIR_SYSTEM . 'library/aws/aws-autoloader.php');
class Aws {
	protected $to;
	protected $from;
	protected $sender;
	protected $reply_to;
	protected $reply_to_sender;
	protected $readreceipt;
	protected $subject;
	protected $text;
	protected $html;
	protected $attachments = array();
	public $parameter;

	/**
	 * Constructor
	 *
	 * @param	string	$adaptor
	 *
 	*/
	public function __construct($adaptor = 'aws') {
		$class = 'Aws\\' . $adaptor;
		
		if (class_exists($class)) {
			$this->adaptor = new $class();
		} else {
			trigger_error('Error: Could not load aws adaptor ' . $adaptor . '!');
			exit();
		}	
	}
	
	
}