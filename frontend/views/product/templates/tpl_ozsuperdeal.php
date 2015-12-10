<meta name="viewport" content="width=device-width">

<!-- 加载js loader -->
<script type="text/javascript">
if(typeof window.scriptLoader==='undefined')window.scriptLoader=(function(){ var e=document.createElement('script'),c=function(s,d){ if(d)e[d]=true;for(i in s){ e.src=s[i];document.write(e.outerHTML)}},l=function(){ c.call(this,arguments);return l},d='defer',a='async';l.load=l;l[d]=function(){ c.call(this,arguments,d);return l};l[a]=function(){ c.call(this,arguments,a);return l};return l})();
if(typeof window.compareVersion==='undefined')window.compareVersion=function(c,d){ c=c.split('.');d=d.split('.');while(c.length){ var b=c.shift(),a=d.shift();if(b!=a) return(b>a)} return true};
</script>

<!-- Import jQuery -->
<script type="text/javascript">
if(typeof jQuery === 'undefined' || !compareVersion(jQuery.fn.jquery, '1.8.3'))
scriptLoader('https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js');
</script>

<!-- Store JS -->
<script type="text/javascript">
scriptLoader('http://ebayimages.x10host.com/ozsuperdeal/assets/js/listing_script.js');
scriptLoader('http://ebayimages.x10host.com/ozsuperdeal/assets/js/zoom.min.js');

</script>


<!-- Store CSS -->
<!-- <link rel="stylesheet" href="http://social.thegoodguys.com.au/ebay_store/2015/listing.css"> -->

<link rel="stylesheet" href="http://ebayimages.x10host.com/ozsuperdeal/assets/css/listing_style.css">
<link rel="stylesheet" href="http://ebayimages.x10host.com/ozsuperdeal/assets/css/zoom.css">

<title>OZ SUPER DEAL eBay Store</title>

