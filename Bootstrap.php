<?php
/**
 * @author <Evgeny Bugaev>
 * @package test
 * Требуется разобрать файл отчета платежной системы и вычислить сумму платежей за 09 января 2017 года по всем имеющимся валютам.
 * Каждая строка в файле Report.csv содержит в себе одну запись.
 * Платежный референс начинается с PAYMENT, затем следуют 6 цифр и оканчивается на 2 заглавные буквы.
 * Например PAYMENT000321FB.
 * --------------------------
 * Я так и не понял, причем здесь платежный референс, поэтому буду использовать все записи в файле.
 * Если же это не верно, то исправить это не такая уж и большая проблема.
 * И как я понял, чтобы вычислить сумму платежей, необходимо найти разницу между дебетом и кредитом.
 */
class Bootstrap
{	
	public static function main($file_report, $date_report)
	{	
		if (@file($file_report)==false) //проверяет, существует ли данный файл
		{
			echo "Неверный файл отчета";
			exit;
		}
		$arr_rep = array_map('str_getcsv', file($file_report));	//заносит данные из файла в массив
		foreach ($arr_rep as $key => $arr_val) //создает массив с нужной датой
		{
			if ($arr_rep[$key][0]==$date_report) $arr_report[$key]=$arr_rep[$key];
		}
		if (empty($arr_report)) //если данной даты нету в файле
		{
			echo "На указанную дату нет данных";
			exit;
		}
		$keys = array_unique(array_column($arr_report, '9')); // выбирает все валюты, которые есть в файле
		$arr_res = array_fill_keys($keys, 0); // заносит валюты в массив, как ключи и присваивает им значение =0
		foreach ($arr_report as $num => $val) // перебор массива с данными о платежах
		{		
			foreach ($arr_res as $key => $arr_val) //перебор массива с валютами 
			{		
				if ($arr_report[$num][9]==$key) $arr_res[$key]+=doubleval($arr_report[$num][8])-doubleval($arr_report[$num][7]);
				//если валюта из платежа совпадает с валютой из массива, то к значению прибавляется дебет и вычитается кредит
			}
		}
		echo "Total for $date_report:\n"; //вывод полученных данных
		foreach ($arr_res as $key => $arr_val)
		{
			echo $key.": ".$arr_val."\n";		
		}	
	}
	
	function validateDate($date, $format = 'd-m-Y')//проверяет переменную с датой, подходит ли она под нужный формат, возвращает true или false
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
}
//перебор полученных из консоли аргументов, поиск .csv и форматирование даты под нужный формат
foreach ($argv as $argv_val)
{
	if (strpos($argv_val, '.csv')) $f_report=$argv_val;
	if (Bootstrap::validateDate($argv_val)) $d_report=str_replace('-', '/', $argv_val);
}
//проверяет, передана ли дата и название файла
if (!isset($d_report)) 
{
	echo "Не указана дата или неверный формат даты (dd-mm-yyyy) \n";
	exit;
} elseif (!isset($f_report))
{
	echo "Не указан файл отчета, будет использован report.csv \n";
	$f_report = 'report.csv';
} 
Bootstrap::main($f_report, $d_report);

