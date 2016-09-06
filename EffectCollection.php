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

class EffectCollection
{
	/** @var array */
	static protected $cache = [];

	/** @var array */
	static protected $effects = [];

	/** @var int */
	static protected $lastId = 0;

	/**
	 * Adds an effect to the collection
	 *
	 * @param Effect $effect
	 */
	static public function addEffect(Effect $effect)
	{
		// Determine the ID of the Effect in the collection. (Useful later)
		$effect->setId(self::$lastId);
		self::$effects[] = $effect;

		self::$lastId++;
	}

	/**
	 * Gets an array of all effects
	 *
	 * @return array
	 */
	static public function getAllEffects()
	{
		return self::$effects;
	}

	/**
	 * Gets an Effect by its ID.
	 * Returns null if no effect was found
	 *
	 * @param int|null $id
	 *
	 * @return Effect|null
	 */
	static public function getEffectById($id)
	{
		if ($id === null)
		{
			return null;
		}

		if (isset(self::$effects[$id]))
		{
			return self::$effects[$id];
		}

		return null;
	}

	/**
	 * Gets an Effect by its name
	 *
	 * @param string $name	The name of the effect
	 *
	 * @return Effect
	 */
	static public function getEffect($name)
	{
		// Builds the cache if it's not yet built
		if (empty(self::$cache))
		{
			self::buildCache();
		}

		// Use the cache if it's in it.
		if (isset(self::$cache[$name]))
		{
			return self::$effects[self::$cache[$name]];
		}

		// Otherwise fall back to searching for the effect in the array. (Slow)
		/** @var Effect $effect */
		foreach (self::$effects as $effect)
		{
			if ($effect->getName() === $name)
			{
				self::$cache[$effect->getName()] = $effect->getId();
				return $effect;
			}
		}

		throw new RuntimeException('Effect with name "' . $name . '" could not be found!');
	}

	/**
	 * Builds the cache. (Effect_name => Effect_id)
	 */
	static protected function buildCache()
	{
		/** @var Effect $effect */
		foreach (self::$effects as $effect)
		{
			self::$cache[$effect->getName()] = $effect->getId();
		}
	}
}
