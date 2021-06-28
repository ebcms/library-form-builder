<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder\Field;

use Ebcms\FormBuilder\ItemInterface;
use Ebcms\Template;

class Select implements ItemInterface
{
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
        <select
            name="{$name}"
            id="field_{:md5($name)}"
            class="custom-select"
            {if isset($title) && $title}title="{$title}" {/if}
            {if isset($required) && $required}required {/if}
            {if isset($readonly) && $readonly}readonly {/if}
            {if isset($disabled) && $disabled}disabled {/if}
        >
            {foreach $options as $vo}
            {if isset($vo['group'])}
            <optgroup label="{$vo.label}">
                {foreach $vo['group'] as $_sub}
                <option value="{$_sub.value}" {if isset($_sub['disabled']) && $_sub['disabled']}disabled{else}{$_sub['value']==$value?'selected':''}{/if}>{$_sub.label}</option>
                {/foreach}
            </optgroup>
            {else}
            <option value="{$vo.value}" {if isset($vo['disabled']) && $vo['disabled']}disabled{else}{$vo['value']==$value?'selected':''}{/if}>{$vo.label}</option>
            {/if}
            {/foreach}
        </select>
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
