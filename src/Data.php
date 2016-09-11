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
 * Data class
 *
 * Reads in the data for the page
 */
class Data
{
	/** @var bool|string */
	protected $cache = false;

	/** @var EffectCollection */
	protected $effects;

	/** @var RegencyCollection */
	protected $regencies;

	/**
	 * Data constructor.
	 *
	 * @param EffectCollection  $effects
	 * @param RegencyCollection $regencies
	 */
	protected function __construct(EffectCollection $effects, RegencyCollection $regencies)
	{
		$this->effects = $effects;
		$this->regencies = $regencies;
	}

	/**
	 * Get the EffectCollection saved in this Data object.
	 *
	 * @return EffectCollection
	 */
	public function getEffects()
	{
		return $this->effects;
	}

	/**
	 * Get the RegencyCollection saved in this Data object.
	 *
	 * @return RegencyCollection
	 */
	public function getRegencies()
	{
		return $this->regencies;
	}

	/**
	 * Saves the current data object to an ini file
	 * Returns whatever file_put_contents() returns
	 *
	 * @param string $file
	 *
	 * @return int
	 */
	public function saveToIniFile($file)
	{
		$iniResult = "[Effects]\n";

		/** @var Effect $effect */
		foreach ($this->getEffects()->getAllEffects() as $effect)
		{
			$iniResult .= str_replace(' ', '_', $effect->getName()) . '=' . (int) $effect->isPositive() . "\n";
		}

		$iniResult .= "\n[Regencies]\n";

		/** @var Regency $regency */
		foreach ($this->getRegencies()->getAllRegencies() as $regency)
		{
			$iniResult .= str_replace(' ', '_', $regency->getName()) . '[effects]=' . implode(',', $regency->getEffects()) . "\n";
			$iniResult .= str_replace(' ', '_', $regency->getName()) . '[price]=' . (int) $regency->getPrice() . "\n";
		}

		return file_put_contents($file, $iniResult);
	}

	/**
	 * Read prices from price data file.
	 *
	 * @param $priceFile
	 */
	public function getPricesFromFile($priceFile)
	{
		$prices = @file_get_contents($priceFile, FILE_TEXT);
		$this->regencies->setPrices($prices);
	}

	/**
	 * Save prices to price data file
	 * Returns whatever file_put_contents() returns.
	 *
	 * @param $priceFile
	 *
	 * @return int
	 */
	public function savePricesToFile($priceFile)
	{
		return file_put_contents($priceFile, $this->regencies->getPrices(), FILE_TEXT);
	}

