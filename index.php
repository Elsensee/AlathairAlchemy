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

require('./common.php');

// Get variables
$effect1 = (int) (isset($_POST['effect1']) ? $_POST['effect1'] : -1);
$effect2 = (int) (isset($_POST['effect2']) ? $_POST['effect2'] : -1);
$effect3 = (int) (isset($_POST['effect3']) ? $_POST['effect3'] : -1);
$effect4 = (int) (isset($_POST['effect4']) ? $_POST['effect4'] : -1);
$effect5 = (int) (isset($_POST['effect5']) ? $_POST['effect5'] : -1);
$filterNegative = isset($_POST['filter_negative']) && $_POST['filter_negative'];
$exactEffects = isset($_POST['exact_effects']) && $_POST['exact_effects'];
$sortPrices = isset($_POST['sort_prices']) && $_POST['sort_prices'];
$mode = isset($_GET['mode']) ? strtolower($_GET['mode']) : '';

// Create and setup new PairBuilder
$builder = new Alchemy\PairBuilder();
$builder->setEffect($effect1)
		->setEffect($effect2)
		->setEffect($effect3)
		->setEffect($effect4)
		->setEffect($effect5)
		->setFilterNegative($filterNegative)
		->setExact($exactEffects)
		->setPriceSort($sortPrices);

$templateVariables = [
	'title'		=> 'Alathair Alchemie',
	'language'	=> 'de',

	'selectedOptions'	=> [
		1	=> $effect1,
		2	=> $effect2,
		3	=> $effect3,
		4	=> $effect4,
		5	=> $effect5,
	],
	'effectOptions'		=> array_merge([-1 => ' '], Alchemy\EffectCollection::getAllEffects()),

	'filterNegative'	=> $filterNegative,
	'exactEffects'		=> $exactEffects,
	'sortPrices'		=> $sortPrices,
	'mode'				=> $mode,
];

if ((isset($_POST['submit']) && ($effect1 > -1 || $effect2 > -1 || $effect3 > -1 || $effect4 > -1 || $effect5 > -1)))
{
	$templateVariables['regencyPairs'] = $builder->getResult();
}
else if (!empty($mode))
{
	$templateVariables['regencyPairs'] = $builder->getResultByMode($mode);
}

$templateVariables['memoryUsed'] = memory_usage();
$templateVariables['timeSpent'] = round(microtime(true) - $startTime, 2);

echo $twig->render('index.html.twig', $templateVariables);
