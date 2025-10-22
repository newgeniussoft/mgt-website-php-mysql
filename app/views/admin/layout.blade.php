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
		 <script>
        // Toggle user menu
        document.getElementById('user-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const button = document.getElementById('user-menu-button');
            const menu = document.getElementById('user-menu');
            
            if (!button.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
        @stack('scripts')
    </body>
</html>