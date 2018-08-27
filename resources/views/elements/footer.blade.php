<footer>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10 col-lg-offset-2 col-sm-12 col-xs-12">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                        <h4>Bare Filter AS</h4>
                        <ul>
                            <li>
                                <a href="{{url('/')}}">Hjem</a>
                            </li>
                            <li>
                                <a href="{{url('/abonnement')}}">Filterabonnement</a>
                            </li>
                            <li>
                                <a href="javascript:;" data-toggle="modal" data-target="#filter-service-modal">Borettslag &amp; sameie</a>
                            </li>
                            <li>
                                <a href="{{url('/partner')}}">Partner</a>
                            </li>
                            <li>
                                <a href="{{url('/om-oss')}}">Om oss</a>
                            </li>
                            <li>
                                <a href="{{url('/support')}}">Support og fordeler</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                        <h4>Filter til enebolig</h4>
                        <ul>
                            <li>
                                <a href="{{route('store', ['type' => 'enebolig', 'category' => 'nilan'])}}">Filter til Nilan</a>
                            </li>
                            <li>
                                <a href="{{route('store', ['type' => 'enebolig', 'category' => 'villaventsystemair'])}}">Filter til Villavent</a>
                            </li>
                            <li>
                                <a href="{{route('store', ['type' => 'enebolig', 'category' => 'flexit'])}}">Filter til Flexit</a>
                            </li>
                            <li>
                                <a href="{{route('store', ['type' => 'enebolig', 'category' => 'salda'])}}">Filter til Salda</a>
                            </li>
                            <li>
                                <a href="{{route('store', ['type' => 'enebolig', 'category' => 'exvent'])}}">Filter til Exvent</a>
                            </li>
                            <li>
                                <a href="{{route('store', ['type' => 'enebolig', 'category' => 'heru'])}}">Filter til Heru</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                        <h4>Filter til industri</h4>
                        <ul>
                            <li>
                                <a href="{{route('store', ['type' => 'industribygg', 'category' => 'f7-industrifilter'])}}">F7 Industrifilter</a>
                            </li>
                            <li>
                                <a href="{{route('store', ['type' => 'industribygg', 'category' => 'm6-industrifilter'])}}">M6 Industrifilter</a>
                            </li>
                            <li>
                                <a href="{{route('store', ['type' => 'industribygg', 'category' => 'filter-til-til-lakkbokser'])}}">Filter til lakkbokser</a>
                            </li>
                            <li>
                                <a href="{{route('store', ['type' => 'industribygg', 'category' => 'm5-industrifilter'])}}">M5 Industrifilter</a>
                            </li>
                            <li>
                                <a href="{{route('store', ['type' => 'industribygg', 'category' => 'super-pleat-eco-f7-48mm'])}}">Super Pleat Eco</a>
                            </li>
                            <li>
                                <a href="{{route('store', ['type' => 'industribygg', 'category' => 'kullfilter-f7'])}}">F7 Kullfilter</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                        <h4>Kundeservice</h4>
                        <ul>
                            <li>
                                <a href="{{url('/kundeservice')}}">Ofte stilte spørsmål</a>
                            </li>
                            <li>
                                <a class="chaton" href="#">Start Live Chat</a>
                            </li>
                            <li>
                                <a href="{{url('/salgs-og-leveringsbetingelser')}}">Salgsbetingelser</a>
                            </li>

                            <li>
                                <a href="{{url('/kontakt-oss')}}">Kontakt oss</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 hidden-xs">
                        <h4>Kontaktinformasjon</h4>
                        <ul class="list-inline">
                            <li>
                                <i class="fa fa-map-marker"></i>&nbsp;&nbsp;&nbsp;Bare Filter AS
                                <br class="footer-space">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Valevegen 22
                                <br class="footer-space">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5451 Valen</li>
                            <li>
                                <i class="fa fa-phone"></i>&nbsp;&nbsp;
                                <a href="tel:+4747145000">+47 47 14 5000</a>
                            </li>
                            <li>
                                <i class="fa fa-envelope"></i>&nbsp;&nbsp;
                                <a href="mailto:kontakt@barefilter.no">kontakt@barefilter.no</a>
                            </li>
                            <li><i class="fa fa-clock-o"></i>&nbsp;&nbsp;Man-Fre: 08:00 - 20:00 <i class="fa fa-clock-o"></i>&nbsp;&nbsp;Lør-Søn: 12:00 - 16:00</li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom-footer">
        <div class="col-xs-12 copyright">
            <ul class="list-inline text-center">
                <li>
                    <img alt="Netaxept" width="80" src="/img/nets-logo.png">
                </li>
                <li>
                    <img alt="Visa" width="65" src="/img/visa-logo.png">
                </li>
                <li>
                    <img alt="MasterCard" width="45" src="/img/mastercard-logo.png">
                </li>
                <li>
                    <img alt="HeltHjem" width="80" src="/img/helthjem-logo.png">
                </li>
            </ul>
            <p class="text-center">Opphavsrett © {{date("Y")}} Bare Filter AS. NO 918 034 145 MVA. Webdesign &amp; Systemutvikling:
                <a href="https://fantasylab.no/"
                    target="_blank">FantasyLab</a>.</p>
            <p class="text-mobile">
                Opphavsrett @ {{date("Y")}} Bare Filter AS.
            </p>
        </div>
    </div>
</footer>
