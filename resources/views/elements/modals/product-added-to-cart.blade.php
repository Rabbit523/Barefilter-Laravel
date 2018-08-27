<div class="modal fade " id="product-added-to-cart" tabindex="-1" role="dialog">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog modal-lg vertical-align-center" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-body">
                    <div class="row">
                        <div class="hold-content">
                            <div class="col-xs-12">
                                <div class="col-xs-12 cart-successful">
                                    <h3 class="text-left">
                                        <img src="/img/order-completed.svg" alt="" width="60">&nbsp;Lagt til i handlekurven</h3>
                                </div>
                                <div class="col-lg-12 no-padding">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <td style='width: 40%;'>Produkt(er)</td>
                                                <td style='width: 10%;'>Pris</td>
                                                <td style='width: 35%;'>Valgt filterabonnement</td>
                                                <td style='width: 10%;'>Antall</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class='product'>
                                                    <img id="image" src="" alt='product preview image' />
                                                    <div>
                                                        <h4 id="name"></h4>
                                                        <span id="category"></span>
                                                    </div>
                                                </td>
                                                <td class="product-price discount">
                                                    <span>kr <span id="price"></span>,-</span>
                                                    <small id="discount">Du sparer: kr <span></span>,-</small>
                                                </td>
                                                <td class="product-subscription">
                                                    @if(isset($subscriptions))
                                                    <select class="form-control" id="filter-subscription">
                                                        @foreach ($subscriptions as $subscription)
                                                            <option value="{{$subscription->id}}" data-id="{{$subscription->id}}" data-discount="{{$subscription->discount}}">{{$subscription->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    @endif
                                                </td>
                                                <td class="amount-selected">
                                                    <input type="number" class="form-control" value='1' id="filter-amount">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12 cart-buttons">
                                    <div class="col-md-6 col-sm-6 col-xs-12 hold-buttons text-left">
                                        <a href="#" data-dismiss="modal" class="barefilter-btn dark-blue-full">Fortsett å handle</a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12 hold-buttons text-right">
                                        <a href="{{route('checkout')}}" class="barefilter-btn light-green">Til kassen&nbsp;&nbsp;
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
