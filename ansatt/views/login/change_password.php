	
<div class="module">
		
	<h2>Endre passord</h2>
	
	<? if ($notice) : ?>
	
		<div class="notice">
			<?= $notice ?>
		</div>
		
		<p class="navigation">
			<a href="<?= $settings->base_url ?>">&larr; Tilbake til ansattesider</a>
		</p>
		
	<? else : ?>
	
		<form method="post" class="input">
	
			<? if ($user && $user->errors) : ?>
				
				<div class="error">
					<ul>
						<? foreach ($user->errors as $error) : ?>
							<li><?= $error['error_description'] ?></li>
						<? endforeach ?>
					</ul>
				</div>
				
			<? endif; ?>
			
			<p>
				N&aring;v&aelig;rende passord:<br />
				<input type="password" name="old_password" value="<?= $_POST['old_password']; ?>" autofocus />
			</p>
				
			<p>
				Nytt passord:<br />
				<input type="password" name="new_password" value="<?= $_POST['new_password']; ?>" />
			</p>
		
			<p><input type="submit" name="submit" value="Lagre passord" class="button large gray rounded-corners" /></p>
			
			<p class="back">
				<a href="<?= $settings->base_url ?>">&larr; Tilbake til hovedsiden</a>
			</p>
	
		</form>
	
	<? endif ?>
	
</div>