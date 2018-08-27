@extends('layouts.public')
@section('title', 'Om Oss')

@section('content')
<section class="barefilter-subscription content">
    <div class="container-fluid">
        <div class="row">
            <div class="description col-lg-12">
                <div class="col-md-6 col-md-offset-3 col-sm-12 text-center">
                    <img width="90" src="/img/order-completed.svg" alt="#">
                    <h2>Din ordre er bekreftet!</h2>
                    <h4>Ordrenummer: {{$order[0]->id}}</h4>
                    <p>Du vil motta en SMS når du kan hente pakken fra ditt lokale sted.
                        <br>
                        <br>Har du spørsmål rundt ordren? Ring oss på
                        <a href="tel:+4747145000">+47 47 14 5000</a>, send en e-post til
                        <a href="mailto:kontakt@barefilter.no">kontakt@barefilter.no</a> eller
                        <a href="#" class="chaton">start live chat</a>.</p>
                    <p></p>
                </div>
            </div>
        </div>
</section>
@endsection