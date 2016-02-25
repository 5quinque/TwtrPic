@section('footer')

<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="//npmcdn.com/masonry-layout@4.0/dist/masonry.pkgd.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.2.0/imagesloaded.pkgd.min.js"></script>

<script>
    // The latest image on the page
    var latest_id = {{ $latest_id }};
    var oldest_id = {{ $oldest_id }};

    // How many images have loaded
    var images_loaded = 0;

    // Can we get newer images yet?
    var initialImageLoadComplete = false;

    // Are we already trying to get some images?
    var gettingMoreImages = false;

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
            initialImageLoadComplete = true;
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

    function showBottomLoad() {
        if (canWeUpdate()) {
            $('.loading').show();
        } else {
            return false;
        }
    }

    function canWeUpdate() {
        if (gettingMoreImages) {
            console.log("We are already getting images!")
            return false;
        }

        if (!initialImageLoadComplete) {
            console.log("Initial images haven't finished loading yet");
            return false;
        }

        return true;
    }

    function update(loadFrom, newer_or_older) {
        // Updating is turned off
        //return false;

        if (canWeUpdate()) {
            gettingMoreImages = true;
        } else {
            return false;
        }

        var elems = [];
        var toLoad = [];

        console.log("Loading images from", loadFrom);

        $.ajax({
            method: "GET",
            url: "/updategrid/%23selfie/" + newer_or_older + "/" + loadFrom
        }).done(function( images ) {

            if (images.length == 0) {
                console.log('No images to update')
                gettingMoreImages = false;

                return false;
            }

            //console.log(images);

            console.log("Latest ID is", images[1]);

            if (latest_id === loadFrom) {
                latest_id = images[1];
            } else if (oldest_id === loadFrom) {
                oldest_id = images[1];
            }

            $.each(images[2].data, function(k,v) {
                //console.log(v.image_location);

                elems.push( $("<div class=\"grid-image-item\"><img class=\"twit_img\" src=\"image/"+v.image_md5_hash+".jpg\"></div>") );
                toLoad.push("image/"+v.image_md5_hash+".jpg");

            });

            loadImages(toLoad, elems, newer_or_older);
       });
    }
    setInterval(function() {update(latest_id, 'newerthan')}, 5000);

    function loadImages(toLoad, elems, noo) {

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
                
                if (noo == 'olderthan') {
                    $grid.append(v).masonry('appended', v);
                } else if (noo == 'newerthan') {
                    $grid.prepend(v).masonry('prepended', v);
                }

            });
        });

        gettingMoreImages = false;
    }



    //below taken from http://www.howtocreate.co.uk/tutorials/javascript/browserwindow
    function getScrollXY() {
        var scrOfX = 0, scrOfY = 0;
        if( typeof( window.pageYOffset ) == 'number' ) {
            //Netscape compliant
            scrOfY = window.pageYOffset;
            scrOfX = window.pageXOffset;
        } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
            //DOM compliant
            scrOfY = document.body.scrollTop;
            scrOfX = document.body.scrollLeft;
        } else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
            //IE6 standards compliant mode
            scrOfY = document.documentElement.scrollTop;
            scrOfX = document.documentElement.scrollLeft;
        }
        return [ scrOfX, scrOfY ];
    }

    //taken from http://james.padolsey.com/javascript/get-document-height-cross-browser/
    function getDocHeight() {
        var D = document;
        return Math.max(
                D.body.scrollHeight, D.documentElement.scrollHeight,
                D.body.offsetHeight, D.documentElement.offsetHeight,
                D.body.clientHeight, D.documentElement.clientHeight
        );
    }

    // If user scrolls down, at a certain point
    // we want to load older images
    $(window).scroll(function() {
        if (getDocHeight() == getScrollXY()[1] + window.innerHeight) {
            console.log('Scrolling hit the bottom');

            showBottomLoad();
            update(oldest_id, 'olderthan');

            console.log("Updating complete?");

        }
    });

</script>
@stop