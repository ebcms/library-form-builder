<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder\Field;

use Ebcms\FormBuilder\ItemInterface;
use Ebcms\Template;

class Checkbox implements ItemInterface
{
    public $inline = false;
    public $options = [];

    public function __construct(string $label, string $name, array $value = [], array $options = [])
    {
        $this->label = $label;
        $this->name = $name;
        $this->value = $value;
        $this->options = $options;
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
    <div>
        {foreach $options as $vo}
        <div class="custom-control custom-checkbox {if $inline}custom-control-inline{/if}">
            <input class="custom-control-input" type="checkbox" name="{$name}[]" id="field_{:md5($name .'~'. $vo['value'])}" value="{$vo['value']}" {if isset($vo['disabled']) && $vo['disabled']}disabled{else}{:in_array($vo['value'], $value)?'checked':''}{/if}>
            <label class="custom-control-label" for="field_{:md5($name .'~'. $vo['value'])}">{$vo['label']??$vo['value']}</label>
        </div>
        {/foreach}
    </div>
    {if isset($help) && $help}
    <small id="help_{:md5($name)}" class="form-text text-muted">{$help}</small>
    {/if}
</div>
str;
    }

    public function __toString()
    {
        return (new Template())->renderFromString($this->getTpl(), get_object_vars($this));
    }
}
