	
	<h3>Timeregistreringer</h3>
	<h4><?= $date; ?></h4>
	
	
	<?php if ($list) : ?>
	<ul>
		<?php foreach ($list as $span) : ?>
		
			<li>
				<span<?= $span->sick ? ' style="color: red;"' : ""; ?>>
					<?= date("H:i", strtotime($span->start_time)); ?> - <?= date("H:i", strtotime($span->end_time)); ?>
				</span>
				
				<a href="<?= $settings->base_url ?>/timer/rediger/<?= $span->id ?>" class="light margin-left-10">Rediger</a> |
				<a href="<?= $settings->base_url ?>/timer/slett/<?= $span->id ?>?return_url=<?= $_SERVER['REQUEST_URI'] ?>" class="light delete">Slett</a>
			</li>
	
		<?php endforeach; ?>
		
	</ul>
	
	<?php else : ?>
		
		Ingen timer registrert denne datoen.
	
	<?php endif; ?>
	
	<p>
		<a href="<?= $settings->base_url ?>/timer/ny/<?= $id ?>/<?= $date ?>">Legg til nye timer &rarr;</a>
	</p>
	
	<p class="back">
		<a href="<?= $settings->base_url ?>/rapporter?ansatt=<?= $id ?>&startdato=<?= date("01.m.Y"); ?>&sluttdato=<?= date("t.m.Y"); ?>">&larr; Tilbake</a>
	</p>