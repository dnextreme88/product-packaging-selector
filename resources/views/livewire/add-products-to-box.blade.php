<div class="space-y-4">
    <div>
        <p>Product count: {{ $current_product_count }}</p>

        @if ($current_product_count < $product_limit)
            <button wire:click="add_product" class="flex items-center gap-2 px-4 py-2 text-gray-600 transition duration-150 bg-green-700 hover:bg-green-500 hover:text-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-white size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>

                <span class="text-white">Add More Products</span>
            </button>
        @endif
    </div>

    <form wire:submit="validate_products" class="space-y-4">
        @for ($i = 0; $i < $current_product_count; $i++)
            <div class="px-6 py-4 border border-gray-400 rounded-md">
                <div class="flex justify-between">
                    <h2 class="mb-5 text-2xl text-gray-700">Product #{{ ($i + 1) }}</h2>

                    @if ($current_product_count > 1)
                        <span wire:click="remove_product({{ $i }})" class="text-2xl hover:cursor-pointer">&times;</span>
                    @endif
                </div>

                <div class="grid grid-cols-1 gap-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <div>
                        <label class="block font-sans text-lg text-slate-700 dark:text-slate-300">Length <span class="text-sm">(in cm)</span></label>
                        <input type="text" class="p-2 bg-gray-300" wire:model="products.{{ $i }}.length" />

                        @error ('products.' .$i. '.length')
                            <p class="my-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-sans text-lg text-slate-700 dark:text-slate-300">Width <span class="text-sm">(in cm)</span></label>
                        <input type="text" class="p-2 bg-gray-300" wire:model="products.{{ $i }}.width" />

                        @error ('products.' .$i. '.width')
                            <p class="my-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-sans text-lg text-slate-700 dark:text-slate-300">Height <span class="text-sm">(in cm)</span></label>
                        <input type="text" class="p-2 bg-gray-300" wire:model="products.{{ $i }}.height" />

                        @error ('products.' .$i. '.height')
                            <p class="my-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-sans text-lg text-slate-700 dark:text-slate-300">Weight <span class="text-sm">(in kg)</span></label>
                        <input type="text" class="p-2 bg-gray-300" wire:model="products.{{ $i }}.weight_limit" />

                        @error ('products.' .$i. '.weight_limit')
                            <p class="my-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-sans text-lg text-slate-700 dark:text-slate-300">Quantity</label>
                        <input type="text" class="p-2 bg-gray-300" wire:model="products.{{ $i }}.quantity" />
                        <small class="block text-sm text-gray-500">This will be multiplied to the other 4 values</small>

                        @error ('products.' .$i. '.quantity')
                            <p class="my-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        @endfor

        <button type="submit" class="px-4 py-2 text-gray-100 transition duration-150 bg-blue-500 hover:bg-blue-700">Determine Box</button>
    </form>

    <div>
        @if (!$is_determined)
            <p>Input the dimensions of your products above and hit the blue button to determine the smallest possible box that will fit these products.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div>
                    <h2 class="text-2xl">Total Dimensions of Products</h2>

                    <ul>
                        <li class="my-2">Total Length: {{ number_format($length_total) }} cm</li>
                        <li class="my-2">Total Width: {{ number_format($width_total) }} cm</li>
                        <li class="my-2">Total Height: {{ number_format($height_total) }} cm</li>
                        <li class="my-2">Total Volume: {{ number_format($volume_total) }} cm</li>
                        <li class="my-2">Total Weight: {{ number_format($weight_limit_total) }} kg</li>
                    </ul>
                </div>

                @if ($largest_products_list)
                    <div>
                        <h2 class="text-2xl">Largest Products</h2>

                        <ul>
                            @foreach ($largest_products_list as $large_product)
                                <li class="my-2 font-bold">Product # {{ $large_product['product_number'] }}</li>
                                <li class="my-2">Length: {{ number_format($large_product['length']) }} cm</li>
                                <li class="my-2">Width: {{ number_format($large_product['width']) }} cm</li>
                                <li class="my-2">Height: {{ number_format($large_product['height']) }} cm</li>
                                <li class="my-2">Volume: {{ number_format($large_product['volume']) }} cm</li>
                                <li class="my-2">Weight: {{ number_format($large_product['weight_limit']) }} kg</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <h2 class="text-2xl">Results</h2>

            @if ($products_in_packed_box)
                <div>
                    <p>{{ count($products_in_packed_box) == 1 ? 'Product' : 'Product #s' }} {{ implode(', ', array_column($products_in_packed_box, 'product_number')) }} will fit in the box with the following details:</p>

                    <ul>
                        <li>Name: {{ $minimum_box_constraint['name'] }}</li>
                        <li>Length: {{ number_format($minimum_box_constraint['length']) }} cm</li>
                        <li>Width: {{ number_format($minimum_box_constraint['width']) }} cm</li>
                        <li>Height: {{ number_format($minimum_box_constraint['height']) }} cm</li>
                        <li>Total Volume: {{ number_format($minimum_box_constraint['volume']) }} cm</li>
                        <li>Weight Limit: {{ number_format($minimum_box_constraint['weight_limit']) }} kg</li>
                    </ul>
                </div>
            @endif

            @if ($products_transferred_to_other_boxes)
                <div>
                    @foreach ($products_transferred_to_other_boxes as $product)
                        <p class="text-blue-500">{{ $product['message'] }}</p>

                        <ul>
                            <li>Name: {{ $product['box']['name'] }}</li>
                            <li>Length: {{ number_format($product['box']['length']) }} cm</li>
                            <li>Width: {{ number_format($product['box']['width']) }} cm</li>
                            <li>Height: {{ number_format($product['box']['height']) }} cm</li>
                            <li>Volume: {{ number_format($product['box']['volume']) }} cm</li>
                            <li>Weight Limit: {{ number_format($product['box']['weight_limit']) }} kg</li>
                        </ul>
                    @endforeach
                </div>
            @endif

            @if ($products_with_errors)
                <p class="text-red-500">{{ count($products_with_errors) == 1 ? 'Product' : 'Product #s' }} {{ implode(', ', array_column($products_with_errors, 'product_number')) }} do not fit in any box!</p>
            @endif
        @endif
    </div>
</div>
