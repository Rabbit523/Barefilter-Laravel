@extends('layouts.email') @section('title', 'Borettslag & sameie') @section('content')
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
                            <td style="font-family: 'Open Sans', 'Helvetica', sans-serif;">Hei
                                <b>{{$properties->contact->first_name}} {{$properties->contact->last_name}}</b>,</td>
                        </tr>
                        <tr>
                            <td style="font-family: 'Open Sans', 'Helvetica', sans-serif; font-size: 18px; color: #26AEE4; padding-top: 10px; padding-bottom: 10px;">
                                <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                                    <tr>
                                        <td width="2%">
                                            <img src="{{url('/')}}/img/check-mark.svg" alt="Barefilter" height="25" style="display: block;" border="0" />
                                        </td>
                                        <td width="50%" style=" font-weight: bold;">
                                            Din henvendelse er mottatt!
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-family: 'Open Sans', 'Helvetica', sans-serif;">Takk for din henvendelse. Vi til ta kontakt i løpet av kort tid. Vi vil gjøre vårt ytterste for
                                å gi dere den beste prisen for deres borettslag og sameie.</td>
                        </tr>
                        <tr>
                            <td bgcolor="#EDEFF0" height="2" style=" display: block; margin-bottom: 20px; margin-top: 20px;"></td>
                        </tr>

                        <tr>
                            <td style="font-family: 'Open Sans', 'Helvetica', sans-serif;">
                                <p style="color: #32AEE3; font-size: 14px; margin-top: 0; font-weight: bold;">ANTALL LEVERINGER</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-family: 'Open Sans', 'Helvetica', sans-serif; padding-top:10px;">
                                <table cellpadding="0" cellspacing="0" width="50%" style="border-collapse: collapse; ">
                                    <tr>
                                        <td width="30%" style="border: 1px solid #bdbdbd; background-color: #f5f5f5; padding-left: 10px;">
                                            <p>{{$properties->subscription}}</p>
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
                                <p style="color: #32AEE3; font-size: 14px; margin-top: 0; font-weight: bold;">ØNSKER DU FILTERBYTTE?</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-family: 'Open Sans', 'Helvetica', sans-serif; padding-top:10px;">
                                <table cellpadding="0" cellspacing="0" width="50%" style="border-collapse: collapse; ">
                                    <tr>
                                        <td width="30%" style="border: 1px solid #bdbdbd; background-color: #f5f5f5; padding-left: 10px;">
                                            <p>{{$properties->filterbytte}}</p>
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
                                <p style="color: #32AEE3; font-size: 14px; margin-top: 0; font-weight: bold;">KONTAKTINFORMASJON</p>

                            </td>
                        </tr>
                        <tr>
                            <td style="font-family: 'Open Sans', 'Helvetica', sans-serif; padding-top:10px;">
                                <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; ">
                                    <tr>
                                        <td width="30%" style="border: 1px solid #bdbdbd; background-color: #f5f5f5; border-radius: 5px; padding-left: 10px;">
                                            <p>Fornavn: {{$properties->contact->first_name}}</p>
                                        </td>
                                        <td width="1%"> </td>
                                        <td width="30%" style="border: 1px solid #bdbdbd; background-color: #f5f5f5; padding-left: 10px;">
                                            <p>Etternavn: {{$properties->contact->last_name}}</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-family: 'Open Sans', 'Helvetica', sans-serif; padding-top:10px;">
                                <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; ">
                                    <tr>
                                        <td width="30%" style="border: 1px solid #bdbdbd; background-color: #f5f5f5; padding-left: 10px;">
                                            <p>Telefonnummer: {{$properties->contact->phone}}</p>
                                        </td>
                                        <td width="1%"> </td>
                                        <td width="30%" style="border: 1px solid #bdbdbd; background-color: #f5f5f5; padding-left: 10px;">
                                            <p>E-post: {{$properties->contact->email}}</p>
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
                                <p style="color: #32AEE3; font-size: 14px; margin-top: 0; font-weight: bold;">BORETTSLAGETSADRESSE</p>
                                <p style="margin:0; padding: 10px; border: 1px solid #bdbdbd; background-color: #f5f5f5; width: 96%;">Adresse: {{$properties->location->street_address}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-family: 'Open Sans', 'Helvetica', sans-serif; padding-top:10px;">
                                <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; ">
                                    <tr>
                                        <td width="30%" style="border: 1px solid #bdbdbd; background-color: #f5f5f5; padding-left: 10px;">
                                            <p>Postnummer: {{$properties->location->postal_code}}</p>
                                        </td>
                                        <td width="1%"> </td>
                                        <td width="30%" style="border: 1px solid #bdbdbd; background-color: #f5f5f5; border-radius: 5px; padding-left: 10px;">
                                            <p>Poststed: {{$properties->location->city}}</p>
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
                                <p style="color: #32AEE3; font-size: 14px; margin-top: 0; font-weight: bold;">BESKRIVELSE</p>
                                <p style="margin:0; padding: 10px; background-color: #bdbdbd; border: 1px solid #bdbdbd;  background-color: #f5f5f5;">{{$properties->description}}</p>
                            </td>
                        </tr>

                        <tr>
                            <td bgcolor="#EDEFF0" height="2" style=" display: block; margin-bottom: 20px; margin-top: 20px;"></td>
                        </tr>
                        <tr>
                            <td style="font-family: 'Open Sans', 'Helvetica', sans-serif;">Har du spørsmål rundt henvendelsen? ring oss på
                                <span style="color:#32AEE3;">+47 47 14 5000</span> eller send oss en e-post til
                                <span style="color:#32AEE3;">kontakt@barefilter.no</span>.</td>
                        </tr>




                    </table>
                </td>
            </tr>
        </table>
    </td>

</tr>

@endsection
