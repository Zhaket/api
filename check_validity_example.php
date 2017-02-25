<?php
require 'zhaket-api.class.php';

/* @param : license token --> 943648a9-2b8f-4878-b817-3d12b302f555
	replace it with your own license token :)
*/
$result = Zhaket_License::isValid('943648a9-2b8f-4878-b817-3d12b302f555');

if ($result->status=='successful')
{
	echo $result->message; // License is valid
}
else
{
	// License not valid / show message
	if (!is_object($result->message))// License is Invalid
		echo $result->message;
	else
		foreach ($result->message as $message)
			foreach ($message as $msg)
				echo $msg.'<br>';
}
?>