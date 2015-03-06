# 500px

[![Build Status](https://img.shields.io/travis/iPublikuj/500px.svg?style=flat-square)](https://travis-ci.org/iPublikuj/500px)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/iPublikuj/500px.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/500px/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/ipub/500px.svg?style=flat-square)](https://packagist.org/packages/ipub/500px)
[![Composer Downloads](https://img.shields.io/packagist/dt/ipub/500px.svg?style=flat-square)](https://packagist.org/packages/ipub/500px)

500px API client with authorization for [Nette Framework](http://nette.org/)

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
	500px: IPub\500px\DI\500pxExtension
```

> NOTE: Don't forget to register [OAuth extension](http://github.com/iPublikuj/oauth), because this extension is depended on it!

## Documentation

Learn how to authenticate the user using 500px's oauth or call 500px's api in [documentation](https://github.com/iPublikuj/500px/blob/master/docs/en/index.md).

***
Homepage [http://www.ipublikuj.eu](http://www.ipublikuj.eu) and repository [http://github.com/iPublikuj/500px](http://github.com/iPublikuj/500px).