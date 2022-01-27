<?php

namespace Label84\TagManager;

use Illuminate\Support\Fluent;
use Illuminate\Support\Str;

class TagManagerItem extends Fluent
{
    public function __construct(string $id, string $name, float $price, float $quantity = 1, array $variables = [])
    {
        $this->attributes['item_id'] = $id;
        $this->attributes['item_name'] = $name;
        $this->attributes['price'] = $price;
        $this->attributes['quantity'] = $quantity;

        foreach ($variables as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }

    public function __call($method, $parameters)
    {
        $this->attributes[Str::snake($method, '_')] = count($parameters) > 0 ? $parameters[0] : true;

        return $this;
    }

    public function getTotal(int $decimals = 2, ?string $decimalSeparator = '.', ?string $thousandsSeparator = ','): string
    {
        return number_format($this->attributes['price'] * $this->attributes['quantity'], $decimals, $decimalSeparator, $thousandsSeparator);
    }
}
