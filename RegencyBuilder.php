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

class RegencyBuilder
{
	protected $effectsWished = [];

	protected $exactEffects = false;

	protected $filterNegative = false;

	protected $priceSort = false;

	protected $result = [];

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

		$this->effectsWished[] = $effect;

		return $this;
	}

	public function setExact($exact)
	{
		$this->exactEffects = $exact;

		return $this;
	}

	public function setFilterNegative($filter)
	{
		$this->filterNegative = $filter;

		return $this;
	}

	public function setPriceSort($sort)
	{
		$this->priceSort = $sort;

		return $this;
	}

	public function getResult()
	{
		$regenciesFromEffects = [];

		foreach ($this->effectsWished as $effectWished)
		{
			$regenciesFromEffects = array_merge($regenciesFromEffects, RegencyCollection::getRegenciesWithEffect($effectWished));
		}
		$regenciesFromEffects = array_unique($regenciesFromEffects);
		sort($regenciesFromEffects, SORT_STRING);
		$regenciesFromEffects = array_values($regenciesFromEffects);

		$checked = $pairs = [];

		$effects = array_map(function($effect) {
			/** @var Effect $effect */
			return $effect->getName();
		}, $this->effectsWished);
		$countEffects = count($effects);

		for ($combineCount = 2; $combineCount <= 4; $combineCount++)
		{
			$combinations = $this->combine($regenciesFromEffects, $combineCount);

			foreach ($combinations as $combination)
			{
				$step = 0;
				$temp_check = &$checked;

				do
				{
					if (!isset($temp_check[$combination[$step]->getId()]))
					{
						$temp_check[$combination[$step]->getId()] = [];
					}
					else if ($temp_check[$combination[$step]->getId()] === true)
					{
						continue 2;
					}

					$temp_check = &$temp_check[$combination[$step]->getId()];
					$step++;
				} while ($step < $combineCount);

				$pair = $this->getEffectsFromRegencies($combination);

				if ($pair === null)
				{
					continue;
				}

				if ($this->filterNegative)
				{
					/** @var Effect $effect */
					foreach ($pair->getEffects() as $effect)
					{
						if (!$effect->isPositive() && !in_array($effect->getName(), $effects))
						{
							continue 2;
						}
					}
				}

				if (count(array_intersect($pair->getEffectNames(), $effects)) < $countEffects)
				{
					continue;
				}

				if ($this->exactEffects)
				{
					if (count(array_diff($pair->getEffectNames(), $effects)) > 0)
					{
						continue;
					}
				}

				$pairs[] = $pair;
				$temp_check = true;
			}
			if (!empty($pairs) && !$this->priceSort)
			{
				$pairs[] = null;
			}
		}

		if ($this->priceSort)
		{
			usort($pairs, function (PairResult $a, PairResult $b) {
				$priceA = $a->getPrice();
				$priceB = $b->getPrice();

				if ($priceA === $priceB)
				{
					return 0;
				}
				return ($priceA < $priceB) ? -1 : 1;
			});
		}

		return $pairs;
	}

	protected function combine($regencies, $size)
	{
		$count = count($regencies);

		$result = [];

		foreach ($regencies as $key => $regency)
		{
			for ($i = $key + 1; $i < $count; $i++)
			{
				if ($size <= 2)
				{
					$result[] = [$regency, $regencies[$i]];
				}
				else
				{
					for ($j = $i + 1; $j < $count; $j++)
					{
						if ($size <= 3)
						{
							$result[] = [$regency, $regencies[$i], $regencies[$j]];
						}
						else
						{
							for ($k = $j + 1; $k < $count; $k++)
							{
								$result[] = [$regency, $regencies[$i], $regencies[$j], $regencies[$k]];
							}
						}
					}
				}
			}
		}

		return $result;
	}

	protected function getEffectsFromRegencies($regencies)
	{
		$regencies = array_unique($regencies);

		$effectArray = [];

		/** @var Regency $regency */
		foreach ($regencies as $regency)
		{
			/** @var Effect $effect */
			foreach ($regency->getEffects() as $effect)
			{
				if (!isset($effectArray[$effect->getName()]))
				{
					$effectArray[$effect->getName()] = [];
				}

				$effectArray[$effect->getName()][] = $regency->getName();
			}
		}

		$effectResult = $regencyResult = [];

		foreach ($effectArray as $effect => $effectRegencies)
		{
			if (count($effectRegencies) >= 2)
			{
				$effectResult[] = EffectCollection::getEffect($effect);

				foreach ($effectRegencies as $effectRegency)
				{
					$regencyResult[] = RegencyCollection::getRegency($effectRegency);
				}
			}
		}

		if (count($regencyResult) === 0 || count($effectResult) === 0)
		{
			return null;
		}

		return new PairResult(array_unique($regencyResult), array_unique($effectResult));
	}
}
