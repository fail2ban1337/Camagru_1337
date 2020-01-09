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
# PAGES :
1. Register Page:
<div style="text-align:center">
<img width="720" alt="Screen Shot 2020-01-09 at 5 04 10 PM" src="https://user-images.githubusercontent.com/47558088/72083537-49d70180-3302-11ea-8b1b-e60da4d16e46.png">
</div>

2. Login Page:
<div style="text-align:center">
<img width="720" alt="Screen Shot 2020-01-09 at 5 07 11 PM" src="https://user-images.githubusercontent.com/47558088/72083701-86a2f880-3302-11ea-8aa8-d34dcab680af.png">
</div>

3. Settings Page:
<div style="text-align:center">
<img width="720" alt="Screen Shot 2020-01-09 at 5 08 47 PM" src="https://user-images.githubusercontent.com/47558088/72083839-bd790e80-3302-11ea-960f-9b17f2b3176b.png">
</div>

4. Studio Page:
<div style="text-align:center">
<img width="720" alt="Screen Shot 2020-01-09 at 5 11 38 PM" src="https://user-images.githubusercontent.com/47558088/72085173-2792b300-3305-11ea-8a62-8020456feaba.png">
</div>

5. Home Page:
<div style="text-align:center">
<img width="720" alt="Screen Shot 2020-01-09 at 5 12 02 PM" src="https://user-images.githubusercontent.com/47558088/72085424-91ab5800-3305-11ea-9134-87ed1dfbdfea.png">
</div>

# Contributing
Feel free to contribute if you think something can be improved in any way.

Happy coding ⚡
