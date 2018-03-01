<?php
function auto_csv($results)
{
	$path = "C:\Users\Nishan\Dropbox\\";	
    $name = $path.date("M-Y", strtotime(date('M-Y')." -1 month")).'.csv';
	
	$fp = fopen($name, 'w');
    
	foreach($results as $result)
    {
        fputcsv($fp, $result);
    }

    fclose($fp);
}
?>