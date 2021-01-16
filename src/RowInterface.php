<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder;

interface RowInterface extends ItemInterface
{
    public function addCol(ColInterface ...$cols): RowInterface;
}
