<?php

namespace Scandio\lmvc\modules\registration\controllers;

use Scandio\lmvc\Controller;
use Scandio\lmvc\LVCConfig;

class Registration extends Controller
{
    public static function index()
    {
        return static::redirect('Registration::signup');
    }

    public static function signup()
    {

    }
}