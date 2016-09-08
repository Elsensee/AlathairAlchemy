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

namespace Alchemy;

class RegencyCollection
{
	/** @var array */
	protected $cache = [];

	/** @var array */
	protected $effectCache = [];

	/** @var int */
	protected $lastId = 0;

	/** @var array */
	protected $regencies = [];

	/**
	 * Adds a regency to the collection
	 *
	 * @param Regency $regency
	 */
	public function addRegency(Regency $regency)
	{
		// Determine the ID of the Regency in the collection. (Useful later)
		$regency->setId($this->lastId);
		$this->regencies[] = $regency;

		$this->lastId++;
	}

	/**
	 * Gets an array of all regencies
	 *
	 * @return array
	 */
	public function getAllRegencies()
	{
		return $this->regencies;
	}

	/**
	 * Gets the regency by its name
	 *
	 * @param string $name
	 *
	 * @return null|Regency
	 */
	public function getRegency($name)
	{
		// Builds the cache if it's not yet built
		if (empty($this->cache))
		{
			$this->buildCache();
		}

		// Use the cache if it's in it.
		if (isset($this->cache[$name]))
		{
			return $this->regencies[$this->cache[$name]];
		}

		// Otherwise fall back to searching for the effect in the array. (Slow)
		/** @var Regency $regency */
		foreach ($this->regencies as $regency)
		{
			if ($regency->getName() === $name)
			{
				return $regency;
			}
		}

		return null;
	}

	/**
	 * Builds the cache (Regency_name => Regency_id)
	 */
	protected function buildCache()
	{
		/** @var Regency $regency */
		foreach ($this->regencies as $regency)
		{
			$this->cache[$regency->getName()] = $regency->getId();
		}
	}

	/**
	 * Builds the cache on the effects (Effect_id => array of regency instances)
	 */
	protected function buildEffectCache()
	{
		/** @var Regency $regency */
		foreach ($this->regencies as $regency)
		{
			/** @var Effect $effect */
			foreach ($regency->getEffects() as $id => $effect)
			{
				if (!isset($this->effectCache[$id]))
				{
					$this->effectCache[$id] = [];
				}

				$this->effectCache[$id][$regency->getId()] = $regency;
			}
		}
	}

	/**
	 * Get an array with all regencies with a specific Effect
	 * Returns null if no regencies were found
	 *
	 * @param Effect $effect
	 *
	 * @return array|null
	 */
	public function getRegenciesWithEffect(Effect $effect)
	{
		// Build the cache
		if (empty($this->effectCache))
		{
			$this->buildEffectCache();
		}

		if (isset($this->effectCache[$effect->getId()]))
		{
			return $this->effectCache[$effect->getId()];
		}

		return null;
	}

	/**
	 * Sets all prices from a config file text.
	 *
	 * @param $text
	 */
	public function setPrices($text)
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
		foreach ($this->regencies as $id => $regency)
		{
			$cache[$regency->getName()] = $id;
		}

		foreach ($priceArray as $name => $price)
		{
			if (isset($cache[$name]) && isset($this->regencies[$cache[$name]]))
			{
				$this->regencies[$cache[$name]]->setPrice($price);
			}
		}
	}

	/**
	 * Returns a string that can be written to a text file that can be recovered.
	 *
	 * @return string
	 */
	public function getPrices()
	{
		$priceArray = [];

		/** @var Regency $regency */
		foreach ($this->regencies as $regency)
		{
			$priceArray[$regency->getName()] = $regency->getPrice();
		}

		return serialize($priceArray);
	}
}
