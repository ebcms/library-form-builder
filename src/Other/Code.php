<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder\Other;

use Ebcms\FormBuilder\ItemInterface;
use Ebcms\Template;

class Code implements ItemInterface
{

    public function __construct(string $label, string $name, $value = '', $mode = 'htmlmixed')
    {
        $this->label = $label;
        $this->name = $name;
        $this->value = $value;
        $this->mode = $mode;
    }

    public function set(string $name, $value): self
    {
        $this->$name = $value;
        return $this;
    }

    private function getTpl(): string
    {
        return <<<'str'
{if !isset($GLOBALS['_codemirror'])}
{php $GLOBALS['_codemirror']=1}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/codemirror@5.62.0/lib/codemirror.min.css" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.62.0/lib/codemirror.min.js"></script>
<script>
var render_code = function(id, mode){
    CodeMirror.fromTextArea(document.getElementById(id), {
        lineNumbers: true,
        matchBrackets: true,
        mode: mode,
        indentUnit: 4,
        indentWithTabs: true,
        lineWrapping: true,
    });
}
</script>
<style>
.CodeMirror {
    height: 550px;
}
</style>
{/if}
{if $mode}
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.62.0/mode/{$mode}/{$mode}.min.js"></script>
{/if}
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.62.0/mode/php/php.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.62.0/mode/javascript/javascript.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.62.0/mode/xml/xml.min.js"></script>
<div class="mb-3">
    <label for="field_{:md5($name)}" class="form-label">{$label}</label>
    <textarea
        class="form-control d-none"
        id="field_{:md5($name)}"
        name="{$name}"
        {if isset($pattern) && $pattern}pattern="{$pattern}"{/if}
        {if isset($title) && $title}title="{$title}"{/if}
        placeholder="{$placeholder??''}"
        autocomplete="{$autocomplete??''}"
        maxlength="{$maxlength??''}"
        {if isset($required) && $required}required {/if}
        {if isset($readonly) && $readonly}readonly {/if}
        {if isset($disabled) && $disabled}disabled {/if}
        aria-describedby="help_{:md5($name)}"
    >{$value}</textarea>
    <div class="bg-light p-4 text-secondary" onclick="$(this).remove();render_code('field_{:md5($name)}', '{$mode}')"><svg t="1611996487173" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6178" width="18" height="18"><path d="M242.752 268.8l90.496 90.496-152.256 152.256 152.256 152.192-90.496 90.496L0 511.552zM780.992 268.8l-90.56 90.496 152.256 152.256-152.256 152.192 90.56 90.496 242.688-242.688z" fill="#425766" p-id="6179"></path><path d="M513.024 192h128l-128 640h-128z" fill="#9AA8B3" p-id="6180"></path></svg> 点此编辑</div>
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
