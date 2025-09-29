<?php

declare(strict_types=1);

namespace App\Http\Responses\OrderProduct;

use App\Models\Dictionary\Dictionary;
use Illuminate\Contracts\Support\Arrayable;

final class OrderProductResponse implements Arrayable
{
    public function __construct(private readonly Dictionary $item)
    {
    }

    /**
     * @return array<string>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->item->id,
            'name' => $this->item->value,
            'created_at' => $this->item->created_at,
            'updated_at' => $this->item->updated_at,
        ];
    }
}
