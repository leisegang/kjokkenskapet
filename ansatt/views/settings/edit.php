	
	<div class="module">
			
		<h2>Endre innstillinger</h2>
			
		<form method="post" class="input">
				
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
			
				<p>
					<strong>Kosttrekk:</strong><br />
					<input type="text" name="food_cost" value="<?= number_format($settings->food_cost, 2, ",", ""); ?>" class="number" autofocus /> kr.
				</p>
					
				<p>
					<strong>Helgetillegg</strong>
					(l&oslash;rdager 1400-2400, s&oslash;ndager 0600-2400):<br />
					<input type="text" name="weekend_extra" value="<?= number_format($settings->weekend_extra, 2, ",", ""); ?>" class="number" /> kr.
				</p>
				
				<p>
					<strong>Kveldstillegg</strong> (alle ukedager 2100-2400):<br />
					<input type="text" name="evening_extra" value="<?= number_format($settings->evening_extra, 2, ",", ""); ?>" class="number" /> kr.
				</p>
				
				<p>
					<strong>Nattillegg</strong> (alle dager 2400-0600):<br />
					<input type="text" name="night_extra" value="<?= number_format($settings->night_extra, 2, ",", ""); ?>" class="number" /> kr.
				</p>
				
				<p>
					<strong>Antall timer f&oslash;r kosttrekk sl&aring;r inn:</strong><br />
					<input type="text" name="food_cost_limit" value="<?= number_format($settings->food_cost_limit, 1, ",", ""); ?>" class="number" /> timer
				</p>
				
				<p>
					<strong>Andre innstillinger:</strong>
					<a href="<?= $settings->base_url; ?>/innstillinger/avdelinger" class="margin-left-5">Endre avdelinger</a> |
					<a href="<?= $settings->base_url; ?>/innstillinger/helligdager">Endre helligdager</a>
				</p>
				
				<p>
					<input type="submit" value="Lagre" name="save" class="button large rounded-corners gray" />
				</p>
				
				<?php if ( ! $notice) : ?>
					<p class="back"><a href="<?= $settings->base_url; ?>">&larr; Tilbake til hovedsiden</a></p>
				<?php endif; ?>
		</form>
		
	</div>