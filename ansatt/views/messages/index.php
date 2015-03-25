	
<div class="module" id="meldinger">
	
	<h2>Viktige meldinger</h2>
	
	<div>
		<div>
			<strong>Vis meldinger for:</strong>
			<?php if ($department_id == 0) : ?>
				<span class="margin-left-5">Alle avdelinger</span>
			<?php else : ?>
				<a href="<?= $settings->base_url ?>/avdeling/0/" class="margin-left-5 light">Alle avdelinger</a>
			<?php endif; ?>
			<?php foreach ($departments as $department) : ?>
				<?php if ($department_id == $department->id) : ?>
					| <?= $department->short_title ? $department->short_title : $department->title ?>
				<?php else : ?>
					| <a href="<?= $settings->base_url ?>/avdeling/<?= $department->id ?>/" class="light"><?= $department->short_title ? $department->short_title : $department->title ?></a>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		
		<?php if ($messages) : ?>
			
			<?php foreach ($messages as $message) : ?>
				<div class="message">
					
					<?php if ($current_user->access_level > 0) : ?>
					
						<p class="alignright margin-none">
							<a href="<?= $settings->base_url; ?>/meldinger/rediger/<?= $message->id; ?>" class="light gray">Rediger</a> |
							<?php if ( ! $archive) : ?>
								<a href="<?= $settings->base_url; ?>/meldinger/arkiver/<?= $message->id; ?>?return_url=<? echo urlencode($_SERVER['REQUEST_URI']); ?>" class="light gray">Arkiver</a> |
							<?php endif; ?>
							<a href="<?= $settings->base_url; ?>/meldinger/slett/<?= $message->id; ?>?return_url=<? echo urlencode($_SERVER['REQUEST_URI']); ?>" class="delete light gray">Slett</a>
						</p>
					
					<?php endif; ?>
					
					<?php $owner = new User($db, array('id' => $message->owner_id)); ?>
					<h4><?= $message->title; ?></h4>
					<p class="metadata">Publisert: <?= $date_helper->timestamp_to_nor($message->created); ?> av <?= $owner->name; ?></p>
					<?= stripslashes($message->content); ?>
					
				</div>
			<?php endforeach; ?>
			
		<?php else : ?>
			
			<p>Ingen meldinger.</p>
		
		<?php endif; // messages ?>
		
		<?php if ($current_user->access_level > 0) : ?>
			
			<p>
				<?php if ( ! $archive) : ?>
					<span class="alignright"><a href="<?= $settings->base_url; ?>/meldinger/arkiv/" class="light">Vis arkiverte meldinger</a></span>
					<a href="<?= $settings->base_url; ?>/meldinger/ny">Legg til melding &rarr;</a>
				<?php else : ?>
					<a href="<?= $settings->base_url; ?>/">&larr; Tilbake</a>
				<?php endif; ?>
			</p>
				
		<?php endif; // administrator ?>
			
	</div>
	
</div>

	