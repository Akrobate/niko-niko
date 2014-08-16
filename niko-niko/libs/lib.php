<?php


class MyMail {

	private $hostname;
	private $username;
	private $password;


	function __construct() {
		
		$this->hostname = MAIL_HOST;
		$this->username = MAIL_USER;
		$this->password = MAIL_PASSWORD;

	
	}	


	public static function getNewDebug() {

		$inbox = imap_open($this->hostname,$this->username,$this->password) 
			or die('Cannot connect to Gmail: ' . imap_last_error());

		var_dump($inbox);
		
		$emails = imap_search($inbox,'ALL');

		/* if emails are returned, cycle through each... */
		var_dump($emails);

		if ($emails) {
		
		/* begin output var */
		  foreach($emails as $email_number) {

			/* get information specific to this email */
			$overview = imap_fetch_overview($inbox,$email_number,0);
			$message = imap_fetchbody($inbox,$email_number,2);

			/* output the email header information */
			$output.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
			$output.= '<span class="subject">'.$overview[0]->subject.'</span> ';
			$output.= '<span class="from">'.$overview[0]->from.'</span>';
			$output.= '<span class="date">on '.$overview[0]->date.'</span>';
			$output.= '</div>';

			/* output the email body */
			$output.= '<div class="body">'.$message.'</div>';
		  }

		  echo $output;
		}

		/* close the connection */
		imap_close($inbox);

	}
	
	
	
	
		public function getNew() {

			$res = array();

			/* try to connect */
			$inbox = imap_open($this->hostname,$this->username,$this->password) or die('Cannot connect to Gmail: ' . imap_last_error());
			$emails = imap_search($inbox,'ALL');
			
			if ($emails) {
		
			  foreach($emails as $email_number) {

				//var_dump($overview);
				$overview = imap_fetch_overview($inbox,$email_number,0);
				$data['message'] = imap_fetchbody($inbox,$email_number,2);
				$data['date'] = $overview[0]->date;
				$data['from'] = $overview[0]->from;
				$data['subject'] = $overview[0]->subject;
				$data['id'] = $overview[0]->message_id;
				
				preg_match('#<(.*)>#', $data['from'], $data['from']) ;
				$data['from'] = trim($data['from'][1]);
				$res[] = $data;
			  }
			}

			/* close the connection */
			imap_close($inbox);

			return $res;
			/* put the newest emails on top */
			//rsort($emails);

	}
	
	
	
	public function getAllAndRemove($realydelete = true) {
		$res = array();

		/* try to connect */
		$inbox = imap_open($this->hostname,$this->username,$this->password)
			or die('Cannot connect to mailbox: ' . imap_last_error());
			
		$emails = imap_search($inbox,'ALL');
		
		if ($emails) {
	
		  foreach($emails as $email_number) {

			$overview = imap_fetch_overview($inbox,$email_number,0);
			$data['message'] = imap_fetchbody($inbox,$email_number,2);
			$data['date'] = $overview[0]->date;
			$data['from'] = $overview[0]->from;
			$data['subject'] = $overview[0]->subject;
			
			preg_match('#<(.*)>#', $data['from'], $data['from']) ;
			$data['from'] = trim($data['from'][1]);
			$res[] = $data;
			if ($realydelete) {
			
				//count($res) - 1
			//	$check = imap_mailboxmsginfo($inbox);
			//	echo "Nombre de messages avant effacement : " . $check->Nmsgs . "<br />\n";
					imap_delete($inbox, $email_number);				
			//	$check = imap_mailboxmsginfo($inbox);
			//	echo "Nombre de messages après effacement : " . $check->Nmsgs . "<br />\n";
			
			}
		  }
		}
		
		if ($realydelete) {
			imap_expunge($inbox);
		}
		

		imap_close($inbox);

		return $res;
	}
	
	
	
	
	public function removeOne($num) {

		$inbox = imap_open($this->hostname, $this->username, $this->password)
			 or die('Cannot connect to Gmail: ' . imap_last_error());
		$emails = imap_search($inbox,'ALL');
		$mbox = $inbox;
		
		$check = imap_mailboxmsginfo($mbox);
		//echo "Nombre de messages avant effacement : " . $check->Nmsgs . "<br />\n";
		imap_delete($mbox, $num);
		$check = imap_mailboxmsginfo($mbox);
		//echo "Nombre de messages après effacement : " . $check->Nmsgs . "<br />\n";
		imap_expunge($mbox);
		$check = imap_mailboxmsginfo($mbox);
		//echo "Nombre de messages après imap_expunge : " . $check->Nmsgs . "<br />\n";
		
		imap_close($mbox);
	}
	
	
	
	public function sendMail($to, $subject, $message) {
	
		$inbox = imap_open($this->hostname, $this->username, $this->password)
			 or die('Cannot connect to Gmail: ' . imap_last_error());
	
		$status = imap_mail ($to , $subject , $message);
	
	}
	
	
	
	
	

}


