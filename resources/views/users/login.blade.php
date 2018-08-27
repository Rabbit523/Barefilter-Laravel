@extends('layouts.public') @section('title', 'Logg inn') @section('content')
<section class="barefilter-login content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-8 col-md-8 col-lg-offset-2 col-md-offset-2">
                <div class="welcome-form">
                    <div class="col-lg-8">
                        <form id="login-form" class="registration-form">
                            <h2>Logg inn på Bare Filter</h2>
                            <div class="required-fields">
                                <div class="col-lg-12">
                                    <input id="email" name="email" type="email" class="form-control" placeholder="Email" required>
                                </div>
                                <div class="col-lg-12">
                                    <input id="password" name="password" type="password" class="form-control" placeholder="Password" required>
                                </div>

                            </div>
                            <div class="col-lg-12 confirmation">
                                <label>
                                    <input id="remember-me" type="checkbox" value=""> Husk meg
                                </label>
                                <button id="login-button" type="submit" class="barefilter-btn dark-blue-full pull-right">Logg inn</button>
                            </div>
                            <div class="col-lg-12 text-right">
                                    <a href="{{route('forgot-password')}}" style="color: white; margin-top: 10px;display: block;margin-right: 8px;">Glemt passord?</a>
                                </div>

                            <div class="col-lg-12 loader-container">
                                <div class="barefilter-loader xs"></div>
                            </div>

                            <div class="col-lg-12 success-container">
                                <p>Velkommen, <span id="user-fullname"></span>. Vi redirekterer deg videre til din portal.</p>
                            </div>
                            <div class="col-lg-12 error-container">
                                <p>Feil brukernavn eller passord.</p>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-4">
                        <div class="login-form">
                            <h2>Ny kunde?</h2>

                            <div class="features">
                                <ul>
                                <li>Vi oppretter en konto til deg automatisk i handlekurven</li>
                                <li>Se Oversikt over dine engangskjøp</li>
                                <li>Handle direkte fra kundeportalen</li>
                                <li>Administrer dine filterabonnement</li>
                                <li>Full kontroll over din egen konto</li>
                                </ul>
                                <!--<a href="{{url('/bli-kunde')}}">
                                    <button class="barefilter-btn light-blue-full">Opprett konto</button>
                                </a>--->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>jQuery(function(){new Barefilter.Controllers.Login();});</script>
@endsection
