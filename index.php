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

require('./Effect.php');
require('./EffectCollection.php');
require('./Regency.php');
require('./RegencyCollection.php');
require('./RegencyBuilder.php');
require('./PairResult.php');
require('./data.php');

$prices = @file_get_contents('price_data.txt', FILE_TEXT);
RegencyCollection::setPrices($prices);

function getEffectsAsOptions($selected)
{
	$result = '<option value="-1"></option>';

	/** @var Effect $value */
	foreach (EffectCollection::getAllEffects() as $key => $value)
	{
		$result .= '<option value="' . $key . '"' . ($selected === $key ? ' selected' : '') . '>' . $value->getName() . '</option>';
	}

	return $result;
}

$effect1 = (int) (isset($_POST['effect1']) ? $_POST['effect1'] : -1);
$effect2 = (int) (isset($_POST['effect2']) ? $_POST['effect2'] : -1);
$effect3 = (int) (isset($_POST['effect3']) ? $_POST['effect3'] : -1);
$effect4 = (int) (isset($_POST['effect4']) ? $_POST['effect4'] : -1);
$filter_negative = isset($_POST['filter_negative']) && $_POST['filter_negative'];
$exact_effects = isset($_POST['exact_effects']) && $_POST['exact_effects'];
$sort_prices = isset($_POST['sort_prices']) && $_POST['sort_prices'];

$builder = new RegencyBuilder();
$builder->setEffect($effect1)
		->setEffect($effect2)
		->setEffect($effect3)
		->setEffect($effect4)
		->setFilterNegative($filter_negative)
		->setExact($exact_effects)
		->setPriceSort($sort_prices);

if (isset($_POST['submit']))
{
	foreach (RegencyCollection::getAllRegencies() as $regency)
	{
		$regency->setPrice(intval($_POST['price' . $regency->getId()]));
	}
}

file_put_contents('price_data.txt', RegencyCollection::getPrices(), FILE_TEXT);

?><!DOCTYPE html>
<html>
<head>
	<title>Alathair Alchemie</title>
	<style>
		table, th, td { border: 1px solid black; }
	</style>
</head>
<body>
	<form action="index.php" method="post">
		<select name="effect1"><?= getEffectsAsOptions($effect1); ?></select>
		<select name="effect2"><?= getEffectsAsOptions($effect2); ?></select>
		<select name="effect3"><?= getEffectsAsOptions($effect3); ?></select>
		<select name="effect4"><?= getEffectsAsOptions($effect4); ?></select>
		<br /><br />
		<input type="checkbox" name="filter_negative" value="1" <?php if ($filter_negative) echo 'checked="checked" '; ?>/> Negative filtern?<br />
		<input type="checkbox" name="exact_effects" value="1" <?php if ($exact_effects) echo 'checked="checked" '; ?>/> Exakt diese Wirkungen?<br />
		<input type="checkbox" name="sort_prices" value="1" <?php if ($sort_prices) echo 'checked="checked" '; ?>/> Nach Preisen sortieren?<br />
		<br />
		<input type="submit" value="Mix it!" name="submit" /><?php

if (isset($_POST['submit']) && ($effect1 > -1 || $effect2 > -1 || $effect3 > -1 || $effect4 > -1))
{
	?>
		<table>
			<tr>
				<th>Reagenzien</th>
				<th>Wirkungen</th>
				<th>Preis</th>
			</tr><?php

	$regency_pairs = $builder->getResult();
	foreach ($regency_pairs as $regency_pair)
	{
		if ($regency_pair === null)
		{
			echo '<tr></tr>' . PHP_EOL;
			continue;
		}
		?>

			<tr>
				<td><?= implode(', ', $regency_pair->getRegencyNames()); ?></td>
				<td><?= implode(', ', $regency_pair->getEffectNames()); ?></td>
				<td><?= $regency_pair->getPriceText(); ?></td>
			</tr><?php
	}

?>
		</table><?php
}
?>
		<br /><br /><hr /><br />
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
				<td><input name="price<?= $regency->getId(); ?>" type="number" value="<?= intval($regency->getPrice()); ?>" style="width: 100px;"></td>
			</tr><?php
		}
			?>
		</table>
		<input type="submit" value="Preise speichern" name="submit" />
	</form>
</body>
</html>
