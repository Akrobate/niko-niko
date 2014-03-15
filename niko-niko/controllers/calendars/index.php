<?php
	// Script Sites/index
	
	// TODO: Security check!
	// TODO: Pagination script sites index
	

	/*** MAIN ***/

	$calendar = new donatj\SimpleCalendar();
	
	
	$mailbox = new MyMail();
	$msgs = $mailbox->getNew();
	//print_r($msgs = $mailbox->getNew());

	$ht = "";
	
	foreach($msgs as $m) {
		
		//print_r($m);
		//echo($m['date'] . "<br>");
		
		//$m['message']
	//	echo($m['subject']);
		//MySmiley::str2smiley($m['subject'], true);
		if(MySmiley::str2smiley($m['subject'], false)) {
		//	$calendar->addDailyHtml(MySmiley::str2smiley($m['subject'], false) ,  'today', 'today'  );
			$ht .= MySmiley::str2smiley($m['subject'], false) ;
		}
	
	}
	$dd = "today";
	$dd = "Sat, 1 Mar 2014 01:58:07 +0100";
	
	$calendar->addDailyHtml($ht ,  $dd, $dd  );
		//$calendar->addDailyHtml(MySmiley::str2smiley(":-(", false) , '2014-02-19', '2014-02-19' );


	

	/*
	$calendar = new donatj\SimpleCalendar();
	$calendar->setStartOfWeek('Sunday');
	
	$calendar->addDailyHtml(MySmiley::str2smiley(":-)", false), 'yesterday', 'yesterday' );
	$calendar->addDailyHtml(MySmiley::str2smiley(":-|", false), 'yesterday', 'yesterday' );
	$calendar->addDailyHtml(MySmiley::str2smiley(":-(", false), 'yesterday', 'yesterday' );
	
	$calendar->addDailyHtml(MySmiley::str2smiley(":-)", false), 'today', 'today' );
	$calendar->addDailyHtml(MySmiley::str2smiley(":-|", false), 'today', 'today' );
	$calendar->addDailyHtml(MySmiley::str2smiley(":-(", false), 'today', 'today' );
	
	$calendar->addDailyHtml(MySmiley::str2smiley(":-(", false) 
							. MySmiley::str2smiley(":-|", false)
							. MySmiley::str2smiley(":-)", false)
							. MySmiley::str2smiley(":-(", false), 'today', 'today' );
	
	$calendar->addDailyHtml(MySmiley::str2smiley(":-(", false) 
						. MySmiley::str2smiley(":-|", false)
						. MySmiley::str2smiley(":-)", false)
						. MySmiley::str2smiley(":-(", false), '2014-02-19', '2014-02-19' );

	
	$calendarHTML = $calendar->show(false);
	*/
	
	
	
?>
