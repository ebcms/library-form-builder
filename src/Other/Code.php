<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder\Other;

use Ebcms\FormBuilder\ItemInterface;
use Ebcms\Template;

class Code implements ItemInterface
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
{if !isset($GLOBALS['_codemirror'])}
{php $GLOBALS['_codemirror']=1}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.59.2/codemirror.min.css" integrity="sha512-MWdvo/Qqcf4pY1ecQUB1uBn0qLp19U/qJ1Rpp2BDZeuBA7YsFEwkvqR/+aG4BroPiAYDunKJ6X8R/Pmdt3p7oA==" crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.59.2/codemirror.min.js" integrity="sha512-UZAFKlbB343VyEfCComsVIxp836iYUvHyAuRYFoVN4LTNB6mpM+8EgKW8ymIV2qLZQsIiNdbpmJuA8y6IKzOow==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.59.2/mode/php/php.min.js" integrity="sha512-i+JuurEwS1TBFIkaoI0KNhkdiR2yu5nAVdFJ/3Sm3BVbMIkq/1Nv/JsFGUZsqB4VKV6vj1wP5yi1aqyxenx2kw==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.59.2/mode/javascript/javascript.min.js" integrity="sha512-+tn2IYnLwD2J9p6Nrn/Dl7ag9lluHA0GAblT/vnMiJV8DU/iDsldgf+9XbEqZUee2ThyDtfmSDb+IDZ9u7jrSA==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.59.2/mode/xml/xml.min.js" integrity="sha512-XPih7uxiYsO+igRn/NA2A56REKF3igCp5t0W1yYhddwHsk70rN1bbbMzYkxrvjQ6uk+W3m+qExHIJlFzE6m5eg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.59.2/mode/htmlmixed/htmlmixed.min.js" integrity="sha512-IC+qg9ITjo2CLFOTQcO6fBbvisTeJmiT5D5FnXsCptqY8t7/UxWhOorn2X+GHkoD1FNkyfnMJujt5PcB7qutyA==" crossorigin="anonymous"></script>
<script>
    var render_code = function(id){
        CodeMirror.fromTextArea(document.getElementById(id), {
            lineNumbers: true,
            matchBrackets: true,
            mode: "htmlmixed",
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
    <div class="bg-light p-4 text-secondary" onclick="$(this).remove();render_code('field_{:md5($name)}')"><svg t="1611996487173" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6178" width="18" height="18"><path d="M242.752 268.8l90.496 90.496-152.256 152.256 152.256 152.192-90.496 90.496L0 511.552zM780.992 268.8l-90.56 90.496 152.256 152.256-152.256 152.192 90.56 90.496 242.688-242.688z" fill="#425766" p-id="6179"></path><path d="M513.024 192h128l-128 640h-128z" fill="#9AA8B3" p-id="6180"></path></svg> 点此编辑</div>
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
