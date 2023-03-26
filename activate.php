<?php
$c=mysqli_connect("localhost","root","");
if($c)
{
	mysqli_select_db($c,"envirevo");

	if(isset($_GET["code"]))
	{
			$usercode = $_GET["code"];
			
		$q = mysqli_query($c,"select * from customer where ucode = '{$usercode}'") or die(mysqli_error($c));
		
		$data = mysqli_fetch_row($q);
		
				if($data>0)
				{
					mysqli_query($c,"update customer set vstatus = '1' where ucode = '{$usercode}'") or die(mysqli_error($c));
					
					echo "Your Email is verified Now Login into System";
				}
				
	}
	else
	{
		echo "Sorry code not found";
	}
	
}
?>