<?php
include 'header.php';
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){
    // Search functionality
    $("#search-form").submit(function(e){
        e.preventDefault(); // Prevent page reload
        var keyword = $("#search-keyword").val().trim(); // Get input value

        if(keyword !== ""){
            $.ajax({
                url: "search.php", // Backend script for searching
                method: "POST",
                data: { search: keyword },
                success: function(response){
                    $("#get_product").html(response); // Update product display
                },
                error: function(){
                    alert("Search request failed! Please try again.");
                }
            });
        } else {
            alert("Please enter a keyword to search.");
        }
    });
});
</script>

<div class="main main-raised">
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <!-- ASIDE -->
                <div id="aside" class="col-md-3">
                    <!-- Search Form -->
                    <form id="search-form">
                        <input type="text" id="search-keyword" class="form-control" placeholder="Search products...">
                        <button type="submit" id="search-btn" class="btn btn-primary"><i class="fa fa-search"></i></button>
                    </form>
                    
                    <!-- aside Widget -->
                    <div id="get_category"></div>
                    <!-- /aside Widget -->

                    <!-- aside Widget -->
                    <div class="aside">
                        <h3 class="aside-title">Price</h3>
                        <div class="price-filter">
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
                    <!-- /aside Widget -->

                    <div id="get_brand"></div>
                    
                    <!-- aside Widget -->
                    <div class="aside">
                        <h3 class="aside-title">Top selling</h3>
                        <div id="get_product_home"></div>
                    </div>
                    <!-- /aside Widget -->
                </div>
                <!-- /ASIDE -->

                <!-- STORE -->
                <div id="store" class="col-md-9">
                    <!-- store top filter -->
                    <div class="store-filter clearfix">
                        <div class="store-sort">
                            <label>
                                Sort By:
                                <select class="input-select">
                                    <option value="0">Popular</option>
                                    <option value="1">Position</option>
                                </select>
                            </label>
                            <label>
                                Show:
                                <select class="input-select">
                                    <option value="0">20</option>
                                    <option value="1">50</option>
                                </select>
                            </label>
                        </div>
                        <ul class="store-grid">
                            <li class="active"><i class="fa fa-th"></i></li>
                            <li><a href="#"><i class="fa fa-th-list"></i></a></li>
                        </ul>
                    </div>
                    <!-- /store top filter -->

                    <!-- store products -->
                    <div class="row" id="product-row">
                        <div class="col-md-12 col-xs-12" id="product_msg"></div>
                        <div id="get_product">
                            <!-- Here we get product jQuery AJAX Request -->
                        </div>
                    </div>
                    <!-- /store products -->

                    <!-- store bottom filter -->
                    <div class="store-filter clearfix">
                        <span class="store-qty">Showing 20-100 products</span>
                        <ul class="store-pagination" id="pageno">
                            <li><a class="active" href="#aside">1</a></li>
                            <li><a href="#"><i class="fa fa-angle-right"></i></a></li>
                        </ul>
                    </div>
                    <!-- /store bottom filter -->
                </div>
                <!-- /STORE -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
</div>

<?php
include "newslettter.php";
include "footer.php";
?>
