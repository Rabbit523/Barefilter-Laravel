@extends('layouts.public')
@section('title', $title)
@section('description', $description)

@section('content')
<section class="barefilter-subscription content">
            <div class="container-fluid">


                <ol class="breadcrumb barefilter-breadcrumb">
                    <li><a href="#">Hjem</a></li>
                    <li class="active">Om oss</li>
                </ol>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="barefilter-jumbotron">
                            <img src="img/banners/om-oss-banner.jpg" alt="any image"/>
                            <div class="overlay">
                                <div class="overlay-content">
                                    {!! $content->section_one->text !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <section class="barefilter-customer-service">
                        <!-- TrustBox widget - Carousel -->
                            <div class="trustpilot-widget" data-locale="nb-NO" data-template-id="53aa8912dec7e10d38f59f36" data-businessunit-id="5addc3555573e100014f796a" data-style-height="130px" data-style-width="100%" data-theme="light" data-stars="5">
                                <a href="https://www.trustpilot.com/review/barefilter.no" target="_blank">Trustpilot</a>
                            </div>
                        <!-- End TrustBox widget -->
                        </section>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-9">
                        <div class="description">
                            {!! $content->section_two->text !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="related">
                            <div class="service">
                                <img class="service-image" src="img/benefits-icon.svg" alt="" />
                                <h3>Support og fordeler</h3>
                                <p>Vi hjelper deg med kjøp, installasjon og vedlikehold av ditt filtersett.</p>
                                <a href="{{route('support')}}"><button type="button" class="barefilter-btn dark-blue">Se fordeler</button></a>
                            </div>

                            <div class="service">
                                <img class="service-image" src="img/customer-service-icon.svg" alt="" />
                                <h3>Kundeservice</h3>
                                <p>Har du noen spørsmål? Eller har du behov for hjelp? Kontakt oss i dag.</p>
                                <a href="{{route('customer-service')}}"><button type="button" class="barefilter-btn dark-blue">Les mer</button></a>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </section>
@endsection
