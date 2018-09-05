$( document ).ready(function() {

    // #############################################################################

    /**
     * Base setup
     */

    var baseUrl = window.location.origin + window.location.pathname;

    // #############################################################################

    function getUrlParams() {

        var urlParams = window.location.search.substring(1),
            paramsArr = urlParams.split('&'),
            pName  = '',
            pValue = '',
            params = {},
            l = paramsArr.length;

        for (var i = 0; i < l; i++) {
            p = paramsArr[i].split('=');
            pName  = p[0];
            pValue = p[1];

            params[pName] = pValue;
        }

        return params;
    }

    function checkProperty(params, propertyName) {

        if ( ( params.hasOwnProperty(propertyName) ) &&
             ( params[propertyName] !== '' ) &&
             ( typeof params[propertyName] !== "undefined" )
        ) {
            return true;
        }
        return false;
    }

    $( "#scl-categories .scl-option" ).click(function() {

        var categoryId = $(this).data("categoryId"),
            params = getUrlParams(),
            // host   = window.location.host,
            paramNames = ['ob', 'o'],
            sclUrl,
            propertyName;

        if ( categoryId === 0 ) {
            sclUrl = baseUrl + '?';
        } else {
            sclUrl = baseUrl + '?c=' + categoryId;
        }

        for (var i = 0; i < paramNames.length; i++) {

            propertyName = paramNames[i];

            if ( checkProperty(params, propertyName) ) {
                sclUrl += '&' + propertyName + "=" + params[propertyName];
            }
        }

        if ( sclUrl == baseUrl + '?' ) {
            sclUrl = baseUrl;
        }

        window.location.href = sclUrl;
    });

    function getCategoryId() {

        var params = getUrlParams();

        if ( checkProperty(params, 'c') ) {
            return params.c;
        }
        return 0;
    }

    function setCategoryName() {
        var categoryId = getCategoryId(),
            categoryName;

        if ( categoryId === 0 ) {
            categoryName = 'Все категории';
        } else {
            categoryName = $('#scl-categories .scl-option[data-category-id="' + categoryId + '"]').text();
        }

        $("#scl-categories-title").html( categoryName + ' &blacktriangledown;' );
    }

    // #############################################################################

    /**
     * Search
     */

    function searchEngine() {
        var searchValue = document.getElementById("search-text"),
            searchString,
            sclUrl;

        if ( typeof searchValue.value != 'undefined' ) {
            searchString = searchValue.value;
        } else {
            searchString = '';
        }

        sclUrl = baseUrl + '?s=' + encodeURI( searchString );

        if ( searchString !== '' ) {
            window.location.href = sclUrl;
        }
    }

    $( "#scl-submit-button" ).click(function() {
        searchEngine();
    });

    $( document ).keypress(function(e) {
        if ( e.which == 13 ) {
            searchEngine();
        }
    });

    // #############################################################################

    /**
     * Set sizes of main blocks
     */

    function getScreenSize() {
        var h = $( window ).height();
        var w = $( window ).width();

        var size = {height: h, width: w};

        return size;
    }

    function setHeaderSize(scrWidth, headerHeight) {
        $( "#scl-header" ).height( headerHeight );
        $( "#scl-header" ).width( scrWidth );
    }

    function setProductsSize(scrHeight, scrWidth, helpersHeight) {

        var productsHeight = scrHeight - helpersHeight;

        $( "#scl-products" ).height( productsHeight );
        $( "#scl-products" ).width( scrWidth );
    }

    function setPaginationSize(scrWidth, paginationHeight) {
        $( "#scl-pagination" ).height( paginationHeight );
        $( "#scl-pagination" ).width( scrWidth );
    }

    function setTradePosition(scrHeight, scrWidth) {
        var tradeHeight = $( "#scl-product-trade .wrapper" ).height();
        var tradeWidth  = $( "#scl-product-trade .wrapper" ).width();

        $( "#scl-product-trade .wrapper" ).css({
            top: ((scrHeight - tradeHeight - 40) / 2),
            left: ((scrWidth - tradeWidth - 100) / 2)
        });
    }

    function setProductEditPosition(scrHeight, scrWidth) {
        var h = $( "#scl-product-edit .edit-wrapper" ).height();
        var w  = $( "#scl-product-edit .edit-wrapper" ).width();

        $( "#scl-product-edit .edit-wrapper" ).css({
            top: ((scrHeight - h - 40) / 2),
            left: ((scrWidth - w - 100) / 2)
        });
    }

    function setProductDeletePosition(scrHeight, scrWidth) {
        var h = $( "#scl-product-delete .delete-wrapper" ).height();
        var w  = $( "#scl-product-delete .delete-wrapper" ).width();

        $( "#scl-product-delete .delete-wrapper" ).css({
            top: ((scrHeight - h - 40) / 2),
            left: ((scrWidth - w - 100) / 2)
        });
    }

    function setSizes() {

        var screen = getScreenSize();

        var scrHeight = screen.height;
        var scrWidth  = screen.width;

        var headerHeight = 75,
            paginationHeight = 27;

        var helpersHeight = headerHeight + paginationHeight;

        setHeaderSize(scrWidth, headerHeight);
        setProductsSize(scrHeight, scrWidth, helpersHeight);
        setPaginationSize(scrWidth, paginationHeight);
        setTradePosition(scrHeight, scrWidth);
        setProductEditPosition(scrHeight, scrWidth);
        setProductDeletePosition(scrHeight, scrWidth);
    }

    // #############################################################################

    /**
     * Set sizes of columns annotations
     */

    function setProductsHeaderWidth() {

        var sizes = [];

        $( "#scl-product-data thead th" ).each(function() {
            var thWidth = $( this ).width();
            sizes.push( thWidth );
        });

        for (i = 0; i < sizes.length; i++) {
            $( "#scl-header-bot div" ).eq( i ).width( sizes[i] - 16 );
        }

    }

// #############################################################################

    function setControlsSize() {
        var screen = getScreenSize();

        var scrHeight = screen.height;
        var scrWidth  = screen.width;

        $( "#scl-control-edit" ).height( scrHeight );
        $( "#scl-control-edit" ).width( scrWidth );
    }

// #############################################################################

    setCategoryName();
    setSizes();
    setProductsHeaderWidth();

    $( window ).resize(function() {
        setSizes();
        setProductsHeaderWidth();
        setControlsSize();
    });

// #############################################################################

    $( "#scl-categories-title" ).on( "mouseenter", function() {
        $( "#scl-categories-wrapper" ).fadeIn( "fast");
    });

    $( "#scl-categories-wrapper" ).on( "mouseleave", function() {
        $( "#scl-categories-wrapper" ).fadeOut( "fast");
    });

    if (document.readyState === 'complete') {
        setProductsHeaderWidth();
        $( "#scl-header-bot > div" ).fadeIn(400);
    }

// #############################################################################
// #############################################################################
// #############################################################################

    // GET PRODUCT BY ID ###################################################
    // #####################################################################

    function getDataById( productId ) {

        $.ajax({

            method: "POST",
            data: { ajax_request: "get_product_data", product_id: productId }

        }).done(function( returnData ) {

            $("#scl-product-by-id-wrapper .close").click(function() {
                $("#scl-product-by-id-wrapper").hide();
                $("#search-by-id").val('');
            });

            var obj = JSON.parse(returnData);

            var categories_set = '';

            obj['categories_set'].forEach(function(element) {

                if (element !== null) {
                    $("#scl-product-by-id-wrapper").show();
                } else {
                    $("#scl-product-by-id-wrapper").hide();
                }

                categories_set += ' > ' + element;
            }, this);

            $("#scl-product-by-id-wrapper .cross-code").html('<span>Кросс-номер:</span> ' + obj['cross_code']);

            $("#scl-product-by-id-wrapper .firm").html('<span>Производитель:</span> ' + obj['firm']);

            $("#scl-product-by-id-wrapper .orig-code").html('<span>Ориг. номер:</span> ' + obj['orig_code']);

            $("#scl-product-by-id-wrapper .name").html('<span>Наименование:</span> ' + obj['name']);

            $("#scl-product-by-id-wrapper .characteristic").html('<span>Характеристики:</span> ' + obj['characteristic']);

            $("#scl-product-by-id-wrapper .category-id").html('<span>Категория:</span> ' + categories_set);

            $("#scl-product-by-id-wrapper .price").html('<span>Цена:</span> ' + obj['price'] + ' р.');

        });
    }

    $("#search-by-id").keyup(function() {
        var id = $(this).val();
        getDataById( id );
    });

    // COMMON ##############################################################
    // #####################################################################

    function replacePrice() {
        $('.scl-prod-price-rub').each(function() {
            var text = $(this).text();
            $(this).html(text.replace(' ', '&nbsp;'));
        });
    }

    replacePrice();

});
