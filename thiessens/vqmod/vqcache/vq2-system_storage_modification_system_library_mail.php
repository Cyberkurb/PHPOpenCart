<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Mail class
*/
include_once(\VQMod::modCheck(DIR_SYSTEM . 'library/phpmailer/class.phpmailer.php'));
class Mail {
	protected $to;
	protected $from;
	protected $sender;
	protected $reply_to;
	protected $subject;
	protected $text;
	protected $html;
	protected $attachments = array();
	public $parameter;

	protected static $event = null;
			

	/**
	 * Constructor
	 *
	 * @param	string	$adaptor
	 *
 	*/
	public function __construct($adaptor = 'mail') {
		$class = 'Mail\\' . $adaptor;
		
		if (class_exists($class)) {
			$this->adaptor = new $class();
		} else {
			trigger_error('Error: Could not load mail adaptor ' . $adaptor . '!');
			exit();
		}	

		// find the OpenCart Event object from calling class if not yet set here
		if (self::$event==null) {
			$object = null;
			$backtrace = debug_backtrace();
			if (!empty($backtrace[1]['class'])) {
				$class = $backtrace[1]['class'];
				if (substr($class,0,strlen('Controller'))=='Controller') {
					if (!empty($backtrace[1]['object'])) {
						$object = $backtrace[1]['object'];
					}
				} else if (substr($class,0,strlen('Model'))=='Model') {
					if (!empty($backtrace[1]['object'])) {
						$object = $backtrace[1]['object'];
					}
				}
			}
			if ($object) {
				if ($object->event) {
					self::$event = $object->event;
				}
			}
		}
			
	}
	
	/**
     * 
     *
     * @param	mixed	$to
     */
	public function setTo($to) {
		$this->to = $to;
	}
	
	/**
     * 
     *
     * @param	string	$from
     */
	public function setFrom($from) {
		$this->from = $from;
	}
	
	/**
     * 
     *
     * @param	string	$sender
     */
	public function setSender($sender) {
		$this->sender = $sender;
	}
	
	/**
     * 
     *
     * @param	string	$reply_to
     */
	public function setReplyTo($reply_to) {
		$this->reply_to = $reply_to;
	}
	
	/**
     * 
     *
     * @param	string	$subject
     */
	public function setSubject($subject) {
		$this->subject = $subject;
	}
	
	/**
     * 
     *
     * @param	string	$text
     */
	public function setText($text) {
		$this->text = $text;
	}
	
	/**
     * 
     *
     * @param	string	$html
     */
	public function setHtml($html) {
		$this->html = $html;
	}
	
	/**
     * 
     *
     * @param	string	$filename
     */
	public function addAttachment($filename) {
		$this->attachments[] = $filename;
	}
	
	/**
     * 
     *
     */
	public function send() {

		// if on catalog frontend then trigger a before-event
		if (!defined('DIR_CATALOG')) { 
			if (self::$event) {
				self::$event->trigger( 'system/mail/send/before', array(&$this) );
			}
		}
			
		if (!$this->to) {
			throw new \Exception('Error: E-Mail to required!');
		}

		if (!$this->from) {
			throw new \Exception('Error: E-Mail from required!');
		}

		if (!$this->sender) {
			throw new \Exception('Error: E-Mail sender required!');
		}

		if (!$this->subject) {
			throw new \Exception('Error: E-Mail subject required!');
		}

		if ((!$this->text) && (!$this->html)) {
			throw new \Exception('Error: E-Mail message required!');
		}
		/*
		foreach (get_object_vars($this) as $key => $value) {
			$this->adaptor->$key = $value;
		}
		

		// if on catalog frontend then trigger an after-event
		if (!defined('DIR_CATALOG')) { 
			if (self::$event) {
				self::$event->trigger( 'system/mail/send/after', array(&$this) );
			}
		}
			
		$this->adaptor->send();
		*/
		$mail  = new PHPMailer();
		$mail->IsSMTP();

		$mail->SetFrom($this->from, $this->sender);
		
		if (is_array($this->to)) {
			foreach ($this->to as $toTmp){
				$mail->AddAddress($toTmp);
			}
		} else {
			$mail->AddAddress($this->to);
		}

		$mail->Username = 'AKIAI2ZUTFYCNUOGOVYQ';
		$mail->Password = 'BOIc397Wx9YmjW8t3nRB/KYmZzzOWrq/MhTtS/+YRbMB';
		
		$mail->addCustomHeader('X-SES-CONFIGURATION-SET', 'ConfigSet');
		
		$mail->Host = 'email-smtp.us-east-1.amazonaws.com';

		$mail->Subject = $this->subject;
		
		
		$mail->AddReplyTo($this->from, $this->sender);

		$mail->Body = $this->text;
		$mail->AltBody = $this->text;
		// Tells PHPMailer to use SMTP authentication
		$mail->SMTPAuth = true;

		// Enable TLS encryption over port 587
		$mail->SMTPSecure = 'tls';
		$mail->Port = 587;

		// Tells PHPMailer to send HTML-formatted email
		$mail->isHTML(true);
		
		$mail->Send();
	}
}