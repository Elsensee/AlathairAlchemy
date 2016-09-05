<?php

class Effect
{
	protected $id;

	protected $isPositive;

	protected $name;

	public function __construct($name, $isPositive)
	{
		$this->name = $name;
		$this->isPositive = $isPositive;
	}

	public function getName()
	{
		return $this->name;
	}

	public function isPositive()
	{
		return $this->isPositive;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}
}

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

class Regency
{
	/** @var EffectCollection */
	static protected $collection;

	protected $effects;

	protected $id;

	protected $name;

	protected $value;

	public function __construct($name, array $effects, $value = null)
	{
		if (self::$collection === null)
		{
			throw new Exception();
		}
		$this->name = $name;
		$this->value = $value;

		$this->effects = [];

		foreach ($effects as $effect)
		{
			$this->effects[] = self::$collection->getEffect($effect);
		}
	}

	static public function setEffectCollection(EffectCollection $collection)
	{
		self::$collection = $collection;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getEffects()
	{
		return $this->effects;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}
}

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
}

class RegencyBuilder
{
	protected $effectsWished = [];

	protected $exactEffects = false;

	protected $filterNegative = false;

	protected $result = [];

	public function setEffect($effectId)
	{
		if ($effectId === null)
		{
			throw new RuntimeException('$effect can not be null');
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

	public function getResult()
	{
		$regenciesFromEffects = [];

		foreach ($this->effectsWished as $effectWished)
		{
			$regenciesFromEffects = array_merge($regenciesFromEffects, RegencyCollection::getRegenciesWithEffect($effectWished));
		}
		$regenciesFromEffects = array_values(array_unique($regenciesFromEffects, SORT_REGULAR));

		$result = [];

		for ($i = 0, $size = count($regenciesFromEffects); $i < $size; $i++)
		{
			for ($j = $i + 1; $j < $size; $j++)
			{
				$temp = $this->getEffectsFromRegencies([$regenciesFromEffects[$i], $regenciesFromEffects[$j]]);
				if ($temp === null)
				{
					continue;
				}
				$result[] = $temp;
			}
		}

		return $this->filter($result);
	}

	protected function filter($regencyPairs)
	{
		$effects = array_map(function($effect) {
			/** @var Effect $effect */
			return $effect->getName();
		}, $this->effectsWished);

		$exact = $this->exactEffects;
		$filterNegative = $this->filterNegative;

		return array_filter($regencyPairs, function($regencyPair) use ($effects, $exact, $filterNegative) {
			if ($filterNegative)
			{
				foreach ($regencyPair[1] as $effect)
				{
					if (!EffectCollection::getEffect($effect)->isPositive() && !in_array($effect, $effects))
					{
						return false;
					}
				}
			}

			if (count(array_intersect($regencyPair[1], $effects)) < count($effects))
			{
				return false;
			}

			if ($exact)
			{
				if (count(array_diff($regencyPair[1], $effects)) > 0)
				{
					return false;
				}
			}

			return true;
		});
	}

	protected function getEffectsFromRegencies($regencies)
	{
		$regencies = array_unique($regencies, SORT_REGULAR);

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

				$effectArray[$effect->getName()] = array_merge($effectArray[$effect->getName()], [$regency->getName()]);
			}
		}

		$effectResult = $regencyResult = [];

		foreach ($effectArray as $effect => $effectRegencies)
		{
			if (count($effectRegencies) >= 2)
			{
				$effectResult[] = $effect;

				foreach ($effectRegencies as $effectRegency)
				{
					$regencyResult[] = $effectRegency;
				}
			}
		}

		if (count($regencyResult) === 0 || count($effectResult) === 0)
		{
			return null;
		}

		return [array_unique($regencyResult), array_unique($effectResult)];
	}
}
