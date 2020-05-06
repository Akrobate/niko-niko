<?php

/**
 *	Controlleur de deconnection
 *	Detruit les sessions d'ACL et d'auth
 *	@author Artiom FEDOROV
 */
 
auth::sessionClose();
acl::sessionClose();

url::redirect("calendars", "view");
