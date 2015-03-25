	
	<div class="module">
		
		<h2>Endre innstillinger</h2>
		
		<h3>Avdelinger</h3>
		
			<form method="post" class="input">
				
				<?php if ($department->errors) : ?>
					<div class="error">
						<ul>
						<? foreach ($department->errors as $error) : ?>
							<li><?= $error['error_description'] ?></li>
						<? endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
				
				<?php if ($notice) : ?>
					
					<div class="notice">
						<?= $notice; ?>
					</div>
					
					<p class="back"><a href="<?= $settings->base_url; ?>/innstillinger/avdelinger">&larr; Tilbake til avdelinger</a></p>
					
				<?php endif; ?>
				

					<p>
						Navn p&aring; avdeling:<br />
						<input type="text" name="title" value="<?= $department->title ?>" autofocus />
					</p>
					
					<p>
						Kortnavn: (eks. "KS Lillemarkens")<br />
						<input type="text" name="short_title" value="<?= $department->short_title ?>" />
					</p>
					
					<p>
						<input type="submit" value="Lagre" name="save" class="button large rounded-corners gray" />
					</p>
					
					<?php if ( ! $notice) : ?>
						<p class="back"><a href="<?= $settings->base_url; ?>/innstillinger/avdelinger">&larr; Tilbake</a></p>
					<?php endif; ?>
			</form>
	
	</div>