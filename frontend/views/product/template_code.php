<?php 
use common\assets\ZeroclipboardAsset;
ZeroclipboardAsset::register($this);
?>
<button tyle='button' class='btn btn-danger' id="copy" data-clipboard-target="code-to-copy"><i class="fa fa-files-o"></i> Copy</button>
<div><textarea id='code-to-copy' class='form-control' rows="30" readonly="true" style='width:100%'><?php echo $code; ?></textarea></div>
<script type="text/javascript">
	
	var client = new ZeroClipboard( $("#copy") );

</script>
