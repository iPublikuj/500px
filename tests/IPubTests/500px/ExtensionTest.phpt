<?php
/**
 * Test: IPub\FiveHundredPixel\Extension
 * @testCase
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:500px!
 * @subpackage	Tests
 * @since		5.0
 *
 * @date		05.03.15
 */

namespace IPubTests\FiveHundredPixel;

use Nette;

use Tester;
use Tester\Assert;

use IPub;
use IPub\FiveHundredPixel;

require __DIR__ . '/../bootstrap.php';

class ExtensionTest extends Tester\TestCase
{
	/**
	 * @return \SystemContainer|\Nette\DI\Container
	 */
	protected function createContainer()
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		FiveHundredPixel\DI\FiveHundredPixelExtension::register($config);

		$config->addConfig(__DIR__ . '/files/config.neon', $config::NONE);

		return $config->createContainer();
	}

	public function testCompilersServices()
	{
		$dic = $this->createContainer();

		Assert::true($dic->getService('twitter.client') instanceof IPub\FiveHundredPixel\Client);
		Assert::true($dic->getService('twitter.config') instanceof IPub\FiveHundredPixel\Configuration);
		Assert::true($dic->getService('twitter.session') instanceof IPub\FiveHundredPixel\SessionStorage);
	}
}

\run(new ExtensionTest());