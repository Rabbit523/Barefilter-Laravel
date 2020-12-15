<tr>
    <td bgcolor="#EDEFF0" height="2" style=" padding-left: 20px; padding-right: 20px;display: block; margin-bottom: 20px; margin-top: 0px;"></td>
</tr>


<tr>
    <td style="padding-left: 20px; padding-right: 20px;font-family: 'Roboto', sans-serif;">
        <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
            <tr>
                <td width="50%" valign="top">
                    <p style="margin:0; padding: 0;font-size: 15px;">Kundenummer :
                        <span style="color:#2568ad;">{{$order->user->id}}</span>
                    </p>
                    <p style="margin:0; padding: 0;font-size: 15px;">Ordrenummer :
                        <span style="color:#2568ad;">{{$order->identifier}} </span>
                    </p>
                    <a href="{{url('/')}}/logg-inn" target="_blank" style="font-family: 'Roboto', sans-serif; display: block; text-decoration: none; line-height: 40px; border-style: solid; border-width:0px;border-radius:5px;background-color:#26AEE4;height:40px;width: 100px; text-align: center; font-size:15px; color:white; margin-top: 40px;">Min Side</a>
                </td>
                <td width="50%">
                    <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                        <tr>
                            <td width="30%" align="right" style="padding-right:10px;">
                                <p style="margin:0; padding: 0;">Varer</p>
                                <p style="margin:0; padding: 0;">Frakt</p>
                                @if($order->properties->summary->discount > 0)
                                    <p style="margin:0; padding: 0;">Rabatt</p>
                                @endif
                                @if($order->properties->netaxept === "false" || $order->properties->netaxept === false)
                                    <p style="margin:0; padding: 0;">Fakturagebyr</p>
                                @endif
                                <p style="margin:0; padding: 0;">25% MVA</p>
                                <p style="margin:0; padding: 0;">
                                    <strong>Totalpris</strong>
                                </p>
                            </td>
                            <td width="20%" align="right">
                                <p style="margin:0; padding: 0;">kr {{$order->properties->summary->goods}}</p>
                                @if($configuration->free_shipping && ($order->properties->summary->subtotal > $configuration->free_shipping_amount))
                                    <p style="margin:0; padding: 0;">Gratis</p>
                                @else
                                    <p style="margin:0; padding: 0;">kr {{$order->properties->summary->shipping}}</p>
                                @endif
                                @if($order->properties->summary->discount > 0)
                                    <p style="margin:0; padding: 0;">kr {{$order->properties->summary->discount}}</p>
                                @endif
                                @if($order->properties->netaxept === "false" || $order->properties->netaxept === false)
                                <p style="margin:0; padding: 0;">kr 35</p>@endif
                                
                                <p style="margin:0; padding: 0;">kr {{$order->properties->summary->tax}}</p>
                                <p style="margin:0; padding: 0;"><strong>kr {{$order->total}}</strong></p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>


<tr>
    <td bgcolor="#EDEFF0" height="2" style=" padding-left: 20px; padding-right: 20px;display: block; margin-bottom: 20px; margin-top: 20px;"></td>
</tr>

