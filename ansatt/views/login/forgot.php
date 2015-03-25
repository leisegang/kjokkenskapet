	
	<h2>Glemt passord?</h2>
	
	<? if ($notice) : ?>
	
		<div class="notice">
			<?= $notice ?>
		</div>
		
		<p class="navigation">
			<a href="<?= $settings->base_url ?>/innlogging">&larr; Tilbake til innlogging</a>
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
			<? endif ?>
		
			<p>
				Skriv inn e-postadressen din:<br />
				<input type="text" name="email" value="<?= $_GET['email'] ?>" class="colspan-3" autofocus />
			</p>
		
			<p><input type="submit" name="submit" value="Send passord" class="button large gray rounded-corners" /></p>
	
		</form>
	
	<? endif ?>