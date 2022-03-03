<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel 8 Livewire</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Font Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" crossorigin="anonymous" />

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @livewireStyles

</head>
<body>

    {{-- แสดงผล component จาก Livewire --}}
    {{-- <livewire:counter /> --}}

    <div class="w-7/12 p-2 mx-auto mt-10 rounded">
        <livewire:comments>
    </div>

    @livewireScripts
</body>
</html>