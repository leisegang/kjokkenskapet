	
	<div class="module">
	
		<h2>Endre innstillinger</h2>
		
		<h3>Avdelinger</h3>
	
		<?php if (count($departments) > 0) : ?>
			<ul>
				<?php foreach ($departments as $department) : ?>
					<li><a href="<?= $settings->base_url; ?>/innstillinger/avdelinger/rediger/<?= $department->id; ?>" class="light"><?= $department->title; ?></a></li>
				<?php endforeach; ?>
			</ul>
		<?php else : ?>
			Ingen avdelinger.					
		<?php endif; ?>
					
		<p>
			<a href="<?= $settings->base_url; ?>/innstillinger/avdelinger/ny">Ny avdeling &rarr;</a>
		</p>
			
		<p>
			<a href="<?= $settings->base_url; ?>/innstillinger">&larr; Tilbake til innstillinger</a>
		</p>
		
	</div>