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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" integrity="sha256-aAr2Zpq8MZ+YA/D6JtRD3xtrwpEz2IqOS+pWD/7XKIw=" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js" integrity="sha256-Xt8pc4G0CdcRvI0nZ2lRpZ4VHng0EoUDMlGcBSQ9HiQ=" crossorigin="anonymous"></script>
</head>

<body>
    <div class="{$class??'container-xl'}">
        {if isset($title) && $title}
        <div class="display-4 my-4">{$title}</div>
        <hr>
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
            <div style="padding-bottom:50px;">
                {foreach $items as $row}
                {echo $row}
                {/foreach}
            </div>
            <div class="mt-2 py-2 fixed-bottom bg-white">
                <div class="container-xl">
                    <button type="submit" class="btn btn-primary px-4">{$submit_text??'提交'}</button>
                </div>
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
