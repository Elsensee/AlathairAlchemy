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

class RegencyCollection
{
	static protected $effectCache = [];

	static protected $regencies = [];

	static public function addRegency(Regency $regency)
	{
		$id = count(self::$regencies);
		$regency->setId($id);
		self::$regencies[] = $regency;
	}

	static public function getAllRegencies()
	{
		return self::$regencies;
	}

	static public function getRegency($name)
	{
		/** @var Regency $regency */
		foreach (self::$regencies as $regency)
		{
			if ($regency->getName() === $name)
			{
				return $regency;
			}
		}

		return null;
	}

	static protected function buildEffectCache()
	{
		/** @var Regency $regency */
		foreach (self::$regencies as $regency)
		{
			/** @var Effect $effect */
			foreach ($regency->getEffects() as $effect)
			{
				if (!isset(self::$effectCache[$effect->getId()]))
				{
					self::$effectCache[$effect->getId()] = [];
				}

				self::$effectCache[$effect->getId()][] = $regency;
			}
		}
	}

	static public function getRegenciesWithEffect(Effect $effect)
	{
		if (empty(self::$effectCache))
		{
			self::buildEffectCache();
		}

		if (isset(self::$effectCache[$effect->getId()]))
		{
			return self::$effectCache[$effect->getId()];
		}

		return null;
	}

	static public function setPrices($text)
	{
		if (empty($text))
		{
			return;
		}

		$priceArray = @unserialize($text);

		if ($priceArray === false)
		{
			return;
		}

		$cache = [];
		/** @var Regency $regency */
		foreach (self::$regencies as $regency)
		{
			$cache[$regency->getName()] = $regency->getId();
		}

		foreach ($priceArray as $name => $price)
		{
			if (isset($cache[$name]) && isset(self::$regencies[$cache[$name]]))
			{
				self::$regencies[$cache[$name]]->setPrice($price);
			}
		}
	}

	static public function getPrices()
	{
		$priceArray = [];

		/** @var Regency $regency */
		foreach (self::$regencies as $regency)
		{
			$priceArray[$regency->getName()] = intval($regency->getPrice());
		}

		return serialize($priceArray);
	}
}
