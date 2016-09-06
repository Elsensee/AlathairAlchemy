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

class PairResult
{
	protected $effects;

	protected $regencies;

	public function __construct(array $regencies, array $effects)
	{
		$this->regencies = $regencies;
		$this->effects = $effects;
	}

	public function getEffectNames()
	{
		$result = [];

		foreach ($this->effects as $effect)
		{
			$result[] = $effect->getName();
		}

		return $result;
	}

	public function getEffects()
	{
		return $this->effects;
	}

	public function getPrice()
	{
		$price = 0;

		foreach ($this->regencies as $regency)
		{
			$price += $regency->getPrice();
		}

		return $price;
	}

	public function getPriceText()
	{
		$prices = [];

		foreach ($this->regencies as $regency)
		{
			$prices[] = $regency->getPrice();
		}

		return implode('+', $prices) . '=' . array_sum($prices);
	}

	public function getRegencyNames()
	{
		$result = [];

		foreach ($this->regencies as $regency)
		{
			$result[] = $regency->getName();
		}

		return $result;
	}

	public function getRegencies()
	{
		return $this->regencies;
	}
}
