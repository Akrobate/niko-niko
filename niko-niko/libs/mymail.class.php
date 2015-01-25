<?php

/**
 * @brief		Classe permettant de gerer les mails
 * @details		Petit client Imap pour gestion des mails 
 *				optimisé pour gmail pour le moment
 *
 * @author		Artiom FEDOROV
 */
 

class MyMail {

	// Parametres de connection
	private $hostname;
	private $username;
	private $password;


	/**
	 * @brief		Constructeur 
	 */
	 
	function __construct() {		
		$this->hostname = MAIL_HOST;
		$this->username = MAIL_USER;
		$this->password = MAIL_PASSWORD;	
	}
	
	
	/**
	 * @brief		Methode qui releve tous les nouveaux mails
	 * @param	host	Releve tous les nouveaux mails
	 * @return	array 	Renvoi le tableau contenant l'ensemble des mails
	 *
	 */
	
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
	}
	
	
	/**
	 * @brief		Methode qui releve tous les nouveaux mails et les efface
	 * @details		Releve tous les nouveaux mails et les supprime de la boite
	 * @param	realydelete	si true alors on vide la corbeille des mails
	 *						si false alors les mails sont conservés dans la corbeille
	 * @return	array 	Renvoi le tableau contenant l'ensemble des mails
	 *
	 */
	
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
				imap_delete($inbox, $email_number);				
			}
		  }
		}
		
		if ($realydelete) {
			imap_expunge($inbox);
		}
		imap_close($inbox);
		return $res;
	}
	
	
	/**
	 * @brief		Methode qui supprime un message
	 * @details		Supprime un message de la boite avec l'id num
	 * @param	num		Numéro id du message a supprimer
	 * @return	void 	
	 *
	 */
	
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
	
	
	/**
	 * @brief		Methode qui envoie un mail templaté
	 * @details		envoi un mail avec un modele 
	 * @param	tplname		Nom du template a envoyer
	 * @param	data	Array contenant les datas pour le tmeplate
	 * @return	bool	True si succes false sinon 	
	 *
	 */
	 
	public function SendTemplatedMail($tplname, $data) {
		$msg = $this->MailTemplate($tplname, $data);
		return $this->sendMailGmail($data['to'], $data['subject'], $msg);
	}
	
	
	/**
	 * @brief		Methode Simple d'envoi mail
	 * @details		Methode simplissime 
	 *
	 * @param	to	Mail du destinataire
	 * @param	subject	String Objet de mon mail
	 * @param	message	String Message a envoyer
	 *
	 * @return	bool	True si succes false sinon 	
	 *
	 */
	 
	public function sendMail($to, $subject, $message) {
		$status = imap_mail ($to , $subject , $message);
		return $status;
	}
	
	
	/**
	 * @brief		Methode qui embade les datas dans le tplname
	 * @details		Equivalent d'un render
	 *
	 * @param	tplname	path vers le template a utiliser
	 * @param	data	Variables pour le template
	 *
	 * @return	string	Renvoi la chaine de carracteres correspondant a l'email rendu
	 *
	 */
	 
	public function MailTemplate($tplname, $data) {
	
		// Variable md comme maildata
		$md = $data;
		ob_start();
			include(PATH_TEMPLATES . "mails/". $tplname .".php");
		$template_content = ob_get_contents();
		ob_end_clean();
		return $template_content;
	
	}
	
	
	/**
	 * @brief		Methode Complexe d'envoi mail
	 * @details		Methode utilisant PHPMailer 
	 *
	 * @param	to	Mail du destinataire
	 * @param	title	String Objet de mon mail
	 * @param	message	String Message a envoyer
	 *
	 * @return	bool	True si succes false sinon 	
	 *
	 */
	 	
	public function sendMailGmail($to, $title, $message) {
		
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Host = "ssl://smtp.gmail.com"; 
		$mail->SMTPDebug = 1;                     
        // 2 = messages only
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = "ssl";
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465;

		$mail->Username   = $this->username; 
		$mail->Password   = $this->password;
	
		$fromMail =  $this->username;	
		$toMail = $to;
		
		$mail->SetFrom($fromMail, "Niko-Niko");		
		$mail->Subject = $title;
		//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		$mail->MsgHTML($message);
		$mail->AddAddress($toMail, $toMail);

		//$mail->AddAttachment("images/phpmailer.gif");      // attachment
		//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

		if(!$mail->Send()) {
		  $result = $mail->ErrorInfo;
		} else {
		  $result = true;
		}   
		return $result;
	}
}
