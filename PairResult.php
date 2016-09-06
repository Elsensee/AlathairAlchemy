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
 * PairResult class
 *
 * Contains a pair result
 */
class PairResult
{
	protected $effects;

	protected $effectNames;

	protected $prices;

	protected $regencies;

	/**
	 * PairResult constructor.
	 *
	 * @param array $regencies
	 * @param array $effects
	 */
	public function __construct(array $regencies, array $effects)
	{
		$this->regencies = $regencies;
		$this->effects = $effects;
		natcasesort($this->effects);
	}

	/**
	 * Returns an array with all effect names
	 *
	 * @return array
	 */
	public function getEffectNames()
	{
		if (!empty($this->effectNames))
		{
			return $this->effectNames;
		}

		$this->effectNames = [];

		foreach ($this->effects as $effect)
		{
			$this->effectNames[] = $effect->getName();
		}

		return $this->effectNames;
	}

	/**
	 * Returns an array with all effects
	 *
	 * @return array
	 */
	public function getEffects()
	{
		return $this->effects;
	}

	/**
	 * Gets the price
	 *
	 * @return int
	 */
	public function getPrice()
	{
		if (empty($this->prices))
		{
			$this->buildPriceArray();
		}

		return array_sum($this->prices);
	}

	/**
	 * Gets the price as sum text
	 *
	 * @return string
	 */
	public function getPriceText()
	{
		if (empty($this->prices))
		{
			$this->buildPriceArray();
		}

		return implode('+', $this->prices) . '=' . array_sum($this->prices);
	}

	/**
	 * Builds the price array
	 */
	protected function buildPriceArray()
	{
		$this->prices = [];

		/** @var Regency $regency */
		foreach ($this->regencies as $regency)
		{
			$this->prices[] = intval($regency->getPrice());
		}
	}

	/**
	 * Gets an array with all regency names
	 *
	 * @return array
	 */
	public function getRegencyNames()
	{
		$result = [];

		foreach ($this->regencies as $regency)
		{
			$result[] = $regency->getName();
		}

		return $result;
	}

	/**
	 * Gets an array of all regencies
	 *
	 * @return array
	 */
	public function getRegencies()
	{
		return $this->regencies;
	}

	/**
	 * Defines a function for usort() for this class.
	 *
	 * @param PairResult $a
	 * @param PairResult $b
	 *
	 * @return int
	 */
	static public function priceSort(PairResult $a, PairResult $b)
	{
		$priceA = $a->getPrice();
		$priceB = $b->getPrice();

		if ($priceA === $priceB)
		{
			return strcasecmp(implode(', ', $a->getRegencyNames()), implode(', ', $b->getRegencyNames()));
		}
		return ($priceA < $priceB) ? -1 : 1;
	}
}
