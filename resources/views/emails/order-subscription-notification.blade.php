@extends('layouts.email')
@section('title', 'Filterabonnement')
@section('content')
<tr>
    <td>
        <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
            <tr>
                <td>
                    <table align="center" bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 600px;  padding-bottom: 20px; padding-left: 20px; padding-right: 20px; font-weight: 100; font-size:12px;">

                        <tr>
                            <td bgcolor="#EDEFF0" height="2" style=" display: block; margin-bottom: 20px;"></td>
                        </tr>
                        <tr>
                            <td style="font-family: 'Open Sans', 'Helvetica', sans-serif;">Hei <b>{{$admin->first_name}} {{$admin->last_name}}</b>,</td>
                        </tr>
                        <tr>
                            <td style="font-family: 'Open Sans', 'Helvetica', sans-serif; font-size: 18px; color: #26AEE4; padding-top: 10px; padding-bottom: 10px;">
                                <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                                    <tr>
                                        <td width="2%">
                                            <img src="{{url('/')}}/img/check-mark.svg" alt="Barefilter" height="25" style="display: block;" border="0" />
                                        </td>
                                        <td width="50%">
                                            Filterabonnement
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-family: 'Open Sans', 'Helvetica', sans-serif;">{{$order->user->first_name}} {{$order->user->last_name}} har lagt inn en ordre. Nedenfor finner du ordrebetaljene.</td>
                        </tr>

                        @include('emails.elements.order-details', ['order' => $order])

                        <tr>
                            <td style="font-family: 'Open Sans', 'Helvetica', sans-serif;">Har du spørsmål rundt ordren? Ring oss på <span style="color:#1F54A3;">+47 47 14 5000</span> eller send oss en e-post til <span style="color:#1F54A3;">kontakt@barefilter.no</span>.</td>
                        </tr>
                        <tr>
                            <td bgcolor="#EDEFF0" height="2" style=" display: block; margin-bottom: 20px; margin-top: 20px;"></td>
                        </tr>
                        <tr >
                            <td style="font-family: 'Open Sans', 'Helvetica', sans-serif;font-size: 12px;">
                                <p>Ved å legge inn en ordre, samtykker du til å ha lest Barefilter.no's <a href="/salgs-og-leveringsbetingelser/" style="color:#1F54A3;">Salgs og Leveringsbetingelser</a>. Produkter solgt av Barefilter.no er skattepliktig i Norge.</p>

                                <p>Denne e-posten ble sendt fra en automatisk e-postadresse som ikke mottar svar. Vennligst ikke svar på denne e-posten.</p>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>
    </td>

</tr>
@endsection
