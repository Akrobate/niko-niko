<?php


class MyMail {


	


	public static function getNewDebug() {

		$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
		$username = 'chess.master.0002@gmail.com';
		$password = 'Kzen=813;';

		/* try to connect */
		$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());

		var_dump($inbox);
		
		
		/* grab emails */
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


		/* put the newest emails on top */
		//rsort($emails);

	}
	
	
	
	
		public static function getNew() {

			$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
			$username = 'chess.master.0002@gmail.com';
			$password = 'Kzen=813;';

			$res = array();

			/* try to connect */
			$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
			$emails = imap_search($inbox,'ALL');
			
			if ($emails) {
		
			  foreach($emails as $email_number) {

				$overview = imap_fetch_overview($inbox,$email_number,0);
				$data['message'] = imap_fetchbody($inbox,$email_number,2);
				$data['date'] = $overview[0]->date;
				$data['from'] = $overview[0]->from;
				$data['subject'] = $overview[0]->subject;
	
				$res[] = $data;
	
	
			  }
			}

			/* close the connection */
			imap_close($inbox);

			return $res;
			/* put the newest emails on top */
			//rsort($emails);

	}
	
	
	
	
	

}


