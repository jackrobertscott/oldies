<?php
/**
*@author Jack Scott
*@version v1.0 7/14
*/
class EmailMonkey extends Base
{
	private $COMPANYNAME;
	private $websiteurl;
	private $supemail;
	private $urlwtags;
	private $email_des = array();
	public $errors = array();

	/*
	*
	* Remember: before creating an email monkey object, check the user has not already unsubscribed from emails.
	*
	*/

	function __construct()
	{
		parent::__construct();
		$this->urlwtags = 'http://'.WEBURL.'/';
		$this->email_des['header'] = '<html>
										<body style="background-color: #efefef;margin: 0;padding: 0;font-family: arial;">
											<p style="padding: 0;margin: 0;color: #efefef;width: 100%;">New: </p>
											<div style="background-color: #ffffff;margin: 10px auto;padding: 10px;width: 560px;-webkit-border-radius: 3px;-moz-border-radius: 3px;border-radius: 3px;">
												<h1 style="text-align: center;margin: 20px 0 0 0;padding: 0;">'.COMPANYNAME.'</h1>
												<h4 style="text-align: center;margin: 0;padding: 10px 0 0;">'.COMPANYSLOGAN.'</h4>
												<br><hr>';
		$this->email_des['footer'] = 			'<br>
												<p>Sincerely Yours,<br><br>'.COMPANYNAME.'<br><br><a href="'.$this->urlwtags.'" style="text-decoration: none;">'.WEBURL.'</a></p>
												<hr>
												<p style="font-size: 10px;text-align: center;">'.COMPANYNAME.'. 74 James Street, Northbridge, WA 6003</p>
												<p style="font-size: 10px;text-align: center;">You\'ve received this service announcement email to update you about changes within '.COMPANYNAME.'.</p>
												<p style="font-size: 10px;text-align: center;"><a href="'.$this->urlwtags.'unsubscribe.php" style="text-decoration:none;">Unsubscribe</a></p>
											</div>
											<p style="padding: 0;margin: 0;color: #efefef;width: 100%;">...</p>
										</body>
										</html>';
	}

	/**
	*Send a verification email
	*
	*@param 	$direction 		String, email adress to send email to
	*@param 	$verCode 		String, verification code to activate users account
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function verifyemail($verCode, $direction)
	{
		$phpemail = new PHPMailer();
		$phpemail->isHTML(true);
		$phpemail->Subject   = 'Account Verification';
		$phpemail->FromName   = COMPANYNAME;
		$phpemail->Body      = $this->email_des['header'] .
				'<h4>Email Verification</h4>' .
				'<p>To verify your email adress follow the link below or enter the code in manualy.</p><br>' .
				'<a href="'.$this->urlwtags.'verify.php?verLinkCode=' . $verCode . '" style="text-decoration: none;margin: 0;padding: 0;">' .
				'<div style="width: 100%;background-color: #77d87a;border: 1px solid #40c844;">' .
					'<h2 style="width: 100%;text-align: center;color: #ffffff;margin: 16px 0;padding: 0;">Verify</h2>' .
				'</div>' .
				'</a>' .
				'<br><p>Verification code: ' . $verCode . '</p>' .
				$this->email_des['footer'];
		$phpemail->AddAddress($direction);
		if(!$phpemail->send()){
			$this->errors[] = "There is something wrong with our servers, your verification email was unable to be sent to " . $direction;
			return false; 
		}
		return true;
	}

	/**
	*Send the user their new password
	*
	*@param 	$direction 		String, email adress to send email to
	*@param 	$np 			String, new password
	*/
	public function passEmail($direction, $np)
	{
		$phpemail = new PHPMailer();
		$phpemail->isHTML(true);
		$phpemail->Subject   = 'Password Reset';
		$phpemail->FromName   = COMPANYNAME;
		$phpemail->Body      = $this->email_des['header'] .
				'<h3>Reset</h3>' .
				'<p>Your password has been reset.</p>' .
				'<p>New password: ' . $np . '</p>' .
				$this->email_des['footer'];
		$phpemail->AddAddress($direction);
		if(!$phpemail->send()) {
			$this->errors[] = "There is something wrong with our servers, your email was unable to be sent.";
			return false; 
		}
		return true;
	}

	/**
	*Send email to the websites email adress containing questions by users from contact page
	*
	*@param 	$email 		String, users email adress (for response email)
	*@param 	$subj 		String, the subject of message
	*@param 	$mess 		String, the users message
	*@see 		The message is sent to the support email.
	*/
	public function sendToSupport($email, $subj, $mess)
	{
		$direction = SUPPORTEMAIL;
		$phpemail = new PHPMailer();
		$phpemail->isHTML(true);
		$phpemail->Subject   = 'User Message: ' . $subj;
		$phpemail->FromName   = COMPANYNAME;
		$phpemail->Body      = $this->email_des['header'] .
				'<h3>Message sent from: contact.php</h3>' .
				'<p>Users email address: '.$email.'</p>' .
				'<p>'.$mess.'</p>' .
				$this->email_des['footer'];
		$phpemail->AddAddress($direction);
		if(!$phpemail->send()) {
			$this->errors[] = "There is something wrong with our servers, your email was unable to be sent.";
			return false; 
		}
		return true;
	}

	/**
	*Send a message to an email account
	*
	*@param 	$direction 	String, the email account to send email to
	*@param 	$subj 		String, the subject of message
	*@param 	$mess 		String, body text of email
	*@see 		The message is sent to the support email.
	*/
	public function sendMessage($direction, $subj, $mess)
	{
		$phpemail = new PHPMailer();
		$phpemail->isHTML(true);
		$phpemail->Subject   = $subj;
		$phpemail->FromName   = COMPANYNAME;
		$phpemail->Body      = $this->email_des['header'] .
				'<h3>'.$subj.'</h3>' .
				'<p>'.$mess.'</p>' .
				$this->email_des['footer'];
		$phpemail->AddAddress($direction);
		if(!$phpemail->send()) {
			$this->errors[] = "There is something wrong with our servers, your email was unable to be sent.";
			return false; 
		}
		return true;
	}

	/**
	*Send a test email
	*
	*@param 	$direction 		String, email adress to send email to
	*@param 	$unsub 			Boolean, the unsubscription status of the user
	*/
	public function testEmail($direction, $unsub)
	{
		if(!$unsub)
		{
			$phpemail = new PHPMailer();
			$phpemail->isHTML(true);
			$phpemail->Subject   = 'Test Email';
			$phpemail->FromName   = COMPANYNAME;
			$phpemail->Body      = $this->email_des['header'] .
					'<h3>Test Email</h3>' .
					'<p>This is a test email.</p>' .
					$this->email_des['footer'];
			$phpemail->AddAddress($direction);
			if(!$phpemail->send()){
				$this->errors[] = "There is something wrong with our servers, your email was unable to be sent.";
				return false; 
			}
		}else{
			$this->errors[] = "No email sent. The user has unsubscribed.";
			return false; 
		}
		return true;
	}
	
}
?>