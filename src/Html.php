<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder;

class Html implements ItemInterface
{
    public function __construct(string $html = '')
    {
        $this->html = $html;
    }
    public function __toString()
    {
        return $this->html;
    }
}
