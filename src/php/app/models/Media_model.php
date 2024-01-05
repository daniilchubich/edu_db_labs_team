<?php

class Media_model
{
    use Model;

    protected $table = "media";
    protected $allowedColumns = [
        "type",
        "url",
        "name",
        "metadate",
        "Origin_id"
    ];
}