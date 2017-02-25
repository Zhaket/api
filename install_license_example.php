<?php
require 'zhaket-api.class.php';

/* 
	@param1 : license_token --> 943648a9-2b8f-4878-b817-3d12b302f555
	@param2 : product_token --> 74b0eb11-451b-495c-bdd9-ad33c34adaf9
	replace it with your own license token and product token :)
*/

$result = Zhaket_License::install('943648a9-2b8f-4878-b817-3d12b302f555','74b0eb11-451b-495c-bdd9-ad33c34adaf9');
if ($result->status=='successful')
{
	echo $result->message; // License installed successful
}
else
{
	// License not installed / show message
	if (!is_object($result->message))// License is Invalid
		echo $result->message;
	else
		foreach ($result->message as $message)
			foreach ($message as $msg)
				echo $msg.'<br>';
				

}
?>