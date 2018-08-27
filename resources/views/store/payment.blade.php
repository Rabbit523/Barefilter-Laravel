@extends('layouts.public') @section('title', $title) @section('content')
<section class="barefilter-subscription content">
    <div class="container-fluid">
        <div class="row">
        <div class="col-lg-12">
        <ol class="breadcrumb barefilter-breadcrumb">
            <li>
                <a href="{{route('home')}}">Hjem</a>
            </li>
            <li class="active">Betaling</li>
        </ol>
    </div>
    @if($success)
    <div class="col-lg-12">
        <div class="description">
            <div class="row">
                <div class="col-md-6 col-md-offset-3 col-sm-12 text-center">
                    <img width="90" src="img/order-completed.svg" alt="#">
                    <h2>Din ordre er bekreftet!</h2>
                    <h4>Ordrenummer: {{$order->identifier}}</h4>
                    <p>Du vil motta en SMS når du kan hente pakken fra ditt lokale sted.<br><br>Har du spørsmål rundt ordren? Ring oss på <a href="tel:+4747145000">+47 47 14 5000</a>, send en e-post til <a href="mailto:kontakt@barefilter.no">kontakt@barefilter.no</a> eller <a href="#" class="chaton">start live chat</a>.</p>
                    <p></p>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
    <script>new Barefilter.Controllers.Payment();</script>
    @endsection
    @else
        @if($error === "already-processed")
            <div class="col-lg-12">
                <div class="description">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 col-sm-12 text-center">
                            <img width="90" src="img/already-processed.svg" alt="#">
                            <h2>Din ordre er allerede prosessert.</h2>
                            <h4>Har du spørsmål rundt ordren?</h4>
                            <p>Ring oss på
                                <a href="tel:+4747145000">+47 47 14 5000</a>, send en e-post til
                                <a href="mailto:kontakt@barefilter.no">kontakt@barefilter.no</a> eller
                                <a href="#" class="chaton">start live chat</a>.</p>
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($error === "failed")
            <div class="col-lg-12">
                <div class="description">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 col-sm-12 text-center">
                            <img width="90" src="img/order-failed.svg" alt="#">
                            <h2>Beklager, din ordre feilet.</h2>
                            <h4>Det kan skyldes av flere grunner. </h4>
                            <p>Ønsker du å legge inn en manuell ordre? Ring oss på
                                <a href="tel:+4747145000">+47 47 14 5000</a>, send en e-post til
                                <a href="mailto:kontakt@barefilter.no">kontakt@barefilter.no</a> eller
                                <a href="#" class="chaton">start live chat</a>.</p>
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="col-lg-12">
                <div class="description">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 col-sm-12 text-center">
                            <img width="90" src="img/order-failed.svg" alt="#">
                            <h2>Beklager, en slik ordre eksisterer ikke.</h2>
                            <p>Ønsker du å legge inn en manuell ordre? Ring oss på
                                <a href="tel:+4747145000">+47 47 14 5000</a>, send en e-post til
                                <a href="mailto:kontakt@barefilter.no">kontakt@barefilter.no</a> eller
                                <a href="#" class="chaton">start live chat</a>.</p>
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
        </div>
    </div>
</section>
@endsection
