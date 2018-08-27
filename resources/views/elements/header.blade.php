<?php
use Illuminate\Support\Facades\Auth;
$shouldRender = (Auth::check()) ? (Auth::user()->role_id === 3): true;
$notification = ($configuration->notification_bar->on && $shouldRender) ? "" : "off";
$partner = (Auth::check()) ? (Auth::user()->role_id === 2): false;
$partner_logo = ($partner) ? Auth::user()->partnerlogo: "undefined_logo";
?>
<header>
    <div class="notification-bar {{$notification}}">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 text-center">
                    {{$configuration->notification_bar->message}}
                </div>
            </div>
        </div>
    </div>

    @guest
    <div class="top-menu hidden-xs">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                  <div class="col-lg-8 hidden-xs">
                      <p>
                        @if ($configuration->free_shipping)
                            <i style="color: #98d609;"  class="fa fa-check-circle"></i>&nbsp;Gratis frakt over kr {{$configuration->free_shipping_amount}},-&nbsp;&nbsp;&nbsp;&nbsp;
                        @else
                            <i style="color: #98d609;"  class="fa fa-check-circle"></i>&nbsp;Gratis frakt er av&nbsp;&nbsp;&nbsp;&nbsp;
                        @endif
                        <i style="color: #98d609;"  class="fa fa-check-circle"></i>&nbsp;30 dager returrett&nbsp;&nbsp;&nbsp;&nbsp;
                        <i style="color: #98d609;" class="fa fa-check-circle"></i>&nbsp;Eurovent godkjent produsent&nbsp;&nbsp;&nbsp;&nbsp;
                        <i style="color: #98d609;"  class="fa fa-check-circle"></i>&nbsp;Norskproduserte filter&nbsp;&nbsp;&nbsp;&nbsp;
                        <i style="color: #98d609;"  class="fa fa-check-circle"></i>&nbsp;Rask Kundeservice&nbsp;&nbsp;&nbsp;&nbsp;
                      </p>
                  </div>
                    <div class="col-lg-4 hidden-sm hidden-xs">
                        <ul class="pull-right">
                            <li><i class="fa fa-facebook"></i>&nbsp;&nbsp;<a href="https://www.facebook.com/barefilter/" target="_blank">Lik oss på Facebook</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endguest
    @auth
    <div class="top-menu">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <p>
                            <b>Du er logget inn som </b> {{auth()->user()->first_name}} {{auth()->user()->last_name}}</p>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 hidden-xs">
                        <ul class="pull-right">
                            <li>
                                <a href="{{url('/logout')}}">Logg ut</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endauth
    <div class="bottom-menu">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-2">
                        <div class="company-logo text-center">
                            <a href="{{url('/')}}">
                                <img src="/img/barefilter-logo.svg" alt="logo" />
                            </a>
                        </div>

                    </div>
                    @if($partner)
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="float: right;">
                        <div class="company-logo text-center">
                            @if(strcmp($partner_logo, "") != 0)
                                <a href="#">
                                    <img alt="partner logo" src="{{$partner_logo}}" name="basic_logo" id="basic_logo"/>
                                </a>
                            @else
                                <a href="#">
                                    <img alt="partner logo" ng-src="@{{partner_logo}}" name="basic_logo" id="basic_logo"/>
                                </a>
                            @endif
                        </div>
                    </div>
                    @endif
                    @if($shouldRender)
                    <div class="col-lg-4 col-md-5 col-sm-7 col-xs-10" id="header-links">
                        <div class="header-links">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <a href="#" class="header-link" id="live-chat">
                                    <span></span>
                                    <div class="information">
                                        <h3>Live Chat</h3>
                                        <p>08:00 - 20:00</p>
                                    </div>
                                </a>
                            </div>
                            @guest
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <a href="{{url('/logg-inn')}}" class="header-link" id="my-account">
                                    <span></span>
                                    <div class="information">
                                        <h3>Logg inn</h3>
                                        <p>Din portal</p>
                                    </div>
                                </a>
                            </div>
                            @endguest
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <a href="{{url('/handlekurv')}}" class="header-link" id="cart">
                                    <b class="cost-badge" id="total-item">0</b>
                                    <span></span>
                                    <div class="information">
                                        <h3>Handlekurv </h3>
                                        <p>KR <b id="total-cost">0</b>,-</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-4 col-sm-9 col-xs-12" id="search-bar">
                    <div class="navbar-header col-lg-0 col-md-0 col-sm-0 col-xs-3">
                        <!-- <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar" style="width: 30px;"></span>
                                <span class="icon-bar"></span>
                            </button><span class="menu-name hidden-lg">MENY</span> -->
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"
                                aria-expanded="false" id="btn-menu-1">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar" style="width: 30px;"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2"
                                aria-expanded="false" id="btn-menu-2">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar" style="width: 30px;"></span>
                                <span class="icon-bar"></span>
                            </button><span class="menu-name hidden-lg">MENY</span>
                        </div>
                        <div class="search-bar col-lg-12 col-md-12 col-sm-12 col-xs-9">
                            <form action="{{route('search')}}">
                            <input type="text" class="search-input" id="search-query" name="q" placeholder="Søk etter kode, merke, modell, filterklasse og dimensjoner" value="{{request()->q}}">
                                <button type="submit" class="btn search-button">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>
