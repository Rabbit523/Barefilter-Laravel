@extends('layouts.public') @section('title', 'Handlekurv') @section('content')

    <section class="barefilter-cart content">
        <div class="container-fluid">
            <ol class="breadcrumb barefilter-breadcrumb">
                <li>
                    <a href="{{route('home')}}">Hjem</a>
                </li>
                <li class="active">Handlekurv</li>
            </ol>
            <barefilter-checkout></barefilter-checkout>
        </div>
    </section>

@endsection
@section('scripts')
<script>var user = @json(auth()->user()), cart = new Barefilter.Controllers.Cart();</script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular.min.js"></script>
<script src="/js/ng.barefilter-checkout.js"></script>
<script>
    // This function will be executed when the user scrolls the page.
    $(window).scroll(function(e) {
        if ($(window).width() <=480) {
            // Get the position of the location where the scroller starts.
            var scroller_anchor = $("#confirmation_footer_anchor").offset().top;
            // Check if the user has scrolled and the current position is after the scroller start location and if its not already fixed at the top 
            var _class = $('#confirmation_footer').attr('class').indexOf('mobile') == 0 ? true : false;
            if ($(this).scrollTop() + $(this).height() >= scroller_anchor && $('#confirmation_footer').css('position') != 'relative' && _class) 
            {    // If the user has scrolled back to the location above the scroller anchor place it back into the content.
                
                // Change the height of the scroller anchor to 0 and now we will be adding the scroller back to the content.
                $('#confirmation_footer_anchor').css('height', '0px');
                
                // Change the CSS and put it back to its original position.
                $('#confirmation_footer').css({
                    'position': 'relative',
                    'display': 'none'
                });
            } else if ($(this).scrollTop() + $(this).height() < scroller_anchor && $('#confirmation_footer').css('position') != 'fixed' && _class  ) 
            {    // Change the CSS of the scroller to hilight it and fix it at the top of the screen.
                $('#confirmation_footer').css({
                    // 'background': '#CCC',
                    // 'border': '1px solid #000',
                    'position': 'fixed',
                    'bottom': '0px',
                    'display': 'block'
                });
                // Changing the height of the scroller anchor to that of scroller so that there is no change in the overall height of the page.
                $('#confirmation_footer_anchor').css('height', '99px');
            } 
        }
    });
</script>
@endsection