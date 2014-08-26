**Warning** This project is still under developement.

[![Build Status](https://drone.io/github.com/okulbilisim/thesaurus-manager/status.png?latest)](https://drone.io/github.com/okulbilisim/thesaurus-manager/latest)


Open Thesaurus Manager
======================

If you want to create your own thesaurus this project will be a super fast solution for you.


Requirements
------------

- Php 5.4
- Mysql
- Neo4j

You may not a web server for development. Laravel's artisan tool will be enough for creating a built-in web server like : `php artisan serve --port 8081`


Install
-------

1. Clone project to your machine.
```
$ git clone git@github.com:okulbilisim/thesaurus-manager.git
```

2. Create a database named 'thesaurus'
```
$ mysql -uroot -proot -e "create database thesaurus charset utf8"
```

3. Install composer packages 
```sh
$ composer install
```

4. Update your settings
```
$ vim app/config/database.php
```

5. Run db migration commands
```
$ php artisan migrate --package=cartalyst/sentry
```

6. Add sample data
```
php artisan db:seed
php artisan sampledata
```

    Sample users
    - Admin
        - email: admin@localhost.com  
        - password: admin
    - Editor
        - email: editor@localhost.com
        - password: editor


Cli tools
---------

**Add sample data to test**

    php artisan sampledata


**Get Suggestions From thesaurus.com**

    php artisan suggestions:thesaurus <word>


**Get Suggestions From google.com**

    php artisan suggestions:google <word>


![preview](https://raw.githubusercontent.com/hasantayyar/thesaurus-manager/master/docs/alpha_preview3.png)
