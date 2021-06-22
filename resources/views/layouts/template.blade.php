<!DOCTYPE html>
<html lang="en">
    <head>
        @include('components.meta')
    </head>
<body>
    {{-- @include('layouts.header') --}}

    @yield('content')

    {{-- @include('layouts.footer') --}}

    @include('components.external_script')
</body>
</html>