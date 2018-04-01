# mysql-slow-query-web

MySQL slow_query log viewer on web.

## Requirements

* PHP >= 5.4
* PDO with mysqlnd

## Supported MySQL Version

* MySQL >= 5.1

## Configrations

### MySQL

mysql-slow-query-web uses `mysql.slow_log` table. Set `log_output = TABLE` in your my.cnf.
And also enable slow_log and set `long_query_time` as you want.

```
[mysqld]
log_output=TABLE # or FILE,TABLE
slow_query_log=ON
long_query_time=0.1 # means 0.1 seconds
```

or set online by `SET GLOBAL` on you mysql client's console.

```
> SET GLOBAL log_output=TABLE;
> SET GLOBAL slow_query_log=ON;
> SET GLOBAL long_query_time=0.1;
```

### Database configration in dbconf.php

#### Add permission to access System Table

```
> CREATE USER 'logviewer'@'localhost' IDENTIFIED BY 'some_pass';
> GRANT CREATE,DROP,SELECT PRIVILEGES ON `mysql`.`*` TO 'logviewer'@'localhost'
```

## Run as server

### Built-in server

You can use PHP builtin server for testing. I don't recommend in production use.

```
php -S 127.0.0.1:8080 -t htdocs
```

### Apache with mod_php

It is easy to run PHP script if you use mod_php. Please set `DocumentRoot` correctly.

```
DocumentRoot path_to/htdocs
```

### nginx with PHP-FPM

```
location / {
  root path_to/htdocs;
  location \.php$ {
    # PHP configrations
  }
}
```

## Logrotate

MySQL's slow_log table becomes large over time.
It causes "slow" fetch slow_log table. ( Not joke :(

If you want to drop old logs, you can use `rotate.php`.
It drops old logs except recent 1000 rows.
`rotate.php` also use `dbconf.php`.

```
php rotate.php
```

