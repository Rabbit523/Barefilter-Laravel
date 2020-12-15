@extends('layouts.public') 
@section('title', $title)
@section('description', $description)
@section('content')
<section class="barefilter-subscription content">
    <div class="container-fluid">


        <ol class="breadcrumb barefilter-breadcrumb">
            <li><a href="#">Hjem</a></li>
            <li class="active">Bli Partner</li>
        </ol>

        <div class="row">
            <div class="col-lg-12">
                <div class="barefilter-jumbotron">
                    <img src="img/banners/bli-partner-banner.jpg" alt="any image"/>
                    <div class="overlay">
                        <div class="overlay-content">
                            {!! $content->section_one->text !!}
                            <a href="{{route('contact')}}"><button type="button" class="barefilter-btn">Kontakt oss</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-lg-9">
                <div class="description">
                    {!! $content->section_two->text !!}
                    <p>Ta kontakt med Bare filter AS for en prat om det å være en av våre partnere.</p>
                    <a href="{{route('contact')}}"><button type="button" class="barefilter-btn dark-blue">KONTAKT OSS</button></a>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="related">
                    <div class="service">
                        <img class="service-image" src="img/filterguide-icon.svg" alt="" />
                        <h3>Filterabonnement</h3>
                        <p>Vi tilbyr filterabonnement slik at det blir enkelt for deg å huske når du skal bytte filter med jevne mellomrom.</p>
                        <a href="{{route('subscription')}}"><button type="button" class="barefilter-btn dark-blue">Les mer</button></a>
                    </div>

                    <div class="service">
                        <img class="service-image" src="img/customer-service-icon.svg" alt="" />
                        <h3>Kundeservice</h3>
                        <p>Har du noen spørsmål? Eller har behov for hjelp? Ta kontakt med oss, vi ser frem til å hjelpe deg.</p>
                        <a href="{{route('customer-service')}}"><button type="button" class="barefilter-btn dark-blue">Les mer</button></a>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>
@endsection