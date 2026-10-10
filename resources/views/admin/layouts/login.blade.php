<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title -->
    <title>@yield('page_title') - {{ __(basicControl()->site_title) }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/custom.css') }}">

    <!-- CSS Front Template -->
    <link rel="preload" href="{{ asset('assets/admin/css/theme.min.css') }}" data-hs-appearance="default" as="style">
    <link rel="preload" href="{{ asset('assets/admin/css/theme-dark.min.css') }}" data-hs-appearance="dark" as="style">

    <style data-hs-appearance-onload-styles>
        * {
            transition: unset !important;
        }

        body {
            opacity: 0;
        }
    </style>

    <script>
        window.hs_config = {
            "autopath": "@@autopath",
            "deleteLine": "hs-builder:delete",
            "deleteLine:build": "hs-builder:build-delete",
            "deleteLine:dist": "hs-builder:dist-delete",
            "previewMode": false,
            "startPath": "/index.html",
            "vars": {
                "themeFont": "https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap",
                "version": "?v=1.0"
            },
            "layoutBuilder": {
                "extend": {"switcherSupport": true},
                "header": {"layoutMode": "default", "containerMode": "container-fluid"},
                "sidebarLayout": "default"
            },
            "themeAppearance": {
                "layoutSkin": "default",
                "sidebarSkin": "default",
                "styles": {
                    "colors": {
                        "primary": "#377dff",
                        "transparent": "transparent",
                        "white": "#fff",
                        "dark": "132144",
                        "gray": {"100": "#f9fafc", "900": "#1e2022"}
                    }, "font": "Inter"
                }
            },
            "languageDirection": {"lang": "en"},
            "skipFilesFromBundle": {
                "dist": ["assets/js/hs.theme-appearance.js", "assets/js/hs.theme-appearance-charts.js", "assets/js/demo.js"],
                "build": ["assets/css/theme.css", "assets/vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside-mini-cache.js", "assets/js/demo.js", "assets/css/theme-dark.css", "assets/css/docs.css", "assets/vendor/icon-set/style.css", "assets/js/hs.theme-appearance.js", "assets/js/hs.theme-appearance-charts.js", "node_modules/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js", "assets/js/demo.js"]
            },
            "minifyCSSFiles": ["assets/css/theme.css", "assets/css/theme-dark.css"],
            "copyDependencies": {
                "dist": {"*assets/js/theme-custom.js": ""},
                "build": {"*assets/js/theme-custom.js": "", "node_modules/bootstrap-icons/font/*fonts/**": "assets/css"}
            },
            "buildFolder": "",
            "replacePathsToCDN": {},
            "directoryNames": {"src": "./src", "dist": "./dist", "build": "./build"},
            "fileNames": {
                "dist": {"js": "theme.min.js", "css": "theme.min.css"},
                "build": {
                    "css": "theme.min.css",
                    "js": "theme.min.js",
                    "vendorCSS": "vendor.min.css",
                    "vendorJS": "vendor.min.js"
                }
            },
            "fileTypes": "jpg|png|svg|mp4|webm|ogv|json"
        }
    </script>
</head>

<body>

<script src="{{ asset('assets/admin/js/hs.theme-appearance.js') }}"></script>

<!-- ========== MAIN CONTENT ========== -->
<main id="content" role="main" class="main">
    <div class="position-fixed top-0 end-0 start-0 bg-img-start">
        <!-- Shape -->
        <div class="shape shape-bottom zi-1">
            <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1921 273">
                <polygon fill="#fff" points="0,273 1921,273 1921,0 "/>
            </svg>
        </div>
        <!-- End Shape -->
    </div>

    <!-- Content -->
    <div class="container py-5 py-sm-7">
        <div class="login-form mx-auto">
            @yield('content')
        </div>
    </div>
</main>
<!-- ========== END MAIN CONTENT ========== -->

<!-- JS Global Compulsory  -->
<script src="{{ asset('assets/global/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery-migrate.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/hs-toggle-password.js') }}"></script>
<script src="{{ asset('assets/admin/js/theme.min.js') }}"></script>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>



@stack('script')

<script>
    "use strict";
    $(document).ready(function () {
        window.onload = function () {
            new HSTogglePassword('.js-toggle-password')
        }
    })
</script>

</body>
</html>
