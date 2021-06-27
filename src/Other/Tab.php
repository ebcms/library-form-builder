<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder\Other;

use Ebcms\FormBuilder\ItemInterface;
use Ebcms\Template;

class Tab implements ItemInterface
{
    private $data = [];

    public function __construct(string $class = 'nav-tabs')
    {
        $this->data['class'] = $class;
        $this->data['tabs'] = [];
        $this->data['id'] = uniqid();
    }

    public function set(string $name, $value): self
    {
        $this->data[$name] = $value;
        return $this;
    }

    public function addTab(string $label, ItemInterface ...$items): self
    {
        $key = 'tab_' . md5($this->data['id']) . '_' . md5($label);
        $this->data['tabs'][] = [
            'key' => $key,
            'label' => $label,
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
<ul class="nav {$class}" role="tablist">
    {foreach $tabs as $key => $vo}
    <li class="nav-item" role="presentation">
        <a class="nav-link {if !$key}active{/if}" id="tab_{$vo.key}" data-toggle="tab" href="#{$vo.key}" role="tab" aria-controls="{$vo.key}" aria-selected="{if !$key}true{else}false{/if}">{$vo.label}</a>
    </li>
    {/foreach}
</ul>
<div class="tab-content">
    {foreach $tabs as $key => $vo}
    <div class="tab-pane fade py-2 {if !$key}show active{/if}" id="{$vo.key}" role="tabpanel" aria-labelledby="{$vo.key}-tab">{echo $vo['items']}</div>
    {/foreach}
</div>
str;
    }

    public function __toString()
    {
        return (new Template())->renderFromString($this->getTpl(), $this->data);
    }
}
