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

use Composer\Script\Event;

class DevScripts
{
	const VENDOR_DIR = __DIR__ . '/../vendor/';

	static public function postInstall(Event $event)
	{
		$directories = $files = [];

		if (!$event->isDevMode())
		{
			// Clean up twig
			$directories = array_merge($directories, [
				self::VENDOR_DIR . 'twig/twig/doc',
				self::VENDOR_DIR . 'twig/twig/ext',
				self::VENDOR_DIR . 'twig/twig/test',
			]);
			$files = array_merge($files, [
				self::VENDOR_DIR . 'twig/.editorconfig',
				self::VENDOR_DIR . 'twig/.gitignore',
				self::VENDOR_DIR . '.travis.yml',
				self::VENDOR_DIR . 'CHANGELOG',
				self::VENDOR_DIR . 'phpunit.xml.dist',
			]);
		}

		// Clean up all collected files
		foreach ($files as $file)
		{
			if (file_exists($file))
			{
				unlink($file);
			}
		}

		foreach ($directories as $directory)
		{
			if (file_exists($directory) && is_dir($directory))
			{
				self::removeDir($directory);
			}
		}
	}

	static protected function removeDir($dir)
	{
		$it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);

		foreach($files as $file)
		{
			if ($file->isDir())
			{
				rmdir($file->getRealPath());
			}
			else
			{
				unlink($file->getRealPath());
			}
		}
		rmdir($dir);
	}
}
