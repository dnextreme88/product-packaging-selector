<?php

namespace App\Livewire;

use App\Models\BoxConstraint;
use Livewire\Component;

class AddProductsToBox extends Component
{
    public $box_constraints;
    public bool $is_determined = false;

    public int $current_product_count = 1;
    public int $product_limit = 10;
    public array $products = [];
    public $minimum_box_constraint;
    public array $largest_products_list = [];
    public array $products_in_packed_box = [];
    public array $products_transferred_to_other_boxes = [];
    public array $products_with_errors = [];

    public float $length_total = 0.0;
    public float $width_total = 0.0;
    public float $height_total = 0.0;
    public float $volume_total = 0.0;
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
        $this->volume_total = 0;
        $this->weight_limit_total = 0;
        $this->minimum_box_constraint = [];
        $this->largest_products_list = [];
        $this->products_in_packed_box = [];
        $this->products_transferred_to_other_boxes = [];
        $this->products_with_errors = [];

        $this->validate([
            'products' => ['required', 'array', 'min:1'],
            'products.*.length' => ['required', 'numeric', 'lte:60', 'gt:0'],
            'products.*.width' => ['required', 'numeric', 'lte:55', 'gt:0'],
            'products.*.height' => ['required', 'numeric', 'lte:50', 'gt:0'],
            'products.*.weight_limit' => ['required', 'numeric', 'lte:50', 'gt:0'],
            'products.*.quantity' => ['required', 'numeric', 'gt:0'],
        ],
        [
            'products.*.length.required' => 'The length field is required',
            'products.*.length.numeric' => 'The length field must be numeric',
            'products.*.length.lte' => 'The length field must be lesser than or equal to 60',
            'products.*.length.gt' => 'The length field must be greater than 0',
            'products.*.width.required' => 'The width field is required',
            'products.*.width.numeric' => 'The width field must be numeric',
            'products.*.width.lte' => 'The width field must be lesser than or equal to 55',
            'products.*.width.gt' => 'The width field must be greater than 0',
            'products.*.height.required' => 'The height field is required',
            'products.*.height.numeric' => 'The height field must be numeric',
            'products.*.height.lte' => 'The height field must be lesser than or equal to 50',
            'products.*.height.gt' => 'The height field must be greater than 0',
            'products.*.weight_limit.required' => 'The weight limit field is required',
            'products.*.weight_limit.numeric' => 'The weight limit field must be numeric',
            'products.*.weight_limit.lte' => 'The weight limit field must be lesser than or equal to 50',
            'products.*.weight_limit.gt' => 'The weight limit field must be greater than 0',
            'products.*.quantity.required' => 'The quantity field is required',
            'products.*.quantity.numeric' => 'The quantity field must be numeric',
            'products.*.quantity.gt' => 'The quantity field must be greater than 0',
        ]);

        foreach ($this->products as $key => $product) {
            $this->products[$key]['volume'] = ($product['length'] * $product['width'] * $product['height']) * $product['quantity'];
            $this->products[$key]['product_number'] = $key + 1;

            $this->length_total += $product['length'];
            $this->width_total += $product['width'];
            $this->height_total += $product['height'];
            $this->volume_total += $this->products[$key]['volume'];
            $this->weight_limit_total += $product['weight_limit'];
        }

        $this->minimum_box_constraint = BoxConstraint::where('volume', '>=', $this->volume_total)
            ->orderBy('volume', 'ASC')
            ->first();

        $this->products_in_packed_box = array_values($this->products);

        if ($this->minimum_box_constraint) {
            $box = $this->minimum_box_constraint;

            // Check if some of the products are larger than the dimensions of this box
            $this->largest_products_list = array_values(array_filter($this->products, function ($product) use ($box) {
                return $product['length'] > $box['length'] ||
                    $product['width'] > $box['width'] ||
                    $product['height'] > $box['height'] ||
                    $product['weight_limit'] > $box['weight_limit'];
            }));

            if ($this->largest_products_list) {
                foreach ($this->largest_products_list as $large_product) {
                    $this->products_in_packed_box = array_filter($this->products_in_packed_box, function ($product) use ($large_product) {
                        return $product['product_number'] != $large_product['product_number'];
                    });

                    $another_box = BoxConstraint::where('length', '>=', $large_product['length'])
                        ->where('width', '>=', $large_product['width'])
                        ->where('height', '>=', $large_product['height'])
                        ->where('volume', '>=', $large_product['volume'])
                        ->where('weight_limit', '>=', $large_product['weight_limit'])
                        ->orderBy('length', 'ASC')
                        ->orderBy('width', 'ASC')
                        ->orderBy('height', 'ASC')
                        ->orderBy('weight_limit', 'ASC')
                        ->first();

                    if ($another_box) {
                        $large_product['box'] = $another_box;
                        $large_product['message'] = 'Product ' .$large_product['product_number']. ' does not fit in ' .$this->minimum_box_constraint['name']. ' and will fit in the box with the following details:';
                        $this->products_transferred_to_other_boxes[] = $large_product;
                    } else {
                        $this->products_with_errors[] = $large_product;
                    }
                }
            }
        } else {
            $largest_box = BoxConstraint::latest('volume')->first();

            if ($largest_box['volume'] > 165000) {
                $this->largest_products_list = array_values(array_filter($this->products, function ($product) use ($largest_box) {
                    return $product['volume'] >= $largest_box['volume'];
                }));
            } else {
                $this->largest_products_list = array_values(array_filter($this->products, function ($product) {
                    return $product['volume'] >= collect($this->products)->pluck('volume')->max();
                }));
            }

            if ($this->largest_products_list) {
                foreach ($this->largest_products_list as $product) {
                    $this->volume_total -= $product['volume'];

                    // Find a box based per product exceeding the largest available box
                    $new_box = BoxConstraint::where('length', '>=', $product['length'])
                        ->where('width', '>=', $product['width'])
                        ->where('height', '>=', $product['height'])
                        ->where('volume', '>=', $product['volume'])
                        ->where('weight_limit', '>=', $product['weight_limit'])
                        ->orderBy('length', 'ASC')
                        ->orderBy('width', 'ASC')
                        ->orderBy('height', 'ASC')
                        ->orderBy('weight_limit', 'ASC')
                        ->first();

                    if ($new_box) {
                        $this->largest_products_list = array_values(array_filter($this->products, function ($packed_product) use ($product) {
                            return $packed_product['volume'] == $product['volume'];
                        }));

                        $product['box'] = $new_box;
                        $product['message'] = 'Product ' .$product['product_number']. ' will fit in the box with the following details:';
                        $this->products_transferred_to_other_boxes[] = $product;
                    } else {
                        $this->products_with_errors[] = $product;
                    }

                    $this->products_in_packed_box = array_filter($this->products, function ($packed_product) use ($product) {
                        return $packed_product['volume'] != $product['volume'];
                    });
                }

                $this->minimum_box_constraint = BoxConstraint::where('volume', '>=', $this->volume_total)
                    ->orderBy('volume', 'ASC')
                    ->first();
            }
        }

        $this->products_in_packed_box = array_values($this->products_in_packed_box);
        $this->products_transferred_to_other_boxes = array_values($this->products_transferred_to_other_boxes);
        $this->products_with_errors = array_values($this->products_with_errors);

        $this->is_determined = true;
    }

    public function render()
    {
        $this->box_constraints = BoxConstraint::all();

        return view('livewire.add-products-to-box');
    }
}
