@extends('structures.main')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4">Add Plan</h1>
    </div>

    <div id="paymentSettingMessage" class="text-center mt-4"></div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h2>Plans</h2>
                </div>

                <div class="modal fade" id="loaderModal" tabindex="-1" aria-labelledby="loaderModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center">
                            <div class="modal-body d-flex justify-content-center align-items-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div class="modal-body">
                                <p class="mt-2">Adding Plan. Please wait a moment.</p>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card-body">
                    <form action="{{ route('membership.plans.create') }}" method="POST" id="membershipPlanForm">
                        @csrf
                        <div class="row mt-3">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="plain_title" class="form-label">Plan Title</label>
                                <input type="text" class="form-control" id="plain_title" placeholder="Plan Name"
                                    name="plain_title" required>
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="plan_description" class="form-label">Plan Description</label>
                                <textarea class="form-control" id="plan_description" placeholder="Description" name="plan_description"></textarea>
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="plan_type" class="form-label">Plan Type</label>
                                <select class="form-control" name="plan_type" id="plan_type" required>
                                    <option value="">Select Period</option>
                                    <option value="month">Monthly</option>
                                    <option value="year">Yearly</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="plan_price" class="form-label">Plan Price</label>
                                <input type="number" class="form-control" id="plan_price" placeholder="Plan Amount"
                                    name="plan_price" required>
                            </div>

                            <div class="col-lg-8 col-md-6 col-sm-12">
                                <label for="plan_details" class="form-label">Plan Details</label>
                                <textarea class="form-control" id="plan_details" name="plan_details"></textarea>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <button class="btn btn-success search-btn me-4 px-4" type="submit">Submit</button>
                        </div>
                    </form>
                </div>


            </div>
        </div>
    </div>

    <div class="mt-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Plan's List</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" style="width: 100%;" id="plan_table">
                        <thead class="table-primary">
                            <tr>
                                <th>SR</th>
                                <th>Plain Title</th>
                                <th>Plan Description</th>
                                <th>Plan Type</th>
                                <th>Plan Price</th>

                                <th>Stripe Product id</th>

                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>




    <!-- Modal for Membership Plan Details -->
    <div class="modal fade" id="viewPlanModal" tabindex="-1" aria-labelledby="viewPlanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg rounded-3 border border-primary">
                <div class="modal-header" style="background: linear-gradient(90deg, #007bff, #6f42c1); color: white;">
                    <h5 class="modal-title" id="viewPlanModalLabel">Membership Plan Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-body text-center p-3">
                                    <h5 class="text-primary fw-bold"><i class="bi bi-file-earmark-text"></i> Title:</h5>
                                    <p class="card-text" id="modalPlanTitle"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-body text-center p-3">
                                    <h5 class="text-success fw-bold"><i class="bi bi-file-earmark-text"></i> Description:
                                    </h5>
                                    <p class="card-text" id="modalPlanDescription"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-warning">
                                <div class="card-body text-center p-3">
                                    <h5 class="text-warning fw-bold"> <i class="bi bi-calendar"></i> Type:</h5>
                                    <p class="card-text  fw-bold" id="modalPlanType"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-danger">
                                <div class="card-body text-center p-3">
                                    <h5 class="text-danger fw-bold"><i class="bi bi-cash-coin"></i> Price:</h5>
                                    <p class="card-text fw-bold" id="modalPlanPrice"></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-muted">
                                <div class="card-body text-center p-3">
                                    <h5 class="text-muted fw-bold"><i class="bi bi-calendar-event"></i> Date:</h5>
                                    <p class="card-text" id="modalPlanCreatedAt"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-dark">
                                <div class="card-body text-center p-3">
                                    <h5 class="text-dark fw-bold"><i class="bi bi-tag"></i> Stripe Product ID:</h5>
                                    <p class="card-text" id="modalPlanStripeProductId"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card border-info">
                                <div class="card-body  p-3">
                                    <h5 class="text-info fw-bold"><i class="bi bi-info-circle"></i> Details:</h5>
                                    <p class="card-text" id="modalPlanDetails"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background-color: #f8f9fa;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    {{-- <script src="https://cdn.tiny.cloud/1/qs368koxtecb2ft1mrbd9b3eumst007bkh4znhgsgemdyn2g/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script> --}}
    <script src="https://cdn.tiny.cloud/1/qs368koxtecb2ft1mrbd9b3eumst007bkh4znhgsgemdyn2g/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>

    <script>
        tinymce.init({
            selector: '#plan_details', // Replace this CSS selector to match the placeholder element for TinyMCE
            plugins: 'powerpaste advcode table lists checklist markdown',
            toolbar: 'undo redo | blocks| bold italic | bullist numlist checklist | code | table'
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            var plan_table = $('#plan_table').DataTable({
                ajax: {
                    url: "/get-membershipplans",
                    dataSrc: function(json) {
                        plansData = json.data;
                        return json.data;
                    }
                },
                lengthMenu: [
                    [20, 35, 70, 150, -1],
                    [20, 35, 70, 150, "All"]
                ],
                columns: [{
                        data: null,
                        render: (data, type, row, meta) => meta.row + 1
                    },
                    {
                        data: 'plain_title'
                    },
                    {
                        data: 'plan_description'
                    },
                    {
                        data: 'plan_type'
                    },
                    {
                        data: 'plan_price'
                    },

                    {
                        data: 'stripe_product_id'
                    },

                    {
                        data: null,
                        render: function(data) {
                            return `
                        <button class="btn btn-info view-btn"><i class="bi bi-eye"></i></button>
                        <button class="btn btn-danger delete-btn" data-id="${data.id}"><i class="bi bi-trash-fill"></i></button>`;
                        }
                    }
                ]
            });

            $('#membershipPlanForm').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission
                $('#loaderModal').modal('show');
                $.ajax({
                    url: "{{ route('membership.plans.create') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.message ||
                                'Membership plan created successfully!',
                            toast: true,
                            position: 'top-end',
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        });
                        plan_table.ajax.reload();
                        $('#loaderModal').modal('hide');
                        $('#membershipPlanForm')[0].reset();
                    },
                    error: function(xhr) {
                        let errorMessage = xhr.responseJSON?.message || 'An error occurred.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            showConfirmButton: true,
                        });
                    }
                });
            });

            $('#plan_table tbody').on('click', '.delete-btn', function() {
                const deleteId = $(this).data('id');

                // Show SweetAlert for confirmation
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/membershipplans-delete/${deleteId}`,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content')
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: response.message ||
                                        'Plan deleted successfully!',
                                    toast: true,
                                    position: 'top-end',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                });
                                plan_table.ajax.reload();
                            },
                            error: function(xhr) {
                                let errorMessage = xhr.responseJSON?.message ||
                                    'An error occurred.';
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: errorMessage,
                                    showConfirmButton: true,
                                });
                            }
                        });
                    }
                });
            });

            $('#plan_table tbody').on('click', '.view-btn', function() {
                const rowIndex = $(this).closest('tr').index(); // Get the index of the row
                const plan = plansData[rowIndex]; // Get the corresponding plan data

                // Populate the modal with data
                $('#modalPlanTitle').text(plan.plain_title);
                $('#modalPlanDescription').text(plan.plan_description);
                $('#modalPlanType').text(plan.plan_type.toUpperCase());
                $('#modalPlanPrice').text('$' + plan.plan_price);

                $('#modalPlanDetails').html(plan.plan_details);

                $('#modalPlanCreatedAt').text(new Date(plan.created_at).toLocaleDateString());
                $('#modalPlanStripeProductId').text(plan.stripe_product_id);

                // Show the modal
                var viewPlanModal = new bootstrap.Modal(document.getElementById('viewPlanModal'));
                viewPlanModal.show();
            });
        });
    </script>

<script>
    $(document).ready(function() {
        // AJAX request to get payment setting data
        $.ajax({
            url: "{{ route('getpaymentsetting') }}",
            method: 'GET',
            success: function(response) {
                if (response.data.length > 0) {
                    // If data exists, keep the page as it is (do nothing)
                    $('#paymentSettingMessage').html('');
                } else {
                    // If no data, display the message and button to redirect
                    $('#paymentSettingMessage').html(`
                        <div class="alert alert-danger d-flex justify-content-between align-items-center" role="alert">
                            <span>
                                Please add STRIPE KEY IN PAYMENT Configuration.
                            </span>
                            <a href="payment-setting" class="btn btn-danger">
                                <i class="bi bi-arrow-right-circle"></i> Go to Configuration
                            </a>
                        </div>
                    `);
                }
            },
            error: function(xhr) {
                console.log("Error fetching payment setting:", xhr);
            }
        });
    });
</script>

    <style>
        .spinner-border {
            width: 3rem;
            height: 3rem;
            color: #007bff;
            /* Bootstrap primary color */
        }
    </style>
@endsection
