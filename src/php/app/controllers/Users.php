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
