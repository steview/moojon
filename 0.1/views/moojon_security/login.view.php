<h1>Security</h1>

<div id="content_main">

<?php if (moojon_security::authenticate() !== true) { ?>
	<form action="#" method="post">
		<fieldset>
			<label for="security[email]">Email:</label>
			<input type="text" name="security[email]" id="security[email]" class="text" value="<?php echo $email; ?>" />
			<label for="security[password]">Password:</label>
			<input type="password" name="security[password]" id="security[password]" class="text" value="<?php echo $password; ?>" />
			<div class="checkbox">
				<input type="checkbox" name="security[remember]" id="security[remember]" value="checked"<?php echo $remember; ?> />
				<label for="security[remember]">Remember me on this computer</label>
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