<?php

/** Adminer Plugin: Dump to Xls format
* @author putuariepratama
*/
class AdminerDumpExcel {

	protected $prefix;

	function __construct() {
		$this->prefix = \date("YmdHis");
	}
	
	function dumpFormat() {
		return array('dump-excel' => 'Excel');
	}

	function dumpTable($table, $style, $is_view = false) {
		if ($_POST["format"] == "dump-excel") {
			return true;
		}
	}	
	
	function dumpData($table, $style, $query) {
		if ($_POST["format"] !== "dump-excel") {
			return;
		}
						
		$connection = connection();
		$result = $connection->query($query);
		if ($result) {
			echo '<table border="1">';
			$no = 0;
			while ($row = $result->fetch_assoc()) {
				if ($no==0) {
					echo '<tr><th>NO</th>';
					foreach ($row as $col => $val) {
						echo '<th>'.h($col).'</th>';
					}
					echo '</tr>';
				}

				$no++;

				echo "<tr>";
				echo "<td>".$no."</td>";
				foreach ($row as $val) {
					if (isset($val)) {
						echo $this->echoWithFormatValue($val);						
					}else{
						echo "<td></td>";
					}					
				}
				echo "</tr>";
			}			

			echo '</table>';												
		}
		return true;
	}	

	function dumpHeaders($identifier, $multi_table = false) {		
		if ($_POST["format"] == "dump-excel") {
			header("Content-Type: application/xls");    
			header("Content-Disposition: attachment; filename=". $this->prefix ." ". $identifier .".xls");  
			header("Pragma: no-cache"); 
			header("Expires: 0");
			return "xls";
		}
	}

	function dumpFilename($identifier) {
		if ($_POST["format"] == "dump-excel") {
			return friendly_url($identifier != "" ? $this->prefix ." ". $identifier : (SERVER != "" ? SERVER : "localhost"));
		}
	}

	protected function echoWithFormatValue($val) {
		if (is_numeric($val) && strlen($val) > 10) {
			return "<td>'" . h($val) . "</td>";			
		}

		return "<td>" . h($val) . "</td>";
	}
}