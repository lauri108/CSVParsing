A small excercise inspired by https://github.com/solinor/PHPUG-Finland-Champion. It reproduces the behaviour that was desired in that contest.

CSVParsing
==========
Parses a CSV file with opening times.

- read-file.php is the main script.
- ravintolat.csv is an example CSV file.
- CSVParser.php and CSVReader.php are helper classes.
- Restaurant.php is the Restaurant class.
- RestaurantTest.php is a PHPUnit test file.
- phpunit.xml is a configuration file for PHPUnit.

Testing
=======
- install PHPUnit
- run 'phpunit' in the root folder
- if you're getting required_once errors when running phpunit make sure phpunit.xml has either testSuiteLoaderClass or TestSuiteLoaderFile set correctly.

Running
=======
run 'php read-file.php ravintolat.CSV' in the root folder to see the results.
