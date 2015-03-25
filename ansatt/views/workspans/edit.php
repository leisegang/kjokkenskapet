	
	<script type="text/javascript">
		$(document).ready(function() {
			$('.date').datepicker({ dateFormat: 'dd.mm.yy', firstDay: 1 });
			
			$('#start_date').change(function() {
				$('#end_date').val($(this).val());
			});
		 });
	</script>
	
	<h3>Timeregistreringer</h3>
		
	<form method="post" class="input">
		
				<?php if ($errors) : ?>
					<div class="error">
						<ul>
						<? foreach ($errors as $error) : ?>
							<li><?= $error['error_description'] ?></li>
						<? endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
				
				<?php if ($notice) : ?>
					
					<div class="notice">
						<?= $notice; ?>
					</div>
					
					<p class="back"><a href="<?= $settings->base_url ?>/timer/vis/<?= $workspan->employee_id ?>/<?= $date_helper->timestamp_to_nor_date($workspan->start_time) ?>">&larr; Tilbake</a></p>
					
				<?php endif; ?>
		
		<table>
			<tbody>
				<tr>
					<td style="width: 85px;"><label for="date">Dato:</label></td>
					<td><input type="text" id="date" name="date" class="date" value="<?= $date ? date("d.m.Y", strtotime($date)) : "" ?>" /></td>
				</tr>
				<tr>
					<td><label for="start_time">Starttidspunkt:</label></td>
					<td><input type="text" id="start_time" name="start_time" class="time" value="<?= $start_time ? date("H:i", strtotime($start_time)) : ""; ?>" /></td>
				</tr>
				<tr>
					<td><label for="end_time">Sluttidspunkt:</label></td>
					<td><input type="text" id="end_time" name="end_time" class="time" value="<?= $end_time ? date("H:i", strtotime($end_time)) : ""; ?>" /></td>
				</tr>
				<tr>
					<td><label for="sick">Sykefrav&aelig;r:</label></td>
					<td><input type="checkbox" id="sick" name="sick"  value="1" <?= $workspan->sick ? "checked" : "" ?> /></td>
				</tr>
				<tr>
					<td>
						<label for="department">Avdeling:</label>
					</td>
					<td>
						<select id="department" name="department">
							<option value="0">Ikke valgt</option>
							<?php foreach ($departments as $department) : ?>
								<option value="<?= $department->id ?>" <?= $workspan->department_id == $department->id ? "selected" : "" ?>><?= $department->title; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" name="save" value="Lagre" class="button gray large rounded-corners" /></td>
				</tr>
			</tbody>
		</table>
		
	</form>
	
	<?php if (! $notice) : ?>
		<?php if ($workspan->exists()) : ?>
			<a href="<?= $settings->base_url ?>/timer/vis/<?= $workspan->employee_id ?>/<?= $date_helper->timestamp_to_nor_date($workspan->start_time) ?>">&larr; Tilbake</a>
		<?php else : ?>
			<a href="<?= $settings->base_url ?>/timer/vis/<?= $user->id ?>/<?= $_GET['date'] ?>">&larr; Tilbake</a>
		<?php endif; ?>
	<?php endif; ?>
	