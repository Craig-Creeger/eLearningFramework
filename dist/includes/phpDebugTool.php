<?php
/* These lines format the output as HTML comments and call dump_array repeatedly */
echo "\n<!-- BEGIN VARIABLE DUMP -->\n\n";
echo "\n\n<!-- BEGIN GET VARS -->\n\n";
echo "<!-- ".dump_array($_GET)." -->\n";

echo "\n\n<!-- BEGIN POST VARS -->\n\n";
echo "<!-- ".dump_array($_POST)." -->\n";

echo "\n\n<!-- BEGIN SESSION VARS -->\n\n";
echo "<!-- ".dump_array($_SESSION)." -->\n";

echo "\n\n<!-- BEGIN COOKIE VARS -->\n\n";
echo "<!-- ".dump_array($_COOKIE)." -->\n";

echo "\n\n<!-- BEGIN SERVER VARS -->\n\n";
echo "<!-- ".dump_array($_SERVER)." -->\n";

echo "\n\n<!-- END VARIABLE DUMP -->\n\n";

function dump_array($array) {
	if(is_array($array)) {
		$size = count($array);
		$string = "";
		if ($size) {
			$count = 0;
			$string .= "{ ";
			//loop thru key:value pairs
			foreach($array as $var => $value) {
				$string .= $var." = ".$value;
				if($count++ < ($size-1)) {
					$string .= ", ";
				}
			}
			$string .= " }";
		}
		return $string;
	} else {
		return $array;
	}
}
?>