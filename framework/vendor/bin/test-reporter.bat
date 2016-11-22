@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../codeclimate/php-test-reporter/composer/bin/test-reporter
php "%BIN_TARGET%" %*
