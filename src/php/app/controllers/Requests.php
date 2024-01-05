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
