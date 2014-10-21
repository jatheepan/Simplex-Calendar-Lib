<?php

class SimplexCalendar {
	public $month;
	public $year;
	public $event = array();
	public function __construct($month, $year) {
		if($_GET['scdate']) {
			$year = substr($_GET['scdate'], 0, 4);
			$month = substr($_GET['scdate'], 5);
		} else {}
		$this->month = $month;
		$this->year = $year;
	}
	
	public function prev_month() {
		$prev_month = date('Y-m', strtotime("-1 month", strtotime("{$this->year}-{$this->month}-1")));
		$get = $_GET;
		if(!empty($_GET)) {
			unset($get['scdate']);
			if(count($get) > 0) {
				return strtok($_SERVER['REQUEST_URI'], '?') . "?" . http_build_query($get, '', '&amp;') . "&scdate=" . $prev_month;
			} else {
				return strtok($_SERVER['REQUEST_URI'], '?') . "?scdate=" . $prev_month;
			}
			
		} else {
			return $_SERVER['REQUEST_URI'] . "?scdate=" . $prev_month;
		}
		
	}
	
	public function next_month() {
		$next_month = date('Y-m', strtotime("+1 month", strtotime("{$this->year}-{$this->month}-1")));
		$get = $_GET;
		if(!empty($get)) {
			unset($get['scdate']);
			if(count($_GET) > 0) {
				return strtok($_SERVER['REQUEST_URI'], '?') . "?" . http_build_query($get, '', '&amp;') . "&scdate=" . $next_month;
			} else {
				return strtok($_SERVER['REQUEST_URI'], '?') . "?scdate=" . $next_month;
			}
			
		} else {
			return $_SERVER['REQUEST_URI'] . "?scdate=" . $next_month;
		}
	}

	public function addDate($date, $content) {
		// array_push($this->event, array($date => $content));
		if(array_key_exists($date, $this->event)) {
			array_push($this->event[$date], $content);
		} else {
			$this->event[$date] = array($content);
		}
	}

	public function printCalendar () {
		$no_of_days = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
		$first_day = date('N', strtotime(date('l', strtotime("{$this->year}-{$this->month}-1")))) - 1;
		?>
			<table class="simplexcalendar" cellpadding="0" cellspacing="0">
				<thead>
					<tr class="year">
						<td>
							<a href="<?php echo $this->prev_month(); ?>" class="prev-month"><</a>
						</td>
						<td colspan="2"></td>
						<td><?php echo $this->year; ?></td>
						<td colspan="2"></td>
						<td>
							<a href="<?php echo $this->next_month(); ?>" class="next-month">></a>
						</td>
					</tr>
					<tr class="month">
						<td colspan="7"><?php echo date('F', mktime(0,0,0,$this->month, 10)); ?></td>
					</tr>
				</thead>
				<tr>
					<th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th><th>S</th>
				</tr>
				<?php

					$d = 0; $next_tr = $first_day;
					echo "<tr>";
					while($d < $no_of_days) {
						$d = $d + 1;
						$next_tr = $next_tr + 1;
						if($first_day > 0) {
							echo "<td colspan='$first_day'></td>";
							$first_day = 0;
						}
						
						if(array_key_exists("{$this->year}-{$this->month}-" . sprintf("%02s", $d), $this->event)) {
							echo "<td class='hasevent'>{$d}<ul class='event'>";
							foreach($this->event["{$this->year}-{$this->month}-" . sprintf("%02s", $d)] as $event) {
								echo "<li>{$event}</li>";
							}
							echo "</ul>";
						} else {
							echo "<td>{$d}";
						}
						echo "</td>";
						if($next_tr == 7) {
							echo "</tr><tr>";
							$next_tr = 0;
						}
					}

					echo "</tr>";
					
				?>
			</table>

		<?php
	}
}


