<?php

namespace Scandio\lmvc\modules\form;

class Form extends AbstractForm
{
    /**
     * @param string $name
     */
    public function mandatory($name)
    {
        if (strlen(trim($this->request()->$name)) == 0) {
            $this->setError($name);
        }
    }
}