@extends('layouts.email')
@section('style')
    <style>
        tr,td {
            font-weight: 500;
            font-size: 15px;
        }
        p {
            line-height: 20px;
        }
    </style>
@endsection
@section('title', 'Din ordre er bekreftet')
@section('content')
<tr>
    <td>
        <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
            <tr>
                <td>
                    <table align="center" bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 650px;  padding-bottom: 20px; padding-left: 20px; padding-right: 20px; font-weight: 100; font-size:12px;">
                        
                        @include('emails.elements.order-details', ['order' => $order])
                        <tr>
                            <td style="padding-left: 20px; padding-right: 20px;font-family: 'Roboto', sans-serif;font-size: 14px;">
                                <p>Bare Filter er godkjent av Trygg e-Handel, det betyr at du kan kjenne deg trygg og
                                    sikker når du handler fra oss. Trygge-Handels formål er å beskytte deg som forbruker
                                    - kun stabile og seriøse nettbutikker kan bli godkjent til å benytte Trygg e-Handel
                                    merket. Du kan lese mer om Trygg e-Handel og dine rettigheter som forbruker på
                                    <a target="_blank" href="https://www.tryggehandel.no/butikker/bare-filter-as/" style="text-decoration: none;color: #2568ad;">www.tryggehandel.no.</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px; padding-right: 20px;font-family: 'Roboto', sans-serif;font-size: 14px; text-align: center;">
                                <img src="{{url('/')}}/img/tryggehandel.png"></img>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>

</tr>
@endsection
@section('scripts')
<script type="application/json+trustpilot">
    {
        "recipientEmail": "{{$order->user->email}}",
        "recipientName": "{{$order->user->first_name . ' ' . $order->user->last_name}}",
        "referenceId": "{{$order->user->id}}"
    }
</script>
@endsection