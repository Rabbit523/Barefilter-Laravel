@extends('layouts.public') @section('title', 'Filtersøk') @section('content')
<barefilter-search ></barefilter-search>
@endsection
@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular.min.js"></script>
<script src="/js/ng.barefilter-search.js"></script>
@endsection