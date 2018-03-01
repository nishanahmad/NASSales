<?php
if(!empty($_POST))
{
	//database settings
	require "connect.php";
	foreach($_POST as $field_name => $val)
	{
		//clean post values
		$field_userid = strip_tags(trim($field_name));
		$val = strip_tags(trim(mysql_real_escape_string($val)));

		//from the fieldname:ar_name we need to get ar_name
		$split_data = explode(':', $field_userid);
		$ar_name = $split_data[1];
		$field_name = $split_data[0];
		if(!empty($ar_name) && !empty($field_name) && !empty($val))
		{
			//update the values
			mysql_query("UPDATE ar_calculation SET $field_name = '$val' WHERE ar_name = $ar_name") or mysql_error();
			echo "Updated";
		} else {
			echo "Invalid Requests";
		}
	}
} else {
	echo "Invalid Requests";
}
?>