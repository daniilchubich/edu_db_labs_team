<?php

class Home extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        echo "this is index method" . "<br>";

        $user = new User_model();
        show($user->findAll());
        //$user->update(9, ['name' => 'Slavik2', 'age' => 12]);

        $this->view('home');
    }

    public function edit($a = '', $b = '', $c = '')
    {
        echo "this is edit method" . "<br>";

        $this->view('home');
    }

}
