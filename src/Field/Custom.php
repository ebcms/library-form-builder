<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder\Field;

use Ebcms\FormBuilder\ItemInterface;
use Ebcms\Template;

class Custom implements ItemInterface
{

    public function __construct(string $tpl)
    {
        $this->_tpl = $tpl;
    }

    public function set(string $name, $value): self
    {
        if ($name != '_tpl') {
            $this->$name = $value;
        }
        return $this;
    }

    public function __toString()
    {
        return (new Template())->renderFromString($this->_tpl, get_object_vars($this));
    }
}
