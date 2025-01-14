<!DOCTYPE html>
<html lang="en">
<head>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center text-uppercase">Product</h2>

       
        <button class="btn btn-primary" id="addProductBtn">
            <i class="bi bi-plus-square-fill"></i> Add Product
        </button>
     

        <div class="table-responsive">
        <table class="table table-sm table-hover table-striped">
            <thead>
                <tr>
                <th>Sl.No</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="productTable">
                
            </tbody>
        </table>
        </div>
    </div>

    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center text-uppercase" id="productModalLabel" >Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="productForm">
                        <input type="hidden" id="productId">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" class="form-control" required>
                             <div class="invalid-feedback" id="name-error"></div>
                         
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" id="price" class="form-control" required>
                            <div class="invalid-feedback" id="price-error"></div>
                        </div>
                       

                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <div class="mb-3"  id="currentImageWrapper" style="display: none;">
  
                            <img id="currentImage" src="" alt="Product Image" style="max-width: 100px; height: auto;">
                            </div>
                            <input type="file" id="image" class="form-control">
                            <div class="invalid-feedback" id="image-error"></div>
                        </div>
                  
                        <button type="button" class="btn btn-primary" id="saveProduct">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-oT4E2oXGK3gG6QFVV2r/ZWNCXor/6yW5/jPPUjCqk7O7fUwOBG/bWhOTKw1+NzOj" crossorigin="anonymous"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>/ -->
         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
           $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

               const loadProducts = () => {
                $.get('/getproductsdetails', (data) => {
                    let rows = '';
                    data.forEach((product,index) => {
                        rows += `
                            <tr>
                              <td>${index + 1}</td>
                                <td>${product.prd_name}</td>
                                <td>${product.prd_description}</td>
                                 <td>${product.prd_price}</td>
                                 <td><img src="${product.prd_image}" alt="${product.prd_name}" width="50" height="50"></td>
                               <td class="d-flex flex-column flex-md-row gap-2" style="padding: 11px">
                                        <button class="btn btn-primary editProduct" data-id="${product.prd_id}">
                                            <i class="bi bi-pencil-square"></i>Edit
                                        </button>
                                        <button class="btn btn-danger deleteProduct" data-id="${product.prd_id}">
                                            <i class="bi bi-trash-fill"></i>Delete
                                        </button>
                            </td>

                            </tr>`;
                    });
                    $('#productTable').html(rows);
                });
            };

            $('#addProductBtn').click(() => {
                $('#productModalLabel').text('Add Product');
                $('#productForm')[0].reset();
                $('#currentImage').attr('src',''); 
                $('#currentImageWrapper').hide();
                $('#productId').val('');
                $('#productModal').modal('show');
            });

                $('#saveProduct').click(() => {
            
                const formData = new FormData();
                
            
                formData.append('name', $('#name').val());
               
                formData.append('description', $('#description').val());
                formData.append('price', $('#price').val());

                
                if ($('#image')[0].files.length > 0) {
                    formData.append('image', $('#image')[0].files[0]);
                }

 
                
            

            
                const id = $('#productId').val();
              
                if( id){
                    formData.append('prd_id', $('#productId').val());
                }
                
            
                const url = id ? `/products/update` : '/addproducts';
                const method = id ? 'POST' : 'POST';

            
                $.ajax({
                    url,
                    type: method,
                    data: formData,
                    processData: false, 
                    contentType: false, 
                    success: () => {
                        
                        $('#productModal').modal('hide');
                        loadProducts();
                    },
                    error: function (xhr) {
                    
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            console.error('Validation errors:', errors);
                            displayValidationErrors(errors);
                        } else {
                            
                            console.error('Unexpected error:', xhr.responseText);
                        }
                    }
                });
            });

            function displayValidationErrors(errors) {
                
                $('.invalid-feedback').text('');
                $('input, textarea').removeClass('is-invalid');

                $.each(errors, function (field, messages) {
                    const fieldName = `#${field}`;
                    $(fieldName).addClass('is-invalid');  
                    const errorMessage = messages.join('<br>'); 
                    $(`#${field}-error`).html(errorMessage); 
                });
            }






            $(document).on('click', '.editProduct', function () {
                console.log('hiiiiii');
                console.log($(this).data);
                
                const id = $(this).data('id');
                console.log('Edit product with ID:', id);
                $.get(`/products/edit/${id}`, (data) => {
                    $('#productId').val(data.prd_id);
                    $('#name').val(data.prd_name);
                    $('#description').val(data.prd_description);
                    $('#price').val(data.prd_price);
                    if (data.prd_image) {
                    $('#currentImage').attr('src', data.prd_image); 
                    
                    $('#currentImageWrapper').show(); 
                    } else {
                    $('#currentImageWrapper').hide(); 
                    }
                    $('#productModalLabel').text('Edit Product');
                    $('#productModal').modal('show');
                });
            });




            $(document).on('click', '.deleteProduct', function () {
                const id = $(this).data('id');
                const confirmed = window.confirm('Are you sure you want to delete this product?');

                if (confirmed) {
            $.ajax({
                url: `/products/delete/${id}`, 
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: () => {
                    loadProducts();
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting product:', error);
                }
            });
        }
        });

                    loadProducts();
                });
    </script>
</body>
</html>

