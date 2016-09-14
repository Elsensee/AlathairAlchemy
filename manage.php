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

use Alchemy\Effect;
use Alchemy\Regency;
use Alchemy\RegencyCollection;

$username = "alathair";
$password = "eimechla";

if (!isset($_SERVER['PHP_AUTH_USER']))
{
	header('WWW-Authenticate: Basic realm="Reagenzien verwalten"');
	http_response_code(401);
	die('Zugriff verweigert!');
}
else if (strcasecmp($_SERVER['PHP_AUTH_USER'], $username) !== 0 || $_SERVER['PHP_AUTH_PW'] !== $password)
{
	http_response_code(401);
	die('Zugriff verweigert!');
}

require('./common.php');

// Save regencies on submit
if (isset($_POST['submit']))
{
	$size = count($_POST['regency']);
	$javascript_on = (!isset($_POST['javascript']) || $_POST['javascript'] !== 'off') && (isset($_POST['regency_effects']) && count($_POST['regency_effects']) === $size);

	$newRegencies = new RegencyCollection();

	for ($i = 0; $i < $size; $i++)
	{
		$name = $_POST['regency'][$i];
		$price = $_POST['price'][$i];
		$effects = ($javascript_on) ? $_POST['regency_effects'][$i] : $regencyCollection->getRegencyById($i)->getEffects();

		if ($javascript_on)
		{
			$effects = explode(',', $effects);
			$effects = array_map(function ($effectId) use ($effectCollection) {
				return $effectCollection->getEffectById($effectId)->getName();
			}, $effects);
		}
		else
		{
			$effects = array_map(function (Effect $effect) {
				return $effect->getName();
			}, $effects);
		}

		$newRegencies->addRegency(new Regency($name, $effects, $price));
	}

	$data->setRegencies($newRegencies);
	$data->saveToIniFile(__DIR__ . '/data.ini');
	$regencyCollection = $newRegencies;
}


$regencies = $regencyCollection->getAllRegencies();
$regencyCount = (int) (count($regencies) / 2);

$templateVariables = [
	'title'		=> 'Reagenzien verwalten',
	'language'	=> 'de',

	'regencies'		=> $regencies,
	'regencyCount'	=> $regencyCount,
	'effects'		=> $effectCollection->getAllEffects(),
];

$templateVariables['memoryUsed'] = memory_usage();
$templateVariables['timeSpent'] = round(microtime(true) - $startTime, 2);

echo $twig->render('manage.html.twig', $templateVariables);
