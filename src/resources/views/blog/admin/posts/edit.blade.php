@extends('pacificdev::blog.layouts.admin')

@section('styles')
@vite([
'resources/js/blog/editPostMarkdownEditor.js',
])
@endsection

@section('content')
<div class="container mt-5">
    @include('pacificdev::blog.partials.session')
    @include('pacificdev::blog.partials.validation')
    
    <livewire:blog.edit :post="$post"></livewire:blog.edit>


</div>
@endsection