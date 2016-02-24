@extends('app')

@section('content')

    Searchhhhhhhing for "{{ $search_term }}"

@stop

@section('footer')
    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script src="https://npmcdn.com/masonry-layout@4.0/dist/masonry.pkgd.min.js"></script>

    <script>
        var $grid = $('.grid').masonry({
            itemSelector: '.grid-item',
            columnWidth: '.grid-sizer',
            percentPosition: true
        });

        var $grid = $('grid').imagesLoaded( function() {
            $grid.masonry({
                itemSelector: '.grid-item',
                columnWidth: '.grid-sizer',
                percentPosition: true
            });
        });

    </script>
@stop
