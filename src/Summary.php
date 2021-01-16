<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder;

class Summary extends Col implements ItemInterface
{

    private $title = '';
    private $class = '';

    public function __construct(string $title = 'Summary', string $class = 'text-primary mb-2')
    {
        $this->title = $title;
        $this->class = $class;
    }

    public function __toString()
    {
        $html = '<details>';
        $html .= '<summary class="' . $this->class . '">' . $this->title . '</summary>';
        foreach ($this->items as $value) {
            $html .= $value;
        }
        $html .= '</details>';
        return $html;
    }
}
