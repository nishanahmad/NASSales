<?php
function target_csv($results)
{
    $name = date("M-Y", strtotime(date('M-Y'))).'-Target.csv';

	header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename='.$name);
    header('Pragma: no-cache');
    header("Expires: 0");

    $outstream = fopen("php://output", "w");

    foreach($results as $result)
    {
        fputcsv($outstream, $result);
    }

    fclose($outstream);
}
?>