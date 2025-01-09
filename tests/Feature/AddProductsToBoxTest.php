<?php

use App\Livewire\AddProductsToBox;
use Livewire\Livewire;

test('product has invalid length', function () {
    Livewire::test(AddProductsToBox::class)
        ->set('products', [
            [
                'length' => '-1',
                'width' => '2',
                'height' => '3',
                'weight_limit' => '4',
                'quantity' => '5'
            ]
        ])
        ->call('validate_products')
        ->assertHasErrors('products.*.length');
});

test('product has invalid width', function () {
    Livewire::test(AddProductsToBox::class)
        ->set('products', [
            [
                'length' => '1',
                'width' => '-2',
                'height' => '3',
                'weight_limit' => '4',
                'quantity' => '5'
            ]
        ])
        ->call('validate_products')
        ->assertHasErrors('products.*.width');
});

test('product has invalid height', function () {
    Livewire::test(AddProductsToBox::class)
        ->set('products', [
            [
                'length' => '1',
                'width' => '2',
                'height' => '-3',
                'weight_limit' => '4',
                'quantity' => '5'
            ]
        ])
        ->call('validate_products')
        ->assertHasErrors('products.*.height');
});

test('product has invalid weight limit', function () {
    Livewire::test(AddProductsToBox::class)
        ->set('products', [
            [
                'length' => '1',
                'width' => '2',
                'height' => '3',
                'weight_limit' => '-4',
                'quantity' => '5'
            ]
        ])
        ->call('validate_products')
        ->assertHasErrors('products.*.weight_limit');
});

test('product has invalid quantity', function () {
    Livewire::test(AddProductsToBox::class)
        ->set('products', [
            [
                'length' => '1',
                'width' => '2',
                'height' => '3',
                'weight_limit' => '4',
                'quantity' => '-5'
            ]
        ])
        ->call('validate_products')
        ->assertHasErrors('products.*.quantity');
});

test('product has valid length', function () {
    Livewire::test(AddProductsToBox::class)
        ->set('products', [
            [
                'length' => '111',
                'width' => '2',
                'height' => '3',
                'weight_limit' => '4',
                'quantity' => '5'
            ]
        ])
        ->call('validate_products')
        ->assertSuccessful('products.*.length');
});

test('product has valid width', function () {
    Livewire::test(AddProductsToBox::class)
        ->set('products', [
            [
                'length' => '1',
                'width' => '222',
                'height' => '3',
                'weight_limit' => '4',
                'quantity' => '5'
            ]
        ])
        ->call('validate_products')
        ->assertSuccessful('products.*.width');
});

test('product has valid height', function () {
    Livewire::test(AddProductsToBox::class)
        ->set('products', [
            [
                'length' => '1',
                'width' => '2',
                'height' => '333',
                'weight_limit' => '4',
                'quantity' => '5'
            ]
        ])
        ->call('validate_products')
        ->assertSuccessful('products.*.height');
});

test('product has valid weight limit', function () {
    Livewire::test(AddProductsToBox::class)
        ->set('products', [
            [
                'length' => '1',
                'width' => '2',
                'height' => '3',
                'weight_limit' => '444',
                'quantity' => '5'
            ]
        ])
        ->call('validate_products')
        ->assertSuccessful('products.*.weight_limit');
});

test('product has valid quantity', function () {
    Livewire::test(AddProductsToBox::class)
        ->set('products', [
            [
                'length' => '1',
                'width' => '2',
                'height' => '3',
                'weight_limit' => '4',
                'quantity' => '555'
            ]
        ])
        ->call('validate_products')
        ->assertSuccessful('products.*.quantity');
});

test('can add products', function () {
    Livewire::test(AddProductsToBox::class)
        ->set('current_product_count', 2)
        ->assertSee('Add More Products');
});

test('cannot add products', function () {
    Livewire::test(AddProductsToBox::class)
        ->set('current_product_count', 10)
        ->assertDontSee('Add More Products');
});

test('products can fit to a valid box', function () {
    Livewire::test(AddProductsToBox::class)
        ->set('products', [
            [
                'length' => '5',
                'width' => '3',
                'height' => '2',
                'weight_limit' => '2',
                'quantity' => '2'
            ],
            [
                'length' => '1',
                'width' => '2',
                'height' => '3',
                'weight_limit' => '1',
                'quantity' => '2'
            ],
        ])
        ->call('validate_products')
        ->assertSee('Results');
});

test('products cannot all fit to 1 box', function () {
    Livewire::test(AddProductsToBox::class)
        ->set('products', [
            [
                'length' => '30',
                'width' => '30',
                'height' => '30',
                'weight_limit' => '10',
                'quantity' => '5'
            ],
            [
                'length' => '20',
                'width' => '21',
                'height' => '3',
                'weight_limit' => '4',
                'quantity' => '5'
            ],
            [
                'length' => '60',
                'width' => '50',
                'height' => '25',
                'weight_limit' => '40',
                'quantity' => '5'
            ],
        ])
        ->call('validate_products')
        ->assertSee('will fit in the box with the following details')
        ->assertSee('do not fit in any box!');
});

test('products cannot all fit to 1 box so largest products will be separated', function () {
    Livewire::test(AddProductsToBox::class)
        ->set('products', [
            [
                'length' => '30',
                'width' => '30',
                'height' => '30',
                'weight_limit' => '10',
                'quantity' => '5'
            ],
            [
                'length' => '20',
                'width' => '21',
                'height' => '3',
                'weight_limit' => '4',
                'quantity' => '5'
            ],
            [
                'length' => '31',
                'width' => '31',
                'height' => '31',
                'weight_limit' => '40',
                'quantity' => '5'
            ],
        ])
        ->call('validate_products')
        ->assertSee('Product 3 will fit in the box with the following details');
});

test('product is larger than the largest box by volume', function () {
    Livewire::test(AddProductsToBox::class)
        ->set('products', [
            [
                'length' => '50',
                'width' => '50',
                'height' => '25',
                'weight_limit' => '40',
                'quantity' => '5'
            ],
        ])
        ->call('validate_products')
        ->assertSee('Largest Products');
});
