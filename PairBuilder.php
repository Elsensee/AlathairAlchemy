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

/**
 * PairBuilder class
 */
class PairBuilder
{
	/** @var array */
	protected $effectsWished = [];

	/** @var bool */
	protected $exactEffects = false;

	/** @var bool */
	protected $filterNegative = false;

	/** @var bool */
	protected $priceSort = false;

	/** @var array */
	protected $result = [];

	/**
	 * Set a wished effect
	 *
	 * @param int|null $effectId
	 *
	 * @return $this
	 */
	public function setEffect($effectId)
	{
		if ($effectId === null)
		{
			return $this;
		}

		$effectId = (int) $effectId;
		if ($effectId < 0)
		{
			return $this;
		}

		$effect = EffectCollection::getEffectById($effectId);
		if ($effect === null)
		{
			throw new RuntimeException('Effect with ID ' . $effectId . ' could not be found.');
		}
		else if (in_array($effect, $this->effectsWished))
		{
			return $this;
		}

		$this->effectsWished[$effectId] = $effect;

		return $this;
	}

	/**
	 * Set if the effects has to be exact
	 *
	 * @param bool $exact
	 *
	 * @return $this
	 */
	public function setExact($exact)
	{
		$this->exactEffects = $exact;

		return $this;
	}

	/**
	 * Set if negative effects should be filtered
	 *
	 * @param bool $filter
	 *
	 * @return $this
	 */
	public function setFilterNegative($filter)
	{
		$this->filterNegative = $filter;

		return $this;
	}

	/**
	 * Set if prices should be sorted
	 *
	 * @param bool $sort
	 *
	 * @return $this
	 */
	public function setPriceSort($sort)
	{
		$this->priceSort = $sort;

		return $this;
	}

	/**
	 * Gets the result of the PairBuilder and returns an array of PairResult's.
	 *
	 * @return array
	 */
	public function getResult()
	{
		if (empty($this->effectsWished))
		{
			return null;
		}

		$regenciesFromEffects = [];

		// First, get all regencies that have the desired effects
		foreach ($this->effectsWished as $effectWished)
		{
			$regenciesFromEffects += RegencyCollection::getRegenciesWithEffect($effectWished);
		}
		sort($regenciesFromEffects, SORT_STRING);
		$regenciesFromEffects = array_values($regenciesFromEffects);

		$combinationsChecked = $pairs = [];

		$wishedEffectNames = array_map(function($effect) {
			/** @var Effect $effect */
			return $effect->getName();
		}, $this->effectsWished);
		$countEffects = count($wishedEffectNames);

		// Now combine and check them
		for ($combineCount = 2; $combineCount <= 4 || ($countEffects === 1 && $combineCount <= 2); $combineCount++)
		{
			$combinations = $this->combine($regenciesFromEffects, $combineCount);

			foreach ($combinations as $combination)
			{
				$combinationCheck = &$combinationsChecked;

				$combinationWithId = [];

				// Check if we already combined some regencies
				for ($i = 0; $i < $combineCount; $i++)
				{
					$id = $combination[$i]->getId();
					if (!isset($combinationCheck[$id]))
					{
						$combinationCheck[$id] = [];
					}
					else if ($combinationCheck[$id] === true)
					{
						continue 2;
					}
					$combinationWithId[$id] = $combination[$i];

					$combinationCheck = &$combinationCheck[$id];
				}

				// ... otherwise get what effects these regencies combined return
				$pair = $this->getEffectsFromRegencies($combinationWithId, $wishedEffectNames, $this->exactEffects, $this->filterNegative);

				// If they don't get anything, continue.
				if ($pair === null)
				{
					continue;
				}

				// Otherwise we found a pair and add it to the list.
				$pairs[] = $pair;
				$combinationCheck = true;
			}
		}

		if ($this->priceSort)
		{
			usort($pairs, array('PairResult', 'priceSort'));
		}

		return $pairs;
	}

