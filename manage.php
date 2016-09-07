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

$username = "alathair";
$password = "eimechla";

if (!isset($_SERVER['PHP_AUTH_USER']))
{
	header('WWW-Authenticate: Basic realm="Preise verwalten"');
	http_response_code(401);
	die('Zugriff verweigert!');
}
else if (strcasecmp($_SERVER['PHP_AUTH_USER'], $username) !== 0 || $_SERVER['PHP_AUTH_PW'] !== $password)
{
	http_response_code(401);
	die('Zugriff verweigert!');
}

require('./common.php');

// Save prices on submit
if (isset($_POST['submit']))
{
	/** @var Regency $regency */
	foreach (RegencyCollection::getAllRegencies() as $regency)
	{
		$regency->setPrice($_POST['price' . $regency->getId()]);
	}

	file_put_contents('price_data.txt', RegencyCollection::getPrices(), FILE_TEXT);
}

$regencies = RegencyCollection::getAllRegencies();
$regencyCount = (int) (count($regencies) / 2);

$templateVariables = [
	'title'		=> 'Alathair Alchemie - Preise verwalten',

	'regencies1'	=> array_slice($regencies, 0, $regencyCount),
	'regencies2'	=> array_slice($regencies, $regencyCount),
	'regencyCount'	=> $regencyCount,
];

$templateVariables['memoryUsed'] = memory_usage();
$templateVariables['timeSpent'] = round(microtime(true) - $startTime, 2);

echo $twig->render('manage.html.twig', $templateVariables);
