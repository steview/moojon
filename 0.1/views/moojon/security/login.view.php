<h1>Security</h1>

<div id="content_main">

<?php if (!moojon_authentication::authenticate()) { ?>
	<?php if (strlen($security_failure_message)) {?>
		<p><?php echo $security_failure_message; ?></p>
	<?php } ?>
	<form action="#" method="post">
		<fieldset>
			<label for="<?php echo $security_key.'['.$security_identity_key.']'; ?>"><?php echo $security_identity_label; ?></label>
			<input type="text" name="<?php echo $security_key.'['.$security_identity_key.']'; ?>" id="<?php echo $security_key.'['.$security_identity_key.']'; ?>" class="text" value="<?php echo $security_identity_value; ?>" />
			<label for="<?php echo $security_key.'['.$security_password_key.']'; ?>"><?php echo $security_password_label; ?></label>
			<input type="password" name="<?php echo $security_key.'['.$security_password_key.']'; ?>" id="<?php echo $security_key.'['.$security_password_key.']'; ?>" class="text" value="<?php echo $security_password_value; ?>" />
			<div class="checkbox">
				<input type="checkbox" name="<?php echo $security_key.'['.$security_remember_key.']'; ?>" id="<?php echo $security_key.'['.$security_remember_key.']'; ?>" value="checked"<?php echo $security_remember_value; ?> />
				<label for="<?php echo $security_key.'['.$security_remember_key.']'; ?>"><?php echo $security_remember_label; ?></label>
			</div>
		</fieldset>
		<input type="submit" name="submit" value="Login" />
	</form>
<?php } else { ?>
	<p>You are logged in</p>
<?php } ?>

</div><!-- /content_main -->

<div id="content_sub">



</div><!-- /content_sub -->