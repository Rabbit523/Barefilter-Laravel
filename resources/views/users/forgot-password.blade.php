@extends('layouts.public') @section('title', 'Logg inn') @section('content')
<section class="barefilter-login content">
    <div class="container-fluid">
        <barefilter-forgot-password></barefilter-forgot-password>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular.min.js"></script>
<script src="/js/ng.barefilter-forgot-password.js"></script>
@endsection