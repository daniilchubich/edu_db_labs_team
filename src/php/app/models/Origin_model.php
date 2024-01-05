<?php

class Origin_model
{
    use Model;

    protected $table = 'origin';
    protected $allowedColumns = [
        'name',
        'location',
        'rating'
    ];
}