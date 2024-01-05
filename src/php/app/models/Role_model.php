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