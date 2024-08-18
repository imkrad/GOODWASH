<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title inertia>Goodwash</title>
        <meta name="description" content="Goodwash offers professional car washing services with top-quality interior and exterior cleaning, detailing, and polishing for all vehicle types. Keep your car looking brand new with Goodwash's expert care.">
        <meta name="keywords" content="Goodwash, car wash, car detailing, vehicle cleaning, auto detailing, interior cleaning, exterior washing, car polishing, professional car wash, car care">
        <meta name="author" content="Krad">
        <meta property="og:title" content="Goodwash">
        <meta property="og:description" content="Booking Management System">
        <meta property="og:image" content="URL to the template's logo or featured image">
        <meta property="og:url" content="URL to the template's webpage">
        <meta name="twitter:card" content="summary_large_image">
        <link rel="shortcut icon" href="{{ URL::asset('images/favicon.ico') }}">
        @vite(['resources/js/app.js'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>