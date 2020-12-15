<div class="modal fade" id="product-quick-view-modal" tabindex="-1" role="dialog">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog modal-lg vertical-align-center" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-5 col-md-5">
                            <div id="product-quick-view-gallery" class="product-gallery">
                                <div class="slides">

                                </div>
                                <div class="page-control">
                                    <a href="javascript:;" class="sl-prev">
                                        <i class="fa fa-angle-left fa-3x"></i>
                                    </a>
                                    <a href="javascript:;" class="sl-next">
                                        <i class="fa fa-angle-right fa-3x"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-7 product-description">
                            <div class="btn-group">
                                <div id="InStock" style="display: block; font-size: 15px;"><strong>Lagerstatus</strong>:<strong style="color: #78c805;"> På lager - kan sendes nå</strong></div> 
                                <div id="OutStock" style="display: block; font-size: 15px;"><strong>Lagerstatus</strong>:<strong style="color: #ff1d02;"> Ikke på lager</strong></div> 
                            </div>
                            <div class='product-title'>
                                <p class="pull-right">
                                  <a href="https://www.grontpunkt.no/" target="_blank">
                                    <img src="/img/gront-punkt-sertifisert.png" width="80px">
                                  </a>
                                </p>
                                <h1 id="name"></h1>
                                <h2 id="short-description"></h2>
                                <h3>Varenr:
                                    <span id="sku"></span>
                                </h3>
                                <h3>Rammemål:<span id="Dimensions"></span></h3>
                                <p>kr <span id="price"></span>,- <br><small id="discount">Du sparer: kr <span></span>,-</small></p>
                                <img src="/img/tryggehandel.png" style="float: right; margin-right: 10px; margin-top: -30px; width: 70px;"></img>
                            </div>
                            
                            <hr />
                            <section class="row info">
                                <div class="col-lg-4 col-md-4">
                                    <div class="row" style="margin-left: 0px;">
                                        <img src="/img/delivery.svg" width="40px" style="float:left;">                         
                                        <h2>GRATIS LEVERING</h2>
                                        <h2>OVER KR {{$configuration->free_shipping_amount}},-</h2>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <div class="row" style="margin-left: 0px;">
                                        <img src="/img/return.svg" width="30px" style="float:left; margin-top: 3px;">                         
                                        <h2>365 DAGERS</h2>
                                        <h2>FULL RETURRETT</h2>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <div class="row" style="margin-left: 0px;">
                                        <img src="/img/service.svg" width="30px" style="float:left; margin-top: 3px;">                         
                                        <h2>PERSONLIG</h2>
                                        <h2>KUNDESERVICE</h2>
                                    </div>
                                </div>
                            </section>
                            <hr />
                            <section>
                                <h2>Filterbeskrivelse</h2>
                                <div id="description"></div>
                            </section>
                            <hr />
                            <section>
                                <h2>Filterabonnement</h2>
                                <p>Velg et filterabonnement og få rabatt på bestillingen.</p>
                                @if(isset($subscriptions))
                                <select class="form-control" id="product-preview-modal-filter-subscription">
                                    @foreach ($subscriptions as $subscription)
                                    <option value="" data-id="{{$subscription->id}}" data-discount="{{$subscription->discount}}">{{$subscription->name}}</option>
                                    @endforeach
                                </select>
                                @endif
                            </section>
                            <hr />
                            <section id='amount-of-filters'>
                                <h2>ANTAL FILTERSETT</h2>
                                <p>Velg hvor mange filter du ønsker</p>
                                <div class="col-lg-4 col-md-4 col-sm-4 no-padding">
                                    <input type="number" value='1' id='product-preview-modal-filter-amount' />
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 no-padding">
                                    <button class="barefilter-btn light-green pull-right product-preview-modal-add-to-cart" data-id="" data-name="" data-category="" data-image="" data-price="" data-dismiss="modal"> Legg til Handlekurv</button>
                                </div>
                                <div class="clearfix"></div>
                            </section>
                        </div>
                    </div>
                    <div class="barefilter-loader md centered"></div>
                </div>
            </div>
        </div>
    </div>
</div>
