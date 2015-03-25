	
		<h2>Gjenopprett passord</h2>
	
		<? if ($notice) : ?>
	
			<div class="notice">
				<?= $notice ?>
			</div>
		
			<p class="navigation">
				<a href="<?= $settings->base_url ?>/innlogging">G&aring; til ansattesider &rarr;</a>
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
			
				<? if ($user->errors[0]["error_code"] != "non_existing") : ?>
		
					<p>
						Skriv inn nytt passord:<br />
						<input type="password" name="password" value="" class="colspan-3" autofocus />
					</p>
		
					<p><input type="submit" name="submit" value="Lagre passord" class="button large gray rounded-corners" /></p>
				
				<? endif ?>
			
				<p class="navigation">
					<a href="<?= $settings->base_url ?>/innlogging/glemt">&larr; Tilbake til passordgjenoppretting</a>
				</p>
	
			</form>
	
		<? endif; ?>
	