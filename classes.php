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

	public function __toString()
	{
		return $this->getName();
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

	protected $price;

	public function __construct($name, array $effects, $price = null)
	{
		if (self::$collection === null)
		{
			throw new Exception();
		}
		$this->name = $name;
		$this->price = $price;

		$this->effects = [];

		foreach ($effects as $effect)
		{
			$this->effects[] = self::$collection->getEffect($effect);
		}
	}

	public function __toString()
	{
		return $this->getName();
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

	public function getPrice()
	{
		return $this->price;
	}

	public function setPrice($price)
	{
		$this->price = $price;
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
