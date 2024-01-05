# Реалізація інформаційного та програмного забезпечення

## SQL-скрипт

```sql
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `mydb` ;

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`user` ;

CREATE TABLE IF NOT EXISTS `mydb`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` TEXT NOT NULL,
  `login` TEXT NOT NULL,
  `password` TEXT NOT NULL,
  `email` TEXT NOT NULL,
  `role` TEXT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`help`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`help` ;

CREATE TABLE IF NOT EXISTS `mydb`.`help` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` TEXT NOT NULL,
  `description` TEXT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`filter`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`filter` ;

CREATE TABLE IF NOT EXISTS `mydb`.`filter` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date_from` DATETIME NOT NULL,
  `date_to` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`request`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`request` ;

CREATE TABLE IF NOT EXISTS `mydb`.`request` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` TEXT NOT NULL,
  `description` TEXT NOT NULL,
  `date` DATETIME NOT NULL,
  `filter_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_request_filter1_idx` (`filter_id` ASC) VISIBLE,
  CONSTRAINT `fk_request_filter1`
    FOREIGN KEY (`filter_id`)
    REFERENCES `mydb`.`filter` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`access`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`access` ;

CREATE TABLE IF NOT EXISTS `mydb`.`access` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `role` TEXT NOT NULL,
  `user_id` INT NOT NULL,
  `help_id` INT NOT NULL,
  `request_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_access_User_idx` (`user_id` ASC) VISIBLE,
  INDEX `fk_Access_Help1_idx` (`help_id` ASC) VISIBLE,
  INDEX `fk_access_request1_idx` (`request_id` ASC) VISIBLE,
  CONSTRAINT `fk_access_User`
    FOREIGN KEY (`user_id`)
    REFERENCES `mydb`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Access_Help1`
    FOREIGN KEY (`help_id`)
    REFERENCES `mydb`.`help` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_access_request1`
    FOREIGN KEY (`request_id`)
    REFERENCES `mydb`.`request` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`result`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`result` ;

CREATE TABLE IF NOT EXISTS `mydb`.`result` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` TEXT NOT NULL,
  `description` TEXT NOT NULL,
  `request_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_result_request1_idx` (`request_id` ASC) VISIBLE,
  CONSTRAINT `fk_result_request1`
    FOREIGN KEY (`request_id`)
    REFERENCES `mydb`.`request` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`source`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`source` ;

CREATE TABLE IF NOT EXISTS `mydb`.`source` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `url` TEXT NOT NULL,
  `request_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_source_request1_idx` (`request_id` ASC) VISIBLE,
  CONSTRAINT `fk_source_request1`
    FOREIGN KEY (`request_id`)
    REFERENCES `mydb`.`request` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `mydb`.`user`
-- -----------------------------------------------------
START TRANSACTION;
USE `mydb`;
INSERT INTO `mydb`.`user` (`id`, `name`, `login`, `password`, `email`, `role`) VALUES (DEFAULT, 'Володимир', 'Ковальов', '123456', 'vladimir@gmail.com', 'public');
INSERT INTO `mydb`.`user` (`id`, `name`, `login`, `password`, `email`, `role`) VALUES (DEFAULT, 'Дмитро', 'Демчик', '123456', 'dmytro@gmail.com', 'public');

COMMIT;


