<?php

$file = file_get_contents('./reagenzienxwirkungen.csv');

$array = [];

$lines = explode("\n", $file);
foreach ($lines as $line)
{
	list($reagenz, $effekt) = explode(';', $line);
	if (isset($array[$reagenz]))
	{
		$array[$reagenz][] = trim($effekt);
	}
	else
	{
		$array[$reagenz] = array(trim($effekt));
	}
}

foreach ($array as $key => $value)
{
	print('$' . "regencies->addRegency(new Regency('" . $key . "', ['" . implode("', '", $value) . "']));" . PHP_EOL);
}
