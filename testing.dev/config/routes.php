<?php
return array(
	new moojon_rest_route('cars', array('app' => 'client')),
	new moojon_rest_route('car_users', array('app' => 'client')),
	new moojon_rest_route('comments', array('app' => 'client')),
	new moojon_rest_route('users', array('app' => 'client')),
	new moojon_rest_route('posts', array('app' => 'client')),
	new moojon_route(':app/:controller/:action'),
	new moojon_route('/', array('app' => 'client', 'controller' => 'index', 'action' => 'index')),
);
?>