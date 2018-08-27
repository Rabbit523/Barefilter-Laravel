@extends('layouts.public') @section('title', $product->name) @section('content')
<section class="barefilter-product content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12">
                    <ol class="breadcrumb barefilter-breadcrumb">
                        <li><a href="{{route('home')}}">Hjem</a></li>
                        <li >Nettbutikk: Enebolig</li>
                        <li>{{$category->name}}</li>
                        <li class="active">{{$product->name}}</li>
                    </ol>
                </div>
                <div class="col-lg-12" style="padding-top: 30px;">
                    <div class="notification">
                      <i class="fa fa-info-circle fa-2x"></i>
                        <h3>Husk at du har 30 dager bytte- og returrett</h3>
                        <p>Ikke være redd for å bestille feil filter. Vi er alltid tilgjengelig etter handelen.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div id="product-page-gallery" class="product-gallery">
                        <div class="slides">
                            @foreach ($product->images as $image)
                            <div class="slide">
                                <img src="{{$image->url}}" alt="placeholder" style="width: 100%; object-fit: contain;" />
                            </div>
                            @endforeach
                        </div>
                        <div class="page-control">
                            <a href="javascript:;" class="sl-prev">
                                <i class="fa fa-angle-left fa-3x"></i>
                            </a>
                            <a href="javascript:;" class="sl-next">
                                <i class="fa fa-angle-right fa-3x"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <div class="product-summary">
                        <div class="btn-group">
                            @if ($product->is_Stock=="1")
                            <button class="barefilter-btn light-green" style="padding: 5px 5px !important; display: block;">PA LAGER</button> 
                            <button class="barefilter-btn light-red" style="padding: 5px 5px !important; display: none;">IKKE PA LAGER</button>
                            @else
                            <button class="barefilter-btn light-green" style="padding: 5px 5px !important; display: none;">PA LAGER</button> 
                            <button class="barefilter-btn light-red" style="padding: 5px 5px !important; display: block">IKKE PA LAGER</button>
                            @endif
                        </div>
                        <div class="product-description">
                            <p class="pull-right"><a href="https://www.grontpunkt.no/" target="_blank"><img src="/img/gront-punkt-sertifisert.png" width="80px"></a></p>
                            <h1>{{$product->name}}</h1>
                            <h2>{{$product->short_description}}</h2>
                            <p><strong>Rammemål:</strong> {{$product->width}}x{{$product->height}}x{{$product->length}}</p>
                            <p><strong>Varenummer:</strong> {{$product->sku}}</p>
                            </br>
                            {!! $product->description !!}
                        </div>
                        </br>
                        <span class="price">kr <span id="price">{{$product->price}}</span>,- (inkl. mva.)</span> <br><small class="price" id="discount" style="display:none;">Du sparer: kr <span></span>,-</small>
                        <hr />
                        <section class="row info">
                            <div class="col-lg-4 col-md-4">
                                <div class="row" style="margin-left: 0px;">
                                    <img src="/img/delivery.svg" width="40px" style="float:left;">                         
                                    <h2>GRATIS LEVERING</h2>
                                    <p style="color: black;font-weight: light;">Gratis levering ved kjøp over kr {{$configuration->free_shipping_amount}},-</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 second">
                                <div class="row" style="margin-left: 0px;">
                                    <img src="/img/return.svg" width="30px" style="float:left; margin-top: 3px;">                         
                                    <h2>365 DAGERS FULL RETURRETT</h2>
                                    <p>Pengene tilbake for ubrukte varer</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 second">
                                <div class="row" style="margin-left: 0px;">
                                    <img src="/img/service.svg" width="30px" style="float:left; margin-top: 3px;">                         
                                    <h2>PERSONLIG KUNDESERVICE</h2>
                                    <p>Rådgivning før og etter kjøp</p>
                                </div>
                            </div>
                        </section>
                        <hr />
                        <div class="col-md-4 no-padding product-subscription">
                            <h3>Velg filterabonnement</h3>
                            <p>Velg abonnement og få rabatt.</p>
                            <select class="form-control" id="product-page-filter-subscription">
                                @foreach ($subscriptions as $subscription)
                                    <option value="" data-id="{{$subscription->id}}" data-discount="{{$subscription->discount}}">{{$subscription->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 no-padding amount-of-filters">
                            <h3>Velg antall filtersett</h3>
                            <p>Velg hvor mange filter du ønsker.</p>
                            <input type="number" class="form-control pull-left" value="1" id="product-page-filter-amount" />
                        </div>
                        <div class="col-md-4 no-padding add-to-cart">
                            <button type="submit" class="barefilter-btn light-green pull-right product-page-add-to-cart" data-id="{{$product->id}}" data-name="{{$product->name}}" data-category="{{$product->sku}}" data-image="{{$product->images->get(0)['url']}}" data-price="{{$product->price}}">Legg til handlekurv</button>
                            <span class="clearfix"></span>
                        </div>

                    </div>
                </div>
                <div class="col-lg-12 text-center pull-left more-products">
                    <h2>Andre produkter fra <span>{{$category->name}}</span></h2>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 products product-container-mobile">
                    <div class="products">
                        @foreach ($related as $product)
                            @include('store.elements.product', ['product' => $product])
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
