<?php
function sales_today_csv($results)
{
    $name = date("d-M-Y").'.csv';

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename='. $name);
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