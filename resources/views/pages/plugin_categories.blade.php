@extends('structures.main')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4">Manage Plugin Categories</h1>
    </div>
    <button type="button" id="addpc" class="btn btn-primary" data-bs-toggle="modal"
        data-bs-target="#Plugin_Categories_Modal">
        <i class="bi bi-plus-circle"></i> Add Plugin Category
    </button>

    <div class="modal fade" id="Plugin_Categories_Modal" tabindex="-1" aria-labelledby="Plugin_Categories_ModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <!-- Adjust margin-top to position higher -->
            <div class="modal-content border-0 rounded shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="Plugin_Categories_ModalLabel">Add New Plugin Category</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="pluginCategoryForm" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="categoryId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="name" name="name" required
                                placeholder="Enter category name" autocomplete="off">
                        </div>
                        <div id="categorySuggestions" class="suggestions-list" style="display: none;">
                            <ul class="list-group" id="categorySuggestionItems"></ul>
                        </div>
                        <!-- Add additional form fields if necessary -->
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="categorysave" class="btn btn-primary">Save Category</button>
                        <button type="button" id="editeform" class="btn btn-primary">Edite Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class=" mt-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Plugin Category List</h5>
                <table class="table" style="width: 100%" id="plugin_category">
                    <thead>
                        <tr>
                            <th>SR</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {

            $('#addpc').click(function(e) {
                $("#pluginCategoryForm")[0].reset();
                $('#editeform').hide();
                $('#categoryId').val('');
                $('#categorysave').show();
            });


            let table = new DataTable('#plugin_category', {
                processing: false,
                serverSide: false,
                ajax: {
                    url: '/plugin_categories/create',
                    type: 'GET',
                },
                columns: [{
                        data: null,
                        name: 'id',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '<button class="btn btn-danger delete-btn" data-id="' + row.id +
                                '"><i class="bi bi-trash"></i></button> <button class="btn btn-success edite-btn" data-id="' +
                                row.id +
                                '"><i class="bi bi-pen"></i></button>';

                        }
                    }
                ]
            });



            $('#pluginCategoryForm').on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: "{{ route('plugin_categories.store') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#Plugin_Categories_Modal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            text: "Categories Has been Save'd"
                        });
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        // Display error message
                        Swal.fire({
                            icon: 'error',
                            text: xhr.responseJSON.message || 'Something went wrong!',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000, // Set a timer (in milliseconds)
                            timerProgressBar: true
                        });
                    }
                });
            });



            $('#plugin_category').on('click', '.delete-btn', function() {
                var categoryId = $(this).data('id'); // Get the ID of the category to be deleted

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Make an AJAX request to delete the category
                        $.ajax({
                            url: '/plugin_categories/' +
                                categoryId,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your category has been deleted.',
                                    'success'
                                );
                                $('#plugin_category').DataTable().ajax
                                    .reload(); // Reload table after delete
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'There was an issue deleting the category.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });



            $('#plugin_category').on('click', '.edite-btn', function() {
                const editeid = $(this).data('id');

                $.ajax({
                    url: '/plugin_categories/' + editeid +
                        '/edit',
                    type: 'GET',
                    success: function(response) {

                        $('#pluginCategoryForm [name="id"]').val(response
                            .id);
                        $('#pluginCategoryForm [name="name"]').val(response.name);

                        $('#Plugin_Categories_Modal').modal('show');
                        $("#categorysave").hide();
                        $('#editeform').show();

                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            text: xhr.responseJSON.message ||
                                'Could not retrieve the category!',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    }
                });
            });

            $('#editeform').click(function(event) {
                event.preventDefault();
                let categoryId = $('#categoryId').val();
                let formData = $('#pluginCategoryForm').serialize();

                $.ajax({
                    url: '/plugin_categories/' + categoryId,
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        $('#Plugin_Categories_Modal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            text: "Categories Has been Save'd"
                        });
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Failed to update category!', 'error');
                    }
                });
            });

        });
    </script>


    <!-- Custom CSS -->
    <style>
        .modal-content {
            border-radius: 0.5rem;
            /* Rounded corners */
        }

        .modal-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            /* Gradient header */
        }

        .modal-title {
            font-size: 1.5rem;
            /* Larger title font size */
            font-weight: bold;
            /* Bold title */
        }

        .form-control {
            border: 2px solid #007bff;
            /* Border color */
            transition: border-color 0.3s;
            /* Smooth transition */
        }

        .form-control:focus {
            border-color: #0056b3;
            /* Focus border color */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            /* Focus shadow effect */
        }

        .btn-primary {
            background-color: #0056b3;
            /* Darker primary color */
            border-color: #0056b3;
            /* Border color */
        }

        .btn-primary:hover {
            background-color: #003d7a;
            /* Darker on hover */
        }

        .suggestions-list {
            position: absolute;
            z-index: 1000;
            background: white;
            border: 1px solid #ccc;
            max-height: 200px;
            overflow-y: auto;
            cursor: pointer;
        }

        .suggestions-list ul {
            padding: 0;
            /* Remove padding from the list */
            margin: 0;
            /* Remove margin from the list */
        }

        .list-group-item {
            padding: 10px;
            /* Add padding to each list item */
        }

        .list-group-item:hover {
            background: #007bff;
            /* Change background on hover */
            color: white;
            /* Change text color on hover */
        }
    </style>
    <script>
        $(document).ready(function() {
            const categories = [
                'Popular',
                'Security',
                'SEO',
                'Social Media',
                'Speed',
                'Forms',
                'Backups',
                'Page Builders',
                'Marketing',
                'eCommerce',
                'Translation',
                'Customer Service',
                'LMS',
            ];

            $('#name').on('keyup', function() {
                const query = $(this).val().toLowerCase();
                const $suggestions = $('#categorySuggestions');
                const $suggestionItems = $('#categorySuggestionItems');

                // Clear previous suggestions
                $suggestionItems.empty();

                // Show suggestions only if there's input
                if (query) {
                    const filteredCategories = categories.filter(category => category.toLowerCase()
                        .includes(query));

                    if (filteredCategories.length > 0) {
                        filteredCategories.forEach(category => {
                            const $li = $('<li class="list-group-item"></li>').text(category);
                            $li.on('click', function() {
                                $('#name').val(
                                    category); // Set input to selected suggestion
                                $suggestions.hide(); // Hide suggestions
                            });
                            $suggestionItems.append($li);
                        });
                        $suggestions.show(); // Show suggestions
                    } else {
                        $suggestions.hide(); // Hide suggestions if no matches
                    }
                } else {
                    $suggestions.hide(); // Hide suggestions if input is empty
                }
            });
        });
    </script>
@endsection
