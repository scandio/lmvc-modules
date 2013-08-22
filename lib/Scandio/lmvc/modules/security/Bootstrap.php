<?php

namespace Scandio\lmvc\modules\security;

use Scandio\lmvc\LVC;
use Scandio\lmvc\modules\snippets\Snippets;

class Bootstrap extends \Scandio\lmvc\Bootstrap
{
    /**
     * Registers the module controller namespace and the views directory
     */
    public function initialize()
    {
        LVC::registerControllerNamespace(new controllers\Security());
        LVC::registerViewDirectory(static::getPath() . '/views/');
        Snippets::registerSnippetDirectory(self::getPath() . '/snippets/');
    }
}