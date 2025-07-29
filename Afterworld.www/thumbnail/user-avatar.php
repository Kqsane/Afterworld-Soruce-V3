<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
if(!isset($_GET['userId'])){
	exit;
}
$uid = intval($_GET['userId']);
?>
<div id="UserAvatar" class="thumbnail-holder" data-reset-enabled-every-page data-3d-thumbs-enabled 
     data-url="/thumbnail/user-avatar?userId=<?php echo $uid; ?>&amp;thumbnailFormatId=124&amp;width=352&amp;height=352" style="width:352px; height:352px;">
    <span class="thumbnail-span" data-3d-url="/avatar-thumbnail-3d/json?userId=<?php echo $uid; ?>"  data-js-files='https://js.rbxcdn.com/1b5ff54032ecaa6588a0c5f2ad7e1a4c.js' ><img alt='Leceptor' class='' src='/Thumbs/Avatar.ashx?userId=<?php echo $uid; ?>' /></span>
    <span class="enable-three-dee btn-control btn-control-small"></span>
</div>