-- -----------------------------------------------------
-- Data for table `mydb`.`request`
-- -----------------------------------------------------
START TRANSACTION;
USE `mydb`;
INSERT INTO `mydb`.`request` (`id`, `title`, `description`, `date`, `filter_id`) VALUES (DEFAULT, 'Text search', 'Український бізнес 2023', '2023-12-20', NULL);
INSERT INTO `mydb`.`request` (`id`, `title`, `description`, `date`, `filter_id`) VALUES (DEFAULT, 'Photo search', 'Пошук картинок', '2023-12-20', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `mydb`.`source`
-- -----------------------------------------------------
START TRANSACTION;
USE `mydb`;
INSERT INTO `mydb`.`source` (`id`, `url`, `request_id`) VALUES (DEFAULT, 'https://forbes.ua', NULL);
INSERT INTO `mydb`.`source` (`id`, `url`, `request_id`) VALUES (DEFAULT, 'https://images.google.com/', NULL);

COMMIT;
```

# RESTfull сервіс 

## Головні файли

### Головний файл для підключення до бази даних відправки запитів (Database.php)

```Database.php
<?php

trait Database
{
    private function connect()
    {
        $string = "mysql:hostname=" . DB_HOST . ";dbname=" . DB_NAME;
        $con = new PDO($string, DB_USER, DB_PASSWORD);
        //show($con);

        return $con;
    }

    public function query($query, $data = [])
    {
        $con = $this->connect();
        $stm = $con->prepare($query);

        $check = $stm->execute($data);
        if ($check) {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);

            if (is_array($result) && count($result)) {
                return $result;
            }
        }

        return false;
    }

    public function get_row($query, $data = [])
    {
        $con = $this->connect();
        $stm = $con->prepare($query);

        $check = $stm->execute($data);
        if ($check) {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);

            if (is_array($result) && count($result)) {
                return $result[0];
            }
        }

        return false;
    }
}

```

### Файл завантаження контролерів (App.php)

```App.php
<?php

class App
{
    private $controller = 'Home';
    private $method = 'index';

    private function splitUrl()
    {
        $URL = $_GET['url'] ?? 'home';
        $URL = explode("/", trim($URL, "/"));
        return $URL;
    }

    public function loadController()
    {
        $URL = $this->splitUrl();
        $filename = "../app/controllers/" . ucfirst($URL[0]) . ".php";

        if (file_exists($filename)) {
            require $filename;
            $this->controller = ucfirst($URL[0]);
            unset($URL[0]);
        } else {
            $filename = "../app/controllers/_404.php";
            require $filename;
            $this->controller = '_404';
        }

        $controller = new $this->controller;

        //check if method exist 
        if (!empty($URL[1])) {
            if (method_exists($controller, $URL[1])) {
                $this->method = $URL[1];
                unset($URL[1]);
            }
        }

        call_user_func_array([$controller, $this->method], $URL);
    }
}


```
### Константи (config.php)
```config.php
<?php

class App
{
    private $controller = 'Home';
    private $method = 'index';

    private function splitUrl()
    {
        $URL = $_GET['url'] ?? 'home';
        $URL = explode("/", trim($URL, "/"));
        return $URL;
    }

    public function loadController()
    {
        $URL = $this->splitUrl();
        $filename = "../app/controllers/" . ucfirst($URL[0]) . ".php";

        if (file_exists($filename)) {
            require $filename;
            $this->controller = ucfirst($URL[0]);
            unset($URL[0]);
        } else {
            $filename = "../app/controllers/_404.php";
            require $filename;
            $this->controller = '_404';
        }

        $controller = new $this->controller;

        //check if method exist 
        if (!empty($URL[1])) {
            if (method_exists($controller, $URL[1])) {
                $this->method = $URL[1];
                unset($URL[1]);
            }
        }

        call_user_func_array([$controller, $this->method], $URL);
    }
}


```
### Відповідає за відображення шаблонів home.view.php та якщо помилка 404view.php (Controller.php)
```Controller.php
<?php

class Controller
{
    public function view($name)
    {
        $filename = "../app/views/" . $name . ".view.php";

        if (file_exists($filename)) {
            require $filename;
        } else {
            $filename = "../app/views/404.view.php";
            require $filename;
        }
    }
}



```
### Файл Функцій (fuctions.php)
```fuctions.php
<?php

function show($stuff)
{
    echo "<pre>";
    print_r($stuff);
    echo "</pre>";
}

function esc($str)
{
    return htmlspecialchars($str);
}


```
### Ініціалізація файлів в Core (init.php)
```init.php
<?php

spl_autoload_register(function ($classname) {
    require $filename = "../app/models/" . ucfirst($classname) . ".php";
    //echo $filename;
});

require 'config.php';
require 'functions.php';
require 'Database.php';
require 'Model.php';
require 'Controller.php';
require 'App.php';



