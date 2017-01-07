<?php

include('libs/phpmailer/class.phpmailer.php');

class Core_Mail extends PHPMailer { public $Host		= 'localhost';
	
	public $From		= CONFIG_MAIL_FROM;
	public $FromName	= CONFIG_MAIL_FROM_NAME;
	public $Sender		= CONFIG_MAIL_FROM;
	
	public $ContentType = 'text/html';
	
	/**
	 * constructor
	 *
	 * @param string $mailer
	 */
	public function __construct( $mailer = 'mail' ) {
		
		if ( in_array( $mailer , array( 'mail' , 'sendmail' , 'smtp' ) ) ){
			$this->Mailer = $mailer;
		} else {
			$this->Mailer = 'mail';
		}		
	}
}