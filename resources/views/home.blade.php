@extends('structures.main')

@section('content')
    <div class="pagetitle mb-5 text-primary">
        <h1>Dashboard</h1>
    </div>

    <div class="row">
        <div class="col-xxl-3 col-md-6 mb-4">
            <div class="card border-0 rounded shadow-lg bg-light">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-container bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 50px; height: 50px;">
                        <i class="fas fa-clipboard" style="font-size: 25px;"></i> <!-- Adjusted icon size -->
                    </div>
                    <div class="text-center">
                        <h5 class="card-title mb-1" style="font-size: 1.2rem;">Running Sites</h5>
                        <!-- Adjusted title font size -->
                        <h6 class="fw-bold mb-0" id="running" style="font-size: 1.5rem;"></h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-md-6 mb-4">
            <div class="card border-0 rounded shadow-lg bg-light">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-container bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 50px; height: 50px;">
                        <i class="fas fa-file-alt" style="font-size: 25px;"></i> <!-- Smaller Font Awesome icon -->
                    </div>
                    <div class="text-center">
                        <h5 class="card-title mb-1" style="font-size: 1.2rem;">Stopped Sites </h5>
                        <h6 class="fw-bold mb-0" id="stopped" style="font-size: 1.5rem;">78</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-md-6 mb-4">
            <div class="card border-0 rounded shadow-lg bg-light">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-container bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 50px; height: 50px;">
                        <i class="fas fa-link" style="font-size: 25px;"></i> <!-- Smaller Font Awesome icon -->
                    </div>
                    <div class="text-center">
                        <h5 class="card-title mb-1" style="font-size: 1.2rem;">Connected Sites</h5>
                        <h6 class="fw-bold mb-0" style="font-size: 1.5rem;">$2,300</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-md-6 mb-4">
            <div class="card border-0 rounded shadow-lg bg-light">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-container bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 50px; height: 50px;">
                        <i class="fas fa-home" style="font-size: 25px;"></i> <!-- Smaller Font Awesome icon -->
                    </div>
                    <div class="text-center">
                        <h5 class="card-title mb-1" style="font-size: 1.2rem;">Hosted Sites</h5>
                        <h6 class="fw-bold mb-0" style="font-size: 1.5rem;">350</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>






    <div class="container">
        <button id="createSiteButton" type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
            data-bs-target="#siteCreationModal">
            Create Your First Site
        </button>
        <div class="card p-4 shadow-lg rounded" id="detailofwordpress" style="background: #f5f5f5;">


            <h2 class="text-center mb-4" style="font-family: 'Open Sans', sans-serif; font-weight: 600;">DETAILS</h2>




            <div class="responsive">
                <table id="userDetailsTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Email</th>
                            <th>URL</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>




    <div class="container mt-3">
        <!-- Button to trigger modal -->


        <!-- Modal -->
        <div class="modal fade" id="siteCreationModal" tabindex="-1" aria-labelledby="siteCreationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="siteCreationModalLabel">Create Your First Site</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="siteCreationFormone" method="POST">
                            <!-- Step 1: Basic Information -->
                            <div id="step1" class="form-step">
                                <div class="row">
                                    <input type="text" name="version" id="version" class="d-none">
                                    <div class="col-md-6 mb-3">
                                        <label for="siteName" class="form-label">Site Name</label>
                                        <input type="text" class="form-control" id="siteName" name="siteName"
                                            placeholder="Leave blank for a surprise" required autocomplete="off">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="user_name" class="form-label">User Name</label>
                                        <input type="text" class="form-control" id="user_name" name="user_name"
                                            placeholder="Leave blank for a surprise" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Leave blank for a surprise" required autocomplete="off">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="wpVersion" class="form-label">WordPress Version</label>
                                        <select class="form-select" id="wpVersion" name="wpVersion" required>
                                            <option value="6.6.2">6.6.2</option>
                                        </select>

                                    </div>

                                </div>
                                <button type="button" class="btn btn-primary next-step" id="next-btn">NEXT</button>
                            </div>
                        </form>

                        <form id="siteCreationFormtwo" action="">
                            <!-- Step 2: Plugin Selection -->
                            <div id="step2" class="form-step d-none">
                                <div class="row">
                                    <!-- Plugin Categories -->
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-12 mb-3">
                                        <h6>Select Plugins</h6>
                                        <div id="pluginCategoriesContainer">
                                            <p>No categories available yet.</p>
                                        </div>
                                    </div>

                                    <!-- Plugin List Container -->
                                    <div class="col-lg-6 col-md-5 col-sm-8 col-12 mb-3">
                                        <div class="border border-secondary p-3 " style="border-radius: 20px;">
                                            <h6>Plugins List</h6>
                                            <div id="pluginList" class="plugin-list">
                                                <p>No plugins available in this category.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Selected Plugins -->
                                    <div class="col-lg-4 col-md-4 col-12 mb-3">
                                        <div class="border border-secondary p-3 " style="border-radius: 20px;">
                                            <h6>Selected Plugins</h6>
                                            <div id="selectedPluginsContainer" class="d-flex flex-wrap">
                                                <p>No plugins selected yet.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <button type="button" class="btn btn-secondary prev-step">BACK</button>
                                    <button type="submit" class="btn btn-success"><i
                                            class="bi bi-download"></i></button>
                                    <button type="button" class="btn btn-primary next-step2"
                                        id="next-step2">NEXT</button>
                                </div>
                            </div>
                        </form>
                        <form id="siteCreationFormthree" action="">
                            <!-- Step 3: Themes Selection -->
                            <div id="step3" class="form-step d-none">
                                <div class="row">
                                    <!-- Themes  -->
                                    <div class="col-lg-12 col-md-6 col-sm-12 mb-3">
                                        <h6>Select Themes</h6>
                                        <div id="all-themes">
                                            <p>No Themes available yet.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <button type="button" class="btn btn-secondary prev-step2">BACK</button>
                                    <button type="button" class="btn btn-success download-themes"><i
                                            class="bi bi-download"></i></button>
                                    <button type="button" class="btn btn-primary next-step3"
                                        id="next-step3">NEXT</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>





    </div>




    {{-- //loader --}}
    <div class="modal fade" id="loaderModal" tabindex="-1" aria-labelledby="loaderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-body d-flex justify-content-center align-items-center">
                    <div class="loader"></div>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center">
                    <p class="mt-2">Downloading WordPress <i class="bi bi-wordpress"></i>. Please wait a moment.</p>

                </div>

            </div>
        </div>
    </div>



    <script>
        $(document).ready(function() {


            // Next step from Step 1 to Step 2
            document.querySelectorAll('.next-step').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('step1').classList.add('d-none');
                    document.getElementById('step2').classList.remove('d-none');
                });
            });

            // Next step from Step 2 to Step 3
            document.querySelectorAll('.next-step2').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('step2').classList.add('d-none');
                    document.getElementById('step3').classList.remove('d-none');
                });
            });

            // Navigate back from Step 2 to Step 1
            document.querySelectorAll('.prev-step').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('step2').classList.add('d-none');
                    document.getElementById('step1').classList.remove('d-none');
                });
            });

            // Navigate back from Step 3 to Step 2
            document.querySelectorAll('.next-step3').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('step3').classList.add('d-none');
                    document.getElementById('step2').classList.remove('d-none');
                });
            });
            document.querySelectorAll('.prev-step2').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('step3').classList.add('d-none');
                    document.getElementById('step2').classList.remove('d-none');
                });
            });
        });
    </script>


    <script src="assets/js/create-wordpress.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/home.css">
@endsection
