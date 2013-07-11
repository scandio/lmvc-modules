<?php

namespace Scandio\lmvc\modules\registration\forms;

use Scandio\lmvc\modules\form\Form;

class Signup extends Form
{
    public $username = [
        'check-username' => ['message' => 'Your desired username "%s" is a bit short, sorry!'],
        'mandatory' => ['message' => 'Please give us a username!']
    ];
    public $email = [
        'check-email' => ['message' => 'Your desired email "%s" aint an email!'],
        'mandatory' => ['message' => 'Please give us an E-Mail of yours!']
    ];
    public $password = [
        'check-password' => ['message' => 'Your password aint strong enough!'],
        'mandatory' => ['message' => 'My dear friend: how are you gonna login without a password?']
    ];
    public $passwordRetyped = [
        'check-password-retyped' => ['message' => 'Passwords are not matching!']
    ];

    protected function checkUsername($name)
    {
        if (!empty($this->request()->$name)
            && (strlen(trim($this->request()->$name)) < 3)
        ) {
            $this->setError($name, array($this->request()->$name));
        }
    }

    protected function checkEmail($name)
    {
        if (!empty($this->request()->$name)
            && (preg_match('^[_\\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\\.)+[a-z]{2,4}\$', $this->request()->$name))
        ) {
            $this->setError($name, array($this->request()->$name));
        }
    }

    protected function checkPassword($name)
    {
        if (!empty($this->request()->$name)
            && (strlen(trim($this->request()->$name)) < 3)
        ) {
            $this->setError($name, array($this->request()->$name));
        }
    }

    protected function checkPasswordRetyped($name)
    {
        if (!empty($this->request()->$name)
            && (sha1($this->request()->$name) != sha1($this->request()->password))
        ) {
            $this->setError($name, array($this->request()->$name));
        }
    }
}