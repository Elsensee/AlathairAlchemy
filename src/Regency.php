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

/**
 * Regency class
 *
 * Represents one regency
 */
class Regency
{
	/** @var EffectCollection */
	static protected $collection;

	/** @var array */
	protected $effects;

	/** @var int */
	protected $id;

	/** @var string */
	protected $name;

	/** @var int */
	protected $price;

	/**
	 * Regency constructor.
	 *
	 * @param string	$name		Name of the regency
	 * @param array		$effects	Array of effect names
	 * @param int		$price		Price of the regency
	 *
	 * @throws \RuntimeException
	 */
	public function __construct($name, array $effects, $price = 0)
	{
		if (self::$collection === null)
		{
			throw new \RuntimeException('No EffectCollection has been set.');
		}
		$this->name = $name;
		$this->price = $price;

		$this->effects = [];

		foreach ($effects as $effect)
		{
			$effect = self::$collection->getEffect($effect);
			$this->effects[$effect->getId()] = $effect;
		}
	}

	/**
	 * Returns a string representing the instance of Regency class
	 *
	 * Mostly used for array_unique()
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->name;
	}

	/**
	 * Returns the name of the regency
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Returns an array of effect instances of this regency
	 *
	 * @return array
	 */
	public function getEffects()
	{
		return $this->effects;
	}

	/**
	 * Returns the price of the regency
	 *
	 * @return int
	 */
	public function getPrice()
	{
		return $this->price;
	}

	/**
	 * Sets the price of the regency
	 *
	 * @param int $price
	 */
	public function setPrice($price)
	{
		$this->price = intval($price);
	}

	/**
	 * Sets the ID of the Regency in the RegencyCollection
	 *
	 * @param int	$id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * Gets the ID of the Regency in the RegencyCollection
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Sets the Effect Collection statically for the Regency class.
	 * (Used to determine which Effect instances have to be selected)
	 *
	 * @param EffectCollection $collection
	 */
	static public function setEffectCollection(EffectCollection $collection)
	{
		self::$collection = $collection;
	}
}
