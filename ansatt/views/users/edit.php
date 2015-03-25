	
		<div class="module">

			<h2><?php echo ($user->exists() ? "Endre ansatt" : "Ny ansatt"); ?></h2>
			
			<form method="post" class="input">
				
				<? if ($user->errors) : ?>
					<div class="error">
						<ul>
						<? foreach ($user->errors as $error) : ?>
							<li><?= $error['error_description'] ?></li>
						<? endforeach; ?>
						</ul>
					</div>
				<? endif; ?>
				
				<?php if ($notice) : ?>
					
					<div class="notice">
						<?= $notice; ?>
					</div>
					
					<p class="back"><a href="<?= $settings->base_url; ?>/brukere/">&larr; G&aring; tilbake til oversikten</a></p>
					
				<?php endif; ?>
				
				<p>
					<strong>Navn:</strong><br />
					<input type="text" name="name" value="<?= $user->name ?>" autofocus />
				</p>
				
				<p>
					<strong>E-post:</strong><br />
					<input type="text" name="email" value="<?= $user->email ?>" />
				</p>
				
				<p>
					<strong>F&oslash;dselsdato:</strong><br />
					<input type="text" name="birthdate" value="<?= $date_helper->timestamp_to_nor_date($user->birthdate); ?>" class="date" />
				</p>
				
				<p>
					<strong>Adresse:</strong><br />
					<input type="text" name="address" value="<?= $user->address ?>" /><br />
					<input type="text" name="zipcode" value="<?= $user->zipcode > 0 ? $user->zipcode : "" ?>" class="number" /> 
					<input type="text" name="city" id="city" value="<?= $user->city ?>" />
				</p>
				
				<p>
					<table>
						<tbody>
							<tr>
								<td><strong>Timel&oslash;nn</strong></td>
								<td style="width: 15px;"></td>
								<td><strong>Fastl&oslash;nn</strong></td>
								<td style="width: 15px;"></td>
								<td><strong>Utbetalingsprosent:</strong></td>
							</tr>
							<tr>
								<td>
									<input type="text" name="salary" value="<?= number_format($user->salary, 2, ",", ""); ?>" class="number" /> kr.
								</td>
								<td></td>
								<td>
									<input type="text" name="static_salary" value="<?= number_format($user->static_salary, 0, ",", " "); ?>" class="number" /> kr.
								</td>
								<td></td>
								<td>
									<input type="text" name="percentage" value="<?= $user->percentage ? number_format($user->percentage, 0, ",", " ") : 100; ?>" class="number" /> %
								</td>
							</tr>
						</tbody>
					</table>
				</p>
				
				<p>
					<input type="checkbox" name="no_extra" id="no_extra" value="1" <?php if ($user->no_extra) echo "checked"; ?>/> <label for="no_extra">Ingen tillegg (med unntak av helligdagstillegg)</label>
				</p>
				
				<p>
					<strong>Hjemmeavdeling:</strong><br />
					<select name="department_id">
						<option value="0">Ikke valgt</option>
						<?php foreach ($departments as $department) : ?>
							<option value="<?= $department->id; ?>" <?php if ($user->department_id == $department->id) echo "selected"; ?>><?= $department->title; ?></option>
						<?php endforeach; ?>
					</select>
				</p>
								
				<p>
					<strong>Brukerniv&aring;:</strong><br />
					<select name="access_level">
						<option value="0" <?php if ($user->access_level == 0) echo "selected"; ?>>Ansatt</option>
						<option value="1" <?php if ($user->access_level == 1) echo "selected"; ?>>Hjelpeleder</option>
						<option value="2" <?php if ($user->access_level == 2) echo "selected"; ?>>Administrator</option>
						<option value="3" <?php if ($user->access_level >= 3) echo "selected"; ?>>Superadministrator</option>
					</select>
				</p>
				
				<? if ($user->exists()) : // User exists ?>
					<p>
						<label for="locked"><strong>Sperret for innlogging:</strong></label>
						<input type="checkbox" name="locked" id="locked" value="1" <?php if ($user->locked) echo "checked"; ?> />
					</p>
					<p>
						<strong>Handlinger:</strong> <a href="<?= $settings->base_url ?>/innlogging/glemt?email=<?=$user->email; ?>">Nullstill passord</a> |
						<a href="<?= $settings->base_url ?>/brukere/slett/<?= $user->id; ?>?return_url=<?= $settings->base_url; ?>/brukere" class="delete">Slett ansatt</a>
					</p>
				
				<? endif ?>
				
				<input type="hidden" name="id" value="<?= $user->id; ?>" />
				<p><input type="submit" name="save" value="Lagre" class="button large gray rounded-corners" /></p>
				
				<?php if ( ! $notice) : ?>
					<p class="back"><a href="<?= $settings->base_url; ?>/brukere">&larr; Tilbake</a></p>
				<?php endif; ?>
			</form>
			
		</div>