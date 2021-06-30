<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder;

use Ebcms\Template;

class Builder
{
    private $novalidate = false;
    private $autocomplete = true;

    public function __construct(string $title = '')
    {
        $this->title = $title;
    }

    public function addItem(ItemInterface ...$items): self
    {
        foreach ($items as $item) {
            $this->items[] = $item;
        }
        return $this;
    }

    public function addRow(RowInterface ...$items): self
    {
        foreach ($items as $item) {
            $this->items[] = $item;
        }
        return $this;
    }

    public function set(string $name, $value): self
    {
        $this->$name = $value;
        return $this;
    }

    protected function getTpl(): string
    {
        return <<<'str'
<!DOCTYPE html>
<html lang="{$lang??'zh-CN'}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title??'表单'}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha256-djO3wMl9GeaC/u6K+ic4Uj/LKhRUSlUFcsruzS7v5ms=" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha256-fh8VA992XMpeCZiRuU4xii75UIG6KvHrbUF8yIS/2/4=" crossorigin="anonymous"></script>
</head>

<body>
    <div class="{$class??'container-xxl'}">
        {if isset($title) && $title}
        <div class="my-4 h1">{$title}</div>
        {/if}
        <form
        action="{$action??''}"
        method="{$method??'POST'}"
        class="my-2"
        enctype="{$enctype??'application/x-www-form-urlencoded'}"
        target="{$target??'_self'}"
        id="{$id??'form'}"
        name="{$name??'form'}"
        {$autocomplete?' autocomplete="on"':' autocomplete="off"'}
        {$novalidate?' novalidate="novalidate"':''}
        >
            {foreach $items as $row}
            {echo $row}
            {/foreach}
            <div class="mt-2 py-2">
                <button type="submit" class="btn btn-primary">{$submit_text??'提交'}</button>
            </div>
        </form>
    </div>
</body>

</html>
str;
    }

    public function __toString()
    {
        return (new Template())->renderFromString($this->getTpl(), array_merge(get_object_vars($this)));
    }
}
