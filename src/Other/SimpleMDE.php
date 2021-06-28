<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder\Other;

use Ebcms\FormBuilder\ItemInterface;
use Ebcms\Template;

class SimpleMDE implements ItemInterface
{

    public function __construct(
        string $label,
        string $name,
        $value = '',
        $upload_url = ''
    ) {
        $this->label = $label;
        $this->name = $name;
        $this->value = $value;
        $this->upload_url = $upload_url;
    }

    public function set(string $name, $value): self
    {
        $this->$name = $value;
        return $this;
    }

    private function getTpl(): string
    {
        return <<<'str'
{if !isset($GLOBALS['simplemdeload'])}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<style>
.CodeMirror {
    height: 350px;
}
</style>
{php $GLOBALS['simplemdeload']=1}
{/if}
<div class="mb-3">
    <label for="field_{:md5($name)}" class="form-label">{$label}</label>
    <textarea
        class="form-control"
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
    {if isset($help) && $help}
    <div id="help_{:md5($name)}" class="form-text">{$help}</div>
    {/if}
</div>
<script>
    new SimpleMDE({
        element: document.getElementById("field_{:md5($name)}"),
        spellChecker: false,
        toolbar: ["bold", "italic", "strikethrough", "heading", "code", "quote", "|", "unordered-list", "ordered-list", "clean-block", "link", "image", "table", "horizontal-rule", "|", {
            name: "",
            action: function customeFunction(editor) {
                var cm = editor.codemirror;
                if (/editor-preview-active/.test(cm.getWrapperElement().lastChild.className)) {
                    return;
                }
                var upload_by_form = function(url, file, callback) {
                    var data = new FormData();
                    data.append('file', file);
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: data,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.code) {
                                callback(response);
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function() {
                            alert('Error');
                        }
                    });
                }
                var fileinput = document.createElement("input");
                fileinput.type = "file";
                fileinput.onchange = function() {
                    $.each(event.target.files, function(indexInArray, valueOfElement) {
                        upload_by_form("{$upload_url}", valueOfElement, function(response) {
                            if (response.code) {
                                cm.replaceSelection("![" + response.data.src + "](" + response.data.src + ")");
                                cm.focus();
                            } else {
                                alert(response.message);
                            }
                        });
                    });
                }
                fileinput.click();
            },
            className: "fa fa-upload",
            title: "图片上传"
        }, "|", "preview", "side-by-side", "fullscreen"]
    });
</script>
str;
    }

    public function __toString()
    {
        return (new Template())->renderFromString($this->getTpl(), get_object_vars($this));
    }
}
