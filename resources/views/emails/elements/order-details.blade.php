<tr>
    <td bgcolor="#EDEFF0" height="2" style=" display: block; margin-bottom: 20px; margin-top: 20px;"></td>
</tr>


<tr>
    <td style="font-family: 'Open Sans', 'Helvetica', sans-serif;">
        <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
            <tr>
                <td width="70%" valign="top">
                    <p style="margin:0; padding: 0;">Kundenummer :
                        <span style="color:#6082B7;">{{$order->user->id}}</span>
                    </p>
                    <p style="margin:0; padding: 0;">Ordrenummer :
                        <span style="color:#6082B7;">{{$order->identifier}} </span>
                    </p>

                    <a href="{{route('login')}}" style="font-family: 'Open Sans', 'Helvetica', sans-serif; display: block; text-decoration: none; line-height: 40px; border-style: solid; border-width:2px;border-radius:5px;background-color:#26AEE4;height:40px;width: 200px; text-align: center; font-size:14px; color:white; margin-top: 10px;">
                        Gå til Kundeportal</a>


                </td>
                <td width="30%">
                    <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                        <tr>
                            <td width="30%" align="right" style="padding-right:20px; font-size: 14px;">
                                <p style="margin:0; padding: 0;">Varer</p>
                                <p style="margin:0; padding: 0;">Frakt</p>
                                <p style="margin:0; padding: 0;">Rabatt</p>
                                @if($order->properties->netaxept === "false")
                                <p style="margin:0; padding: 0;">Fakturagebyr</p>@endif
                                <p style="margin:0; padding: 0;">25% MVA</p>
                                <p style="margin:0; padding: 0;">
                                    <strong>Totalpris</strong>
                                </p>
                            </td>
                            <td width="30%" align="left" style="font-size: 14px;">
                                <p style="margin:0; padding: 0;">kr {{$order->properties->summary->goods}},-</p>
                                @if($configuration->free_shipping && ($order->properties->summary->goods > $configuration->free_shipping_amount))
                                    <p style="margin:0; padding: 0;">Gratis</p>
                                @else
                                    <p style="margin:0; padding: 0;">kr {{$order->properties->summary->shipping}},-</p>
                                @endif
                                <p style="margin:0; padding: 0;">kr {{$order->properties->summary->discount}},-</p>
                                @if($order->properties->netaxept === "false")
                                <p style="margin:0; padding: 0;">kr 35,-</p>@endif
                                
                                <p style="margin:0; padding: 0;">kr {{$order->properties->summary->tax}},-</p>

                                @if($configuration->free_shipping && ($order->properties->summary->goods > $configuration->free_shipping_amount))
                                    <p style="margin:0; padding: 0;"><strong>kr {{$order->properties->summary->goods + 35}},-</strong></p>
                                @else
                                    <p style="margin:0; padding: 0;"><strong>kr {{$order->total}},-</strong></p>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>


<tr>
    <td bgcolor="#EDEFF0" height="2" style=" display: block; margin-bottom: 20px; margin-top: 20px;"></td>
</tr>

<tr>
    <td style="font-family: 'Open Sans', 'Helvetica', sans-serif;">
        <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
            @foreach ($order->products as $orderProduct)
            <tr>
                <td width="12%" valign="top" style="padding-top: 8px">
                    <a href="http://www.barefilter.no/">
                        <img src='{{$orderProduct->product->images->get(0)["url"]}}' alt="Barefilter" height="60" style="display: block;" border="0"
                        />
                    </a>
                </td>
                <td width="78%" valign="top">
                    <p style="margin:0; padding: 0; color:#1F54A3; font-size: 14px; ">{{$orderProduct->product->name}}</p>
                    <p style="margin:0; padding: 0; color: #aaa;">Varenummer: {{$orderProduct->product->sku}}</p>
                    <p style="margin:0; padding: 0; color: #aaa;">Antall: {{$orderProduct->amount}}</p>
                    <p style="margin:0; padding: 0; color: #aaa;">{{$orderProduct->subscription->name}}</p>

                </td>
                <td width="10%" valign="top">
                    @if($orderProduct->amount == 1)
                    <p style="margin:0; padding: 0; color: #aaa;">kr {{$orderProduct->product->price}},-</p>@endif @if($orderProduct->amount > 1)
                    <p style="margin:0; padding: 0; color: #aaa;">{{$orderProduct->amount}} stk.
                        <br>kr {{$orderProduct->product->price * $orderProduct->amount}},-</p>@endif
                </td>
            </tr>
            @endforeach
        </table>
    </td>
