<?php
/**
 * Profile.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:500px!
 * @subpackage	common
 * @since		5.0
 *
 * @date		07.03.15
 */

namespace IPub\FiveHundredPixel;

use Nette;
use Nette\Utils;

use IPub;
use IPub\FiveHundredPixel\Exceptions;

/**
 * 500px's user profile
 *
 * @package		iPublikuj:500px!
 * @subpackage	common
 *
 * @author Adam Kadlec <adam.kadlec@fastybird.com>
 */
class Profile extends Nette\Object
{
	/**
	 * @var Client
	 */
	private $fiveHundredPixel;

	/**
	 * @var string
	 */
	private $profileId;

	/**
	 * @var Utils\ArrayHash
	 */
	private $details;

	/**
	 * @param Client $fiveHundredPixel
	 * @param string $profileId
	 *
	 * @throws Exceptions\InvalidArgumentException
	 */
	public function __construct(Client $fiveHundredPixel, $profileId = NULL)
	{
		$this->fiveHundredPixel = $fiveHundredPixel;

		if (is_numeric($profileId)) {
			throw new Exceptions\InvalidArgumentException("ProfileId must be a username of the account you're trying to read or NULL, which means actually logged in user.");
		}

		$this->profileId = $profileId;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		if ($this->profileId === NULL) {
			return $this->fiveHundredPixel->getUser();
		}

		return $this->profileId;
	}

	/**
	 * @param string $key
	 *
	 * @return Utils\ArrayHash|NULL
	 */
	public function getDetails($key = NULL)
	{
		if ($this->details === NULL) {
			try {

				if ($this->profileId !== NULL) {
					if (($result = $this->fiveHundredPixel->get('users/show', ['username' => $this->profileId])) && ($result instanceof Utils\ArrayHash)) {
						$this->details = $result->user;
					}

				} else if ($this->fiveHundredPixel->getUser()) {
					if (($result = $this->fiveHundredPixel->get('users')) && ($result instanceof Utils\ArrayHash)) {
						$this->details = $result->user;
					}

				} else {
					$this->details = new Utils\ArrayHash;
				}

			} catch (\Exception $e) {
				// todo: log?
			}
		}

		if ($key !== NULL) {
			return isset($this->details[$key]) ? $this->details[$key] : NULL;
		}

		return $this->details;
	}
}