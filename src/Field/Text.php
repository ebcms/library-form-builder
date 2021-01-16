<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder\Field;

use Ebcms\FormBuilder\ItemInterface;
use Ebcms\Template;

class Text implements ItemInterface
{

    public function __construct(string $label, string $name, $value = '')
    {
        $this->label = $label;
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
<div class="form-group">
    <label for="field_{:md5($name)}">{$label}</label>
    <input
        type="text"
        class="form-control"
        id="field_{:md5($name)}"
        name="{$name}"
        value="{$value}"
        {if isset($pattern) && $pattern}pattern="{$pattern}"{/if}
        {if isset($title) && $title}title="{$title}"{/if}
        placeholder="{$placeholder??''}"
        autocomplete="{$autocomplete??''}"
        maxlength="{$maxlength??''}"
        {if isset($required) && $required}required {/if}
        {if isset($readonly) && $readonly}readonly {/if}
        {if isset($disabled) && $disabled}disabled {/if}
        aria-describedby="help_{:md5($name)}"
        list="list_{:md5($name)}"
    >
    {if isset($list) && is_array($list)}
    <datalist id="list_{:md5($name)}">
        {foreach $list as $_vo}
        <option value="{$_vo}">
        {/foreach}
    </datalist>
    {/if}
    {if isset($help) && $help}
    <small id="help_{:md5($name)}" class="form-text text-muted">{$help}</small>
    {/if}
</div>
str;
    }

    public function __toString()
    {
        // title readonly disabled required list maxlength pattern placeholder autocomplete min max multiple
        return (new Template())->renderFromString($this->getTpl(), get_object_vars($this));
    }
}
