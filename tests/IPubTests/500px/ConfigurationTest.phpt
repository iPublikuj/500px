<?php
/**
 * Test: IPub\FiveHundredPixel\Configuration
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

require_once __DIR__ . '/../bootstrap.php';

class ConfigurationTest extends Tester\TestCase
{
	/**
	 * @var FiveHundredPixel\Configuration
	 */
	private $config;

	protected function setUp()
	{
		$this->config = new FiveHundredPixel\Configuration('123', 'abc');
	}

	public function testCreateUrl()
	{
		Assert::match('https://api.500px.com/v1/users', (string) $this->config->createUrl('api', 'users'));

		Assert::match('https://api.500px.com/v1/oauth/access_token?oauth_consumer_key=123&oauth_signature_method=HMAC-SHA1', (string) $this->config->createUrl('oauth', 'access_token', array(
			'oauth_consumer_key' => $this->config->consumerKey,
			'oauth_signature_method' => 'HMAC-SHA1'
		)));

		Assert::match('https://api.500px.com/v1/oauth/request_token?oauth_consumer_key=123&oauth_signature_method=HMAC-SHA1', (string) $this->config->createUrl('oauth', 'request_token', array(
			'oauth_consumer_key' => $this->config->consumerKey,
			'oauth_signature_method' => 'HMAC-SHA1'
		)));
	}
}

\run(new ConfigurationTest());