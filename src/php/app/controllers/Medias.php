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
