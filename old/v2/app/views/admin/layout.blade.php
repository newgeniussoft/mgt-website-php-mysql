<!DOCTYPE html>
<html lang="en">
    <head>
	    @include('admin.partials.head')
        @stack('styles')
    </head>
    <body>
	    <div class="wrapper">
		    @include('admin.partials.sidebar')
		    <div class="main">
			    @include('admin.partials.header')
			    <main class="content">
				    <div class="container-fluid p-0">
                        @yield('content')
				    </div>
			    </main>
			    @include('admin.partials.footer')
		    </div>
	    </div>
	    <script src="@asset('js/app.js')"></script>
        @stack('scripts')
    </body>
</html>