	/**
	 * Get data from an array in the format:
	 * [
	 * 		'effects' => [
	 * 			EFFECT_NAME => EFFECT_POSITIVE (bool)
	 * 		],
	 * 		'regencies' => [
	 * 			REGENCY_NAME => [
	 * 				'effects'	=> [EFFECT_NAMES], (optional)
	 * 				'price'		=> PRICE, (optional)
	 * 			],
	 * 		],
	 * ]
	 *
	 * @param array			$data		The data array in the specified format
	 * @param bool|string	$cache		The path to the cache file. False if cache should be disabled.
	 * @param bool|string	$checksum	False if checksum should be calculated, a string if its already given
	 *                              	Mostly internal argument.
	 *
	 * @return Data
	 */
	static public function getDataFromArray(array $data, $cache = false, $checksum = false)
	{
		if (empty($data) || !is_array($data) || !isset($data['effects']) || !isset($data['regencies']))
		{
			throw new \RuntimeException('Invalid data format.');
		}

		$cacheResult = "<?php\n\n";
		$cacheResult .= "\$effects = new \\Alchemy\\EffectCollection();\n\\Alchemy\\Regency::setEffectCollection(\$effects);\n\$regencies = new \\Alchemy\\RegencyCollection();\n\n";
		$checksumText = '';

		$effects = new EffectCollection();
		Regency::setEffectCollection($effects);
		$regencies = new RegencyCollection();

		$checksumText .= "[Effects]\n";

		foreach ($data['effects'] as $effect => $isPositive)
		{
			$effect = str_replace('_', ' ', $effect);

			$effects->addEffect(new Effect($effect, $isPositive));

			$cacheResult .= "\$effects->addEffect(new \\Alchemy\\Effect('" . addcslashes($effect, "'") . "', " . (($isPositive) ? 'true' : 'false') . "));\n";
			$effect = str_replace(' ', '_', $effect);
			$checksumText .= "$effect=" . (int) $isPositive . "\n";
		}

		$cacheResult .= "\n";
		$checksumText .= "\n[Regencies]";

		foreach ($data['regencies'] as $regency => $regencyData)
		{
			$regency = str_replace('_', ' ', $regency);

			if (!is_array($regencyData))
			{
				throw new \RuntimeException('Invalid data format.');
			}

			$regencyEffects = [];
			$price = 0;

			foreach ($regencyData as $key => $value)
			{
				switch ($key)
				{
					case 'effects':
						if (is_array($value))
						{
							$regencyEffects = $value;
							$value = '"' . implode(',', $value) . '"';
						}
						else
						{
							$regencyEffects = explode(',', $value);
						}
					break;

					case 'price':
						$price = (int) $value;
					break;
				}

				$checksumText .= str_replace(' ', '_', $regency) . "[$key]=$value\n";
			}

			$regencies->addRegency(new Regency($regency, $regencyEffects, $price));

			$cacheResult .= "\$regencies->addRegency(new \\Alchemy\\Regency('";
			$cacheResult .= addcslashes($regency, "'") . "', ['" . implode("', '", array_map(function ($effect) {
					return addcslashes($effect, "'");
			}, $regencyEffects)) . "'], $price));\n";
		}

		if (!empty($cache))
		{
			if ($checksum === false)
			{
				$checksum = md5($checksumText);
			}

			$cacheResult .= "\nreturn [\n";
			$cacheResult .= "\t'checksum'\t=> '$checksum',\n";
			$cacheResult .= "\t'data'\t\t=> [\n";
			$cacheResult .= "\t\t'effects'\t=> \$effects,\n";
			$cacheResult .= "\t\t'regencies'\t=> \$regencies,\n";
			$cacheResult .= "\t],\n];\n";

			file_put_contents($cache, $cacheResult);
		}
		unset($cacheResult, $checksumText);

		return new Data($effects, $regencies);
	}

	/**
	 * Get Data from Ini-File in the following format:
	 * [Effects]
	 * EFFECT_NAME=EFFECT_POSITIVE (spaces in effect names should be replaced with underscores)
	 *
	 * [Regencies]
	 * REGENCY_NAME[effects]=REGENCY_EFFECTS (comma separated list, spaces in *regency* name should be replaced with an
	 * underscores) REGENCY_NAME[price]=REGENCY_PRICE (spaces in regency name should be replaced with an underscore)
	 *
	 * @param string		$file	The path to the data ini-file.
	 * @param bool|string	$cache	The path to the cache file, false if cache should be disabled
	 *
	 * @return Data
	 */
	static public function getDataFromIniFile($file, $cache = false)
	{
		$data = file_get_contents($file);
		if ($data === false)
		{
			throw new \RuntimeException('There was an error while reading the data file.');
		}

		$checksum = md5($data);

		if (!empty($cache) && file_exists($cache))
		{
			$cachedData = include $cache;

			if (!empty($cachedData) && isset($cachedData['data']) && isset($cachedData['checksum']))
			{
				if ($cachedData['checksum'] === $checksum)
				{
					return new Data($cachedData['data']['effects'], $cachedData['data']['regencies']);
				}
			}
		}

		$iniData = parse_ini_string($data, true, INI_SCANNER_TYPED);
		unset($data);

		if (empty($iniData))
		{
			throw new \RuntimeException('The data was invalid');
		}

		if (isset($iniData['Effects']))
		{
			$iniData['effects'] = $iniData['Effects'];
			unset($iniData['Effects']);
		}
		if (isset($iniData['Regencies']))
		{
			$iniData['regencies'] = $iniData['Regencies'];
			unset($iniData['Regencies']);
		}

		return self::getDataFromArray($iniData, $cache, $checksum);
	}
}
