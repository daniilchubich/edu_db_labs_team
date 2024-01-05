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