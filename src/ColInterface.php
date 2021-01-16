<?php

declare(strict_types=1);

namespace Ebcms\FormBuilder;

interface ColInterface extends ItemInterface
{
    public function addItem(ItemInterface ...$items): ColInterface;
    public function addRow(RowInterface ...$rows): ColInterface;
}
