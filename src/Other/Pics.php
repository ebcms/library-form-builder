<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder\Other;

use Ebcms\FormBuilder\ItemInterface;
use Ebcms\Template;

class Pics implements ItemInterface
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
{if !isset($GLOBALS['picloader'])}
{php $GLOBALS['picloader']=1}
<style>
.ximg:hover .close{
    display:block !important;
}
</style>
<script>
function pic_render(key){
    var val = $('#field_'+key).val();
    if (val) {
        var arr = JSON.parse(val);
        $.each(arr, function(k, v){
            var html = "";
            html += '<div class="position-relative float-left mr-2 mb-2 ximg">';
            var size = v.size;
            if(v.size > 1024*1024){
                size = parseInt(v.size/(1024*1024)) + 'MB';
            } else if (v.size > 1024){
                size = parseInt(v.size/1024) + 'KB';
            } else {
                size = size + 'B';
            }
            html += '<img style="cursor:pointer;height:100px;width:100px;" class="img-thumbnail img-fluid" alt="'+v.filename+'(大小:'+size+')" title="'+v.filename+'(大小:'+size+')" src="'+v.src+'" >';
            html += '<div class="position-absolute" style="background:#00000080;left:0;top:0;right:0;">';
            html += '<div class="float-left text-white close" style="cursor:pointer;padding: 0 6px 5px 6px;display:none;" onclick="pic_del(\''+key+'\','+k+')">×</div>';
            if(k!=0){
                html += '<div class="float-left text-white close" style="cursor:pointer;padding: 0 4px 5px 4px;display:none;" onclick="pic_move(\''+key+'\','+k+', -1)">←</div>';
            }
            if(k<arr.length-1){
                html += '<div class="float-left text-white close" style="cursor:pointer;padding: 0 4px 5px 4px;display:none;" onclick="pic_move(\''+key+'\','+k+', 1)">→</div>';
            }
            html += '</div>';
            html += '</div>';
            $("#pics_container_"+key).append(html);
        });
    }
}
function pic_move(key, index, pos){
    var val = $('#field_'+key).val();
    if(val){
        arr = JSON.parse(val);
        arr[index] = arr.splice(index+pos, 1, arr[index])[0];
        $('#field_'+key).val(JSON.stringify(arr));
        $("#pics_container_"+key).html('');
        pic_render(key);
    }
}
function pic_del(key, index){
    var val = $('#field_'+key).val();
    arr = JSON.parse(val);
    arr.splice(index, 1);
    $('#field_'+key).val(JSON.stringify(arr));
    $("#pics_container_"+key).html('');
    pic_render(key);
}
function pic_upload(key){
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
    fileinput.multiple = "multiple";
    fileinput.accept = "image/*";
    fileinput.onchange=function () {
        $.each(event.target.files, function(indexInArray, valueOfElement) {
            upload_by_form("{$upload_url}", valueOfElement, function(response) {
                if (response.code) {
                    var val = $('#field_'+key).val();
                    if(val){
                        arr = JSON.parse(val);
                    }else{
                        arr = [];
                    }
                    arr.push(response.data);
                    $('#field_'+key).val(JSON.stringify(arr));
                    $("#pics_container_"+key).html('');
                    pic_render(key);
                } else {
                    alert(response.message);
                }
            });
        });
    }
    fileinput.click();
}
</script>
{/if}
<div class="mb-3">
    <label for="field_{:md5($name)}" class="form-label">{$label}</label>
    <input type="text" class="form-control d-none" name="{$name}" value="{$value}" id="field_{:md5($name)}">
    <div class="overflow-hidden" id="pics_container_{:md5($name)}"></div>
    <div class="py-3">
        <button type="button" class="btn btn-primary" onclick="pic_upload('{:md5($name)}')">{$upload_text??'上传'}</button>
    </div>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                pic_render('{:md5($name)}');
            }, 100);
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
