#!/bin/sh
set -ev

composer install

[ -d tmp ] && rm -fr tmp
git clone -b gh-pages git@github.com:${GITHUB_USER}/marvel-api-client tmp
./vendor/bin/phpdoc.php --directory src --target tmp/docs --template responsive-twig --sourcecode --defaultpackagename Chadicus --title "Marvel API PHP Client"
cd tmp
git add .
git commit -m "Build phpdocs"
git push origin gh-pages:gh-pages

cd ..
rm -fr tmp
