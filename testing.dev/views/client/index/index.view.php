<h1>Content header</h1>

<div id="flash_banner">Alternative content to go here</div>

<div id="content_main">
	
	<form action="/index.php/firsts" method="get"><input type="submit" value="Index" /></form><br />
	<form action="/index.php/firsts/1" method="get"><input type="submit" value="Show" /></form><br />
	<form action="/index.php/firsts/new" method="get"><input type="submit" value="New" /></form><br />
	<form action="/index.php/firsts/1/edit" method="get"><input type="submit" value="Edit" /></form><br />
	<form action="/index.php/firsts/1/delete" method="get"><input type="submit" value="Delete" /></form><br />
	<form action="/index.php/firsts" method="post">
		<input type="submit" value="Create" />
	</form><br />
	<form action="/index.php/firsts" method="post">
		<input type="hidden" name="_method" value="put" />
		<input type="hidden" name="id" value="1" />
		<input type="submit" value="Update" />
	</form><br />
	<form action="/index.php/firsts" method="post">
		<input type="hidden" name="_method" value="delete" />
		<input type="hidden" name="id" value="1" />
		<input type="submit" value="Destroy" />
	</form><br />
</div><!-- /content_main -->

<div id="content_sub">
	
	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore 
	et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut 
	aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum 
	dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui 
	officia deserunt mollit anim id est laborum.</p>
	
</div><!-- /content_sub -->