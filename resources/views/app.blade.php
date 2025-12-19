<!DOCTYPE html>
<html lang="en" data-topbar-color="dark" data-bs-theme="light" data-layout-mode="default" data-layout-width="default" data-menu-color="light" data-menu-icon="default" data-sidenav-size="default">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

		<script src="assets/js/head.js"></script>
		<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="app-style">
		<link href="assets/css/app.min.css" rel="stylesheet" type="text/css">
		<link href="assets/css/icons.min.css" rel="stylesheet" type="text/css">

        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