```
### Методи для роботи з Моделями (Model.php)
```Model.php
<?php

trait Model
{
    //you can only extends one Class but can add many traits
    //add trait Database to this Model
    use Database;

    // test connect to DB
    function test()
    {
        $query = "SELECT * FROM users";
        $result = $this->query($query);
        //show($result);
    }

    protected $limit = 10;
    protected $offset = 0;
    protected $order_type = "desc";
    protected $order_column = "id";

    //FINDALL query
    public function findAll()
    {
        //build query
        $query = "SELECT * FROM $this->table ORDER BY $this->order_column $this->order_type LIMIT $this->limit OFFSET $this->offset";
        //echo $query;

        return $this->query($query);
    }

    //WHERE query
    public function where($data, $data_not = [])
    {
        //grab all keys from array
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $query = "SELECT * FROM $this->table WHERE ";

        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . " && ";
        }
        foreach ($keys_not as $key) {
            $query .= $key . " != :" . $key . " && ";
        }

        //delete last symbols in string
        $query = trim($query, " && ");

        //build query
        $query .= " ORDER BY $this->order_column $this->order_type LIMIT $this->limit OFFSET $this->offset";
        $data = array_merge($data, $data_not);
        //echo $query;

        return $this->query($query, $data);
    }

    //FIRST query, get first item
    public function first($data, $data_not = [])
    {
        //grab all keys from array
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $query = "SELECT * FROM $this->table WHERE ";

        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . " && ";
        }
        foreach ($keys_not as $key) {
            $query .= $key . " != :" . $key . " && ";
        }

        //delete last symbols in string
        $query = trim($query, " && ");

        //build query
        $query .= " LIMIT $this->limit OFFSET $this->offset";
        $data = array_merge($data, $data_not);
        //echo $query;

        $result = $this->query($query, $data);
        if ($result) {
            return $result[0];
        }

        return false;
    }

    //INSERT query, add new row to db
    public function insert($data)
    {
        //remove unwanted columns in data
        if (!empty($this->allowedColumns)) {
            foreach ($data as $key) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        //grab all keys from array
        $keys = array_keys($data);
        $query = "INSERT INTO $this->table (" . implode(", ", $keys) . ") VALUES (:" . implode(", :", $keys) . ")";
        //echo $query;

        $this->query($query, $data);

        return false;
    }

    //UPDATE query
    public function update($id, $data, $id_column = 'id')
    {
        //remove unwanted columns in data
        if (!empty($this->allowedColumns)) {
            foreach ($data as $key) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        //grab all keys from array
        $keys = array_keys($data);
        $query = "UPDATE $this->table SET ";

        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . ", ";
        }

        //delete last symbols in string
        $query = trim($query, ", ");

        //build query
        $query .= "  WHERE $id_column = :$id_column";
        //echo $query;

        $data[$id_column] = $id;
        $this->query($query, $data);

        return false;
    }

    //DELETE query
    public function delete($id, $id_column = 'id')
    {
        $data[$id_column] = $id;
        $query = "DELETE FROM $this->table WHERE $id_column = :$id_column";
        //echo $query;

        $this->query($query, $data);

        return false;
    }
}


```
### Константи (config.php)
```config.php
<?php

class App
{
    private $controller = 'Home';
    private $method = 'index';

    private function splitUrl()
    {
        $URL = $_GET['url'] ?? 'home';
        $URL = explode("/", trim($URL, "/"));
        return $URL;
    }

    public function loadController()
    {
        $URL = $this->splitUrl();
        $filename = "../app/controllers/" . ucfirst($URL[0]) . ".php";

        if (file_exists($filename)) {
            require $filename;
            $this->controller = ucfirst($URL[0]);
            unset($URL[0]);
        } else {
            $filename = "../app/controllers/_404.php";
            require $filename;
            $this->controller = '_404';
        }

        $controller = new $this->controller;

        //check if method exist 
        if (!empty($URL[1])) {
            if (method_exists($controller, $URL[1])) {
                $this->method = $URL[1];
                unset($URL[1]);
            }
        }

        call_user_func_array([$controller, $this->method], $URL);
    }
}


