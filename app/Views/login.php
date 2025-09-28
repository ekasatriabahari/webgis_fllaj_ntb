<!DOCTYPE html>
<html lang="en">

<head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Primary Meta Tags -->
<title>WEBGIS FLLAJ | DINAS PERHUBUNGAN PROVINSI NTB</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="title" content="WEBGIS FLLAJ DISHUB PROVINSI NTB">
<meta name="author" content="Dinas Perhubungan Provinsi NTB">
<meta name="description" content="Webgis Fasilitas Keselamatan Jalan Dinas Perhubungan Provinsi NTB 2025">
<meta name="keywords" content="dishub, fllaj, webgis, provinsi ntb" />
<link rel="manifest" href="">
<meta name="theme-color" content="#0d6efd">
<link rel="apple-touch-icon" href="">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">


<!-- Jquery -->
<script src="<?= base_url('assets/template') ?>/js/jquery.min.js"></script>

<!-- Sweet Alert -->
<link type="text/css" href="<?= base_url('assets/template') ?>/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

<!-- Notyf -->
<link type="text/css" href="<?= base_url('assets/template') ?>/notyf/notyf.min.css" rel="stylesheet">

<!-- Volt CSS -->
<link type="text/css" href="<?= base_url('assets/template') ?>/css/volt.css" rel="stylesheet">

<!-- FontAwesome -->
<link rel="text/css" href="<?= base_url('assets/template') ?>/fontawesome/css/all.min.css">
<script src="<?= base_url('assets/template') ?>/fontawesome/js/all.min.js"></script>
</head>

<body>
    <main>
        <!-- Section -->
        <section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
            <div class="container">
                <div class="row justify-content-center form-bg-image">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
                            <div class="text-center text-md-center mb-4 mt-md-0">                    
                                <img src="<?= base_url('assets/images/') ?>kemenhub.png" alt="kemenhub" height="100">
                                <img src="<?= base_url('assets/images/') ?>ntbprov.webp" alt="ntbprov" height="100">
                            </div>
                            <div class="text-center text-md-center mb-4 mt-md-0">
                                <h1 class="mb-0 h3">WEBGIS FLLAJ <br> DISHUB PROVINSI NTB</h1>
                            </div>
                            <form id="loginForm" class="mt-4">
                                <!-- Form -->
                                <div class="form-group mb-4">
                                    <label for="username">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1">
                                            <i class="fas fa-id-card"></i>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Username..." id="username" name="username" autofocus required>
                                    </div>  
                                </div>
                                <div class="form-group">
                                    <div class="form-group mb-4">
                                        <label for="password">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon2">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input type="password" placeholder="Password" class="form-control" id="password" name="password" required>
                                        </div>  
                                    </div>
                                    <!-- End of Form -->
                                    <div class="d-flex justify-content-between align-items-top mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="remember">
                                            <label class="form-check-label mb-0" for="remember">
                                              Remember me
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-gray-800">Sign in</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

<script>
    $(document).ready(function() {
        $('#loginForm').submit(function(e) {
           e.preventDefault();
           let url = "<?= base_url('login') ?>";
           $.ajax({
                type: 'POST',
                url: url,
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (!response.success) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                        });
                    } else {
                        Swal.fire({
                            icon:' success',
                            title: ' Login Berhasil',
                        });
                      window.location.href = response.redirect;
                      
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                       icon: 'error',
                       title: 'Oops...',
                       text: xhr.responseJSON.message, 
                    });
                }
            });  
        });
    });
</script>
<!-- Core -->
<script src="<?= base_url('assets/template') ?>/@popperjs/core/dist/umd/popper.min.js"></script>
<script src="<?= base_url('assets/template') ?>/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Vendor JS -->
<script src="<?= base_url('assets/template') ?>/onscreen/dist/on-screen.umd.min.js"></script>

<!-- Slider -->
<script src="<?= base_url('assets/template') ?>/nouislider/dist/nouislider.min.js"></script>

<!-- Smooth scroll -->
<script src="<?= base_url('assets/template') ?>/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

<!-- Charts -->
<script src="<?= base_url('assets/template') ?>/chartist/dist/chartist.min.js"></script>
<script src="<?= base_url('assets/template') ?>/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>

<!-- Datepicker -->
<script src="<?= base_url('assets/template') ?>/vanillajs-datepicker/dist/js/datepicker.min.js"></script>

<!-- Sweet Alerts 2 -->
<script src="<?= base_url('assets/template') ?>/sweetalert2/dist/sweetalert2.all.min.js"></script>

<!-- Moment JS -->
<script src="<?= base_url('assets/template') ?>/js/moment.min.js"></script>

<!-- Vanilla JS Datepicker -->
<script src="<?= base_url('assets/template') ?>/vanillajs-datepicker/dist/js/datepicker.min.js"></script>

<!-- Notyf -->
<script src="<?= base_url('assets/template') ?>/notyf/notyf.min.js"></script>

<!-- Simplebar -->
<script src="<?= base_url('assets/template') ?>/simplebar/dist/simplebar.min.js"></script>

<!-- Volt JS -->
<script src="<?= base_url('assets/template') ?>/assets/js/volt.js"></script>

    
</body>

</html>
