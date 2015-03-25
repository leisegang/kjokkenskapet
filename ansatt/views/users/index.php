	<div class="module">
		
		<h2>Alle ansatte</h2>
		
		<?php if (count($users) > 0) : ?>
		
			<table class="list">
				<tbody>
					<tr>
						<th>Ansatt</th>
						<th>Rapporter</th>
						<th>Sist innlogget</th>
					</tr>
					<?php foreach ($users as $user) : ?>
					<tr>
						<td>
							<a href="<?= $settings->base_url; ?>/brukere/rediger/<?= $user->id ?>"><?= $user->name ?></a>
						</td>
						<td>
							<a href="<?= $settings->base_url ?>/rapporter?ansatt=<?= $user->id ?>&startdato=<?= date("01.m.Y") ?>&sluttdato=<?= date("t.m.Y") ?>" class="light">Se rapport</a>
						</td>
						<td>
							<?php
							if ($user->last_login == null || $user->last_login == "0000-00-00 00:00:00")
							{
								echo '<span style="color: red">Aldri</span>';
								echo "<a href='<?= $settings->base_url; ?>/brukere/rediger/<?= $user->id ?>'><span style="color: red">Aldri</span></a>";
								
							}
							else
							{
								// Logged in earlier than 14 days ago?
								if ((time() - strtotime($user->last_login)) > 60 * 60 * 24 * 14)
									echo '<span style="color: red;">';
								// Logged in today?
								elseif (date("Y-m-d", strtotime($user->last_login)) == date("Y-m-d"))
									echo '<span style="color: green;">';
								else
									echo '<span>';
									
								echo date("d.m.Y", strtotime($user->last_login)) . " kl. " . date("H:i", strtotime($user->last_login));
								echo '<span>';
							}
							?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			
			<!--
			<strong>Sist innlogget:</strong>
			<span style="color: green;">Har v&aelig;rt innlogget i dag</span> | 
			<span style="color: red;">Mer enn 14 dager siden siste innlogging</span>
			-->
			
		<?php endif; ?>
		
		<p><a href="<?= $settings->base_url; ?>/brukere/ny">Ny ansatt &rarr;</a></p>
		
		<p class="back">
			<a href="<?= $settings->base_url ?>">&larr; Tilbake til hovedsiden</a>
		</p>
		
	</div>