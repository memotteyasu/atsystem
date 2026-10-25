<?php
	switch(true){
		case (($user['Rolemaster']['id'] >= 10)and($user['Rolemaster']['id'] <= 19)):
			echo $this->element('HostSidemenu');
			break;
		case (($user['Rolemaster']['id'] >= 20)and($user['Rolemaster']['id'] <= 28)):
			echo $this->element('ClerkSidemenu');
			break;
		case ($user['Rolemaster']['id'] == 29):
			echo $this->element('PartnerSidemenu');
			break;
		case (($user['Rolemaster']['id'] >= 30)and($user['Rolemaster']['id'] <= 39)):
			echo $this->element('DriverSidemenu');
			break;
	}
?>