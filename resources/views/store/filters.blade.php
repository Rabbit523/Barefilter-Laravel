@extends('layouts.public') @section('title', $title) @section('content')
<section class="barefilter-store-industry content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 hidden-xs">
                <div class="filter-categories">
                    <h3>Filter til {{ $typeHandle == "enebolig" ? "Enebolig" : "Industribygg"}}</h3>
                    <ul>
                        @foreach ($categories as $c)
                            <li class="<?= ($c->handle === $categoryHandle) ? "active" : "" ?>">
                                <a href="{{route('store', ['type' => $typeHandle, 'category' => $c->handle])}}">{{$c->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="col-lg-12">
                    <ol class="breadcrumb barefilter-breadcrumb">
                        <li>
                            <a href="#">Hjem</a>
                        </li>
                        <li class="active">Nettbutikk : Enebolig</li>
                    </ol>
                    <div class="barefilter-jumbotron">
                        <img src="{{$category->banner_img}}" alt="any image" />
                        <div class="overlay">
                            <div class="overlay-content text-center">
                                <h1>{{$category->name}}</h1>
                                {!! $category->description !!}


                                <!--<div class="search-bar">
                                    <form>
                                        <input type="text" class="search-input" id="search-query" placeholder="Search by code, brand, model, dimensions or bags">
                                        <button type="submit" class="btn search-button">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>


                                    </form>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
                @if($category->type_id == 1)
                <h1>Filter til {{$category->name}}</h1>
                @else
                <h1>{{$category->name}}</h1>
                @endif

                @if(isset($children) && count($children) > 0)
                    <div class="col-lg-12 col-md-12 col-sm-12 product-container-mobile">
                        <h3>Kategorier</h3>
                        <div class="products categories">
                            @foreach ($children as $child)
                                @include('store.elements.category', ['category' => $child, 'typeHandle' => $typeHandle])
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(isset($products) && count($products) > 0)
                    <div class="col-lg-12 col-md-12 col-sm-12 product-container-mobile">
                        @if(isset($children) && count($children) > 0)
                        <h3>Produkter</h3>
                        @endif
                        <div class="products">
                            @foreach ($products as $product)
                                @include('store.elements.product', ['product' => $product])
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
