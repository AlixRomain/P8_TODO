To-do List

OpenClassrooms project for "PHP / Symfony" course.

The objective is to improve an existing application build with Symfony 3.1.6 and Bootstrap 3.3.7.

Build With
Symfony 5.2
Bootstrap 5
PhpUnit 9.2
Installation
1 - Clone or download the project

https://github.com/AlixRomain/P8_TODO

2 - Update your database identifiers in project/.env

DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name

3 - Install composer -> Composer installation doc

4 - Run composer.phar to install dependencies

symfony console composer.phar install

5 - Create the database

symfony console doctrine:database:create

6 - Create the database table/schema

symfony console doctrine:schema:update

7 - Load fixtures

symfony console doctrine:fixtures:load

Usage
Login link :

/login

An user account is already available, use it to test the application :

"email" : "user@gmail.com",
"password" : "OpenClass21!"
An admin account is already available, use it to test the application :

"email" : "admin@gmail.com",
"password" : "OpenClass21!"
An super admin account is already available, use it to test the application :

"email" : "superadmin@gmail.com",
"password" : "OpenClass21!"
Tests
1 - Update the test database identifiers in project/ phpunit.xml.dist

<env name="DATABASE_URL" value="DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db__test_name"/>

2 - Run tests and generate coverage

vendor\bin\phpunit  --coverage-html  public/coverage  For windows

3 - Run tests for one File

vendor\bin\phpunit  tests/Controller/MY_TESTS_FILE.php For Windows




Contributing
To contribute see CONTRIBUTING.md