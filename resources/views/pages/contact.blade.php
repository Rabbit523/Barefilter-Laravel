@extends('layouts.public') @section('title', 'Kontakt oss') @section('content')
<section class="barefilter-subscription content">
    <div class="container-fluid">
        <ol class="breadcrumb barefilter-breadcrumb">
            <li>
                <a href="#">Hjem</a>
            </li>
            <li class="active">Kontakt oss</li>
        </ol>
        <div class="row">
            <div class="col-lg-12">
                <div class="barefilter-jumbotron">
                    <img src="img/banners/contact-banner.jpg" alt="" />
                    <div class="overlay">
                        <div class="overlay-content">
                            {!! $content->section_one->text !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="description col-lg-9">
                <div class="col-md-6 col-sm-6 col-xs-12 contact-desc">
                    <h2>Send oss en melding</h2>
                    <p>Kontakt oss i dag. Vi vil svare deg innen 24 timer.</p>
                    <form id="contact-form" action="">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <input type="text" name="first_name" id="first_name" placeholder="Fornavn" required>
                            <input type="text" name="last_name" id="last_name" placeholder="Etternavn" required>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <input type="text" name="phone" id="phone" placeholder="Telefonnummer" required>
                            <input type="text" name="email" id="email" placeholder="E-post" required>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <input type="text" name="subject" id="subject" placeholder="Emne" required>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <textarea class="form-control" id="message" name="message" rows="5" placeholder="Skriv melding" required></textarea>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="barefilter-btn dark-blue" id="send-message" type="submit">Send Melding</button>
                        </div>
                        <div class="col-md-12 text-center" id="feedback" style="display: none;">
                            <p>Message sent</p>
                        </div>
                    </form>
                    

                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 contact-desc">
                    <h2>Kontaktinfo</h2>
                    <p>Besøk, ring, send en e-post eller chat med oss.</p>

                    <div class="col-md-6 col-xs-12">
                        {!! $content->section_two->text !!}
                    </div>
                    <div class="col-md-6 col-xs-12">
                        {!! $content->section_three->text !!}
                    </div>
                    <div class="col-md-6 col-xs-12">
                        {!! $content->section_four->text !!}
                    </div>
                    <div class="col-md-6 col-xs-12">
                        {!! $content->section_five->text !!}
                        {!! $content->section_six->text !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="related">
                    <div class="service">
                        <img class="service-image" src="img/filterguide-icon.svg" alt="" />
                        <h3>Bli Partner</h3>
                        <p>Vi tilbyr partneravtale for firma som driver med salg, montering og service av ventilasjonsanlegg.
                        </p>
                        <button type="button" class="barefilter-btn dark-blue">Les mer</button>
                    </div>

                    <div class="service">
                        <img class="service-image" src="img/customer-service-icon.svg" alt="" />
                        <h3>Support</h3>
                        <p>Har du noen spørsmål? Eller har behov for hjelp? Ta kontakt med oss, vi ser frem til å hjelpe deg.
                        </p>
                        <button type="button" class="barefilter-btn dark-blue">Les mer</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@section('scripts')
<script>new Barefilter.Controllers.Contact();</script>
@endsection
