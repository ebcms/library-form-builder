<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder\Other;

use Ebcms\FormBuilder\ItemInterface;
use Ebcms\Template;

class Switchs implements ItemInterface
{
    private $data = [];

    public function __construct(string $label, string $name, $value = null)
    {
        $this->data['label'] = $label;
        $this->data['name'] = $name;
        $this->data['value'] = $value;
        $this->data['inline'] = true;
        $this->data['switchs'] = [];
    }

    public function set(string $name, $value): self
    {
        $this->data[$name] = $value;
        return $this;
    }

    public function addSwitch(string $label, $value, ItemInterface ...$items): self
    {
        $key = 'switch_' . md5($this->data['name']) . '_' . md5($value);
        $this->data['switchs'][$key] = [
            'label' => $label,
            'value' => $value,
            'items' => (function () use ($items): string {
                $res = '';
                foreach ($items as $value) {
                    $res .= $value;
                }
                return (string)$res;
            })()
        ];
        return $this;
    }

    private function getTpl(): string
    {
        return <<<'str'
<div class="mb-3">
    <label for="field_{:md5($name)}" class="form-label">{$label}</label>
    <div>
        {foreach $switchs as $key=>$vo}
        <div class="custom-control custom-radio {if $inline}custom-control-inline{/if}">
            <input class="custom-control-input" type="radio" onclick="$('#{$key}').removeClass('d-none').siblings().addClass('d-none')" name="{$name}" id="field_{:md5($name .'~'. $vo['value'])}" value="{$vo['value']}" {if isset($vo['disabled']) && $vo['disabled']}disabled{/if} {:$vo['value']==$value?' checked':''}>
            <label class="custom-control-label" for="field_{:md5($name .'~'. $vo['value'])}">{$vo['label']??$vo['value']}</label>
        </div>
        {/foreach}
    </div>
    {if isset($help) && $help}
    <div id="help_{:md5($name)}" class="form-text">{$help}</div>
    {/if}
</div>
<div class="bg-light p-3 mb-3">
{foreach $switchs as $key => $vo}
<div id="{$key}" {if $vo['value'] != $value}class="d-none"{/if}>
{$vo.items}
</div>
{/foreach}
</div>
str;
    }

    public function __toString()
    {
        return (new Template())->renderFromString($this->getTpl(), $this->data);
    }
}
