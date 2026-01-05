@extends('pacificdev::blog.layouts.admin')

@section('content')
<div class="container mt-5">
    @include('pacificdev::blog.partials.session')
    @include('pacificdev::blog.partials.validation')
    <h4 class="mb-3 mb-lg-5">
        👋 Hi {{Auth::user()->name}}, let's create a new amnazing Post! 
    </h4>

    <livewire:blog.create></livewire:blog.create>


</div>
@endsection