<tr>
    <td style="padding-left: 20px; padding-right: 20px;font-family: 'Roboto', sans-serif;">
        <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
            @foreach ($order->products as $orderProduct)
            <tr>
                <td width="12%" valign="top" style="padding-top: 8px">
                    <a href="http://www.barefilter.no/">
                        <img src='{{$orderProduct->product->images->get(0)["url"]}}' alt="Barefilter" height="60" style="display: block;" border="0"
                        />
                    </a>
                </td>
                <td width="70%" valign="top">
                    <p style="margin:0; padding: 0; color:#2568ad; font-size: 15px; "><strong>{{$orderProduct->product->name}}</strong></p>
                    <p style="margin:0; padding: 0; color: #000000;font-size: 13px;">Varenummer: {{$orderProduct->product->sku}}</p>
                    <p style="margin:0; padding: 0; color: #000000;font-size: 13px;">Antall: {{$orderProduct->amount}}</p>
                    @if($order->payment_method_id === 1)
                    <p style="margin:0; padding: 0; color: #000000;font-size: 13px;">Betaling med Netaxept</p>
                    @endif @if($order->payment_method_id === 2)
                    <p style="margin:0; padding: 0; color: #000000;font-size: 13px;">Betaling med Faktura</p>
                    @endif
                    <p style="margin:0; padding: 0; color: #000000;font-size: 13px;">Frakt : {{$order->properties->tas}}</p>
                    @if(isset($order->properties->service_partner) && $order->properties->service_partner !== null)
                    <p style="margin:0; padding: 0; color: #000000;font-size: 13px;">Pickup Point : {{$order->properties->service_partner->name}}</p>
                        @if(isset($order->properties->service_partner->address))
                            <p style="margin:0; padding: 0; color: #000000;font-size: 13px;">Adresse : {{$order->properties->service_partner->address}}</p>
                        @elseif(isset($order->properties->service_partner->address1))
                            <p style="margin:0; padding: 0; color: #000000;font-size: 13px;">Adresse : {{$order->properties->service_partner->address1}}</p>
                        @endif
                    @endif 
                    @if(isset($order->properties->company) && $order->properties->company !== null)
                    <p style="margin:0; padding: 0; color: #000000;font-size: 13px;">{{isset($order->properties->company->name) ? $order->properties->company->name : '' }}</p>
                    <p style="margin:0; padding: 0; color: #000000;font-size: 13px;">{{isset($order->properties->company->number) ? $order->properties->company->number : ''}}</p>
                    @endif
                    <p style="margin:0 0 25px 0; padding: 0; color: #000000;font-size: 13px;">{{$orderProduct->subscription->name}}</p>
                </td>
                <td width="18%" valign="top">
                    @if($orderProduct->amount == 1)
                    <p style="margin:0; padding: 0; color: #000000;font-weight: bold;" align="right" >kr {{$orderProduct->product->price}}</p>@endif 
                    @if($orderProduct->amount > 1)
                    <p style="margin:0; padding: 0; color: #000000;font-weight: bold;" align="right" >kr {{$orderProduct->product->price * $orderProduct->amount}}</p>@endif
                </td>
            </tr>
            @endforeach
        </table>
    </td>
</tr>

@if (isset($order->properties->notes) && $order->properties->notes !== null)
<tr>
    <td bgcolor="#EDEFF0" height="2" style=" display: block; margin-bottom: 10px; margin-top: 10px;"></td>
</tr>

<tr>
    <td style="padding-left: 20px; padding-right: 20px;font-family: 'Roboto', sans-serif;">
        <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
            <tr>
                <td width="100%" valign="top">
                    <p style="color: #26AEE4; font-size: 20px; ">Merknader</p>
                    <p style="margin:0; padding: 0;font-size: 14px;">{{$order->properties->notes}}</p>
                </td>
            </tr>
        </table>
    </td>
</tr>
@endif
<tr>
    <td bgcolor="#EDEFF0" height="2" style=" display: block; margin-bottom: 10px; margin-top: 10px;"></td>
</tr>
<tr>
    <td style="padding-left: 20px; padding-right: 20px;font-family: 'Roboto', sans-serif;">
        <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
            <tr>
                <td width="50%" valign="top">
                    <p style="color: #26AEE4; font-size: 20px; ">Leveringsadresse</p>
                    <p style="margin:0; padding: 0;font-size: 14px;">{{$order->shipping->first_name}} {{$order->shipping->last_name}}</p>
                    <p style="margin:0; padding: 0;font-size: 14px;">{{$order->shipping->street_address}}, {{$order->shipping->postal_code}} {{$order->shipping->city}}</p>
                    <p style="margin:0; padding: 0;font-size: 14px;">Tlf: {{$order->shipping->phone}}</p>
                    <p style="margin:0; padding: 0;font-size: 14px;">E-post: {{$order->shipping->email}}</p>
                </td>
                <td width="50%" valign="top">
                    <p style="color: #26AEE4; font-size: 20px; ">Fakturaadresse</p>
                    <p style="margin:0; padding: 0;font-size: 14px;">{{$order->billing->first_name}} {{$order->billing->last_name}}</p>
                    <p style="margin:0; padding: 0;font-size: 14px;">{{$order->billing->street_address}}, {{$order->billing->postal_code}} {{$order->billing->city}}</p>
                </td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td bgcolor="#EDEFF0" height="2" style="padding-left: 20px; padding-right: 20px; display: block; margin-bottom: 10px; margin-top: 20px;">
    </td>
</tr>