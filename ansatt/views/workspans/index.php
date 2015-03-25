	
	<script type="text/javascript">
		$(document).ready(function() {
			
			var id = <?= $current_user->id; ?>;
			var date;
			var sick;
			
			$(document).click(function() {
				$('.calendar_view').hide();
			});
			
			$('.show_hours').click(function(e) {
				e.preventDefault();
				e.stopPropagation();			
				
				date = $(this).find('input.date').val();
				today = getToday();
				
				var topPadding = 85;
				var leftPadding = 0;
				
				if ($.browser.mozilla) {
					topPadding = 35;
				} else if ($.browser.msie) {
					topPadding = 40;
				}
				
				var parentPosition = $('table.calendar').position();
				var position = $(this).position();
				var width = $(this).parents('td').find('p.hours').css("width");
				    width = width.substr(0, width.length - 2);
				var left = parseInt(position.left) + parseInt(width) - parseInt(width/5) - parentPosition.left + leftPadding;
				var top = position.top - parentPosition.top + topPadding;
				
				$('.calendar_view .workspans').html('Laster inn...');
				
				$.ajax({  
    				type: "GET",
			    	dataType: 'json',
    		    	url: "<?= $settings->base_url; ?>/controllers/ajax_controller.php",
        			data: "action=get_workspans_for_date_and_id&date="+date+"&id="+id,
		    		success: function(response) {
						if (response.responseCode == "OK") {
							list = response.response;
							str = "";
							
							for (var i = 0; i < list.length; i++) {
								workspan = list[i];
								starttime = workspan.start_time.split(" ");
								endtime = workspan.end_time.split(" ");
								
								if (workspan.sick == '1')
									str += '<p style="color: red">';
								else
									str += '<p>';
								str += starttime[1].substr(0, 5) + " &ndash; " + endtime[1].substr(0, 5);
								str += ' <em>(' + workspan.sum + ' t)</em>';
								str += ' <a href="#" class="delete_workspan light gray">(x)</a>';
								str += ' <input type="hidden" value="' + workspan.id + '" />';
								str += '</p>';
							}
							$('.calendar_view .workspans').html(str);
						} else {
							$('.calendar_view .workspans').html('<p>' + response.response + '</p>');
						}
	 				}
	 			});
	 			
	 			if (date == today)
	 				$('.show_hours_box .buttons').show();
	 			else
	 				$('.show_hours_box .buttons').hide();
	 			
	 			$(".calendar_view").css("margin-left", left);
   				$(".calendar_view").css("margin-top", top);
   				$(".calendar_view").show();
   				
   				switch_view("show");
			});
			
			
			$('a#save_hours').click(function(e) {
				e.preventDefault();
				e.stopPropagation();
				
				if (date != getToday())
					return;
				
				var start_time = $('input#start_time').val();
				var end_time = $('input#end_time').val();
				var department_id = $('select#department_id').val();
				
				if (! start_time) {
					alert("Du m\u00E5 fylle ut starttidspunkt.");
					$('input#start_time').select();
					return;
				}
				
				if (! end_time) {
					alert("Du m\u00E5 fylle ut sluttidspunkt.");
					$('input#end_time').select();
					return;
				}
				
				// CHANGE TO RECOGNIZED FORMAT
				// Less than four characters? Display error.
				if (start_time.length < 4) {
					alert("Starttidspunkt m\u00E5 v\u00E6re minst fire siffer.");
					return;
				}
				
				if (end_time.length < 4) {
					alert("Sluttidspunkt m\u00E5 v\u00E6re minst fire siffer.");
					return;
				}
				
				// Only four digits? Convert to full time with colon.
				if (start_time.length == 4)
					{ start_time = start_time.substr(0, 2) + ":" + start_time.substr(2,2); }
				
				if (end_time.length == 4)
					{ end_time = end_time.substr(0, 2) + ":" + end_time.substr(2,2); }
				
				// Time written with "."? Convert to colon.
				if (start_time.substr(2,1) == ".")
					{ start_time = start_time.substr(0, 2) + ":" + start_time.substr(3,2); }
				
				if (end_time.substr(2,1) == ".")
					{ end_time = end_time.substr(0, 2) + ":" + end_time.substr(3,2); }
				
				if (start_time.substr(0, 2) > 23 || start_time.substr(3,2) > 59) {
					alert("Ugyldig klokkeslett for starttidspunkt.");
					return;
				}
				
				if (end_time.substr(0, 2) > 23 || end_time.substr(3,2) > 59) {
					alert("Ugyldig klokkeslett for sluttidspunkt.");
					return;
				}
				
				// End time before start time?
				if (((parseFloat(end_time.substr(0,2)) * 100) + parseFloat(end_time.substr(3,2))) < ((parseFloat(start_time.substr(0,2)) * 100) + parseFloat(start_time.substr(3,2)))) {					
					alert("Starttidspunkt kan ikke v\u00E6re senere enn sluttidspunkt.");
					return;
				}
				
				$('.calendar_view .workspans').html('Laster inn...');
				
				$.ajax({  
    				type: "POST",
			    	dataType: 'json',
    		    	url: "<?= $settings->base_url; ?>/controllers/ajax_controller.php",
        			data: "action=save_workspan&date="+date+"&start_time="+start_time+"&end_time="+end_time+"&sick="+Number(sick)+"&department_id="+department_id+"&id="+id,
		    		success: function(response) {
						if (response.responseCode == "OK") {
							sum = response.sum == "0.00" ? "&nbsp;" : response.sum;
							hours = response.sum == "0.00" ? "&nbsp;" : "timer";
							$('table.calendar').find('input[value="' + date + '"]').siblings('p.hours').html(sum);
							$('table.calendar').find('input[value="' + date + '"]').siblings('p.hour_label').html(hours);
							$('table.calendar').find('input[value="' + date + '"]').siblings('p.hours').parent().click();
							$('#month_sum').html(response.month_sum);
						}
	 				}
	 			});
				
				$('a#cancel_save_hours').click();
			});
			
			$('a.delete_workspan').live('click', function(e) {
				e.preventDefault();
				//$('table.calendar').find('input[value="' + date + '"]').siblings('p.hours').parent().click();
				
				var response = confirm("Er du sikker p\u00E5 at du vil slette disse arbeidstimene?");
				
				if (response) {
				
					var workspan_id = $(this).siblings('input').val();
				
					$.ajax({  
    					type: "POST",
				    	dataType: 'json',
    		    		url: "<?= $settings->base_url; ?>/controllers/ajax_controller.php",
        				data: "action=delete_workspan&id="+workspan_id+"&date="+date,
			    		success: function(response) {
							if (response.responseCode == "OK") {
								$('table.calendar').find('input[value="' + date + '"]').siblings('p.hours').html(response.sum);
								$('table.calendar').find('input[value="' + date + '"]').siblings('p.hour_label').html('timer');
								$('table.calendar').find('input[value="' + date + '"]').siblings('p.hours').parent().click();
								$('#month_sum').html(response.month_sum);
							}
		 				}
		 			});
		 		}
			});
			
			$('a#cancel_save_hours').click(function(e) {
				e.preventDefault();
				e.stopPropagation();
				
				switch_view("show");
			});
			
			$('a#register_hours').click(function(e) {
				e.preventDefault();
				e.stopPropagation();
				
				sick = false;
				
				show_registration_view();				
				
			});
			
			$('a#register_sick_leave').click(function(e) {
				e.preventDefault();
				e.stopPropagation();
				
				sick = true;
				
				show_registration_view();
			});
			
			$('select#department_id').click(function(e) {
				e.stopPropagation();
			});
			
			$('#start_time').click(function(e) {
				e.stopPropagation();
			});
			
			$('#end_time').click(function(e) {
				e.stopPropagation();
			});
			
			$('#start_time').keyup(function(e) {
				if (e.keyCode == 13) {
					$('a#save_hours').click();
				}
			});
			
			$('#end_time').keyup(function(e) {
				if (e.keyCode == 13) {
					$('a#save_hours').click();
				}
			});
						
			
			function show_registration_view() {			
				var date = new Date();
				var hours = date.getHours();
				var minutes = date.getMinutes();
				
				if (hours < 10) { hours = "0" + hours; }
				if (minutes < 10) { minutes = "0" + minutes; }
								
				$('input#start_time').val('');
				$('input#end_time').val(hours + ":" + minutes);
				
				switch_view("register");
				
				$('input#start_time').select();
			}
			
			function switch_view(status) {
				switch (status) {
					case 'show':
						$('div.show_hours_box').show();
						$('div.register_hours_box').hide();
						break;
					case 'register':
						$('div.show_hours_box').hide();
						$('div.register_hours_box').show();
						break;
				}
			}
			
			function getToday() {
				t = new Date();
				
				return t.getFullYear() + "-" + (t.getMonth() < 9 ? "0" : "") + (t.getMonth() + 1) + "-" + (t.getDate() < 10 ? "0" : "") + t.getDate();
			}
		});
	</script>
	
	<style type="text/css">
		
		
	</style>
	
	
				<div class="calendar_view">
                	                	
        			<div class="left-arrow"></div>
                	<div class="box blue-with-border rounded-corners box-shadow">
                		
                		<h3>Arbeidstimer</h3>
                		<div class="show_hours_box">
							<div class="workspans">
                				
                			</div>
                		
                			<div class="buttons">
                				<a href="#" id="register_hours" class="button rounder-corners gray">Registrer timer</a>
                				<a href="#" id="register_sick_leave" class="button rounder-corners gray">Sykefrav&aelig;r</a>
                			</div>
                		</div>
                		
                		<div class="register_hours_box">
                			<table>
								<tbody>
									<tr>
										<td><label for="start_time">Starttidspunkt:</label></td>
										<td><input type="text" id="start_time" name="start_time" class="time" value="<?= $_POST['start_time']; ?>" /></td>
									</tr>
									<tr>
										<td><label for="end_time">Sluttidspunkt:</label></td>
										<td><input type="text" id="end_time" name="end_time" class="time" value="<?= $_POST['end_time'] ? $_POST['end_time'] : date("H:i"); ?>" /></td>
									</tr>
									<tr>
										<td colspan="2">
											<select name="department_id" id="department_id">
												<?php foreach ($departments as $department) : ?>
													<option value="<?= $department->id; ?>" <?php if ($current_user->department_id == $department->id) echo "selected"; ?>><?= $department->title; ?></option>
												<?php endforeach; ?>
											</select>
										</td>
									</tr>
								</tbody>
							</table>
							
							<div class="buttons">
								<a href="#" id="cancel_save_hours" class="button rounded-corners gray" style="margin-right: 4px;">Avbryt</a>
								<a href="#" id="save_hours" class="button rounded-corners gray">Lagre</a>
							</div>
							
                		</div>
                	</div>  
                </div>
                
