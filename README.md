# Popular Bank

A Symfony web application used to emulate a an online bankng site.

You can see the running application [here](http://apps.cavitos.net:45002)

Admin credentials: ```admin/admin```

## Setup 

### Prerequisites

* [PHP 7.1](https://secure.php.net/)
* [Composer](https://getcomposer.org/)

### Database

* [MySQL 5.7](https://www.mysql.com/)
* [Flyway DB command line](https://flywaydb.org/)

To setup database:

* Create ```transactions``` database
* Create ```dweb``` user for created db
* Run migrations ```migrate```

You need to configure ```php``` command as part of your path

Move to the project folder

Install dependencies: ```composer install```

Run application: ```php bin/console server:run```

## ER Model

[DB Model](https://github.com/fvcastellanos/transactions/blob/master/diagrams/ER/transactions.png)

![Model](https://github.com/fvcastellanos/transactions/blob/master/diagrams/ER/transactions.png)