	public function getResultByMode($mode)
	{
		$mode = strtolower($mode);

		$pairs = [];

		switch ($mode)
		{
			case 'cheapest':
				$combinations = $this->combine(RegencyCollection::getAllRegencies(), 2);

				foreach ($combinations as $combination)
				{
					$combinationWithId = [];

					/** @var Regency $regency */
					foreach ($combination as $regency)
					{
						$combinationWithId[$regency->getId()] = $regency;
					}

					$pair = $this->getEffectsFromRegencies($combination);

					if ($pair === null)
					{
						continue;
					}

					$pairs[] = $pair;
				}

				usort($pairs, array('PairResult', 'priceSort'));
			break;
		}

		return $pairs;
	}

	/**
	 * Combines regencies with eachother with length $size.
	 *
	 * @param array $regencies
	 * @param int $size Needs to be 2, 3 or 4.
	 *
	 * @return array
	 */
	protected function combine(array $regencies, $size)
	{
		$count = count($regencies);

		$result = [];

		foreach ($regencies as $key => $regency)
		{
			for ($i = $key + 1; $i < $count; $i++)
			{
				if ($size === 2)
				{
					$result[] = [$regency, $regencies[$i]];
				}
				else
				{
					for ($j = $i + 1; $j < $count; $j++)
					{
						if ($size === 3)
						{
							$result[] = [$regency, $regencies[$i], $regencies[$j]];
						}
						else
						{
							for ($k = $j + 1; $k < $count; $k++)
							{
								if ($size === 4)
								{
									$result[] = [$regency, $regencies[$i], $regencies[$j], $regencies[$k]];
								}
							}
						}
					}
				}
			}
		}

		return $result;
	}

	/**
	 * Gets all effects from given regencies. ($mustHave if some regencies has to be set)
	 *
	 * @param array	$regencies
	 * @param array	$mustHave
	 * @param bool	$exact
	 * @param bool	$filterNegative
	 *
	 * @return null|PairResult
	 */
	protected function getEffectsFromRegencies(array $regencies, array $mustHave = [], $exact = false, $filterNegative = false)
	{
		$regencyEffectArray = [];
		$effectPossible = [];
		$mustHaveExist = !empty($mustHave);
		$mustHaveCopy = $mustHave;

		/** @var Regency $regency */
		foreach ($regencies as $regencyId => $regency)
		{
			/** @var Effect $effect */
			foreach ($regency->getEffects() as $effectId => $effect)
			{
				if (!isset($regencyEffectArray[$effectId]))
				{
					$regencyEffectArray[$effectId] = [];
				}
				else
				{
					// We definitely already had one. This is the second, so it's possible, yay!
					$effectPossible[$effectId] = true;
					if (!empty($mustHave) && isset($mustHave[$effectId]))
					{
						unset($mustHave[$effectId]);
					}
					else if (!empty($mustHave) && $exact && !isset($mustHaveCopy[$effectId]))
					{
						return null;
					}

					if ($filterNegative)
					{
						if (!empty($mustHaveCopy) && !isset($mustHaveCopy[$effectId]) && !EffectCollection::getEffectById($effectId)->isPositive())
						{
							return null;
						}
					}
				}

				$regencyEffectArray[$effectId][$regencyId] = $regency;
			}
		}

		if ($mustHaveExist && !empty($mustHave))
		{
			return null;
		}

		$effectResult = $regencyResult = [];

		foreach ($regencyEffectArray as $effectId => $regencyArray)
		{
			if (isset($effectPossible[$effectId]) && $effectPossible[$effectId] === true)
			{
				$effectResult[] = EffectCollection::getEffectById($effectId);

				$regencyResult += $regencyArray;
			}
		}

		if (empty($regencyResult) || empty($effectResult))
		{
			return null;
		}

		return new PairResult($regencyResult, $effectResult);
	}
}
