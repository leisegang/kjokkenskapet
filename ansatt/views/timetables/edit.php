	
	<div class="module">
		
		<h3><?php echo ($timetable->exists() ? "Endre vaktliste" : "Ny vaktliste"); ?></h3>
			
			<form method="post" class="input" enctype="multipart/form-data">
				
				<?php if ($timetable->errors) : ?>
					<div class="error">
						<ul>
						<? foreach ($timetable->errors as $error) : ?>
							<li><?= $error['error_description'] ?></li>
						<? endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
				
				<?php if ($notice) : ?>
					
					<div class="notice">
						<?= $notice; ?>
					</div>
					
					<p class="back"><a href="<?= $settings->base_url; ?>">&larr; G&aring; tilbake til oversikten</a></p>
					
				<?php endif; ?>
				
				<p>
					<input type="text" id="title" name="title" value="<?= $timetable->title ?>" class="colspan-4<?= $timetable->error_for("title"); ?>" placeholder="Overskrift" />
				</p>
				<p>
					<?php if ($timetable->filename) : ?>
						<a href="<?= $settings->base_url; ?>/public/images/<?= $timetable->filename; ?>"><img src="<?= $settings->base_url; ?>/public/images/<?= $timetable->filename; ?>" class="timetable" /></a>
						<a href="<?= $settings->base_url; ?>/vaktlister/rediger/<?= $timetable->id; ?>/slett-bilde?return_url=<?= $_SERVER['REQUEST_URI']; ?>" class="delete light" style="margin-left: 1em;">Slett bilde</a>
					<?php else : ?>
						<input type="file" id="filename" name="filename" />
					<?php endif; ?>
				</p>
				
				<p>
					<strong>Avdeling:</strong><br />
					<select name="department_id">
						<option value="0">Alle avdelinger</option>
						<?php foreach ($departments as $department) : ?>
							<option value="<?= $department->id; ?>" <?php if ($timetable->department_id == $department->id) echo "selected"; ?>><?= $department->title; ?></option>
						<?php endforeach; ?>
					</select>
				</p>
				
				<p>
					<input type="text" id="hide_date" name="hide_date" class="date" value="<?= $date_helper->timestamp_to_nor_date($timetable->hide_date) ?>" class="colspan-4<?= $timetable->error_for("hide_date"); ?>" placeholder="Utl&oslash;psdato" /> &larr; Skjul p&aring; denne datoen
				</p>
					
				<p>
					<input type="submit" value="Lagre" name="save" class="button large rounded-corners gray" />
				</p>
					
				<?php if ( ! $notice) : ?>
					<p class="back"><a href="<?= $settings->base_url; ?>">&larr; Tilbake</a></p>
				<?php endif; ?>
			</form>
	
	</div>