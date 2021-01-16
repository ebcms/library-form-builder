<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder\Other;

use Ebcms\FormBuilder\ItemInterface;
use Ebcms\Template;

class Cover implements ItemInterface
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
<div class="form-group">
    <label for="field_{:md5($name)}">{$label}</label>
    <input type="text" class="form-control d-none" name="{$name}" value="{$value}" id="field_{:md5($name)}">
    {if !isset($GLOBALS['_loadholder'])}
    {php $GLOBALS['_loadholder']=1}
    <script src="https://cdn.jsdelivr.net/npm/holderjs@2.9.6/holder.min.js" integrity="sha256-yF/YjmNnXHBdym5nuQyBNU62sCUN9Hx5awMkApzhZR0=" crossorigin="anonymous"></script>
    {/if}
    <div class="position-relative">
        <img
        style="cursor:pointer;max-height:200px;max-width:200px;"
        class="img-thumbnail img-fluid"
        data-src="holder.js/300x200?auto=yes&text=click%20upload&size=25"
        id="field_{:md5($name)}_handler"
        >
        <div
        class="position-absolute bg-secondary text-white close"
        id="field_{:md5($name)}_close"
        style="left:0;top:0;cursor:pointer;padding: 0 4px 4px 4px;display:none;"
        >&times;</div>
    </div>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                if ($('#field_{:md5($name)}').val()) {
                    $("#field_{:md5($name)}_close").show();
                    $('#field_{:md5($name)}_handler').attr('src', $('#field_{:md5($name)}').val());
                }
            }, 100);
            $("#field_{:md5($name)}_handler").bind('click',function(){
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
                                $("#field_{:md5($name)}_close").show();
                                $('#field_{:md5($name)}_handler').attr('src',response.data.src);
                                $("#field_{:md5($name)}").val(response.data.src);
                            } else {
                                alert(response.message);
                            }
                        });
                    });
                }
                fileinput.click();
            });
            $("#field_{:md5($name)}_close").bind('click',function(){
                $("#field_{:md5($name)}_close").hide();
                $('#field_{:md5($name)}_handler').attr("data-src","holder.js/300x200?auto=yes&text=click%20upload&size=25");
                Holder.run({
                    images: document.getElementById('field_{:md5($name)}_handler')
                  });
                $("#field_{:md5($name)}").val('');
            });
        });
    </script>
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
