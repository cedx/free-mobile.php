# Changelog
This file contains highlights of what changes on each version of the [Free Mobile for PHP](https://github.com/cedx/free-mobile.php) library.

## Version 6.0.0
- Breaking change: renamed the `freemobile` namespace to `FreeMobile`.
- Breaking change: reverted the API of the `Client` class to an [Observable](http://reactivex.io/intro.html)-based one.
- Enabled the strict typing.
- Replaced [phpDocumentor](https://www.phpdoc.org) documentation generator by [ApiGen](https://github.com/ApiGen/ApiGen).
- Updated the package dependencies.

## Version 5.0.0
- Breaking change: dropped the dependency on [Observables](http://reactivex.io/intro.html).
- Breaking change: the `Client` class is now an `EventEmitter`.
- Ported the unit test assertions from [TDD](https://en.wikipedia.org/wiki/Test-driven_development) to [BDD](https://en.wikipedia.org/wiki/Behavior-driven_development).
- Updated the package dependencies.

## Version 4.0.0
- Breaking change: changed the signature of the constructor.
- Breaking change: changed the return type of the `sendMessage` method.
- Breaking change: renamed the `END_POINT` constant to `DEFAULT_ENDPOINT`.
- Added the `endPoint` property.
- Updated the package dependencies.

## Version 3.1.1
- Improved the code coverage.
- Updated the package dependencies.

## Version 3.1.0
- Replaced the [Codacy](https://www.codacy.com) code coverage service by the [Coveralls](https://coveralls.io) one.
- Updated the package dependencies.

## Version 3.0.0
- Breaking change: removed the `toJSON()` method.
- Added the `onRequest` and `onResponse` event streams.
- Removed the `final` modifier from the `jsonSerialize()` method.

## Version 2.0.2
- Fixed a missing `implements \JsonSerializable` statement.

## Version 2.0.1
- Fixed the bug with some foreign characters being received as garbage.

## Version 2.0.0
- Breaking change: modified the signature of the class constructor.
- Added property getters and setters.
- Added the `jsonSerialize()` and `toJSON()` methods.

## Version 1.2.0
- Messages are automatically trimmed.

## Version 1.1.0
- Added an `onNext` event to ease the usage of the `subscribeCallback` method.

## Version 1.0.0
- Initial release.
