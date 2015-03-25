	
	<div class="module" id="vaktlister">
		
		<h2>Vaktlister</h2>	
		
		<div>
			<strong>Vis vaktlister for:</strong>
			<?php if ($department_id == 0) : ?>
				<span class="margin-left-5">Alle avdelinger</span>
			<?php else : ?>
				<a href="<?= $settings->base_url ?>/avdeling/0/#vaktlister" class="margin-left-5 light">Alle avdelinger</a>
			<?php endif; ?>
			<?php foreach ($departments as $department) : ?>
				<?php if ($department_id == $department->id) : ?>
					| <?= $department->short_title ? $department->short_title : $department->title ?>
				<?php else : ?>
					| <a href="<?= $settings->base_url ?>/avdeling/<?= $department->id ?>/#vaktlister" class="light"><?= $department->short_title ? $department->short_title : $department->title ?></a>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
			
		<?php if (count($timetables) > 0) : ?>
			
			<?php $i = 0; ?>
			<?php foreach ($timetables as $timetable) : ?>
				<div class="timetable<?php if ($i%2 == 0) echo " margin-right"; ?>">
					<h4><?= $timetable->title; ?></h4>
					<p>
						<a href="<?= $settings->base_url; ?>/public/images/<?= $timetable->filename ?>">
							<img src="<?= $settings->base_url; ?>/public/images/<?= $timetable->filename ?>" class="timetable"/>
						</a>
					
						<?php if ($current_user->access_level > 0) : ?>
							<br />
							<a href="<?= $settings->base_url; ?>/vaktlister/rediger/<?= $timetable->id; ?>" class="light gray">Rediger</a> |
								<?php if ( ! $archive) : ?>
									<a href="<?= $settings->base_url; ?>/vaktlister/arkiver/<?= $timetable->id; ?>?return_url=<? echo urlencode($_SERVER['REQUEST_URI']); ?>" class="light gray">Arkiver</a> |
								<?php endif; ?>
								<a href="<?= $settings->base_url; ?>/vaktlister/slett/<?= $timetable->id; ?>?return_url=<? echo urlencode($_SERVER['REQUEST_URI']); ?>" class="delete light gray">Slett</a>
						<?php endif; // administrator ?>	
					</p>
				</div>
				
				<?php if ($i%2 == 1) : ?>
					<div class="clear"></div>
				<?php endif; ?>
				
				<?php $i++; ?>
			<?php endforeach; // timetables?>
			
			
			<div class="clear"></div>
		<?php else : ?>
		
			<p>Ingen vaktlister tilgjengelige.</p>
			
		<?php endif; ?>
		
		<?php if ($current_user->access_level > 0) : ?>
			<p>
				<?php if ( ! $archive) : ?>
					<a href="<?= $settings->base_url; ?>/vaktlister/arkiv/" class="alignright light">Vis arkiverte vaktlister</a>
				<?php endif; ?>
			
				<a href="<?= $settings->base_url; ?>/vaktlister/ny">Ny vaktliste &rarr;</a>
			</p>
		<?php endif; ?>
		
	</div>