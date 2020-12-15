<?php
use Illuminate\Support\Facades\Auth;
$shouldRender = (Auth::check()) ? (Auth::user()->role_id === 3 || $page !== "dashboard"): true;
?>
@if($shouldRender)
<nav class="navbar navbar-default barefilter-navigation content">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <span class="menu-name hidden-lg">MENY</span>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"
                aria-expanded="false" id="btn-menu-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2"
                aria-expanded="false" id="btn-menu-2">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
        <ul class="nav__list">
            @auth
            <li class="<?= ($page === "dashboard") ? "active" : "" ?>">
                <a href="{{url('/dashboard')}}">Min Side
                </a>
            </li>
            @endauth
            <li>
                <input id="group-1" type="checkbox" hidden />
                <label for="group-1"><span class="fa fa-caret-right"></span> Nettbutikk</label>
                <ul class="group-list">
                    <li>
                        <input id="sub-group-1" type="checkbox" hidden />
                        <label for="sub-group-1"><i class="fa fa-circle list-style-circle"></i><span class="fa fa-caret-right"></span>Filter til Enebolig</label>
                        <ul class="sub-group-list">
                            @foreach ($menu_categories as $c)
                                @if ($c->type_id == 1 && $c->parent_id == 0)
                                <li>
                                    <a href="{{route('store', ['type' => 'enebolig', 'category' => $c->handle])}}"><i class="fa fa-circle list-style-circle"></i>Filter til {{$c->name}}</a>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>   
                    <li>
                        <input id="sub-group-2" type="checkbox" hidden />
                        <label for="sub-group-2"><i class="fa fa-circle list-style-circle"></i><span class="fa fa-caret-right"></span>Filter til Industribygg</label>
                        <ul class="sub-group-list">
                        @foreach ($menu_categories as $c)
                            @if ($c->type_id == 2 && $c->parent_id == 0)
                            <li>
                                <a href="{{route('store', ['type' => 'industribygg', 'category' => $c->handle])}}"><i class="fa fa-circle list-style-circle"></i>{{$c->name}}</a>
                            </li>
                            @endif
                        @endforeach
                        </ul>
                    </li>   
                </ul>
            </li>
            <li><a href="{{url('/abonnement')}}">Filterabonnement</a>
            </li>
            <li><a href="{{url('/partner')}}">Partner</a>
            </li>
            <li>
                <input id="group-2" type="checkbox" hidden />
                <label for="group-2"><span class="fa fa-caret-right"></span> Om Bare Filter</label>
                <ul class="group-list">
                    <li><a href="{{url('/om-oss')}}"><i class="fa fa-circle list-style-circle"></i>Om oss</a></li>
                    <li><a href="{{url('/support')}}"><i class="fa fa-circle list-style-circle"></i>Support &amp; fordeler</a></li>
                    <li><a href="{{url('/kontakt-oss')}}"><i class="fa fa-circle list-style-circle"></i>Kontakt oss</a></li>
                </ul>
            </li>
            <li><a href="javascript:;" data-toggle="modal" data-target="#filter-service-modal">Borettslag &amp; Sameie </a>
            </li>
            <li><a href="{{url('/kundeservice')}}">Kundeservice</a>
            </li>
            <li><a href="https://www.facebook.com/barefilter/">Facebook</a>
            </li>
            </ul>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav" style="width: 100%;">
                @auth
                <li class="<?= ($page === "dashboard") ? "active" : "" ?>">
                    <a href="{{url('/dashboard')}}">Min Side
                    </a>
                </li>
                @endauth
                <li class="dropdown <?= ($page === "store" || $page === "product") ? "active" : "" ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Nettbutikk
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{route('store', ['type' => 'enebolig', 'category' => 'flexit'])}}">Filter til Enebolig</a>
                        </li>
                        <li>
                            <a href="{{route('store', ['type' => 'industribygg', 'category' => 'f7-industrifilter'])}}">Filter til Industribygg</a>
                        </li>
                    </ul>
                </li>
                <li class="<?= ($page === "subscription") ? "active" : "" ?>">
                    <a href="{{url('/abonnement')}}">Filterabonnement </a>
                </li>
                <li class="<?= ($page === "partner") ? "active" : "" ?>">
                    <a href="{{url('/partner')}}">Partner </a>
                </li>

                <li class="dropdown <?= ($page === "about" || $page === "support" || $page === "contact") ? "active" : "" ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Om Bare Filter
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{url('/om-oss')}}">Om oss</a>
                        </li>
                        <li>
                            <a href="{{url('/support')}}">Support &amp; fordeler</a>
                        </li>
                        <li>
                            <a href="{{url('/kontakt-oss')}}">Kontakt oss</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" data-toggle="modal" data-target="#filter-service-modal">Borettslag &amp; Sameie </a>
                </li>
                <li class="<?= ($page === "customer-service") ? "active" : "" ?>">
                    <a href="{{url('/kundeservice')}}">Kundeservice</a>
                </li>
                <li>
                    <a href="https://www.facebook.com/barefilter/" target="_blank">Facebook </a>
                </li>
                <li style="float: right;">
                    <!-- TrustBox widget - Micro Combo -->
                    <div class="trustpilot-widget" data-locale="nb-NO" data-template-id="5419b6ffb0d04a076446a9af" data-businessunit-id="5addc3555573e100014f796a" data-style-height="50px" data-style-width="100%" data-theme="dark">
                    <a href="https://no.trustpilot.com/review/barefilter.no" target="_blank">Trustpilot</a>
                    </div>
                    <!-- End TrustBox widget -->
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
@endif
