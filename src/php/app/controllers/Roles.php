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
