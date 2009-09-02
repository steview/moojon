<?php
$firsts = new moojon_rest_route('firsts');
$firsts->set_app('client');
return array(
	$firsts,
	new moojon_route(':app/:controller/:action'),
	new moojon_route('/', array('app' => 'client', 'controller' => 'index', 'action' => 'index')),
);
?>