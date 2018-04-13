<?php error_reporting(0);?>
<?php

        define(DB_HOST,"localhost");
		define(DB_USER,"root");
		define(DB_PASSWORD,"");				
		define(DB_NAME,"psp");
		
				$conn=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);		
				if(!$conn)
				{
					echo "<strong>Connection has not established with the server</strong>";
				}
				
?>