</tr>

<tr>
    <td bgcolor="#EDEFF0" height="2" style=" display: block; margin-bottom: 20px; margin-top: 20px;"></td>
</tr>

<tr>
    <td style="font-family: 'Open Sans', 'Helvetica', sans-serif;">
        <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
            <tr>
                <td width="50%" valign="top">
                    <p style="color: #26AEE4; font-size: 18px; ">Leveringsadresse</p>
                    <p style="margin:0; padding: 0; color: #aaa;">Send til:</p>
                    <p style="margin:0; padding: 0;">{{$order->shipping->first_name}} {{$order->shipping->last_name}}</p>
                    <p style="margin:0; padding: 0;">{{$order->shipping->street_address}}, {{$order->shipping->postal_code}} {{$order->shipping->city}}</p>
                    <p style="margin:0; padding: 0;">Tlf: {{$order->shipping->phone}}</p>
                    <p style="margin:0; padding: 0;">E-post: {{$order->shipping->email}}</p>
                </td>
                <td width="50%" valign="top">
                    <p style="color: #26AEE4; font-size: 18px; ">Fakturaadresse</p>
                    <p style="margin:0; padding: 0; color: #aaa;">Faktura til:</p>
                    <!--<p style="margin:0; padding: 0;">FantasyLab DA</p>-->
                    <p style="margin:0; padding: 0;">{{$order->billing->first_name}} {{$order->billing->last_name}}</p>
                    <p style="margin:0; padding: 0;">{{$order->billing->street_address}}, {{$order->billing->postal_code}} {{$order->billing->city}}</p>
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td style="font-family: 'Open Sans', 'Helvetica', sans-serif;">
        <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
            <tr>
                <td width="50%" valign="top">
                    <p style="color: #26AEE4; font-size: 18px; ">
                        <strong>Fraktmetode</strong>
                    </p>
                    <p style="margin:0; padding: 0; color: #aaa; font-size: 16px;">Valgt fraktmetode:</p>
                    <p style="margin:0; padding: 0; font-size: 14px;">Frakt : {{$order->properties->tas}}</p>
                    @if(isset($order->properties->service_partner) && $order->properties->service_partner !== null)
                    <p style="margin:0; padding: 0; font-size: 14px;">Pickup Point : {{$order->properties->service_partner->name}}</p>
                    <p style="margin:0; padding: 0; font-size: 14px;">Address : {{$order->properties->service_partner->address1}}</p>
                    @endif @if(isset($order->properties->company) && $order->properties->company !== null)
                    <p style="margin:0; padding: 0; font-size: 14px;">{{$order->properties->company->name}}</p>
                    <p style="margin:0; padding: 0; font-size: 14px;">{{$order->properties->company->number}}</p>
                    @endif
                </td>
                @if(isset($order->properties->notes) && $order->properties->notes !== "")
                <td width="50%" valign="top">
                    <p style="color: #26AEE4; font-size: 18px; ">Merknader</p>
                    <p style="margin:0; padding: 0; color: #aaa;">Din merknad:</p>
                    <p style="margin:0; padding: 0;">{{$order->properties->notes}}</p>
                </td>
                @endif
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td style="font-family: 'Open Sans', 'Helvetica', sans-serif;">
        <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
            <tr>
                <td width="50%" valign="top">
                    <p style="color: #26AEE4; font-size: 18px; ">
                        <strong>Betaling</strong>
                    </p>
                    <p style="margin:0; padding: 0; color: #aaa; font-size: 16px;">Betaling med:</p>
                    @if($order->payment_method_id === 1)
                    <p style="margin:0; padding: 0; font-size: 14px;">Netaxept</p>
                    @endif @if($order->payment_method_id === 2)
                    <p style="margin:0; padding: 0; font-size: 14px;">Faktura</p>
                    @endif
                </td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td bgcolor="#EDEFF0" height="2" style=" display: block; margin-bottom: 20px; margin-top: 20px;"></td>
</tr>