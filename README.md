# About

PHP framework, routes, controllers and views

# Getting start

1. Download [Composer](http://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
1. Install in Windows:

  ```
  cd c:\wamp\www\
  composer create-project brcontainer/inphinit:dev-master [project_name]
  ```

1. Or (if no using Wamp/Xampp/easyphp)

    ```
    cd c:\Users\[username]\Documents\
    composer create-project brcontainer/inphinit:dev-master [project_name]
    ```

1. Install in Unix-like:

    ```
    cd /etc/www/
    php composer.phar create-project brcontainer/inphinit [project_name]
    ```

1. Or (if no using Apache)

    ```
    cd /etc/www/
    php composer.phar create-project brcontainer/inphinit:dev-master [project_name]
    ```

# Apache

1. If using Apache (or Xampp, Wamp, Easyphp, etc) only navigate to `http://localhost/[project_name]/`
1. Navigate `http://localhost/[project_name]/generate-htaccess.php` for create .htaccess

# PHP built-in web server in Windows

1. Navigate with explorer.exe to project folder and run `server.bat`
1. Open webbrowser and navigate to `http://localhost:8000`

# PHP built-in web server in Linux and Mac (or Unix-like)

1. If using Linux or Mac navigate to project folder and run using terminal:

    ```
    cd /home/[project_name]/
    php -S localhost:8000 server.php
    ```

1. Open web-browser and navigate to `http://localhost:8000`

# Routing

1. In folder `[project_name]/system/` find `main.php` and put something like this:

    Route::set('GET', '/foo', 'MyController:action');

1. In `[project_name]/system/application/Controller/` folder create an file with this name `MyController.php` (case sensitive)
1. Put this content:

    ```
    <?php
    namespace Controller;

    use Inphinit\View;

    class MyController
    {
        public function action()
        {
            $data = array( 'foo' => 'Hello', 'Baz' => 'World!' );
            View::render('myview', $data);
        }
    }
    ```

1. In `[project_name]/system/application/View/` create file with this name `myview.php` (case sensitive) and put:

    ```
    <p><?php echo $foo, ' ', $baz; ?></p>
    ```

1. Navigate to `http://localhost:8000/foo` or `http://localhost/[project_name]/foo`

# Developer vs production

For setup access `[project_name]/system/application/Config/config.php` with your text editor and change `developer` key to `true` or `false`:

    ```
    <?php
    return array(
        'appdata_expires' => 86400,
        'developer'       => true,
        'maintenance'     => false
    );
    ```

# Checking webserver requirements

For check requeriments navigate with your web-browser to `http://localhost:8000/check.php` or `http://localhost/[project_name]/check.php`

# Nginx

For create Ngnix config run with terminal:

```
cd /home/[project_name]/
php generate-nginx.php
```

And copy content to clipboard and adjust `nginx.conf`

# IIS

Move content of project folder to root IIS folder (note: Inphinit is tested in IIS Express)
