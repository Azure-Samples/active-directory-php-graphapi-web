<?php
    class PrintObjectHelper
    {
        // Outputs a given user/group/contact on to the screen.
        public static function PrintObject($change)
        {
            echo "<table cellspacing=2 cellpadding=3 border=1>
				<tr class=header>
					<td colspan=2>".$change->{'odata.type'}."</td>
				</tr>";
            $changeProperties=get_object_vars($change);
			foreach($changeProperties as $key=>$value) 
            {
                echo "<tr><td class=key>".$key."</td>";
	            echo "<td>";
                $type = gettype($value);
                switch ($type)
                {
                    case "array":
                        print_r($value);
                        break;
                    case "boolean":
                        echo ($value == 1 ? "TRUE" : "FALSE");
                        break;
                    default:
                        echo ($value);
                }
                
				echo "</td></tr>";
			}
            echo "</table>";
        }
    }
?>

