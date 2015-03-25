	
	<script type="text/javascript">
		$(document).ready(function() {
			
			var startDate = strToDate($('#startdato').val());
			var endDate = strToDate($('#sluttdato').val());
			var dateDiff = getDateDiff(startDate, endDate);
			
			$('#startdato').change(function(e) {
				startDate = strToDate($(this).val());
				
				endDate.setTime(startDate.getTime() + dateDiff);
				endDate.setMonth(endDate.getMonth() - 1);
				
				$('#sluttdato').val(dateToStr(endDate));
				//alert(dateDiff / (1000 * 60 * 60 * 24));
			});
			
			$('#sluttdato').change(function(e) {
				endDate = strToDate($(this).val());
				dateDiff = getDateDiff(startDate, endDate);
			});
			
			function getDateDiff(aStartDate, aEndDate) {
				return Math.floor(aEndDate.getTime() - aStartDate.getTime());
			}
			
			function strToDate(dStr) {
				var now = new Date();
				
				now.setDate(dStr.substr(0, 2));
				now.setMonth(dStr.substr(3, 2) - 1);
				now.setYear(dStr.substr(-4));
				now.setHours(0);
				now.setMinutes(0);
				now.setSeconds(0);
				
				return now;
			}
			
			function dateToStr(d) {
				d.setMonth(d.getMonth() + 2);
				
				var date = d.getDate();
				var month = d.getMonth();
				var year = d.getFullYear();
				
				date = date < 10 ? "0" + date : date;
				month = month < 10 ? "0" + month : month;
				
				return date + "." + month + "." + year;
			}
			
		});
	</script>
	
	
	<div class="module">
		
		<h2>Rapporter</h2>		

		<form method="get" class="input">
						
			<div class="report-type">
				<p>
					<strong>Vis rapport for:</strong><br />
					<select name="ansatt">
						<option value="0" <?= ($_GET['ansatt'] ? "" : "selected"); ?>>Alle ansatte</option>
						<?php foreach ($users as $_user) : ?>
							<option value="<?= $_user->id; ?>" <?= ($_GET['ansatt'] == $_user->id ? "selected" : ""); ?>><?= $_user->name; ?></option>
						<?php endforeach; ?>
					</select>
				</p>
				
				<?php if ($current_user->access_level >= 3) : ?>
					<p>
						<strong>Avdeling:</strong><br />
						<select name="avdeling">
							<option value="0" <?= ($_GET['ansatt'] ? "" : "selected"); ?>>Alle avdelinger</option>
							<?php foreach ($departments as $department) : ?>
								<option value="<?= $department->id; ?>" <?= ($_GET['avdeling'] == $department->id ? "selected" : ""); ?>><?= $department->title; ?></option>
							<?php endforeach; ?>
						</select>
					</p>
				<?php endif; ?>
				
				<p>
					<strong>I tidsrommet:</strong><br />
					<input type="text" class="date" id="startdato" name="startdato" value="<?php echo ($_GET['startdato'] ? $_GET['startdato'] : date("d.m.Y", mktime(0, 0, 0, date("m"), 1, date("Y")))); ?>"/>
						&ndash;
					<input type="text" class="date" id="sluttdato" name="sluttdato" value="<?php echo ($_GET['sluttdato'] ? $_GET['sluttdato'] : date("d.m.Y", mktime(0, 0, 0, date("m"), date("t"), date("Y")))); ?>" />
					
					<?php 	$url =  '?ansatt=' . $_GET['ansatt'];
							$url .= "&avdeling=" . $_GET['avdeling'];
							
							// Date is in this month, and selection shows the whole month -> show link to today
							if (date("n", strtotime($_GET['startdato'])) == date("n")
								&& $_GET['startdato'] == date("01.m.Y")
								&& $_GET['sluttdato'] == date("t.m.Y"))
							{	
								$url .= "&startdato=" . date("d.m.Y");
								$url .= "&sluttdato=" . date("d.m.Y");
								$link_text = 'Vis rapport for i dag';
							}
							// Date is in another month and selection is not the whole month -> show link to whole month
							else if ($_GET['startdato'] != date("01.m.Y", strtotime($_GET['startdato']))
									 || $_GET['sluttdato'] != date("t.m.Y", strtotime($_GET['sluttdato'])))
							{
								$url .= "&startdato=" . date("01.m.Y", strtotime($_GET['startdato']));
								$url .= "&sluttdato=" . date("t.m.Y", strtotime($_GET['startdato']));
								$link_text = 'Vis rapport for hele m&aring;neden';
							}
					?>
					<a href="<?= $url; ?>" class="light margin-left-10"><?= $link_text ?></a>
				</p>
			</div>
			
			<p>
				<input type="submit" value="Lag rapport" class="button gray rounded-corners" />
			</p>
			
		</form>
		
		
	<?php if ($report) : ?>
		
		<?php
		$data = array();
		
		$department = $db->prepare($_GET['avdeling']);
		$start_date = $db->prepare($_GET['startdato']);
		$end_date = $db->prepare($_GET['sluttdato']);
		
		if ($person == 0)
		{
			foreach ($users as $_user)
			{
						$hours = $workspan->get_sum_by_timespan_and_id(
											$start_date,
											$end_date,
											$_user->id,
											$department);
						$sick_hours = $workspan->get_sum_by_timespan_and_id(
											$start_date,
											$end_date,
											$_user->id,
											$department,
											"sick");
						$extra = $workspan->get_extra_by_timespan_and_id(
											$start_date,
											$end_date,
											$_user->id,
											$department);
						$salary = $workspan->get_salary_by_timespan_and_id(
											$start_date,
											$end_date,
											$_user->id,
											$department);
						$food_cost = $workspan->get_food_cost_by_timespan_and_id(
											$start_date,
											$end_date,
											$_user->id,
											$department);
						$sum_hours = $hours + $sick_hours;
						
						// Skip this user if no hours are present and this it not their home group
						if ($department > 0 && ($department != $_user->department_id))
						{
							if ($hours + $sick_hours == 0)
								continue;
							else
							{
								if ($_user->static_salary > 0)
								{
									$salary = 0;
									$food_cost = 0;
								}
							}
						}
						
						//echo $_user->name . ": " . $extra . '<br />';
						
						$salary = $_user->static_salary ? $salary : $salary + $extra;
						
						$total_hours += $hours;
						$total_sick_hours += $sick_hours;
						$total_extra += $extra;
						$total_food_cost += $food_cost;
						$total_salary += $salary;
						$total_sum_hours += $sum_hours;
						
						$url =  '?ansatt=' . $_user->id;
						$url .= "&avdeling={$department}";
						$url .= "&startdato={$start_date}";
						$url .= "&sluttdato={$end_date}";
						
						
						
						array_push($data, array('name' => '<a href="' . $url . '">' . $_user->name . '</a>',
												'id' => $_user->id,
												'hours' => $hours,
												'sick_hours' => $sick_hours,
												'total' => $sum_hours,
												'extra' => $extra,
												'salary' => $salary,
												'food_cost' => $food_cost));
					}
					
					// Add totals
					
					array_push($data, array('type' => 'total',
											'name' => '<strong>Totalt</strong>',
											'hours' => $total_hours,
											'sick_hours' => $total_sick_hours,
											'total' => $total_sum_hours,
											'extra' => $total_extra,
											'salary' => $total_salary,
											'food_cost' => $total_food_cost));
		}
		else
		{			
					$start_date = strtotime($db->prepare($_GET['startdato']));
					$end_date = strtotime($db->prepare($_GET['sluttdato']));
					$cur_date = $start_date;					
					
					while ($cur_date <= $end_date)
					{
						$hours = $workspan->get_sum_by_timespan_and_id(
											date("Y-m-d", $cur_date),
											date("Y-m-d", $cur_date),
											$user->id,
											$department);
						$sick_hours = $workspan->get_sum_by_timespan_and_id(
											date("Y-m-d", $cur_date),
											date("Y-m-d", $cur_date),
											$user->id,
											$department,
											"sick");
						$extra = $workspan->get_extra_by_timespan_and_id(
											date("Y-m-d", $cur_date),
											date("Y-m-d", $cur_date),
											$user->id,
											$department);
						$salary = $workspan->get_salary_by_timespan_and_id(
											date("Y-m-d", $cur_date),
											date("Y-m-d", $cur_date),
											$user->id,
											$department);
						$food_cost = $workspan->get_food_cost_by_timespan_and_id(
											date("Y-m-d", $cur_date),
											date("Y-m-d", $cur_date),
											$user->id,
											$department);
						
						$sum_hours = $hours + $sick_hours;
						
						$salary = $user->static_salary ? $salary : $salary + $extra;
						
						$total_hours += $hours;
						$total_sick_hours += $sick_hours;
						$total_sum_hours += $sum_hours;
						$total_salary += $salary;
						$total_extra += $extra;
						$total_food += $food_cost;
						
						array_push($data, array('name' => $date_helper->timestamp_to_nor_date(date('Y-m-d', $cur_date)),
												'hours' => $hours,
												'sick_hours' => $sick_hours,
												'total' => $sum_hours,
												'extra' => $extra,
												'salary' => $salary,
												'food_cost' => $food_cost));
						$cur_date = mktime(0, 0, 0, date("n", $cur_date), date("j", $cur_date) + 1, date("Y", $cur_date));
					}
					
					// Add totals
					array_push($data, array('type' => 'total',
											'name' => '<strong>Totalt</strong>',
											'hours' => $total_hours,
											'sick_hours' => $total_sick_hours,
											'total' => $total_sum_hours,
											'extra' => $total_extra,
											'salary' => $total_salary,
											'food_cost' => $total_food));
		}
		?>
		
		<div class="report">
			
		<h3>Timeliste</h3>
			
			<h4>
			<?php
			if ($department)
			{
				$department = new Department($db, array('id' => $department));
				echo $department->title;
			}
			
			if ($_GET['ansatt'] > 0)
			{
				if ($_GET['avdeling'])
					echo ' &mdash; ';
				
				$cur_user = new User($db, array('id' => $db->prepare($_GET['ansatt'])));
				echo $cur_user->name;
			}
			?>
			</h4>
			
			<?php if ($_GET['ansatt'] == 0 && $current_user->access_level >= 3) : ?>
				<?
				$url =  $settings->base_url . '/rapporter/excel?ansatt=' . $_user->id;
						$url .= "&avdeling=" . $_GET['avdeling'];
						$url .= "&startdato={$start_date}";
						$url .= "&sluttdato={$end_date}";
				?>
				<a href="<?= $url ?>">Last ned Excel-rapport</a>
			<?php endif; ?>
			
			<table class="list">
				<tbody>
					<tr>
						<th><?= ($_GET['ansatt'] == 0) ? "Ansatt" : "Dato"; ?></th>
						<th>Arbeidstimer</th>
						<th>Sykefrav&aelig;r</th>
						<!--<th>Sum</th>-->
						<th>Tillegg</th>
						<th>L&oslash;nn</th>
						<th>Kost</th>
					</tr>
					<?php					
						
					foreach ($data as $row) : ?>
						
						<tr<? if ($row['type'] == 'total') echo ' class="total"'; ?>>
						
							<td>
								<?php if ($_GET['ansatt'] > 0) : ?>
									<a href="<?= $settings->base_url ?>/timer/vis/<?= $_GET['ansatt'] ?>/<?= $row['name'] ?>">
										<?= $row['name']; ?>
									</a>
								<?php else :?>
									<?= $row['name']; ?>
								<?php endif; ?>
								
								<?php if ($row['id']) : ?>
									<input type="hidden" value="<?= $row['id']; ?>" />
								<?php endif; ?>
							</td>
							<td class="number">
								<?= $row['hours'] ? number_format($row['hours'], 2, ",", " ") : ""; ?>
							</td>
							<td class="number">
								<span style="color: red;"><?= $row['sick_hours'] ? number_format($row['sick_hours'], 2, ",", " ") : ""; ?></span>
							</td>
							<!--<td class="number">
								<strong><?= $row['total'] ? number_format($row['total'], 2, ",", " ") : ""; ?></strong>
							</td>-->
							<td class="number">
								<?= $row['extra'] ? number_format($row['extra'], 2, ",", " ") : ""; ?>
							</td>
							<td class="number">
								<?= $row['salary'] ? number_format($row['salary'], 2, ",", " ") : ""; ?>
							</td>
							<td class="number">
								<?= $row['food_cost'] ? number_format($row['food_cost'], 2, ",", " ") : ""; ?>
							</td>
						</tr>
					<?php endforeach; // $data as $row ?>
						
				</tbody>
			
			</table>
		
			
		</div>
		
	<?php endif; // if ($report) ?>
		
		<p><a href="<?= $settings->base_url; ?>">&larr; Tilbake til hovedsiden</a></p>
		
		<?php if ($current_user->access_level > 0) : ?>
			<p>
				
			</p>
		<?php endif; ?>
		
	</div>