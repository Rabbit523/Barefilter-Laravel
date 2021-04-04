@extends('layouts.email')
@section('title', 'Nye kunderregistrering')
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
                                            Bare Filter vokser dag for dag!
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-family: 'Open Sans', 'Helvetica', sans-serif;">{{$user->first_name}} {{$user->last_name}} har blitt kunde av Bare Filter.</td>
                        </tr>
                        <tr>
                            <td bgcolor="#EDEFF0" height="2" style=" display: block; margin-bottom: 20px; margin-top: 20px;"></td>
                        </tr>
                        <tr>
                            <td style="font-family: 'Open Sans', 'Helvetica', sans-serif;">Har du spørsmål om kontoen din, kan du ringe oss på
                                <span style="color:#1F54A3;">+47 47 14 5000</span> eller sende oss en e-post til
                                <span style="color:#1F54A3;">kontakt@barefilter.no</span>.</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
@endsection