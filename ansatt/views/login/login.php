	
	<div class="login module">
		
		<h2>Logg inn</h2>
		
		<form method="post" class="input">
			
			<? if ($user->errors) : ?>
				<div class="error">
					<ul>
						<? foreach ($user->errors as $error) : ?>
							<li><?= $error['error_description'] ?></li>
						<? endforeach ?>
					</ul>
				</div>
			<? endif ?>
			
			<p>
				E-postadresse:<br />
				<input input type="email" name="username" value="<?= $username; ?>" autocorrect="off" autocapitalize="off" <?php if ( ! $_POST['submit']) echo "autofocus"; ?> />	
			</p>
			
			<p>
				Passord:<br />
				<input type="password" name="password" <?php if ($_POST['submit']) echo "autofocus"; ?> />	
			</p>
			<p>
				<input type="checkbox" name="remember_me" id="remember_me" value="1" <?php if ($remember_me) echo "checked"; ?> /> <label for="remember_me">Forbli p&aring;logget</label>
			</p>
			
			<div style="vertical-align: text-bottom;">
				<input type="submit" name="submit" value="Logg inn" class="button large gray rounded-corners" />
				<a href="<?= $settings->base_url ?>/innlogging/glemt" class="margin-left-75<?php if ($_POST['submit']) echo ' forgot-password small-rounded-corners'; ?>">Glemt passord?</a>
			</div>
			
		</form>
			
	</div>