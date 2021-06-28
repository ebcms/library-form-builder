<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder\Other;

use Ebcms\FormBuilder\ItemInterface;
use Ebcms\Template;

class TextUpload implements ItemInterface
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
<div class="mb-3">
    <label for="field_{:md5($name)}" class="form-label">{$label}</label>
    <div class="input-group">
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
        <div class="input-group-append">
            <button
            class="btn btn-primary"
            type="button"
            id="field_{:md5($name)}_trigger"
            {if isset($readonly) && $readonly}readonly {/if}
            {if isset($disabled) && $disabled}disabled {/if}
            >上传</button>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#field_{:md5($name)}_trigger").bind('click',function(){
                var upload_by_form=function(url, file, callback) {
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
                fileinput.onchange=function () {
                    $.each(event.target.files, function(indexInArray, valueOfElement) {
                        upload_by_form("{$upload_url}", valueOfElement, function(response) {
                            if (response.code) {
                                $("#field_{:md5($name)}").val(response.data.src);
                            } else {
                                alert(response.message);
                            }
                        });
                    });
                }
                fileinput.click();
            });
        });
    </script>
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
