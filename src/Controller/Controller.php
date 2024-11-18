<?php

namespace SPF\Controller;

use SPF\View\View;

class Controller
{
    public function __toString(): string
    {
        return __CLASS__;
    }
}