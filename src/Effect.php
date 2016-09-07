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
 * Effect class
 *
 * Represents one effect
 */
class Effect
{
	/** @var int */
	protected $id;

	/** @var bool */
	protected $isPositive;

	/** @var string */
	protected $name;

	/**
	 * Effect constructor.
	 *
	 * @param string	$name		Name of the effect
	 * @param bool		$isPositive	Defines whether effect is positive or negative
	 */
	public function __construct($name, $isPositive)
	{
		$this->name = $name;
		$this->isPositive = $isPositive;
	}

	/**
	 * Returns a string representing the instance of Effect class
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
	 * Returns the name of the effect
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Returns whether the effect is positive or negative
	 *
	 * @return bool
	 */
	public function isPositive()
	{
		return $this->isPositive;
	}

	/**
	 * Sets the ID of the Effect in the EffectCollection
	 *
	 * @param int	$id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * Gets the ID of the Effect in the EffectCollection
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}
}
