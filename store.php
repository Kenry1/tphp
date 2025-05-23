<?php
include 'header.php';
?>
<script id="jsbin-javascript">
(function (global) {
    if (typeof (global) === "undefined") {
        throw new Error("window is undefined");
    }
    var _hash = "!";
    var noBackPlease = function () {
        global.location.href += "#";
        global.setTimeout(function () {
            global.location.href += "!";
        }, 50);
    };
    global.onhashchange = function () {
        if (global.location.hash !== _hash) {
            global.location.hash = _hash;
        }
    };
    global.onload = function () {
        noBackPlease();

        // Search functionality
        document.getElementById('search-button').onclick = function () {
            var query = document.getElementById('search-input').value;
            if (query) {
                fetchProducts(query);
            }
        };
    };

    function fetchProducts(query) {
        fetch('search.php?q=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                document.getElementById('get_product').innerHTML = data.html; // Assuming your PHP returns HTML
            })
            .catch(error => console.error('Error fetching products:', error));
    }
})(window);
</script>

<div class="main main-raised">
    <div class="section">
        <div class="container">
            <div class="row">
                <div id="aside" class="col-md-3">
                    <div id="get_category"></div>
                    <div class="aside">
                        <h3 class="aside-title">Price</h3>
                        <div class="price-filter">
                            <div id="price-slider" class="noUi-target noUi-ltr noUi-horizontal">
                                <div class="noUi-base">
                                    <div class="noUi-origin" style="left: 0%;">
                                        <div class="noUi-handle noUi-handle-lower" data-handle="0" tabindex="0" role="slider" aria-orientation="horizontal" aria-valuemin="0.0" aria-valuemax="100.0" aria-valuenow="0.0" aria-valuetext="1.00" style="z-index: 5;"></div>
                                    </div>
                                    <div class="noUi-connect" style="left: 0%; right: 0%;"></div>
                                    <div class="noUi-origin" style="left: 100%;">
                                        <div class="noUi-handle noUi-handle-upper" data-handle="1" tabindex="0" role="slider" aria-orientation="horizontal" aria-valuemin="0.0" aria-valuemax="100.0" aria-valuenow="100.0" aria-valuetext="999.00" style="z-index: 4;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-number price-min">
                                <input id="price-min" type="number">
                                <span class="qty-up">+</span>
                                <span class="qty-down">-</span>
                            </div>
                            <span>-</span>
                            <div class="input-number price-max">
                                <input id="price-max" type="number">
                                <span class="qty-up">+</span>
                                <span class="qty-down">-</span>
                            </div>
                        </div>
                    </div>
                    <div id="get_brand"></div>
                    <div class="aside">
                        <h3 class="aside-title">Top selling</h3>
                        <div id="get_product_home"></div>
                    </div>
                </div>

                <!-- STORE -->
                <div id="store" class="col-md-9">
                    <!-- Search Bar -->
                    <div class="search-bar">
                        <input type="text" id="search-input" placeholder="Search products..." />
                        <button id="search-button">Search</button>
                    </div>

                    <!-- store products -->
                    <div class="row" id="product-row">
                        <div class="col-md-12 col-xs-12" id="product_msg"></div>
                        <div id="get_product">
                            <!-- Here we get product jquery Ajax Request -->
                        </div>
                    </div>

                    <!-- store bottom filter -->
                    <div class="store-filter clearfix">
                        <span class="store-qty">Showing 20-100 products</span>
                        <ul class="store-pagination" id="pageno">
                            <li><a class="active" href="#aside">1</a></li>
                            <li><a href="#"><i class="fa fa-angle-right"></i></a></li>
                        </ul>
                    </div>
                </div>
                <!-- /STORE -->
            </div>
        </div>
    </div>
</div>

<?php
include "newslettter.php";
include "footer.php";
?>
