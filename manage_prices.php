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
else if (strtolower($_SERVER['PHP_AUTH_USER']) !== strtolower($username) || $_SERVER['PHP_AUTH_PW'] !== $password)
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
?><!DOCTYPE html>
<html>
<head>
	<title>Alathair Alchemie</title>
	<style type="text/css">
		table, th, td {
			border: 1px solid black;
		}
	</style>
</head>
<body>
	<a href="index.php">Zurück</a><br />
	<h1>Preise verwalten</h1>
	<form action="index.php" method="post">
		<table>
		<tr>
			<th>Reagenz</th>
			<th>Preis</th>
		</tr><?php

	foreach (RegencyCollection::getAllRegencies() as $regency)
	{
		?>

		<tr>
		<td><?= $regency->getName(); ?></td>
		<td><input name="price<?= $regency->getId(); ?>" type="number" title="Preis eingeben" value="<?= $regency->getPrice(); ?>" style="width: 100px;"></td>
		</tr><?php
	}
	?>
</table>
<input type="submit" value="Preise speichern" name="submit" />
</form>
</body>
</html>