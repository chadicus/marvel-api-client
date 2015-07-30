## Questions and Bug Reports

Submit via [GitHub Issues](https://github.com/chadicus/marvel-api-client/issues)

## Pull Requests

Code changes should be sent through [GitHub Pull Requests](https://github.com/chadicus/marvel-api-client/pulls).  Before submitting the pull request, make sure that phpunit reports success:

```sh
./vendor/bin/phpunit --coverage-html coverage
```

This build enforces 100% [PHPUnit](http://www.phpunit.de) code coverage and 0 errors for the [coding standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)

```sh
./vendor/bin/phpcs --standard=PSR2 src tests
```
