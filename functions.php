<?php

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
