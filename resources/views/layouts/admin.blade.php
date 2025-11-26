@props([
    'title' => config('app.name', 'Laravel'),
    'breadcrumbs' => []])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{--Sweetalert2--}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- WireUI-->
        <wireui:scripts />

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-gray-100">
        @include('layouts.includes.admin.navigation')
        @include('layouts.includes.admin.sidebar')

        <div class="p-4 sm:ml-64">
            <div class="mt-14 flex items-center justify-between w-full">
                @include('layouts.includes.admin.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                @isset($action)
                    {{ $action }}
                @endisset
            </div>
            {{ $slot }}
        </div>

        @stack('modals')

        @livewireScripts
        <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
    {{--mostrar sweetalert--}}
    @if (session('swal'))
        <script>
            Swal.fire(@json(session('swal')));
        </script>
    @endif
    <script>
        //buscar todos los elementos de una clase especifica
        forms = document.querySelectorAll('.delete-form');
        forms.forEach(form => {
            //se pone al tiro/ se pone al pendiente de cualquier accion submit/activa el modo chismoso
            form.addEventListener('submit', function(e){
                //evita que se envie
                e.preventDefault();
                Swal.fire({
                    title: "Estas seguro?",
                    text: "No podras revertir esto ",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, eliminar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        //borra el registro
                        form.submit();
                    }
                });
            })
        });
    </script>
    </body>
</html>
