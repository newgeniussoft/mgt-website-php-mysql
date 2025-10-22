<!DOCTYPE html>
<html lang="en">
    <head>
	    @include('admin.partials.head')
        @stack('styles')
    </head>
	<body class="bg-gray-100">
		@include('admin.partials.header')
		<div class="flex">
		    @include('admin.partials.sidebar')
			<div class="flex-1 p-6">
				@yield('content')
			</div>
		</div>
	    <script src="@asset('js/app.js')"></script>
        @stack('scripts')
    </body>
</html>