```
## Файли Моделей
Grant_model.php
```Grant_model.php
<?php

class Grant_model
{
    use Model;

    protected $table = 'grant';
    protected $allowedColumns = [
        'title',
        'description',
        'role_id'
    ];
}

```
Media_model.php
```Media_model.php
<?php

class Media_model
{
    use Model;

    protected $table = "media";
    protected $allowedColumns = [
        "type",
        "url",
        "name",
        "metadate",
        "Origin_id"
    ];
}

```

Origin_model.php
```Origin_model.php
<?php

class Origin_model
{
    use Model;

    protected $table = 'origin';
    protected $allowedColumns = [
        'name',
        'location',
        'rating'
    ];
}


```

Request_model.php
```Request_model.php
<?php

class Request_model
{
    use Model;

    protected $table = 'request';
    protected $allowedColumns = [
        'id',
        'desription',
        'media_id',
        'user_id'
    ];
}

```
Role_model.php
```Role_model.php
<?php

class Role_model
{
    use Model;

    protected $table = 'role';
    protected $allowedColumns = [
        'name',
        'grants'
    ];
}


```
User_model.php
```User_model.php
<?php

class User_model
{
    use Model;

    protected $table = 'user';
    protected $allowedColumns = [
        'name',
        'login',
        'password',
        'email',
        'role_id'
    ];
}


```
Users.php
```Users.php
<?php

class Users extends Controller
{
    public function findAll()
    {
        echo "this is findAll method" . "<br>";
        $user = new User_model();
        if($user->findAll() !== false){
            show($user->findAll());
        }
        else{
            show("No Users");
    }

        $this->view('home');
    }

    public function add()
    {
        echo "this is add method" . "<br>";
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json') {
            $data = json_decode(file_get_contents('php://input'), true);
           // show($data['email']);
        } else {
            echo "Send User Details";
        }
        $user = new User_model();
        if($user->first(['email' => $data['email']]) === false && $user->first(['login' => $data['login']]) === false){
            show($user->insert($data).  'User ADD');
        }
        else{
            show("User Exists");
    }

        $this->view('home');
    }
    public function delete($data)
    {
        echo "this is delete method" . "<br>";

        $user = new User_model();
        if($user->first(['login' => $data]) !== false){
            $row_user = $user->first(['login' => $data]);
            show($user->delete($row_user->id) .  'User delete');
        }
        else{
            show("No Users");
    }

        $this->view('home');
    }
    public function find($data)
    {
        echo "this is find method" . "<br>";

        $user = new User_model();
        if($user->first(['login' => $data]) !== false){
            show($user->first(['login' => $data]));
        }
        else{
            show("No Users");
    }

        $this->view('home');
    }
//   $user->update(9, ['name' => 'Slavik2', 'age' => 12]);

}



```
Roles.php
```Roles.php
<?php

class Roles extends Controller
{
    public function findAll()
    {
        echo "this is findAll method" . "<br>";

        $role = new Role_model();
        if($role->findAll() !== false){
            show($role->findAll());
        }
        else{
            show("No roles");
    }

        $this->view('home');
    }

    public function add()
    {
        echo "this is add method" . "<br>";
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json') {
            $data = json_decode(file_get_contents('php://input'), true);
        } else {
            echo "Send Role Details";
        }
        $role = new Role_model();
        if($role->first(['name' => $data['name']]) === false){
            show($role->insert($data) .  'Role ADD');
        }
        else{
            show("role Exists");
    }

        $this->view('home');
    }
    public function delete($data)
    {
        echo "this is delete method" . "<br>";

        $role = new Role_model();
        if($role->first(['name' => $data]) !== false){
            $row_role = $role->first(['name' => $data]);
            show($role->delete($row_role->id).  'Role DELETE');
        }
        else{
            show("No roles");
    }

        $this->view('home');
    }
    public function find($data = '')
    {
        echo "this is find method" . "<br>";

        $role = new Role_model();
        if($role->first(['name' => $data]) !== false){
            show($role->first(['name' => $data]));
        }
        else{
            show("No roles");
    }

        $this->view('home');
    }

}



