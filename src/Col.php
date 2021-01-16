<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder;

class Col implements ColInterface
{
    protected $items = [];

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function addItem(ItemInterface ...$items): ColInterface
    {
        foreach ($items as $item) {
            $this->items[] = $item;
        }
        return $this;
    }

    public function addRow(RowInterface ...$items): ColInterface
    {
        foreach ($items as $item) {
            $this->items[] = $item;
        }
        return $this;
    }

    public function __toString()
    {
        $html = '<div class="' . $this->class . '">';
        foreach ($this->items as $value) {
            $html .= $value;
        }
        $html .= '</div>';
        return $html;
    }
}
