<h1>First</h1>

<div id="content_main">

<ul>
	<li><?php echo link_to('Update', 'update/id/'.moojon_uri::get('id'), firsts, client); ?></li>
	<li><?php echo link_to('Destroy', 'destroy/id/'.moojon_uri::get('id'), firsts, client); ?></li>
</ul>

<?php
//partial('dl', array('first' => $first));
?>

</div><!-- /content_main -->

<div id="content_sub">



</div><!-- /content_sub -->