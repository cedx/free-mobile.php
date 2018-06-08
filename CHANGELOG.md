# Changelog

## Version [11.0.0](https://github.com/cedx/free-mobile.php/compare/v10.1.0...v11.0.0)
- Breaking change: raised the required [PHP](https://secure.php.net) version.
- Added support for [phpDocumentor](https://www.phpdoc.org).
- Updated the package dependencies.

## Version [10.1.0](https://github.com/cedx/free-mobile.php/compare/v10.0.0...v10.1.0)
- Added a user guide based on [MkDocs](http://www.mkdocs.org).
- Added the `ClientException` class.
- Updated the package dependencies.

## Version [10.0.0](https://github.com/cedx/free-mobile.php/compare/v9.0.0...v10.0.0)
- Breaking change: changed the signature of the `Client` events.
- Breaking change: raised the required [PHP](https://secure.php.net) version.
- Breaking change: using PHP 7.1 features, like class constant visibility and void functions.
- Added the `EVENT_REQUEST` and `EVENT_RESPONSE` constants to the `Client` class.

## Version [9.0.0](https://github.com/cedx/free-mobile.php/compare/v8.0.0...v9.0.0)
- Breaking change: changed the signature of the constructor.
- Breaking change: the class properties are now read-only.
- Breaking change: removed the `jsonSerialize()` and `__toString()` methods.
- Updated the package dependencies.

## Version [8.0.0](https://github.com/cedx/free-mobile.php/compare/v7.0.0...v8.0.0)
- Breaking change: moved the `Observable` API to a synchronous one.
- Breaking change: moved the `Subject` event API to the `EventEmitter` one.
- Changed licensing for the [MIT License](https://opensource.org/licenses/MIT).
- Restored the [Guzzle](http://docs.guzzlephp.org) HTTP client.

## Version [7.0.0](https://github.com/cedx/free-mobile.php/compare/v6.0.0...v7.0.0)
- Breaking change: the `endPoint` property is now an instance of [`Psr\Http\Message\UriInterface`](http://www.php-fig.org/psr/psr-7/#35-psrhttpmessageuriinterface) interface.
- Added new unit tests.
- Replaced the [Guzzle](http://docs.guzzlephp.org) HTTP client by an `Observable`-based one.

## Version [6.0.0](https://github.com/cedx/free-mobile.php/compare/v5.0.0...v6.0.0)
- Breaking change: renamed the `freemobile` namespace to `FreeMobile`.
- Breaking change: reverted the API of the `Client` class to an [Observable](http://reactivex.io/intro.html)-based one.
- Enabled the strict typing.
- Replaced [phpDocumentor](https://www.phpdoc.org) documentation generator by [ApiGen](https://github.com/ApiGen/ApiGen).
- Updated the package dependencies.

## Version [5.0.0](https://github.com/cedx/free-mobile.php/compare/v4.0.0...v5.0.0)
- Breaking change: dropped the dependency on [Observables](http://reactivex.io/intro.html).
- Breaking change: the `Client` class is now an `EventEmitter`.
- Ported the unit test assertions from [TDD](https://en.wikipedia.org/wiki/Test-driven_development) to [BDD](https://en.wikipedia.org/wiki/Behavior-driven_development).
- Updated the package dependencies.

## Version [4.0.0](https://github.com/cedx/free-mobile.php/compare/v3.1.1...v4.0.0)
- Breaking change: changed the signature of the constructor.
- Breaking change: changed the return type of the `sendMessage` method.
- Breaking change: renamed the `END_POINT` constant to `DEFAULT_ENDPOINT`.
- Added the `endPoint` property.
- Updated the package dependencies.

## Version [3.1.1](https://github.com/cedx/free-mobile.php/compare/v3.1.0...v3.1.1)
- Improved the code coverage.
- Updated the package dependencies.

## Version [3.1.0](https://github.com/cedx/free-mobile.php/compare/v3.0.0...v3.1.0)
- Replaced the [Codacy](https://www.codacy.com) code coverage service by the [Coveralls](https://coveralls.io) one.
- Updated the package dependencies.

## Version [3.0.0](https://github.com/cedx/free-mobile.php/compare/v2.0.2...v3.0.0)
- Breaking change: removed the `toJSON()` method.
- Added the `onRequest` and `onResponse` event streams.
- Removed the `final` modifier from the `jsonSerialize()` method.

## Version [2.0.2](https://github.com/cedx/free-mobile.php/compare/v2.0.1...v2.0.2)
- Fixed a missing `implements \JsonSerializable` statement.

## Version [2.0.1](https://github.com/cedx/free-mobile.php/compare/v2.0.0...v2.0.1)
- Fixed the bug with some foreign characters being received as garbage.

## Version [2.0.0](https://github.com/cedx/free-mobile.php/compare/v1.2.0...v2.0.0)
- Breaking change: modified the signature of the class constructor.
- Added property getters and setters.
- Added the `jsonSerialize()` and `toJSON()` methods.

## Version [1.2.0](https://github.com/cedx/free-mobile.php/compare/v1.1.0...v1.2.0)
- Messages are automatically trimmed.

## Version [1.1.0](https://github.com/cedx/free-mobile.php/compare/v1.0.0...v1.1.0)
- Added an `onNext` event to ease the usage of the `subscribeCallback` method.

## Version 1.0.0
- Initial release.
