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

require('./Effect.php');
require('./EffectCollection.php');
require('./Regency.php');
require('./RegencyCollection.php');
require('./PairResult.php');
require('./PairBuilder.php');

function getEffectsAsOptions($selected)
{
	$result = '<option value="-1">&nbsp;</option>';

	/** @var Effect $value */
	foreach (EffectCollection::getAllEffects() as $key => $value)
	{
		$result .= '<option value="' . $key . '"' . ($selected === $key ? ' selected' : '') . '>' . $value->getName() . '</option>';
	}

	return $result;
}

function echo_memory_usage()
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

require('./data.php');

// Get variables
$effect1 = (int) (isset($_POST['effect1']) ? $_POST['effect1'] : -1);
$effect2 = (int) (isset($_POST['effect2']) ? $_POST['effect2'] : -1);
$effect3 = (int) (isset($_POST['effect3']) ? $_POST['effect3'] : -1);
$effect4 = (int) (isset($_POST['effect4']) ? $_POST['effect4'] : -1);
$filterNegative = isset($_POST['filter_negative']) && $_POST['filter_negative'];
$exactEffects = isset($_POST['exact_effects']) && $_POST['exact_effects'];
$sortPrices = isset($_POST['sort_prices']) && $_POST['sort_prices'];
$mode = isset($_GET['mode']) ? strtolower($_GET['mode']) : '';

// Create and setup new PairBuilder
$builder = new PairBuilder();
$builder->setEffect($effect1)
		->setEffect($effect2)
		->setEffect($effect3)
		->setEffect($effect4)
		->setFilterNegative($filterNegative)
		->setExact($exactEffects)
		->setPriceSort($sortPrices);

?><!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="utf-8">
	<title>Alathair Alchemie</title>
	<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
	<form action="index.php" method="post">
		<div id="options" class="bottom_margin">
			<div id="effects" class="leftside">
				<select id="effect1" name="effect1" title="Effekt 1"><?= getEffectsAsOptions($effect1); ?></select>&nbsp;
				<select id="effect2" name="effect2" title="Effekt 2"><?= getEffectsAsOptions($effect2); ?></select>&nbsp;
				<select id="effect3" name="effect3" title="Effekt 3"><?= getEffectsAsOptions($effect3); ?></select>&nbsp;
				<select id="effect4" name="effect4" title="Effekt 4"><?= getEffectsAsOptions($effect4); ?></select>&nbsp;
			</div>
			<div id="price_link" class="rightside">
				<a href="./manage.php">Preise verwalten</a><br />
			</div>
		</div>
		<div id="menu" class="bottom_margin">
			<div id="filter_boxes" class="leftside right_margin">
				<input type="checkbox" id="filter_negative" name="filter_negative" value="1" <?php if ($filterNegative) echo 'checked="checked" '; ?>/>
				<label for="filter_negative">Negative filtern?</label><br />
				<input type="checkbox" id="exact_effects" name="exact_effects" value="1" <?php if ($exactEffects) echo 'checked="checked" '; ?>/>
				<label for="exact_effects">Exakt diese Wirkungen?</label><br />
				<input type="checkbox" id="sort_prices" name="sort_prices" value="1" <?php if ($sortPrices || strtolower($mode) === 'cheapest') echo 'checked="checked" '; ?>/>
				<label for="sort_prices">Nach Preisen sortieren?</label><br />
			</div>
			<div id="mix_links">
				<a href="index.php?mode=cheapest">Günstigste Mixturen</a><br />
			</div>
		</div>
		<input type="submit" value="Mix it!" name="submit" class="bottom_margin" /><?php

if ((isset($_POST['submit']) && ($effect1 > -1 || $effect2 > -1 || $effect3 > -1 || $effect4 > -1)) || !empty($mode))
{
	?>

	<table class="bottom_margin">
		<tr>
			<th>Reagenzien</th>
			<th>Wirkungen</th>
			<th>Preis</th>
		</tr><?php

		$regencyPairs = [];
		if (!empty($mode))
		{
			$regencyPairs = $builder->getResultByMode($mode);
		}
		else
		{
			$regencyPairs = $builder->getResult();
		}
		/** @var PairResult $regencyPair */
		foreach ($regencyPairs as $regencyPair)
		{
			if ($regencyPair === null)
			{
				continue;
			}

			?>

		<tr>
			<td><?= implode(', ', $regencyPair->getRegencyNames()); ?></td>
			<td><?= implode(', ', $regencyPair->getEffectNames()); ?></td>
			<td><?= $regencyPair->getPriceText(); ?></td>
		</tr><?php
		}

		?>

	</table>
	<?php
}
		?>

	</form>
	<hr />
	<p>Benötigte Ressourcen: <?= round((microtime(true) - $startTime), 2) . ' Sekunden | ' . echo_memory_usage(); ?></p>
</body>
</html>
