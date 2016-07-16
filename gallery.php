<link rel='stylesheet' type='text/css' href='scripts/magnific_popup/dist/magnific-popup.css' />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type='text/javascript' src='scripts/magnific_popup/dist/jquery.magnific-popup.min.js'></script>

<script type='text/javascript'>
$(document).ready(function{
	
	
});
</script>

<?php
	$imgFiles = glob("images/gallery/*");
	foreach($imgFiles as $imgf){
		print '<a class="img_popup" href="'.$imgf.'"><div class="imgHolder mfp-zoom" style="width: 80px; height:50px; overflow:hidden;float:left;" ><img src="'.$imgf.'" width=100%/></div></a>&nbsp;&nbsp;';
	}
?>
<script type='text/javascript'>
$('.img_popup').magnificPopup({ 
  type: 'image',
  gallery:{
    enabled:true
  }
	// other options
});
</script>
