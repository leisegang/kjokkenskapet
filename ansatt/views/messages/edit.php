	<div class="module">
			
		<h3><?php echo ($message->exists() ? "Endre melding" : "Ny melding"); ?></h3>
			
			<form method="post" class="input" enctype="multipart/form-data">
				
				<?php if ($message->errors) : ?>
					<div class="error">
						<ul>
						<? foreach ($message->errors as $error) : ?>
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
				
				<?php if ($filename) : ?>
					<div class="notice">
						<strong>Opplastet bilde:</strong> <a href="http://<?= $settings->domain; ?>/ansatt/public/images/<?= $filename; ?>">http://<?= $settings->domain; ?>/ansatt/public/images/<?= $filename; ?></a>
					</div>
				<?php endif; ?>

					<p>
						<input type="text" id="title" name="title" value="<?= $message->title ?>" placeholder="Overskrift" autofocus />
						<br />
						<textarea id="content" name="content"><?= stripslashes($message->content); ?></textarea>
					</p>
					<p>
						<input type="file" id="filename" name="filename" /> <input type="submit" name="upload" value="Last opp bilde" />
					</p>
					<p>
						<strong>Avdeling:</strong><br />
						<select name="department_id">
							<option value="0">Alle avdelinger</option>
							<?php foreach ($departments as $department) : ?>
								<option value="<?= $department->id; ?>" <?php if ($message->department_id == $department->id) echo "selected"; ?>><?= $department->title; ?></option>
							<?php endforeach; ?>
						</select>
					</p>
					<p>
						<input type="text" id="hide_date" class="date" name="hide_date" value="<?= $message->hide_date ? $date_helper->timestamp_to_nor_date($message->hide_date) : date("d.m.Y", mktime(0, 0, 0, date("m"), date("d")+10, date("Y"))); ?>" class="colspan-4<?= $message->error_for("hide_date"); ?>" placeholder="Utl&oslash;psdato" /> &larr; Skjul p&aring; denne datoen
					</p>
					
					<p>
						<input type="submit" value="Lagre" name="save" class="button large rounded-corners gray" />
					</p>
					
					<?php if ( ! $notice) : ?>
						<p class="back"><a href="<?= $settings->base_url; ?>">&larr; Tilbake</a></p>
					<?php endif; ?>
			</form>
			
			<script type="text/javascript">
				CKEDITOR.replace( 'content' );
			</script>
	
	</div>