$( document ).ready(function() {

    // #############################################################################

    /**
     * Base setup
     */

    var baseUrl = window.location.origin + window.location.pathname;

    // #############################################################################

    /**
     * Set the form on login page in the middle of screen
     */

    function alignHeight() {

        var pageHeight = $( window ).height(),
            containerHeight = $( "#login-page .container" ).height();

        if ( pageHeight > containerHeight ) {
            $( "#login-page .container" ).css( "padding", function( index ) {
                return (pageHeight - containerHeight) / 2;
            });
        }
    }

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
            params = getUrlParams(),
            // host   = window.location.host,
            sclUrl;

        if ( typeof searchValue.value != 'undefined' ) {
            searchString = searchValue.value;
        } else {
            searchString = '';
        }

        sclUrl = baseUrl + '?s=' + encodeURI( searchString );

        if ( checkProperty(params, 'c') ) {
            sclUrl += '&c=' + params.c;
        }

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

    function setFooterSize(scrWidth, footerHeight) {
        $( "#scl-footer" ).height( footerHeight );
        $( "#scl-footer" ).width( scrWidth );
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

    function setSizes() {

        var screen = getScreenSize();

        var scrHeight = screen.height;
        var scrWidth  = screen.width;

        var headerHeight = 75,
            paginationHeight = 27,
            footerHeight = 40;

        var helpersHeight = headerHeight + paginationHeight + footerHeight;

        setHeaderSize(scrWidth, headerHeight);
        setProductsSize(scrHeight, scrWidth, helpersHeight);
        setPaginationSize(scrWidth, paginationHeight);
        setFooterSize(scrWidth, footerHeight);
        setTradePosition(scrHeight, scrWidth);
        setProductEditPosition(scrHeight, scrWidth);
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
        if ( $( "#login-page" ).length ) {
            alignHeight();
        }
        setControlsSize();
    });

// #############################################################################

    function logout() {
        // var host   = window.location.host,
        var sclUrl = baseUrl + '?logout=1';
        window.location.href = sclUrl;
    }

// #############################################################################

    $( "#scl-categories-title" ).on( "mouseenter", function() {
        $( "#scl-categories-wrapper" ).fadeIn( "fast");
    });

    $( "#scl-categories-wrapper" ).on( "mouseleave", function() {
        $( "#scl-categories-wrapper" ).fadeOut( "fast");
    });

    $( "#logout" ).on( "click", function() {
        logout();
    });

    if (document.readyState === 'complete') {
        setProductsHeaderWidth();
        $( "#scl-header-bot > div" ).fadeIn(400);
    }

    if ( $( "#login-page" ).length ) {
        alignHeight();
    }

// #############################################################################
// #############################################################################
// #############################################################################

    // SCL CATEGORY EDIT ###################################################
    // #####################################################################

    $( "#footer-controls .new-category" ).on( "click", function() {
        $( "#scl-control-edit" ).fadeIn( 400 );
        $( "#category-edit-block" ).fadeIn( 400 );
        setControlsSize();
    });

    // Check if New Category is Needed ####################
    function setCategoryBlock() {
        var catEditType = $('input[name=category-edit]:checked').val();

        if ( catEditType === "new" ) {
            $( "#category-new" ).show();
            $( "#category-old" ).hide();
        }

        if ( catEditType === "old" ) {
            $( "#category-new" ).hide();
            $( "#category-old" ).show();
        }
    }

    setCategoryBlock();

    $( "#category-edit-block label.choose" ).on( "click", function() {
        setCategoryBlock();
    });

    // Check if New Optgroup is Needed ####################
    function checkOptgroup() {
        if( $( "#check-optgroup" ).is( ":checked" ) ) {
            $( "#new-optgroup" ).show();
            $( "#current-optgroup" ).hide();
        } else {
            $( "#new-optgroup" ).hide();
            $( "#current-optgroup" ).show();
        }
    }

    checkOptgroup();

    $( "#check-optgroup-label" ).on( "click", function() {
        checkOptgroup();
    });

    // ADD NEW PRODUCT #####################################################
    // #####################################################################

    $( "#footer-controls .new-good" ).on( "click", function() {
        $( "#scl-product-edit h2" ).html( "Добавление нового товара" );
        $( "#product-edit-type" ).val( "new" );
        $( ".old-quantity-only" ).hide();
        $( ".new-quantity-only" ).show();
        $( "input.input-text" ).val("");
        $("#new-good-category").select2();
        $( "#scl-product-edit" ).fadeIn( 400 );
        setSizes();
    });

    $( "#product-edit-close" ).on( "click", function()  {
        $( "#scl-product-edit" ).fadeOut( 400 );
    });

    // EDIT OLD PRODUCT ####################################################
    // #####################################################################

    function fillProductData( productId ) {

        $.ajax({

            method: "POST",
            data: { ajax_request: "get_product_data", product_id: productId }

        }).done(function( returnData ) {

            var obj = JSON.parse(returnData);

            $("input[name=new-cross-code]").val(obj['cross_code']);

            $("input[name=new-orig-code]").val(obj['orig_code']);

            $("input[name=new-name]").val(obj['name']);

            $("input[name=new-characteristic]").val(obj['characteristic']);

            $("#old-good-category").val( obj['category_id'] );

            $("input[name=new-price]").val(obj['price']);

            $("input[name=new-place]").val(obj['place']);

        });
    }

    $( ".scl-prod-edit .sharp" ).on("click", function() {
        var productId = $( this ).data( "prodEditId" );
        $( "#scl-product-edit h2" ).html( "Редактирование имеющегося товара" );
        $( "#product-edit-type" ).val( "old" );
        $( "#goods-old-id" ).val( productId );
        $( ".new-quantity-only" ).hide();
        $( ".old-quantity-only" ).show();

        fillProductData( productId );

        $( "#scl-product-edit" ).fadeIn( 400 );
        setSizes();
    });

    // Check if New Good is Needed ########################

        // function setGoodsBlock() {
        //     var catEditType = $('input[name=goods-edit]:checked').val();

        //     if ( catEditType === "new" ) {
        //         $( "#goods-new" ).show();
        //         $( "#goods-old" ).hide();
        //         $( "#submit-goods" ).val( "Добавить" );
        //     }

        //     if ( catEditType === "old" ) {
        //         $( "#goods-new" ).hide();
        //         $( "#goods-old" ).show();
        //         $( "#submit-goods" ).val( "Изменить" );
        //     }
        // }

        // setGoodsBlock();

        // $( "#goods-edit-block label.choose" ).on( "click", function() {
        //     setGoodsBlock();
        // });

    // Image Input ########################################

    function showImagePreview(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#good-image-preview').attr('src', e.target.result);
                $('#good-image-preview').show( 400 );
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#new-image-input").change(function(){
        showImagePreview(this);
    });

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

            $("#scl-product-by-id-wrapper .orig-code").html('<span>Ориг. номер:</span> ' + obj['orig_code']);

            $("#scl-product-by-id-wrapper .name").html('<span>Наименование:</span> ' + obj['name']);

            $("#scl-product-by-id-wrapper .characteristic").html('<span>Характеристики:</span> ' + obj['characteristic']);

            $("#scl-product-by-id-wrapper .category-id").html('<span>Категория:</span> ' + categories_set);

            $("#scl-product-by-id-wrapper .price").html('<span>Цена:</span> ' + obj['price'] + ' р.');

            $("#scl-product-by-id-wrapper .place").html('<span>Место:</span> ' + obj['place']);

        });
    }

    $("#search-by-id").keyup(function() {
        var id = $(this).val();
        getDataById( id );
    });

    // #####################################################################
    // #####################################################################

    $( "#scl-product-data .plus" ).on('click', function(event) {

        var prodId = $(this).data('prodId');
        var prodQuant = $(this).data('prodQuant');

        $( "#scl-product-trade" ).show( 0 );
        $( "#trade-form-plus" ).show( 0 );

        $( "#trade-form-plus .trade-first" ).html( prodQuant );

        $( "#trade-form-plus .trade-amount" ).html( prodQuant );

        $( "#trade-form-plus input[name=trade-id]" ).val( prodId );

        var screen = getScreenSize();

        var scrHeight = screen.height;
        var scrWidth  = screen.width;

        setTradePosition(scrHeight, scrWidth);
    });

    $( "#scl-product-data .minus" ).on('click', function(event) {

        var prodId = $(this).data('prodId');
        var prodQuant = $(this).data('prodQuant');

        $( "#scl-product-trade" ).show( 0 );
        $( "#trade-form-minus" ).show( 0 );

        $( "#trade-form-minus .trade-first" ).html( prodQuant );

        $( "#trade-form-minus .trade-amount" ).html( prodQuant );

        $( "#trade-form-minus input[name=trade-id]" ).val( prodId );

        var screen = getScreenSize();

        var scrHeight = screen.height;
        var scrWidth  = screen.width;

        setTradePosition(scrHeight, scrWidth);
    });

    $( "#scl-product-trade .trade-close" ).on('click', function(event) {
        $("input[name=trade-second]").val( "" );
        $( "#trade-form-plus" ).hide( 0 );
        $( "#trade-form-minus" ).hide( 0 );
        $( "#scl-product-trade" ).hide( 0 );
    });

    $("#trade-form-plus input[name=trade-second]").keyup(function(event) {
        var first = $( "#trade-form-plus .trade-first" ).html();
        var second = $(this).val();
        var amount = Number(first) + Number(second);
        $( "#trade-form-plus .trade-amount" ).html( amount );
        $("#trade-form-plus input[name=trade-amount]").val( amount );
    });

    $("#trade-form-minus input[name=trade-second]").keyup(function(event) {
        var first = $( "#trade-form-minus .trade-first" ).html();
        var second = $(this).val();
        var amount = Number(first) - Number(second);
        if ( amount < 0 ) {
            $(this).val("");
            alert("Вы не можете продать больше " + first + " шт. товара. Введите верное значение.");
        }
        $( "#trade-form-minus .trade-amount" ).html( amount );
        $("#trade-form-minus input[name=trade-amount]").val( amount );
    });

    // CURRENCY ############################################################
    // #####################################################################

    $( "#currency" ).on( "click", function() {

        $( "#rate" ).replaceWith( "<input type=\"text\" id=\"input-rate\">" );

        $( "#input-rate" ).focus();

        $(document).keypress(function(e) {
            if(e.which == 13) {
                var rate = $( "#input-rate" ).val();

                $.ajax({

                    method: "POST",
                    data: { ajax_request: "set_currency_rate", currency_rate: rate }

                }).done(function( returnData ) {

                    if ( returnData ) {
                        sclUrl = baseUrl;
                        window.location.href = sclUrl;
                    }

                });
            }
        });
    });

    // BALANCE #############################################################
    // #####################################################################

    function renderBalance( type ) {

        $( "#scl-common-wrapper" ).fadeOut( 400 );
        $( "#scl-special-wrapper" ).fadeIn( 400 );

        function twoDigits(d) {
            if(0 <= d && d < 10) return "0" + d.toString();
            if(-10 < d && d < 0) return "-0" + (-1*d).toString();
            return d.toString();
        }

        function getCurrentDate() {

            var fullDate = new Date();

            var twoDigitMonth = twoDigits(1 + fullDate.getUTCMonth());
            var twoDigitDate  = twoDigits(fullDate.getUTCDate());

            var currentDate = fullDate.getFullYear() + "-" + twoDigitMonth + "-" + twoDigitDate;

            return currentDate;
        }

        var currentDate = getCurrentDate();

        function getBalanceData(currentDate) {

            $.ajax({

                method: "POST",
                data: { ajax_request: "get_balance_data", balance_date: currentDate, balance_type: type }

            }).done(function( returnData ) {

                if ( returnData ) {

                    $( "#scl-special-wrapper" ).html( returnData );

                    $('.csc-cm').each(function() {
                        var cscText = $(this).html();
                        $(this).html(cscText.replace(' ', '&nbsp;'));
                    });

                    $( "#datepicker" ).val( function() {
                        if ( currentDate !== "all" ) {
                            return currentDate;
                        } else {
                            $( "#balance-all" ).addClass( "active" );
                        }
                    });

                    $( "#datepicker" ).datepicker({
                        dateFormat: "yy-mm-dd",
                        firstDay: 1
                    });

                    $( "#balance-close" ).on( "click", function()  {
                        $( "#scl-special-wrapper" ).fadeOut( 400 );
                        $( "#scl-common-wrapper" ).fadeIn( 400 );
                        $( "#scl-special-wrapper" ).empty();
                    });

                    $( "#balance-all" ).on( "click", function() {
                        getBalanceData("all");
                    });

                    $( "#datepicker" ).change(function() {
                        var newBalanceDate = $( "#datepicker" ).val();
                        getBalanceData(newBalanceDate);
                    });
                }
            });
        }

        getBalanceData(currentDate);

    };

    $( "#footer-controls .income" ).on( "click", function() {
        renderBalance( 'income' );
    });

    $( "#footer-controls .outcome" ).on( "click", function() {
        renderBalance( 'outcome' );
    });

    $( "#footer-controls .balance" ).on( "click", function() {
        renderBalance( 'balance' );
    });

    // COMMON ##############################################################
    // #####################################################################

    $( "#control-edit-close" ).on( "click", function()  {
        $( "#scl-control-edit" ).fadeOut( 400 );
        $( "#user-edit-block" ).fadeOut( 400 );
        $( "#category-edit-block" ).fadeOut( 400 );
        $( "#goods-edit-block" ).fadeOut( 400 );
    });

    function replacePrice() {
        $('.scl-prod-price-rub').each(function() {
            var text = $(this).text();
            $(this).html(text.replace(' ', '&nbsp;'));
        });
    }

    replacePrice();

});
