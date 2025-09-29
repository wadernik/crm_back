<?php

declare(strict_types=1);

namespace App\Managers\OrderProduct;

use App\Models\Dictionary\Dictionary;

interface ProductDeleterInterface
{
    public function delete(Dictionary $product): Dictionary;
}
