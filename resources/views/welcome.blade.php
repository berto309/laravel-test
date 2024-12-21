<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Products</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            
        @endif
        <style>
            [x-cloak] > * {
                display: none;
            }
        </style>
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <main x-data="products" class="container" style="margin-top:50px; display: flex; flex-direction:column; justify-content:center; align-items:center" x-cloak>
            <div class="flex justify-end" style="width: 100%">
                <button type="button" @click="show = true" class="btn btn-primary mb-1">Show Product Form</button>
               
            </div>
            <form @submit.prevent="createProduct" x-show="show" class="row g-3">
                <div class="col-md-6">
                  <label for="name" class="form-label">Product name</label>
                  <input type="text" x-model="form.product_name" class="form-control" id="name" required>
                </div>
                <div class="col-md-6">
                  <label for="price" class="form-label">Price</label>
                  <input type="number" x-model="form.price" class="form-control" id="price" required>
                </div>
                <div class="col-12">
                  <label for="stock" class="form-label">Stock</label>
                  <input type="number" x-model="form.stock" min="1" class="form-control" required id="stock" placeholder="Enter stock">
                </div>
                <div class="col-12">
                   <button  type="button" @click="show = false" class="btn btn-primary mb-1">Hide Product form</button>
                  <button type="submit" class="btn btn-primary"><span>Create Product Form</span></button>
                </div>
              </form>
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Product name</th>
                    <th scope="col">Quantity in stock</th>
                    <th scope="col"> Price per item</th>
                    <th scope="col"> Datetime submitted</th>
                    <th scope="col"> Total value</th>
                  </tr>
                </thead>
                <tbody>
                  <template x-for="pr,i in products" key="i">
                    <tr>
                        <th scope="row" x-text="pr.product_name"></th>
                        <td x-text="pr.stock"></td>
                        <td x-text="pr.price"></td>
                        <td x-text="pr.created_at"></td>
                        <td x-text="pr.total_value"></td>
                      </tr>
                  </template>
                  <tr>
                    <th></th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td x-text="grandTotal"></td>
                  </tr>
                </tbody>
              </table>
        </main>
        <script>
            // Using alpine js and Fetch API to make POST request for creating a post
            document.addEventListener('alpine:init', () => {
            Alpine.data('products', () => ({
        
                products: [],
                loading: false,
                show: false,
                form: {
                    product_name: null,
                    price: null,
                    stock: null
                },
                init() {

                  this.getProducts()

                },

                async getProducts() {

                    fetch('http://laravel-test.test/api/products')
                    .then(response => response.json())
                    .then(data => {
                        let prdts = data.products;

                        this.products = prdts.reverse()
                      
                                        
                    })
                    .catch(error => {
                        console('Error: ' + error)
                    });
                },

                get grandTotal() {
                    let prdts = this.products
                    let sum = 0
                    for (let i = 0; i < prdts.length; i++) {
                       sum += parseFloat(prdts[i].total_value)
                    }
                    return sum.toFixed(2);
                },
              
                async createProduct () {
                        this.loading = true;
                        let data = this.form

                        try {
                        const response = await fetch('http://laravel-test.test/api/products', {
                            method: 'POST',  // using post method
                            headers: {
                                'Content-Type' : 'application/json', // setting header as json format
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content // csrf token check
                            },
                            body: JSON.stringify(data)
                        });
        
                        if (response.ok) {
                            
                             this.form.product_name = null
                             this.form.stock = null
                             this.form.price  = null

                             this.getProducts()
        
                             alert('Product created!')
                        }
                    
                    } catch(error) {
                        alert('Error:' + error.message);
                    } 
                }

            
            }))
        })
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

        

    </body>
</html>
