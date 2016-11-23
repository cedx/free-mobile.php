# Changelog
This file contains highlights of what changes on each version of the [Free Mobile for PHP](https://github.com/cedx/free-mobile.php) library.

## Version 3.0.0
- Added the `onRequest` and `onResponse` event streams.
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
