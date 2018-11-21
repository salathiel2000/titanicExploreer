<?php

	// Based on https://stackoverflow.com/questions/19271381/correctly-determine-if-date-string-is-a-valid-date-in-that-format
	function is_date($var) {
		
		$format = 'Y-m-d';
		$d = DateTime::createFromFormat($format, $var); // Create example of format needed.
		return (bool)($d && $d->format($format) === $var); // Check variable against generated example.
	}

	function error($msg) {
		echo "<p><div class=\"error\">".$msg."</div></p>";
	}
	function success($msg) {
		echo "<p><div class=\"success\">".$msg."</div></p>";
	}
?>