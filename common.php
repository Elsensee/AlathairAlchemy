<?php
/*
 * Copyright (c) 2016 Oliver Schramm
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

$startTime = microtime(true);

error_reporting(E_ALL);

require('./vendor/autoload.php');

function memory_usage()
{
	$memUsage = memory_get_peak_usage(true);

	if ($memUsage < 1024)
	{
		return $memUsage . ' Bytes';
	}
	else if ($memUsage < 1048576)
	{
		return round($memUsage / 1024, 2) . ' KB';
	}
	else
	{
		return round($memUsage / 1048576, 2) . ' MB';
	}
}

$data = \Alchemy\Data::getDataFromIniFile(__DIR__ . '/_data.ini', __DIR__ . '/cache/data.php');
$data->getPricesFromFile('price_data.txt');
$regencyCollection = $data->getRegencies();
$effectCollection = $data->getEffects();

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader, [
	'cache'	=> __DIR__ . '/cache/twig/',
]);
