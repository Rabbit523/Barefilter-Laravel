@extends('layouts.public') 
@section('title', $title)
@section('description', $description)
@section('content')
<section class="barefilter-subscription content">
    <div class="container-fluid">
        <ol class="breadcrumb barefilter-breadcrumb">
            <li><a href="#">Hjem</a></li>
            <li class="active">Support og fordeler</li>
        </ol>

        <div class="row">
            <div class="col-lg-12">
                <div class="barefilter-jumbotron">
                    <img src="img/banners/fordeler-banner.jpg" alt="any image"/>
                    <div class="overlay">
                        <div class="overlay-content">
                            {!! $content->section_one->text !!}
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

                {!! $content->section_four->text !!}

                {!! $content->section_five->text !!}

                {!! $content->section_six->text !!}
              </div>
            </div>
            <div class="col-lg-3">
                <div class="related">
                    <div class="service">
                        <img class="service-image" src="img/about-icon.svg" alt="" />
                        <h3>Om oss</h3>
                        <p>Vi leverer kvalitetsfilter til både enebolig og industribygg. </p>
                        <button type="button" class="barefilter-btn dark-blue">Les mer</button>
                    </div>

                    <div class="service">
                        <img class="service-image" src="img/customer-service-icon.svg" alt="" />
                        <h3>Kundeserivce</h3>
                        <p>Vi vet hvor viktig det er å få god hjelp når du trenger det. Har du spørsmål? Ta kontakt. Vi ser frem til å høre fra deg.</p>
                        <button type="button" class="barefilter-btn dark-blue">Les mer</button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>
@endsection