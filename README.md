**Warning** This project is still under developement.

[![Build Status](https://drone.io/github.com/okulbilisim/thesaurus-manager/status.png?latest)](https://drone.io/github.com/okulbilisim/thesaurus-manager/latest)


Open Thesaurus Manager
======================

If you want to create your own thesaurus this project will be a super fast solution for you.



Install
-------

```sh
composer install
# edit app/config/database.php
php artisan migrate --package=cartalyst/sentry
php artisan db:seed
php artisan sampledata
```

Cli tools
---------

**Add sample data to test**

    php artisan sampledata


**Get Suggestions From thesaurus.com**

    php artisan suggestions:thesaurus <word>


**Get Suggestions From google.com**

    php artisan suggestions:google <word>



![preview](https://raw.githubusercontent.com/hasantayyar/thesaurus-manager/master/docs/alpha_preview3.png)
Demo User
---------

    email: user@localhost.com
    password: user
