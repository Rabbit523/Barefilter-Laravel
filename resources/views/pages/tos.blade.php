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
            <li class="active">Salgs- & Leveringsbetingelser</li>
        </ol>

        <div class="row">
            <div class="col-lg-9">
              <div class="description">
                {!! $content->section_one->text !!}
              </div>
            </div>
            <div class="col-lg-3">
                <div class="related">
                    <div class="service">
                        <img class="service-image" src="img/customer-service-icon.svg" alt="" />
                        <h3>Support</h3>
                        <p>Har du noen spørsmål? Eller har behov for hjelp? Ta kontakt med oss, vi ser frem til å hjelpe deg.</p>
                        <button type="button" class="barefilter-btn dark-blue">Read more</button>
                    </div>

                    <div class="service">
                        <img class="service-image" src="img/live-chat-icon.svg" alt="" />
                        <h3>Live Chat</h3>
                        <p>Ønsker du rask support? Vi er tilgjengelig på Chat fra klokken 07:00 - 21:00 alle dager. Vi ser frem til å hjelpe deg!</p>
                        <button type="button" class="barefilter-btn dark-blue chaton">Start chat</button>
                    </div>

                    <div class="service">
                        <img class="service-image" src="img/package-tracking-icon.svg" alt="" />
                        <h3>Pakkesporing</h3>
                        <p>Lurer du på hvor langt leveransen din har kommet? Sporpakken din med et sporingsnummer gjennom PostNord eller ring oss for mer informasjon.</p>
                        <button type="button" class="barefilter-btn dark-blue">Spor pakke</button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>
@endsection
