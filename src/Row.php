<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder;

class Row implements RowInterface
{
    protected $items = [];

    public function __construct(string $class = 'row')
    {
        $this->class = $class;
    }

    public function addCol(ColInterface ...$items): RowInterface
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
