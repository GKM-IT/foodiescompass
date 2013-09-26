<?php

	require_once("include/config.inc.php");
	
	require_once("include/connection.inc.php");


?>

						
						<div class="gen_table">
						<?php
						$query = "select * from restaurant";
						
						$i_count=0;
						if($query)
						{
							echo '<table><tr class="color3" >';
							$result = mysql_query($query);
							$field = mysql_num_fields( $result );
    
								for ( $i = 0; $i < $field; $i++ ) {
								
									echo '<th style="padding:5px">'.strtoupper(mysql_field_name( $result, $i )).'</th>';
								
								}
							echo '</tr>';
						while($row = mysql_fetch_array($result))
						{
						if($i_count%2 == 0)
						{
							echo '<tr>';
						}
						else
						{
						echo '<tr class="color2">';
						}
						for ( $i = 0; $i < $field; $i++ ) {
								
									echo '<td>'.$row[mysql_field_name( $result, $i )].'</td>';
								
								}
							echo '</tr>';
							$i_count++;
						}
							echo '</tr></table>';
						}
						else
						{
						
						}
						
						
						?>
						</div>
						
						