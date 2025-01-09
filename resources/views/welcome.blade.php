<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans antialiased">
        <div class="w-[90%] mx-auto bg-gray-50">
            <header>
                <h1 class="pt-4 text-4xl text-gray-600">Product Packaging Selector</h1>

                <p class="mt-4 text-xl">Welcome to a simple form to determine the smallest box to fit your products in! Enter the dimensions for your products and hit the button below to determine the smallest possible box your products can fit in. You can add up to 10 products.</p>
            </header>

            <main class="mt-6">
                <livewire:AddProductsToBox />
            </main>

            <footer class="py-16 text-sm text-center text-black dark:text-white/70">Copyright &copy; 2025</footer>
        </div>
    </body>
</html>
