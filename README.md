# Product Packaging Selector

A Laravel app to determine boxes that the inputted products can fit into.

## Installation
1. On your terminal/Powershell, clone this repository with: `https://github.com/dnextreme88/product-packaging-selector`.
2. Start the Apache and MySQL services on XAMPP/Laragon. Usually, the default settings work fine so changing the port is not necessary.
3. Install the Composer packages with `composer i`.
4. Install the Node packages with `npm i`.
5. Add a separate terminal. In the 2nd terminal, run your local PHP server with `php artisan serve`. Run migrations and seeders with `php artisan migrate:fresh --seed`.
6. Add another separate terminal (3rd). In the 3rd terminal, run Vite with `npm run dev`.
7. Visit `127.0.0.1:8000/` and start filling up values in the form.

## Testing
All tests use Laravel Pest. Simply add another terminal and on that terminal, run the tests with `php artisan test`.

## Validation
1. All 5 inputs have the following validations defined: `required`, `numeric`, `gt:0`. They will first be passed as strings before being validated.
2. Values such as `0`, `-1`, `asf`, and `123s` will fail. Each input from the containers must also not be empty, otherwise they will fail.
3. Values greater than 0 will pass as long as they are positive numbers.
4. Values for length, width, height, and weight are constrained up to a maximum number, since it won't make sense to have them larger than the dimensions of the largest box (they just won't fit). They are 60, 55, 50, and 50, respectively.

## Process
1. The form will have an initial product container that can be added until 10 products are on the screen. You cannot add more than 10 products at a given time and the button to add them will disappear once 10 products are reached.
2. If there are 2 or more products, they can be deleted by pressing the X button on the far right side of the product container.
3. Once values are provided, user can press the blue button labeled "Determine Box" to validate the inputs and determine if there is a box that can fit the dimensions of the products. If there is none, there will be a red text displayed. If there is, the system will provide the details of the box on which all the products can fit into.
4. The total volume of the products are first considered. If there are products that are taking too much volume, they will be transferred to another box. Otherwise, the remaining products will be retained in smaller boxes.

## Acknowledgement

Special thanks to the people behind MachShip for providing me the opportunity to do a coding challenge like this.
