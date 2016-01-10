$(function(){
	/*summernoteText.on('summernote.image.upload', function(customEvent, files) {
  console.log('image upload:', files);
});
*/});

function summerNoteImgUpload(file,id,imgServer)
{
	data = new FormData();
    data.append("file", file);
    var $node = $("<i class='ace-icon fa fa-spinner fa-spin orange bigger-225'></i>");
    $('#'+id).summernote('insertNode', $node[0]);
    $.ajax({
        data: data,
        type: "POST",
        url: imgServer,
        cache: false,
        contentType: false,
        processData: false,
        success: function(url) {
        	if(url.status_code==200){

        		$('#'+id).summernote('editor.insertImage', url.data.img_url);
        		setTimeout(function(){$node[0].remove();},'800');
        		//$node[0].remove();

        	}

        }
    });
}
