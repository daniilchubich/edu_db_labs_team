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