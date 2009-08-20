<?php
return array(
	new moojon_route(':app/:controller/:action'),
	new moojon_route('/', array('app' => 'client', 'controller' => 'index', 'action' => 'index')),
);
?>