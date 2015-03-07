# Quickstart

This extension adds support for OAuth connection to 500px, so you can seamlessly integrate your application with and provide login through 500px. You can also communicate with 500px's API through this extension.

## Installation

The best way to install ipub/500px is using  [Composer](http://getcomposer.org/):

```json
{
	"require": {
		"ipub/500px": "dev-master"
	}
}
```

or

```sh
$ composer require ipub/500px:@dev
```

After that you have to register extension in config.neon.

```neon
extensions:
	500px: IPub\FiveHundredPixel\DI\FiveHundredPixelExtension
```

## Usage

### Basic configuration

This extension creates a special section for configuration for your NEON configuration file. The absolute minimal configuration is consumerKey and consumerSecret.

```neon
500px
	consumerKey    : "123456789"
	consumerSecret : "e807f1fcf82d132f9bb018ca6738a19f"
```

### Authentication

Authentication is done through several HTTP requests and redirects and is done through a component model for easy integration into application.

```php
use IPub\FiveHundredPixel\UI\LoginDialog

class LoginPresenter extends BasePresenter
{
	
	/**
	 * @var \IPub\FiveHundredPixel\Client
	 */
	private $fiveHundredPixel;

	/**
	 * @var UsersModel
	 */
	private $usersModel;

	/**
	 * You can use whatever way to inject the instance from DI Container,
	 * but let's just use constructor injection for simplicity.
	 *
	 * Class UsersModel is here only to show you how the process should work,
	 * you have to implement it yourself.
	 */
	public function __construct(\IPub\FiveHundredPixel\Client $fiveHundredPixel, UsersModel $usersModel)
	{
		parent::__construct();

		$this->fiveHundredPixel = $fiveHundredPixel;
		$this->usersModel = $usersModel;
	}

	/**
	 * @return LoginDialog
	 */
	protected function createComponentFiveHundredPixelLogin()
	{
		$dialog = new LoginDialog($this->fiveHundredPixel);
	
		$dialog->onResponse[] = function (LoginDialog $dialog) {
			$fiveHundredPixel = $dialog->getClient();

			if ( !$fiveHundredPixel->getUser()) {
				$this->flashMessage("Sorry bro, twitter authentication failed.");
				return;
			}

			/**
			 * If we get here, it means that the user was recognized
			 * and we can call the 500px API
			 */

			try {
				$me = $fiveHundredPixel->getProfile();

				if (!$existing = $this->usersModel->findBy500pxId($fiveHundredPixel->getUser())) {
					/**
					 * Variable $me contains all the public information about the user
					 * including twitter id and name.
					 */
					$existing = $this->usersModel->registerFrom500px($me);
				}

				/**
				 * You should save the access token to database for later usage.
				 *
				 * You will need it when you'll want to call 500px API,
				 * when the user is not logged in to your website,
				 * with the access token in his session.
				 */
				$this->usersModel->update500pxAccessToken($fiveHundredPixel->getUser(), $fiveHundredPixel->getAccessToken());

				/**
				 * Nette\Security\User accepts not only textual credentials,
				 * but even an identity instance!
				 */
				$this->user->login(new \Nette\Security\Identity($existing->id, $existing->roles, $existing));

				/**
				 * You can celebrate now! The user is authenticated :)
				 */

			} catch (\IPub\OAuth\ApiException $ex) {
				/**
				 * You might wanna know what happened, so let's log the exception.
				 *
				 * Rendering entire bluescreen is kind of slow task,
				 * so might wanna log only $ex->getMessage(), it's up to you
				 */
				Debugger::log($ex, 'twitter');

				$this->flashMessage("Sorry bro, twitter authentication failed hard.");
			}

			$this->redirect('this');
		};

		return $dialog;
	}
}
```

And now whe your component is created, put a link into template

```html
{* By the way, this is how you do a link to signal of subcomponent. *}
<a n:href="fiveHundredPixelLogin-open!">Login using twitter</a>
```

When the user clicks on the link, he will be redirected to the 500px authentication page where he can allow access for you page or decline it. Whe he confirm your application and requested permission, he will be redirected back to you website.
This authentication action was done in component, so the redirect back link is linked to component signal, that will invoke the event and your **onResponse** callback will be invoked. And from now is quite simple how to work with authenticated user.

## Using 500px API

The 500px [API documentation](https://github.com/500px/api-documentation) can be found on github pages. All request are done through api v1 and are configured to return a JSON object.

Some methods don't need authentication, but if your user is authenticated, all request will be done with this authentication.

Calling API's methods is really simple. You just need to include client services to where you want to use it:

```php
$photos = $fiveHundredPixel->api('photos/:id')
```

or

```php
$photos = $fiveHundredPixel->get('photos/:id')
```

In the output will be and array of photos from selected gallery.

## Best practices

Please keep in mind that the user can revoke the access to his account literary anytime he wants to. Therefore you must wrap every twitter API call with try catch.

```php
try {
	// ...
} catch (\IPub\OAuth\ApiException $ex) {
	// ...
}
```

and if it fails, try requesting the test login. This will tell you if the user revoked your application.

And if he revokes your application, drop the access token, it will never work again, you may only acquire a new one.