<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Bare Filter | Ordrebekreftelse</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css' />
    @yield('style')
</head>

<body style="margin: 0; padding: 0; background: #EDEFF0;">
    <table cellpadding="0" cellspacing="0" width="100%" style="padding-top: 0px;">
        <tr>
            <td>
                <table cellpadding="0" align="center" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 650px;">
                    <tr>
                        <td height="100" bgcolor="#FFFFFF">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding-left: 20px; padding-right: 20px;">
                                <tr>
                                    <td width="60%" align="left">
                                        <a href="http://www.barefilter.no/">
                                            <img src="{{url('/')}}/img/barefilter-logo.svg" alt="Barefilter" height="40" style="display: block;" border="0" />
                                        </a>
                                    </td>
                                    <td width="40%" valign="right" style="font-weight: 700;padding-bottom: 10px; font-size:15px; font-family: 'Roboto', sans-serif;">
                                        @yield('title')
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @yield('content')
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                    <tr>
                        <td>
                            <table align="center" bgcolor="#EDEFF0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 650px; padding-top: 20px; font-size: 12px; color:#aaa;">
                                <tr align="center" style="font-family: 'Roboto', sans-serif;">
                                    <td>Med enerett. Bare filter AS. Webdesign & Webutvikling:
                                        <a href="https://fantasylab.no/" target="_blank" style="text-decoration: none; color:#1F54A3;">FantasyLab</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>

        </tr>
    </table>
    @yield('scripts')
</body>
</html>
