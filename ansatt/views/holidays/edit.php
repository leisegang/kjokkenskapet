	
	<div class="module">
		
		<h2>Endre innstillinger</h2>
		
		<h3>Helligdager</h3>
		
			<form method="post" class="input">
				
				<?php if ($holiday->errors) : ?>
					<div class="error">
						<ul>
						<? foreach ($holiday->errors as $error) : ?>
							<li><?= $error['error_description'] ?></li>
						<? endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
				
				<?php if ($notice) : ?>
					
					<div class="notice">
						<?= $notice; ?>
					</div>
					
					<p class="back"><a href="<?= $settings->base_url; ?>/innstillinger/helligdager">&larr; Tilbake til helligdager</a></p>
					
				<?php else : ?>

					<p>
						<input type="text" name="date" class="date" value="<?= $holiday->date ?>" placeholder="Dato" /><br />
						<input type="text" name="name" id="name" value="<?= $holiday->name ?>" placeholder="Navn p&aring; helligdag" /> <label for="name"><em>(valgfritt)</em> eks. "Skj&aelig;rtorsdag"</label>
					</p>
					
					<p>
						<input type="submit" value="Lagre" name="save" class="button large rounded-corners gray" />
					</p>
					
					<?php if ( ! $notice) : ?>
						<p class="back"><a href="<?= $settings->base_url; ?>/innstillinger/helligdager">&larr; Tilbake</a></p>
					<?php endif; ?>
					
				<?php endif; ?>
				
			</form>
	
	</div>