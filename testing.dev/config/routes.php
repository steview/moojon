<?php
return array(
	new moojon_rest_route('seconds', array('app' => 'client')),
	new moojon_rest_route('firsts', array('app' => 'client')),
	new moojon_route(':app/:controller/:action'),
	new moojon_route('/', array('app' => '<[default_app]>', 'controller' => '<[default_controller]>', 'action' => '<[default_action]>')),
);
?>