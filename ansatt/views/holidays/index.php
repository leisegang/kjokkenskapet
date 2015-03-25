	
	<div class="module">
	
		<h2>Endre innstillinger</h2>
		
		<h3>Helligdager</h3>
	
		<?php if (count($holidays) > 0) : ?>
			<ul>
				<?php foreach ($holidays as $holiday) : ?>
					<li>
						<?= date("d.m.Y", strtotime($holiday->date)); ?>
						<?php if ($holiday->name) echo "(" . $holiday->name . ")"; ?>
						<a href="<?= $settings->base_url; ?>/innstillinger/helligdager/slett/<?= $holiday->id ?>?return_url=<?= $_SERVER['REQUEST_URI'] ?>" class="light delete margin-left-10">Slett</a>
					</li>	
				<?php endforeach; ?>
			</ul>
		<?php else : ?>
			Ingen helligdager.					
		<?php endif; ?>
					
		<p>
			<a href="<?= $settings->base_url; ?>/innstillinger/helligdager/ny">Ny helligdag &rarr;</a>
		</p>
			
		<p>
			<a href="<?= $settings->base_url; ?>/innstillinger">&larr; Tilbake til innstillinger</a>
		</p>
		
	</div>