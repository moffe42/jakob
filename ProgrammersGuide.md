# Coding standard #
All code in JAKOB should conform to the [Zend Coding Standard](http://framework.zend.com/manual/en/coding-standard.overview.html). As a minimum the code should pass a run with [PHP\_codesniffer](http://pear.php.net/package/PHP_CodeSniffer) with standard set to `zend`.
```
phpcs --standard=zend filename
```
The run should not produce any errors. Some warnings are allowed, but should be keeps to a minimum.

# Documentation #
All PHP library files (places in the lib directory) should contain inline documentation of the same quality describet [here](http://framework.zend.com/manual/en/coding-standard.coding-style.html). (See paragraph _Inline Documentation_). Both a file level and a class level docblock is required.

Files placed in the www directory should have at least a file level docblock

# Misc #
Remember to set the svn:keywords property on all newly created files to `Id URL`.
```
svn propset svn:keywords "Id URL" filename
```