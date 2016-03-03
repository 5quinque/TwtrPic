@section('footer')

<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="//npmcdn.com/masonry-layout@4.0/dist/masonry.pkgd.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.2.0/imagesloaded.pkgd.min.js"></script>

<script>
    // The latest image on the page
    var latest_id = 0;
    var oldest_id = 0;

    // How many images have loaded
    var images_loaded = 0;

    var autoupdate = true;

    // Are we already trying to get some images?
    var gettingMoreImages = false;

    var unixTime = Date.now();

    // Masonry Grid
    var $grid = $('.grid').masonry({
            itemSelector: '.grid-image-item',
            columnWidth: '.grid-sizer',
            percentPosition: true,
        });

    update(latest_id, 'olderthan');

    var autoUpdateInterval = setInterval(function() {update(latest_id, 'newerthan')}, 5000);

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

        $('.switch').toggleClass('switch-full');
    });

    $("input[name='autoupdate']").on('change', function() {
        autoupdate = $(this).val();

        if (autoupdate) {
            console.log("No more auto updates");
            clearInterval(autoUpdateInterval);
        } else {
            console.log("Turning auto updates back on");
            //var autoUpdateInterval = setInterval(function() {update(latest_id, 'newerthan')}, 5000);
        }
    });

    // Clicking on search field
    $('#searchbox').on('focusin', function() {
        $(this).parent().addClass('search-full');
    });
    $('#searchbox').on('focusout', function() {
        $(this).parent().removeClass('search-full');
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
            console.log("We are already getting images!");
            return false;
        }

        return true;
    }

    function update(loadFrom, newer_or_older) {
        var toLoad = [];

        if (canWeUpdate()) {
            console.log("Setting gettingMoreImages true");
            gettingMoreImages = true;
        } else {
            return false;
        }

        $.ajax({
            method: "GET",
            url: "/updategrid/%23selfie/" + newer_or_older + "/" + loadFrom
        }).done(function( images ) {
            if (images.length == 0) {
                console.log('No images to update')
                gettingMoreImages = false;

                return false;
            }

            console.log("Latest ID is", images[0]);

            if (latest_id === loadFrom) {
                latest_id = images[0];
            } else if (oldest_id === loadFrom) {
                oldest_id = images[0];
            }

            $.each(images[1].data, function(k,v) {
                toLoad.push("image/"+v.image_md5_hash+".jpg?"+unixTime);
            });

            loadImages(toLoad, newer_or_older);
       });
    }

    function loadImages(toLoad, noo) {
        $(toLoad).each(function(i, v){
            var tmpImg = new Image();

            tmpImg.src = v;
            tmpImg.onload = function() {
                images_loaded += 1;
                $(tmpImg).addClass('twit_img');
                var tmpElem = $(tmpImg).wrap("<div class='grid-image-item'></div>").parent();

                if (noo == 'newerthan') {
                    $grid.prepend(tmpElem).masonry('prepended', tmpElem);
                } else {
                    $grid.append(tmpElem).masonry('appended', tmpElem);
                }

                toLoad = removeA(toLoad, v);

                if (toLoad.length == 0) {
                    console.log("All images loaded");
                    gettingMoreImages = false;
                }
            };
        });

    }

    function removeA(arr) {
        var what, a = arguments, L = a.length, ax;
        while (L > 1 && arr.length) {
            what = a[--L];
            while ((ax= arr.indexOf(what)) !== -1) {
                arr.splice(ax, 1);
            }
        }
        return arr;
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