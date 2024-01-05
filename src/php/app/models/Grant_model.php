<?php

class Grant_model
{
    use Model;

    protected $table = 'grant';
    protected $allowedColumns = [
        'id',
        'title',
        'description',
        'role_id'
    ];
}