```
Requests.php
```Requests.php
<?php

class Requests extends Controller
{
    public function findAll()
    {
        echo "this is findAll method" . "<br>";

        $request = new Request_model();
        if($request->findAll() !== false){
            show($request->findAll());
        }
        else{
            show("No requests");
    }

        $this->view('home');
    }

    public function add()
    {
        echo "this is ADD method" . "<br>";
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json') {
            $data = json_decode(file_get_contents('php://input'), true);
        } else {
            echo "Send Request Details";
        }
        $request = new Request_model();
            show($request->insert($data). 'ADD request');

        $this->view('home');
    }
    public function delete($data)
    {
        $request = new Request_model();
        if($request->first(['id'=> $data]) !== false){
            show($request->delete($data). 'Request DELETE');
        }
        else{
            show("No requests");
    }

        $this->view('home');
    }
    public function find($data)
    {
        echo "this is Find method" . "<br>";
        
        $request = new Request_model();
        
            show($request->first(['id' => $data]));

        $this->view('home');
    }
//   $request->update(9, ['name' => 'Slavik2', 'age' => 12]);

}



```
Origins.php
```Origins.php
<?php

class Origins extends Controller
{
    public function findAll()
    {
        echo "this is findAll method" . "<br>";

        $origin = new Origin_model();
        if($origin->findAll() !== false){
            show($origin->findAll());
        }
        else{
            show("No origins");
    }

        $this->view('home');
    }

    public function add()
    {
        echo "this is ADD method" . "<br>";
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json') {
            $data = json_decode(file_get_contents('php://input'), true);
            //var_dump($data);
        } else {
            echo "Send Origin Details";
        }
        $origin = new Origin_model();
        if($origin->first(['name'=> $data['name']]) === false){
            show($origin->insert($data). "Origin ADD");
        }
        else{
            show("origin Exists");
    }

        $this->view('home');
    }
    public function delete($data)
    {
        echo "this is DELETE method" . "<br>";

        $origin = new Origin_model();
        if($origin->first(['name'=>$data]) !== false){
            $row_origin = $origin->first(['name' => $data]);
            show($origin->delete($row_origin->id).  'Role DELETE');
        }
        else{
            show("No origins");
    }

        $this->view('home');
    }
    public function find($data)
    {
        echo "this is FIND method" . "<br>";

        $origin = new Origin_model();
        if($origin->first(['name'=>$data]) !== false){
            show($origin->first(['name' => $data]));
        }
        else{
            show("No origins");
    }

        $this->view('home');
    }

}



```
Medias.php
```Medias.php
<?php

class Medias extends Controller
{
    public function findAll()
    {
        echo "this is findAll function" . "<br>";

        $media = new Media_model();
        if($media->findAll() !== false){
            show($media->findAll());
        }
        else{
            show("No medias");
    }

        $this->view('home');
    }

    public function add()
    {
        echo "this is ADD function" . "<br>";
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json') {
            $data = json_decode(file_get_contents('php://input'), true);
        } else {
            echo "Send Media Details";
        }
        $media = new Media_model();
        if($media->first(["name" => $data['name']]) === false){
            show($media->insert($data). "Media ADD");
        }
        else{
            show("media Exists");
    }

        $this->view('home');
    }
    public function delete($data)
    {
        echo "this is DELETE function" . "<br>";

        $media = new Media_model();
        if($media->first(["name" => $data]) !== false){
            $row_media = $media->first(['name' => $data]);
            show($media->delete($row_media->id).  'media DELETE');
        }
        else{
            show("No medias");
    }

        $this->view('home');
    }
    public function find($data)
    {
        echo "this is FIND function" . "<br>";

        $media = new Media_model($data);
        if($media->first(["name" => $data]) !== false){
            show($media->first(['name' => $data]));
        }
        else{
            show("No medias");
    }

        $this->view('home');
    }
//   $media->update(9, ['name' => 'Slavik2', 'age' => 12]);

}



```

