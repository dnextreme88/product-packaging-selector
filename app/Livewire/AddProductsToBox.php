<?php

namespace App\Livewire;

use App\Models\BoxConstraint;
use Livewire\Component;

class AddProductsToBox extends Component
{
    public $box_constraints;
    public $minimum_box_constraint;
    public bool $is_determined = false;

    public int $current_product_count = 1;
    public int $product_limit = 10;
    public array $products = [];
    public array $results = [];

    public float $length_total = 0.0;
    public float $width_total = 0.0;
    public float $height_total = 0.0;
    public float $weight_limit_total = 0.0;

    public function add_product()
    {
        if ($this->current_product_count < $this->product_limit) {
            $this->current_product_count++;
        }
    }

    public function remove_product(int $product_num = null)
    {
        $this->current_product_count--;

        if ($this->products) {
            unset($this->products[$product_num]);

            $this->products = array_values($this->products);
        }
    }

    public function validate_products()
    {
        // Reset
        $this->length_total = 0;
        $this->width_total = 0;
        $this->height_total = 0;
        $this->weight_limit_total = 0;

        $this->validate([
            'products' => ['required', 'array', 'min:1'],
            'products.*.length' => ['required', 'numeric', 'gt:0'],
            'products.*.width' => ['required', 'numeric', 'gt:0'],
            'products.*.height' => ['required', 'numeric', 'gt:0'],
            'products.*.weight_limit' => ['required', 'numeric', 'gt:0'],
            'products.*.quantity' => ['required', 'numeric', 'gt:0'],
        ],
        [
            'products.*.length.required' => 'The length field is required',
            'products.*.length.numeric' => 'The length field must be numeric',
            'products.*.length.gt' => 'The length field must be greater than 0',
            'products.*.width.required' => 'The width field is required',
            'products.*.width.numeric' => 'The width field must be numeric',
            'products.*.width.gt' => 'The width field must be greater than 0',
            'products.*.height.required' => 'The height field is required',
            'products.*.height.numeric' => 'The height field must be numeric',
            'products.*.height.gt' => 'The height field must be greater than 0',
            'products.*.weight_limit.required' => 'The weight limit field is required',
            'products.*.weight_limit.numeric' => 'The weight limit field must be numeric',
            'products.*.weight_limit.gt' => 'The weight limit field must be greater than 0',
            'products.*.quantity.required' => 'The quantity field is required',
            'products.*.quantity.numeric' => 'The quantity field must be numeric',
            'products.*.quantity.gt' => 'The quantity field must be greater than 0',
        ]);

        foreach ($this->products as $product) {
            $this->length_total += $product['quantity'] * $product['length'];
            $this->width_total += $product['quantity'] * $product['width'];
            $this->height_total += $product['quantity'] * $product['height'];
            $this->weight_limit_total += $product['quantity'] * $product['weight_limit'];
        }

        $this->minimum_box_constraint = BoxConstraint::where('length', '>=', $this->length_total)
            ->where('width', '>=', $this->width_total)
            ->where('height', '>=', $this->height_total)
            ->where('weight_limit', '>=', $this->weight_limit_total)
            ->first();

        $this->is_determined = true;
    }

    public function render()
    {
        $this->box_constraints = BoxConstraint::all();

        return view('livewire.add-products-to-box');
    }
}
