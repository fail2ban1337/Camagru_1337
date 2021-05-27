


# Camagru_1337_MVC

a small web application allowing you to
make basic photo and video editing using your webcam and some predefined images.

# 🚀Quick start

1. first of all creat a database called camagru or you can change the name on
also you have to change the db_user and db_password ;)
```
# app/config/database.php
<?php
// DB Params
define('DB_HOST', 'mysql');
define('DB_NAME','camagru');

$DB_DSN = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$DB_USER = 'root';
$DB_PASSWORD = 'tiger';

define('DB_USER',$DB_USER);
define('DB_PASSWORD',$DB_PASSWORD);
define ('DB_DSN', $DB_DSN);
```
2. to creat tables you just have to naviagte to 

```
http://localhost/abelomar/install/setup
```

# Contributing
Feel free to contribute if you think something can be improved in any way.

Happy coding ⚡

First Project using php MVC

