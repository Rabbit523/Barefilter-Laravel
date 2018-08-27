@extends('layouts.public') @section('title', 'Bli kunde') @section('content')
<section class="barefilter-become-member content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-lg-offset-2 col-md-offset-2">
                <div class="welcome-form">
                    <div class="col-lg-8">
                        <form id="join-form" class="registration-form">
                            <h2>Bli kunde hos Bare Filter</h2>
                            <div class="required-fields">
                                <div class="col-lg-6">
                                    <input id="first-name" name="first-name" type="text" class="form-control" placeholder="Fornavn" required data-msg="Please fill this field" tabindex="1"/>
                                    <input id="email" name="email" type="email" class="form-control" placeholder="E-postadresse" required data-msg="Please fill this field" tabindex="3" />
                                    <input id="password" name="password" type="password" class="form-control" placeholder="Passord" required data-msg="Please fill this field " tabindex="5" />
                                </div>
                                <div class="col-lg-6">
                                    <input id="last-name" name="last-name" type="text" class="form-control" placeholder="Etternavn" required data-msg="Please fill this field" tabindex="2"/>
                                    <input id="phone" name="phone" type="text" class="form-control" placeholder="Telefonnummer" required data-msg="Please fill this field" tabindex="4" />
                                    <input id="repeat_password" name="repeat-password" type="password" class="form-control" placeholder="Gjenta passord" required data-msg="Please fill this field" tabindex="6"/>
                                </div>
                            </div>
                            <div class="col-lg-12 confirmation">
                                <label>
                                    <input type="checkbox" value="" id="accept-tos"> Jeg aksepterer Barefilter.no's
                                    <a href="{{route('tos')}}">Standard avtalevilkår</a>
                                </label>

                                <button id="join-button" type="submit" class="barefilter-btn light-blue pull-right">Bli medlem</button>
                            </div>

                            <div class="col-lg-12 loader-container">
                                <div class="barefilter-loader xs"></div>
                            </div>

                            <div class="col-lg-12 success-container">
                                <p>Velkommen, <span id="user-fullname"></span>. Vi redirekterer deg videre til din portal.</p>
                            </div>
                            <div class="col-lg-12 error-container">
                                <div class="barefilter-loader xs"></div>
                            </div>

                        </form>
                    </div>
                    <div class="col-lg-4">
                        <div class="login-form">
                            <h2>Logg in</h2>

                            <div class="features">
                                <ul>
                                    <li>Oversikt over dine engangskjøp</li>
                                    <li>Handle direkte fra kundeportalen</li>
                                    <li>Administrer dine filterabonnement</li>
                                    <li>Full kontroll over din egen konto</li>
                                    <li>Telefonsupport +47 47 14 5000</li>
                                    <li>LiveChat Support 08:00 - 20:00</li>
                                </ul>

                                <a href="{{url('/logg-inn')}}">
                                    <button class="barefilter-btn dark-blue">Logg inn</button>
                                </a>
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
<script>jQuery(function(){new Barefilter.Controllers.Join();});</script>
@endsection
