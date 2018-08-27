<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <!-- Google Tag Manager -->
        {{--<script src="https://connect.facebook.net/signals/plugins/identity.js?v=2.8.6" async=""></script>--}}
        {{--<script src="https://connect.facebook.net/signals/config/876783445796110?v=2.8.6&amp;r=stable" async=""></script>--}}
        {{--<script async="" src="https://connect.facebook.net/en_US/fbevents.js"></script>--}}
        {{--<script async="" charset="utf-8" src="https://v2.zopim.com/?4YZS2GvFzfmqbb8nPvgTlJlA5xjEGn6e" type="text/javascript"></script>--}}
        {{--<script type="text/javascript" async="" src="https://www.google-analytics.com/analytics.js"></script>--}}
        {{--<script async="" src="https://www.googletagmanager.com/gtm.js?id=GTM-NMP27Q7"></script>--}}
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-NMP27Q7');
        </script>
        <!-- End Google Tag Manager -->
        <!-- Hotjar Tracking Code for www.barefilter.no -->
        <script>
            (function(h,o,t,j,a,r){
                h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                h._hjSettings={hjid:477975,hjsv:6};
                a=o.getElementsByTagName('head')[0];
                r=o.createElement('script');r.async=1;
                r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                a.appendChild(r);
            })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
        </script>
        <!-- End Hotjar Tracking Code for www.barefilter.no -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>@yield('title') | Kvalitetsfilter til enebolig og industribygg - Barefilter.no</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">

        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="/css/barefilter.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <script src="/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>

        <script>var config = @json($configuration); config.server = "<?= route("barefilter-api", [],false) ?>/"; config.token = "<?= csrf_token() ?>";</script>
        <!--Start of Zendesk Chat Script-->
        <script type="text/javascript">
            window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
            d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
            _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
            $.src="https://v2.zopim.com/?4YZS2GvFzfmqbb8nPvgTlJlA5xjEGn6e";z.t=+new Date;$.
            type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
        </script>
        <!--End of Zendesk Chat Script-->
    </head>
    <body class="barefilter {{$page}}" @isset($ngApp) ng-app="{{$ngApp}}" @endisset>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        @include('elements.header')
        @include('elements.navigation')
        @yield('content')
        @include('elements.footer')
        @include('elements.modals.application')
        <script src="/js/vendor/jquery-1.11.2.js"></script>
        <script src="/js/vendor/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
        <script src="/js/barefilter.js"></script>
        <script>jQuery(function(){window.barefilterStore = new Barefilter.Controllers.Store();});</script>
        @yield('scripts')
    </body>
</html>
