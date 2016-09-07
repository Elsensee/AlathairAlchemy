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

error_reporting(E_ALL);

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

require('./Effect.php');
require('./EffectCollection.php');
require('./Regency.php');
require('./RegencyCollection.php');

require('./data.php');

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
$regencyCount = count($regencies);
$middle = (int) ($regencyCount / 2);

?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Alathair Alchemie - Preise verwalten</title>
	<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
	<a href="index.php">Zurück</a><br />
	<h1>Preise verwalten</h1>
	<input type="submit" value="Preise speichern" name="submit" class="bottom_margin" />
	<form action="index.php" method="post">
		<div class="bottom_margin"<?php if ($regencyCount % 2 === 0) echo ' style="margin-bottom: 2.4em;"'; ?>>
			<table class="leftside right_margin">
				<tr>
					<th>Reagenz</th>
					<th>Preis</th>
				</tr><?php

	for ($i = 0; $i < $middle; $i++)
	{
		?>

				<tr>
					<td><?= $regencies[$i]->getName(); ?></td>
					<td><input name="price<?= $regencies[$i]->getId(); ?>" type="number" title="Preis eingeben" value="<?= $regencies[$i]->getPrice(); ?>" class="price"></td>
				</tr><?php
	}
	?>

			</table>
			<table><?php

	for ($i = $middle; $i < $regencyCount; $i++)
	{
		?>

				<tr>
					<td><?= $regencies[$i]->getName(); ?></td>
					<td><input name="price<?= $regencies[$i]->getId(); ?>" type="number" title="Preis eingeben" value="<?= $regencies[$i]->getPrice(); ?>" class="price"></td>
				</tr><?php
	}
	?>

			</table>
		</div>
	<input type="submit" value="Preise speichern" name="submit" class="bottom_margin" />
	</form>
</body>
</html>