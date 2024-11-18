<?php

namespace SPF\Database;

abstract class Model
{
    protected ?\PDO $db = null;

    public function __construct()
    {
        $this->db = db();
    }
}
