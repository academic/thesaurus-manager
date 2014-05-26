**Warning** This project is still under developement.

Open Thesaurus Manager
======================



Install
-------

    composer install
    php artisan migrate
    php artisan db:seed
    php artisan sampledata

Cli tools
---------

**Add sample data to test**

    php artisan sampledata


**Get Suggestions From thesaurus.com**

    php artisan suggestions:thesaurus <word>


**Get Suggestions From google.com**

    php artisan suggestions:google <word>



![preview](https://raw.githubusercontent.com/hasantayyar/thesaurus-manager/master/docs/grapheditor_demo.png)
Demo User
---------

    email: user@localhost.com
    password: user
