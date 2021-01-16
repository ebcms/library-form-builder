<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder\Field;

use Ebcms\FormBuilder\ItemInterface;
use Ebcms\Template;

class Hidden implements ItemInterface
{

    public function __construct(string $name, $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function set(string $name, $value): self
    {
        $this->$name = $value;
        return $this;
    }

    private function getTpl(): string
    {
        return <<<'str'
        <input type="hidden" name="{$name}" value="{$value}">
str;
    }

    public function __toString()
    {
        return (new Template())->renderFromString($this->getTpl(), get_object_vars($this));
    }
}
