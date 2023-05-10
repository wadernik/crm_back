<?php

namespace App\Models\Order;

use BeyondCode\Comments\Traits\HasComments;
use Parental\HasParent;

class Order extends BaseOrder
{
    use HasParent;
    use HasComments;
}