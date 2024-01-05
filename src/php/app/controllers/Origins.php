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
