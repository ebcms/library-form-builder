<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder\Field;

use Ebcms\FormBuilder\ItemInterface;
use Ebcms\Template;

class Week implements ItemInterface
{

    public function __construct(
        string $label,
        string $name,
        $value = '',
        $min = null,
        $max = null,
        int $step = null
    ) {
        $this->label = $label;
        $this->name = $name;
        $this->value = $value;
        if (!is_null($min)) {
            $this->min = $min;
        }
        if (!is_null($max)) {
            $this->max = $max;
        }
        if (!is_null($step)) {
            $this->step = $step;
        }
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
    <input
        type="week"
        class="form-control"
        id="field_{:md5($name)}"
        name="{$name}"
        value="{$value}"
        {if isset($min)}min="{$min}" {/if}
        {if isset($max)}max="{$max}" {/if}
        {if isset($step)}step="{$step}" {/if}
        {if isset($pattern) && $pattern}pattern="{$pattern}" {/if}
        {if isset($title) && $title}title="{$title}" {/if}
        placeholder="{$placeholder??''}"
        autocomplete="{$autocomplete??''}"
        maxlength="{$maxlength??''}"
        {if isset($required) && $required}required {/if}
        {if isset($readonly) && $readonly}readonly {/if}
        {if isset($disabled) && $disabled}disabled {/if}
        aria-describedby="help_{:md5($name)}"
    >
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
