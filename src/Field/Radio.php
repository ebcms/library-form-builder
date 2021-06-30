<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder\Field;

use Ebcms\FormBuilder\ItemInterface;
use Ebcms\Template;

class Radio implements ItemInterface
{
    public $inline = false;
    public $options = [];

    public function __construct(string $label, string $name, $value = '', array $options = [])
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
<div class="mb-3">
    <label for="field_{:md5($name)}" class="form-label">{$label}</label>
    <div>
        {foreach $options as $vo}
        <div class="form-check {if $inline}form-check-inline{/if}">
            <input class="form-check-input" type="radio" name="{$name}" id="field_{:md5($name .'~'. $vo['value'])}" value="{$vo['value']}" {if isset($vo['disabled']) && $vo['disabled']}disabled{/if} {:$vo['value']==$value?' checked':''}>
            <label class="form-check-label" for="field_{:md5($name .'~'. $vo['value'])}">{$vo['label']??$vo['value']}</label>
        </div>
        {/foreach}
    </div>
    {if isset($help) && $help}
    <div id="help_{:md5($name)}" class="form-text">{$help}</div>
    {/if}
</div>
str;
    }

    public function __toString()
    {
        return (new Template())->renderFromString($this->getTpl(), get_object_vars($this));
    }
}
