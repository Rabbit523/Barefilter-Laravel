@extends('layouts.public')
@section('title', 'Hjem')

@section('content')
<section class="barefilter-sitemap content">
            <div class="container-fluid">
                <ol class="breadcrumb barefilter-breadcrumb">
                    <li><a href="#">Hjem</a></li>
                    <li class="active">Nettsidekart</li>
                </ol>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="barefilter-jumbotron">
                            <img src="" alt="any image"/>
                            <div class="overlay">
                                <div class="overlay-content">
                                    <h1>Nettsidekart</h1>
                                    <p>Se full oversikt over alle tilgjengelige sider og produktkategorier.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="index">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="index-column">
                                <h2>Online Store</h2>
                                <ul>
                                    <li><a href="#">Nettbuttik: Bolig</a>
                                        <ul>
                                            <li><a href="#">Filter til Exvent</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="index-column">
                                <h2>Online Store</h2>
                                <ul>
                                    <li><a href="#">Nettbuttik: Industribygg</a>
                                        <ul>
                                            <li><a href="#">Filter til Exvent</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="index-column">
                                <h2>Customer Service</h2>
                                <ul>
                                    <li><a href="#">Contact</a></li>
                                    <li><a href="#">Live Chat</a></li>
                                    <li><a href="#">FAQ</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="index-column">
                                <h2>Shortcuts</h2>
                                <ul>
                                    <li><a href="#">Home</a></li>
                                    <li><a href="#">Subscription</a></li>
                                    <li><a href="#">Partner</a></li>
                                    <li><a href="#">About Barefilter</a>
                                        <ul>
                                            <li><a href="#">About us</a></li>
                                            <li><a href="#">Support & Benefits</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Your Account</a>
                                        <ul>
                                            <li><a href="#">Login</a></li>
                                            <li><a href="#">Member</a></li>
                                            <li><a href="#">Your Account</a></li>
                                            <li><a href="#">One time transactions</a></li>
                                            <li><a href="#">Your subscriptions</a></li>
                                            <li><a href="#">Package Tracking</a></li>
                                            <li><a href="#">User Settings</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
@endsection
