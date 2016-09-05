<?php

error_reporting(E_ALL);

require('./classes.php');
require('./data.php');

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

$builder = new RegencyBuilder();
$builder->setEffect($effect1)
		->setEffect($effect2)
		->setEffect($effect3)
		->setEffect($effect4)
		->setFilterNegative($filter_negative)
		->setExact($exact_effects);

?><!DOCTYPE html>
<html>
<head>
	<title>Alathair Alchemie</title>
	<style>
		table, td, th { border: 1px solid black; }
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
		<br />
		<input type="submit" value="Mix it!" name="submit" />
	</form><?php

if (isset($_POST['submit']))
{
	?>
	<table>
		<tr>
			<th>Reagenzien</th>
			<th>Wirkungen</th>
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
			<td><?= implode(', ', $regency_pair->getRegencies()); ?></td>
			<td><?= implode(', ', $regency_pair->getEffects()); ?></td>
		</tr>
<?php
	}

?>
	</table><?php
}
?>
</body>
</html>
