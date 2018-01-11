<?php
/**
 * @author <insert name>
 * @package test
 *
 */
class Bootstrap
{
	public static function main($file_report, $date_report)
	{
		echo "Total for $date_report";
		echo '<br /> GBR: ';
		echo '<br /> USD: ';
		echo '<br /> EUR: ';
		echo '<br /> CAD: ';
		$csv = array_map('str_getcsv', file($file_report));
		/*echo '<pre>';
		print_r($csv);
		echo '</pre>';*/
	}
}
Bootstrap::main($argv[1], $argv[2]);

