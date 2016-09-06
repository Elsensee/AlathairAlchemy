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
	static protected $cache = [];

	static protected $effects = [];

	static public function addEffect(Effect $effect)
	{
		$id = count(self::$effects);
		$effect->setId($id);
		self::$effects[] = $effect;
	}

	static public function getEffectById($id)
	{
		if (is_null($id))
		{
			return null;
		}

		if (isset(self::$effects[$id]))
		{
			return self::$effects[$id];
		}

		return null;
	}

	static public function getAllEffects()
	{
		return self::$effects;
	}

	static public function getEffect($name)
	{
		if (empty(self::$cache))
		{
			self::buildCache();
		}

		if (isset(self::$cache[$name]))
		{
			return self::$effects[self::$cache[$name]];
		}

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

	static protected function buildCache()
	{
		/** @var Effect $effect */
		foreach (self::$effects as $effect)
		{
			self::$cache[$effect->getName()] = $effect->getId();
		}
	}
}