<div class="wrapper">

	<div class="container">
		<div class="header">
			<div class="logo">
				<a href="#" title="OZ SUPER DEAL ebay store"><img src="http://ebayimages.x10host.com/ozsuperdeal/assets/images/logo.png" width="235"></a>
			</div>

			<div class="search-container">
				<form action="#" method="get" name="Search">
				<input name="submit" type="submit" class="search-btn">
				<input name="_nkw" type="text" class="search-field" placeholder="SEARCH">
				</form>
			</div>
		</div> <!-- end header -->
		
		

	</div> <!-- end container -->
	
	<div class="features">
		<div class="container">
			<div class="feature-section">
			<img src="http://ebayimages.x10host.com/ozsuperdeal/assets/images/feature01.png">
			<p>
			<a href="#">Add OZ SUPER DEAL to<br>your favourite eBay Stores</a>
			</p>
			</div>
			<div class="feature-section">
			<img src="http://ebayimages.x10host.com/ozsuperdeal/assets/images/feature02.png">
			<p>
			<a href="#">About our service</a>
			</p>
			</div>
			<div class="feature-section">
			<img src="http://ebayimages.x10host.com/ozsuperdeal/assets/images/feature03.png">
			<p>
			<a href="#">Sign up to get news<br>about hot products<br>and deals</a>
			</p>
			</div>
			<div class="feature-section">
			<img src="http://ebayimages.x10host.com/ozsuperdeal/assets/images/feature04.png">
			<p>
			<a href="#">Contact Us</a>
			</p>
			</div>
		</div>
	</div> <!-- end top four marketing link -->
	
	<div class="content listing">
		<div class="container">
			
			
			<h3><?php echo $product->name; ?></h3>
			<div class="gallery">
				<div class="zoom-section">
					<div class="zoom-small-image">
						<div id="wrap" style="top:0px;z-index:9999;position:relative;">
							<a href="products/01.jpg" class="cloud-zoom" id="zoom1" rel="position:'outside',showTitle:false,adjustX:-4,adjustY:-4" style="position: relative; display: block;">
								<img src="products/01.jpg" style="display: block;">
							</a>
						</div>
					</div>
					<div class="zoom-desc">
						<p>
						<a href="products/01.jpg" class="cloud-zoom-gallery" rel="useZoom: 'zoom1', smallImage: 'products/01.jpg' "><img class="zoom-tiny-image" src="products/01.jpg"></a>
						<a href="products/02.jpg" class="cloud-zoom-gallery" rel="useZoom: 'zoom1', smallImage: 'products/02.jpg' "><img class="zoom-tiny-image" src="products/02.jpg"></a>
						<a href="products/03.jpg" class="cloud-zoom-gallery" rel="useZoom: 'zoom1', smallImage: 'products/03.jpg' "><img class="zoom-tiny-image" src="products/03.jpg"></a>
						<a href="products/04.jpg" class="cloud-zoom-gallery" rel="useZoom: 'zoom1', smallImage: 'products/04.jpg' "><img class="zoom-tiny-image" src="products/04.jpg"></a>
						<a href="products/05.jpg" class="cloud-zoom-gallery" rel="useZoom: 'zoom1', smallImage: 'products/05.jpg' "><img class="zoom-tiny-image" src="products/05.jpg"></a>
						<a href="products/06.jpg" class="cloud-zoom-gallery" rel="useZoom: 'zoom1', smallImage: 'products/06.jpg' "><img class="zoom-tiny-image" src="products/06.jpg"></a>
						<a href="products/07.jpg" class="cloud-zoom-gallery" rel="useZoom: 'zoom1', smallImage: 'products/07.jpg' "><img class="zoom-tiny-image" src="products/07.jpg"></a>
						<a href="products/08.jpg" class="cloud-zoom-gallery" rel="useZoom: 'zoom1', smallImage: 'products/08.jpg' "><img class="zoom-tiny-image" src="products/08.jpg"></a>

						</p>
					</div>
					
				</div>
			</div> <!-- gallery end -->
			<div class="gallery-detail">
				<div><?php echo $product->mini_desc; ?></div> 

				<div class="price-section">
					<h2>$147.00</h2>
					<script>
						document.write ('<a target=\"_top\" href=\"http://offer.ebay.com.au/ws/eBayISAPI.dll?BinConfirm&amp;item=' + '111530977399' + '&amp;fromPage=2047675&amp;quantity=1&amp;fb=1" ><img src="http://ebayimages.x10host.com/ozsuperdeal/assets/images/buynow.png" /></a>');
					</script>
					<script>
						document.write ('<a target=\"_parent\" href=\"http://payments.ebay.com.au/ws/eBayISAPI.dll?ShopCartProcessor&item='+'111530977399'+'&atc=true&ssPageName=CART:ATC" title=\"Add to Cart\"><img src="http://ebayimages.x10host.com/ozsuperdeal/assets/images/addtocart.png" /></a>');
					</script>
					<script>
						document.write ("<a href='http://contact.ebay.com.au/ws1/eBayISAPI.dll?ShowEmailAuctionToFriend&item=" + '111530977399' + "' title='Tell a Friend' class='button_links' id='button_links' >Tell a Friend</a>");
					</script>
					<script>
						document.write ("<a href='http://cgi1.ebay.com.au/ws/eBayISAPI.dll?MakeTrack&item=" + '111530977399' + "' title='Watch This Item' class='button_links' id='button_links' >Watch this Item</a>");
					</script>
				</div>
			</div> <!-- gallery detail end -->
		</div>
	</div> <!-- content end -->

	<div class="tabs">
		<div class="container">
			<ul class="tabs-ul">
				<li id="tab1" class="active"><a>Description</a></li>
				<li id="tab2"><a>Specifications</a></li>
				<li id="tab3"><a>Shipping Information</a></li>
				<li id="tab4"><a>Warranty Information</a></li>
				<li id="tab5"><a>Payment</a></li>
				<li id="tab6"><a>Contact Us</a></li>
			</ul>
		</div>
	</div> <!-- tabs title end -->

	<div class="content">
		<div class="container">
			<div class="paragraph" id="event1">
				<?php echo $product->description; ?>
			</div>
			<div class="paragraph" id="event2">
				<?php echo $product->mini_desc; ?>
			</div>
			<div class="paragraph" id="event3">
				<?php echo $ebayAcc->shipping_info; ?>
			</div>
			<div class="paragraph" id="event4">
				<?php echo $ebayAcc->warranty_info; ?>
			</div>
			<div class="paragraph" id="event5">
				<?php echo $ebayAcc->payment_info; ?>
			</div>
			<div class="paragraph" id="event6">
				<?php echo $ebayAcc->contact_info; ?>
			</div>
		</div>
	</div>


</div> <!-- end wrapper -->