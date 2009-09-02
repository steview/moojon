<h1>Destroy first</h1>

<div id="content_main">

<ul>
	<li><?php echo link_to('Read', 'read/id/'.moojon_uri::get('id'), firsts, client); ?></li>
	<li><?php echo link_to('Update', 'update/id/'.moojon_uri::get('id'), firsts, client); ?></li>
</ul>

<h2><?php echo $first; ?></h2>

<?php
partial('destroy_form', array('first' => $first));
?>

</div><!-- /content_main -->

<div id="content_sub">



</div><!-- /content_sub -->