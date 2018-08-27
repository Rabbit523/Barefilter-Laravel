@extends('layouts.public') @section('title', 'Hjem') @section('content')
<section class="barefilter-home content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="filter-categories">
                    <h3>Filter til Enebolig</h3>
                    <ul>
                        @foreach ($residentialCategories as $c)
                            <li>
                                <a href="{{route('store', ['type' => 'enebolig', 'category' => $c->handle])}}">Filter til {{$c->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="filter-categories">
                    <h3>Filter til Industribygg</h3>
                    <ul>
                        @foreach ($industrialCategories as $c)
                            <li>
                                <a href="{{route('store', ['type' => 'industribygg', 'category' => $c->handle])}}">{{$c->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 barefilter-mobile-jumbotron">
                        <div class="barefilter-jumbotron">
                            <img src="img/banners/home-banner.jpg" alt="any image" />
                            <div class="overlay">
                                <div class="overlay-content">
                                    {!! $content->section_one->text !!}
                                    <button type="button" class="barefilter-btn">Start Live Chat</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 product-container-mobile">
                        <div class="products">
                            @foreach ($products as $product)
                                @include('store.elements.product', ['product' => $product])
                            @endforeach
                        </div>
                    </div>
                </div>
                <!--<div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                        <ul class="pagination">
                            <li>
                                <a href="#" aria-label="Previous">
                                    <span aria-hidden="true">Previous</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">1</a>
                            </li>
                            <li>
                                <a href="#">2</a>
                            </li>
                            <li class="active">
                                <a href="#">3</a>
                            </li>
                            <li>
                                <a href="#">4</a>
                            </li>
                            <li>
                                <a href="#" aria-label="Next">
                                    <span aria-hidden="true">Next</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>-->
            </div>
        </div>
    </div>
</section>
@endsection
