**Warning** This project is still under developement.

Open Thesaurus Manager
======================

If you want to create your own thesaurus this project will be a super fast solution for you.


Requirements
------------

- Php 5.4
- Mysql
- Neo4j

You may not need a web server for development. Laravel's artisan tool will be enough for creating a built-in web server like : `php app/console server:run`


Install
-------

**Clone project to your machine.**
```
$ git clone git@github.com:okulbilisim/thesaurus-manager.git --branch sf
```

**Create a database named 'thesaurus'**
```
$ mysql -uroot -proot -e "create database thesaurus charset utf8"
```

**Install composer packages**
```sh
$ composer install
```

**Run db migration commands**
```
$ php app/console doctrine:schema:update --force
```

**Create admin,editor users promote roles**
```
$ php app/console fos:user:create admin --super-admin
$ php app/console fos:user:create editor
$ php app/console fos:user:promote editor ROLE_EDITOR
```

**Add sample data**
```
$ php app/console sampledata
```

**Run system**
```
$ php app/console server:run
```

Cli tools
---------

**Add sample data to test**

    php app/console sampledata

![preview](https://raw.githubusercontent.com/hasantayyar/thesaurus-manager/master/docs/alpha_preview3.png)


