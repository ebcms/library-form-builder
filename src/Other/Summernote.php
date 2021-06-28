<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder\Other;

use Ebcms\FormBuilder\ItemInterface;
use Ebcms\Template;

class Summernote implements ItemInterface
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
        $this->lang = 'zh-CN';
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
    <div>
        {if !isset($GLOBALS['_summernote'])}
        {php $GLOBALS['_summernote']=1;}
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js" integrity="sha256-lasqRX7iHFTYIkce7X5tXZT5Xa+k0/79RVBUgBWFrFY=" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" integrity="sha256-ztUDTRE0Jq4ZR/ZKD+fivOhevPPuiXD0ua7M+3OE+t4=" crossorigin="anonymous">
        {/if}
        {if !isset($GLOBALS['_summernote_lang_'.$lang])}
        {php $GLOBALS['_summernote_lang_'.$lang]=1;}
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/lang/summernote-{$lang??'en-US'}.min.js"></script>
        {/if}
        <textarea
            class="form-control"
            id="field_{:md5($name)}"
            name="{$name}"
        >{$value}</textarea>
        <script>
            $(document).ready(function() {
                $("#field_{:md5($name)}").summernote({
                    lang: "{$lang??'en-US'}",
                    height: "{$height??'250'}",
                    callbacks: {
                        {if $upload_url}
                        onImageUpload: function(files) {
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
                            $.each(files, function(indexInArray, valueOfElement) {
                                upload_by_form("{$upload_url}", valueOfElement, function(response) {
                                    if (response.code) {
                                        $("#field_{:md5($name)}").summernote('insertImage', response.data.src);
                                    } else {
                                        alert(response.message);
                                    }
                                });
                            });
                        }
                        {/if}
                    }
                });
            });
        </script>
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
