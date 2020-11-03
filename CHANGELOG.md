# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

- `Added` for new features.
- `Changed` for changes in existing functionality.
- `Deprecated` for soon-to-be removed features.
- `Removed` for now removed features.
- `Fixed` for any bug fixes.
- `Security` in case of vulnerabilities

## [1.1.0] - 2020.11.03

### Added

- Added ability for translations to throw a `TranslationException` if not existing.

## [1.0.3] - 2020.09.15

### Changed

- Updated default PDO adapter table name from "languages" to "translations".

## [1.0.2] - 2020.09.14

### Fixed

- Updated requirements in `README.md` and `composer.json`.

## [1.0.1] - 2020.09.13

### Fixed

- Updated PDO adapter from referencing `$this->pdo` from within the constructor, 
as it can return `NULL` under certain circumstances.

## [1.0.0] - 2020.09.12

### Added

- Initial release.