<?php

namespace App\Models;

use BeyondCode\Comments\Traits\HasComments;
use Parental\HasParent;

class Order extends BaseOrder
{
    use HasParent;
    use HasComments;
}
