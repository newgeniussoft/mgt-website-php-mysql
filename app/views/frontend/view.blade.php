@extends('frontend.layout')

@section('title', 'Madagascar Green Tours')
@push('styles')
    <link rel="stylesheet" href="css/style.css">
@endpush
@section('content')

<img src="@asset('img/images/plage.jpg')" alt="Beautiful Beach" style="width:100%;">
<section class="container py-4">
    <div class="card-styled">
        <div class="card-styled-body">
            <h2>Madagascar Green Tours</h2>
            <p>Madagascar Green Tours is a company that specializes in sustainable tourism in Madagascar. We offer a wide range of activities 
                and tours to help you explore the beauty of Madagascar while supporting the local community and preserving the environment.</p>
        </div>  
    </div>
</section>

@endsection
@push('scripts')
    <script>console.log('Hello');</script>
@endpush