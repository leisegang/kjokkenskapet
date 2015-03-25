
		<?php if ($current_user->access_level < 2) : ?>
			
			<strong>Logget inn som:</strong> <?= $current_user->name ?> | 
			
		<?php else : ?>
			
			<?php 	$url = "?startdato=" . date("01.m.Y");
					$url .= "&sluttdato=" . date("t.m.Y");
			?>
			<a href="<?= $settings->base_url; ?>">Forsiden</a> |
			<a href="<?= $settings->base_url; ?>/rapporter<?= $url ?>">Se rapporter</a> |
			<?php if ($current_user->access_level >= 3) : ?>
				<a href="<?= $settings->base_url; ?>/brukere">Administrer ansatte</a> |
				<a href="<?= $settings->base_url; ?>/innstillinger" class="light">Innstillinger</a> |
			<?php endif; ?>
			
		<?php endif; ?>
		
		<a href="<?= $settings->base_url; ?>/innlogging/endre-passord" class="light">Endre passord</a> |
		<a href="<?= $settings->base_url; ?>/logg-ut">Logg ut</a>
		