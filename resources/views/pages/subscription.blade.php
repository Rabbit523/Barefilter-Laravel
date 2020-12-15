@extends('layouts.public') 
@section('title', $title)
@section('description', $description)
@section('content')
<section class="barefilter-subscription content">
    <div class="container-fluid">
        <ol class="breadcrumb barefilter-breadcrumb">
            <li>
                <a href="#">Hjem</a>
            </li>
            <li class="active">Filterabonnement</li>
        </ol>


        <div class="row">
            <div class="col-lg-12">
                <div class="barefilter-jumbotron">
                    <img src="img/banners/filterabonnement-banner.jpg" alt="" />
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
            <div class="col-lg-9">
                <div class="description">
                    {!! $content->section_two->text !!}
                    {!! $content->section_three->text !!}
                </div>
            </div>
            <div class="col-lg-3">
                <div class="related">
                    <div class="service">
                        <img class="service-image" src="img/filterguide-icon.svg" alt="" />
                        <h3>Bli Partner</h3>
                        <p>Vi tilbyr partneravtale for firma som driver med salg, montering og service av ventilasjonsanlegg.
                            </p>
                        <a href="{{route('register')}}"><button type="button" class="barefilter-btn dark-blue">Les mer</button></a>
                    </div>

                    <div class="service">
                        <img class="service-image" src="img/customer-service-icon.svg" alt="" />
                        <h3>Support</h3>
                        <p>Har du noen spørsmål? Eller har behov for hjelp? Ta kontakt med oss, vi ser frem til å hjelpe
                            deg.</p>
                        <a href="{{route('support')}}"><button type="button" class="barefilter-btn dark-blue">Les mer</button></a>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>
@endsection