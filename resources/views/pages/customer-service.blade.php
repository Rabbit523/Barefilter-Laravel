@extends('layouts.public') @section('title', 'Kundeservice') @section('content')
<section class="barefilter-customer-service content">
    <div class="container-fluid">
        <ol class="breadcrumb barefilter-breadcrumb">
            <li>
                <a href="#">Hjem</a>
            </li>
            <li class="active">Kundeservice</li>
        </ol>
        <div class="services">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="service">
                        <img class="service-image" src="img/contact-icon.svg" alt="Kontakt oss ikon">
                        <h3>Kontakt oss</h3>
                        <p>Finner du ikke svar på det du lurer på? Ring oss på
                            <br>+47 47 14 5000 eller send oss en e-post til kontakt@barefilter.no</p>
                        <br>
                        <a href="/contact" class="barefilter-btn dark-blue">Send Melding</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="service">
                        <img class="service-image" src="img/live-chat-icon.svg" alt="Live chat ikon">
                        <h3>Live Chat</h3>
                        <p>Ønsker du rask support? Vi er tilgjengelig på Chat fra klokken 07:00 - 21:00 alle dager. Vi ser frem
                            til å hjelpe deg!</p>
                        <br>
                        <a href="#" class="barefilter-btn dark-blue chaton">Start Chat</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="service">
                        <img class="service-image" src="img/package-tracking-icon.svg" alt="Pakkesporing ikon">
                        <h3>Pakkesporing</h3>
                        <p>Lurer du på hvor langt leveransen din har kommet? Sporpakken din med et sporingsnummer gjennom PostNord
                            eller ring oss for mer informasjon.</p>
                        <br>
                        <a href="https://minside.postnord.no/public-services/tracking/" target="_blank" class="barefilter-btn dark-blue">Spor pakke</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<section class="barefilter-faq">

    <div class="content">
        <!-- Nav tabs -->

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="categories">
                    <h2>Ofte stilte spørsmål</h2>
                    <hr>
                    <p>Denne seksjonen tar for seg de fleste spørsmålene ved kjøp og salg av filter</p>
                    <hr>
                    <ul class="nav nav-pills nav-stacked" role="tablist">
                        
                        @foreach($content as $key => $node)
                        <li role="presentation">
                            <a href="/#cat{{$key}}" aria-controls="cat{{$key}}" role="tab" data-toggle="tab">{{$node->category}}</a>
                        </li>
                        @endforeach
                    </ul>
                    <!-- <ul class="nav nav-pills nav-stacked" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#general" aria-controls="general" role="tab" data-toggle="tab">Generelt</a>
                        </li>
                        <li role="presentation">
                            <a href="#filterguide" aria-controls="filterguide" role="tab" data-toggle="tab">Tips &amp; Triks</a>
                        </li>
                        <li role="presentation">
                            <a href="#borettslag" aria-controls="borettslag" role="tab" data-toggle="tab">Borettslag og sameie</a>
                        </li>
                        <li role="presentation">
                            <a href="#orders" aria-controls="orders" role="tab" data-toggle="tab">Ordre</a>
                        </li>
                        <li role="presentation">
                            <a href="#delivery" aria-controls="delivery" role="tab" data-toggle="tab">Levering</a>
                        </li>
                        <li role="presentation">
                            <a href="#payment" aria-controls="payment" role="tab" data-toggle="tab">Betaling</a>
                        </li>
                        <li role="presentation">
                            <a href="#refund" aria-controls="refund" role="tab" data-toggle="tab">Refundering, angrerett &amp; reklamasjon</a>
                        </li> -->
                    </ul>
                </div>
            </div>

            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="answers">
                    <div class="tab-content">
                    @php ($i = 1)
                    @foreach($content as $catFaqId => $cat)
                        @if ($i == 1)
                            <div role="tabpanel" class="tab-pane fade in active" id="cat{{$catFaqId}}">
                                @php ($i++)
                        @else
                            <div role="tabpanel" class="tab-pane fade" id="cat{{$catFaqId}}">
                        @endif
                            <div class="panel-group accordion collapse in" id="accordion{{$catFaqId}}" role="tablist" aria-multiselectable="true" aria-expanded="true"
                                style="">
                                @foreach($cat->answer as $faqId => $answer)
                                <div class="panel panel-default">
                                    <div class="panel-heading" id="heading{{$catFaqId.''.$faqId}}" role="tab">
                                        <h4 class="panel-title">
                                            <a aria-controls="collapse{{$catFaqId.''.$faqId}}" aria-expanded="false" class="collapsed" data-parent="#accordion{{$catFaqId}}"
                                                data-toggle="collapse" href="/#collapse{{$catFaqId.''.$faqId}}"
                                                role="button">{{$answer->question}}? </a>
                                        </h4>
                                    </div>
                                    <div aria-labelledby="heading{{$catFaqId.''.$faqId}}" class="panel-collapse collapse" id="collapse{{$catFaqId.''.$faqId}}"
                                        role="tabpanel">
                                        <div class="panel-body">{!!$answer->answer!!}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection