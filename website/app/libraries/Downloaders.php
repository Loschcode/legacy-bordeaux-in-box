<?php

namespace App\Libraries;

use Formatter, Response;

/**
 * Downloaders
 * by Laurent Schaffner
 */

class Downloaders {


    public static function makeCsvFromArray($file_name, $my_array)
    {

		$formatter = Formatter::make($my_array, Formatter::ARR);
		$csv = $formatter->toCsv();

		$headers = array(
	        'Content-Type' => 'text/csv',
	        'Content-Disposition' => 'attachment; filename="'.$file_name.'"',
	    );

	    // Mac compatibility shit for accents
 	   	$csv = str_replace(',', "\t", $csv);
        $csv = mb_convert_encoding($csv, 'UTF-16LE', 'UTF-8');
        $csv = chr(255) . chr(254) . $csv;
       	$csv = rtrim($csv, "\n");

    	return Response::make(rtrim($csv, "\n"), 200, $headers);

    }

    public static function prepareForCsv($string)
    {

		return str_replace(',', '', str_replace("\r", "", str_replace("\n", " ", strip_tags($string))));

    }

}