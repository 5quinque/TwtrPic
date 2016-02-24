@section('footer')

<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="https://npmcdn.com/masonry-layout@4.0/dist/masonry.pkgd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.2.0/imagesloaded.pkgd.min.js"></script>

<script>
    // The latest image on the page
    var latest_id = {{ $latest_id }};
    var oldest_id = {{ $oldest_id }};

    // How many images have loaded
    var images_loaded = 0;

    // Can we get newer images yet?
    var can_update = false;

    // Masonry Grid
    var $grid = $('.grid').imagesLoaded().progress( function() {
        $grid.masonry({
            itemSelector: '.grid-image-item',
            columnWidth: '.grid-sizer',
            percentPosition: true,
        });
        images_loaded += 1;

        if (images_loaded == 80) {
            console.log("all images loaded");
            can_update = true;
        }
    });

    $(document).ready(function(){
        // Enlarge images when hovering
        $(document).on('mouseenter', '.twit_img', function() {
            $(this).addClass('transition');
        });
        $(document).on('mouseleave', '.twit_img', function() {
            $(this).removeClass('transition');
        });



        // Clicking on settings icon
        $('.icon-setting').on('click', function() {
           $(this).toggleClass('fa-spin');
            setTimeout( function(){ $('.icon-setting').removeClass('fa-spin'); }, 400);
           $(this).parent().toggleClass('settings-full');
        });

        // Clicking on search field
        $('#searchbox').on('focusin', function() {
            $(this).parent().addClass('search-full');
        });
        $('#searchbox').on('focusout', function() {
            $(this).parent().removeClass('search-full');
        });
    });

    //$(function() {
        function update(loadFrom, newer_or_older) {

            // Don't start loading new images,
            // Until all images on the page
            // have loaded
            if (!can_update) {
                console.log("Not allowed to update yet");
                return false;
            }

            var elems = [];
            var toLoad = [];
           $.ajax({
               method: "GET",
               url: "/updategrid/%23selfie/" + newer_or_older + "/" + loadFrom
           }).done(function( images ) {
               $.each(images.data, function(k,v) {
                   console.log(v.image_location);
                    elems.push( $("<div class=\"grid-image-item\"><img class=\"twit_img\" src=\"image/"+v.image_md5_hash+".jpg\"></div>") );

                   toLoad.push("image/"+v.image_md5_hash+".jpg");

                   // Update our ID vars
                   if (latest_id === loadFrom) {
                       latest_id = v.id;
                   } else if (oldest_id === loadFrom) {
                       oldest_id = v.id;
                   }

               });

               var promises = [];

               for (var i = 0; i < toLoad.length; i++) {
                   (function(url, promise) {
                       var img = new Image();
                       img.onload = function() {
                           promise.resolve();
                       };
                       img.src = url;
                   })(toLoad[i], promises[i] = $.Deferred());
               }

               $.when.apply($, promises).done(function() {
                   console.log('Preloading done');

                   var $items = $( elems );
                   $.each($items, function(i,v) {
                       $grid.prepend(v).masonry('prepended', v);
                   });
               });
           });
        }
       setInterval(function() {update(latest_id, 'newerthan')}, 5000);
    //});

    // If user scrolls down, at a certain point
    // we want to load older images
    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() == $(document).height()) {// && images_loaded == 80) {
            console.log('Can load more images');
            //update(oldest_id, 'olderthan');
        }
    });

</script>
@stop