<div class="module">

	<h2>Min timeliste</h2>
	
	<table class="calendar">
		<tbody>
			<!--
			<thead>
				<td>M</td>
				<td>T</td>
				<td>O</td>
				<td>T</td>
				<td>F</td>
				<td>L</td>
				<td>S</td>
			</thead>
			-->
			
			<?php
			$month = isset($_GET['month']) ? (int)$_GET['month'] : date("n");
			$year = isset($_GET['year']) ? (int)$_GET['year'] : date("Y");
			
			$cur_time = mktime(0, 0, 0, $month, 1, $year);
			$num_days = date("t", $cur_time);
			
			$start_day = date("w", $cur_time);
			if ($start_day == 0)
				$start_day = 7;		
			
			$j = 1;
			$n = 0;
			while ($n < $num_days)
			{
				echo "<tr>\n";
				
				for ($i = 1; $i < 8; $i++)
				{
					if (date("j", $cur_time) == date("j") && $j >= $start_day && date("n", $cur_time) == date("n"))
						echo '  <td class="today">';
					else
						echo '  <td>';
						
					if (($i >= $start_day || $started))
					{	
						if (date("n", $cur_time) == $month)
						{
							echo '<a class="show_hours" href="#">';
							echo '<input type="hidden" class="date" value="' . date("Y-m-d", $cur_time) . '" />';
							echo '<p class="day_num">' . date("j", $cur_time) . '</p>';
							echo '<p class="hours">';
							$hours = $workspan->get_sum_by_date_and_id(date("Y-m-d", $cur_time), $current_user->id);
							if ($hours != "0.00")
							{
								echo $hours;
								echo '</p>';
								echo '<p class="hour_label">timer</p>';
							}
							else
							{
								echo "&nbsp;";
								echo '</p>';
								echo '<p class="hour_label">&nbsp;</p>';
							}
							
							echo '</a>';
						}
							
						$started = true;
						$cur_time = mktime(0, 0, 0, date("m", $cur_time), date("d", $cur_time)+1, date("Y", $cur_time));
						$n++;
					}
					$j++;
					echo "</td>\n";
				}
				echo "</tr>\n";
				//echo $n;
			}
			?>
		</tbody>
	</table>
	
	<p class="alignright">Sykefrav&aelig;r er merket med <span style="color: red;">r&oslash;dt</span></p>
	<p>
		Totalt denne m&aring;neden: <span id="month_sum"><?php echo $workspan->get_sum_by_date_and_id(date("Y-m-d", mktime(0, 0, 0, $month, 1, $year)), $current_user->id, "month"); ?></span> timer
	</p>

</div>