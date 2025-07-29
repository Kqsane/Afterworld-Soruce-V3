<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
//die;
if (!isset($_COOKIE["_ROBLOSECURITY"]) && !getuserinfo($_COOKIE["_ROBLOSECURITY"])){
    die(header("Location: https://devopstest1.aftwld.xyz/login"));
}
if (isset($_GET["type"])){
    $assetTypeNames = array_flip($asset_types);
    $assetTypeName = ucfirst(mb_strtolower($_GET["type"]));
    $assetTypeId = $assetTypeNames[$assetTypeName];
    
    $allowedTypes = [ 9, 10 ];

    if (!in_array($assetTypeId, $allowedTypes))
        die(header("Location: https://devopstest1.aftwld.xyz/request-error?code=404"));
}else{
    $assetTypeName = "Place";
    $assetTypeId = 9;
}
$userInfo = getuserinfo($_COOKIE["_ROBLOSECURITY"]);
$assetTypeNameLower = mb_strtolower($assetTypeName);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" xmlns:fb="http://www.facebook.com/2008/fbml">
<head data-machine-id="nil">
    <!-- MachineID: nil -->
    <title>Publish <?php echo $assetTypeName; ?> As</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,requiresActiveX=true" />
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">



<script type="application/ld+json">
    {
    "@context" : "http://schema.org",
    "@type" : "Organization",
    "name" : "Roblox",
    "url" : "https://devopstest1.aftwld.xyz/",
    "logo": "https://images.rbxcdn.com/e870a0b9bcd987fbe7f730c8002f8faa.png",
    "sameAs" : [
    "https://www.facebook.com/ROBLOX/",
    "https://twitter.com/roblox",
    "https://www.linkedin.com/company/147977",
    "https://www.instagram.com/roblox/",
    "https://www.youtube.com/user/roblox",
    "https://plus.google.com/+roblox",
    "https://www.twitch.tv/roblox"
    ]
    }
</script>
    <meta ng-csp="no-unsafe-eval">
    
<style>
/* ~/CSS/Pages/IDE/AssetList.css */
#assetList {
	margin-top: 13px;
}
#assetList.tab-active {
	margin-top: 0;
}
.asset-list {
	overflow-x: hidden;
	min-height: 350px;
	border: 1px solid #ccc;
	padding: 20px;
	margin-bottom: 20px;
}
#assetList div {
	*display: inline;
}
#newAssetText {
	text-transform: uppercase;
	font-size: 26px;
	color: #888;
	position: absolute;
	top: 33px;
	left: 60px;
	z-index: 2;
	width: 50px;
	font-weight: bold;
}
#newModelImage {
	padding: 20px 13px;
	width: 84px;
	height: 70px;
	background: white;
}
#MoreGames {
	text-align: center;
}
.asset {
	display: inline-block;
	padding: 9px 11px 19px 11px;
	margin-right: -3px;
	border: 1px solid;
	border-color: transparent;
	z-index: 0;
	margin-bottom: 10px;
	vertical-align: top;
}
.asset p {
	font-weight: bold;
}
a.game-image,
a.model-image {
	position: relative;
	color: inherit;
	text-decoration: none;
	border: none;
	width: 0;
	height: 0;
}
a.game-image img,
a.model-image img {
	border: none;
	display: block;
}
.asset:hover,
.asset img:hover {
	background: url('/ide/assets/bg-selected_thumb.png') repeat-x;
	border: 1px solid;
	border-color: #a7a7a7;
	cursor: pointer;
	background-size: 70px;
}
.asset img:hover {
	background: none;
}
a.game-image img:hover,
a.model-image img:hover {
	border: none;
}
.asset img.modelThumbnail {
	width: 110px;
	height: 110px;
	border: none;
	margin: 0 auto;
}
.model.asset p {
	width: 110px;
	margin: 0 auto;
}
.asset .item-name-container {
	text-align: center;
	font-weight: bold;
	font-size: 12px;
	margin-top: 4px;
}
.ellipsis-overflow {
	text-overflow: ellipsis;
	white-space: nowrap;
	overflow: hidden;
}
.asset img.placeThumbnail {
	width: 210px;
	height: 115px;
	margin: 0 auto;
}
#GroupUniverses .content,
#groupAssetList .content {
	position: relative;
}
#universeList .loading,
#groupUniverseList .loading,
#assetList .loading,
#groupAssetList .loading {
	width: 100%;
	background: url('/images/ProgressIndicator4.gif') no-repeat center #fff;
	height: 20px;
	position: static;
	top: 0;
	z-index: 2;
}
.group-select {
	margin-bottom: 10px;
}
.group-select label {
	font: -webkit-small-control;
}
/* ~/CSS/Base/CSS/StyleGuide.css */
body {
	background-color: #fff;
	margin: 0;
}
body,
.text,
pre {
	font-family: 'Source Sans Pro', Arial, Helvetica, sans-serif;
	color: #343434;
	font-size: 14px;
	line-height: 1.428;
}
::-webkit-input-placeholder,
:-moz-placeholder,
::-moz-placeholder,
:-ms-input-placeholder {
	font-family: 'Source Sans Pro', Arial, Helvetica, sans-serif;
}
body ::selection,
body ::-moz-selection {
	background-color: #00a1da;
	color: #fff;
}
#Body.body-is-liquid {
	width: auto;
}
#Body.body-min-width {
	min-width: 970px;
}
#Body.body-width {
	width: 970px;
}
.text {
	font-weight: normal;
}
strong {
	font-weight: bold;
}
em {
	font-style: italic;
}
del {
	text-decoration: line-through;
}
textarea {
	resize: none;
}
h1,
h2,
h3,
h4,
h5,
h6 {
	display: inline-block;
	margin: 0;
	padding: 0;
}
h1,
h1 a,
h1 a:visited,
h1 a:active,
h1 a:link {
	font-size: 32px;
	font-weight: bold;
	margin: 12px;
	color: #343434;
	letter-spacing: -1px;
}
h1 a,
h1 a:visited,
h1 a:active,
h1 a:link {
	margin: 0;
}
h1 a:hover {
	text-decoration: none;
}
h2,
h2 a:link,
h2 a:visited,
h2 a:active,
h2 .text {
	font-size: 30px;
	font-weight: normal;
	color: #343434;
	letter-spacing: -1px;
	text-decoration: none;
}
h2.title {
	margin-top: 20px;
	margin-bottom: 20px;
}
h2.light {
	font-size: 16px;
	font-weight: normal;
	color: #000;
}
h3,
h3 a {
	font-size: 15px;
	font-weight: bold;
	color: #343434;
}
.divider-top {
	border-top: 1px solid #ccc;
}
.divider-bottom {
	border-bottom: 1px solid #ccc;
}
.divider-left {
	border-left: 1px solid #ccc;
}
.divider-right {
	border-right: 1px solid #ccc;
}
.blank-box {
	border: 1px solid #ccc;
}
.dark-box {
	border: 1px solid #bcbcbc;
	background: #e1e1e1;
	padding: 5px;
}
.sub-divider-bottom {
	border-bottom: 1px solid #ededed;
}
a.text-link {
	font-weight: normal;
	text-decoration: none;
	color: #0055b3;
}
a.text-link:hover {
	text-decoration: underline;
}
.table td,
.table th {
	padding: 5px;
	border-top: 1px solid #ccc;
	margin: 0;
	text-align: left;
}
table.table {
	border-top: 1px solid #9e9e9e;
}
.table-header th {
	border-left: 1px solid #ccc;
	font-weight: 600;
	background-color: #f1f1f1;
	border-top: none;
}
.table-header .first {
	border-left: 1px solid #f1f1f1;
}
table.table .loading td {
	height: 50px;
	background: url('/images/spinners/spinner16x16.gif') center no-repeat;
}
.tip-text {
	padding-top: 2px;
	color: #666;
	display: block;
	font-size: 11px;
}
.tool-tip {
	border: 1px solid #ccc;
	font-weight: normal;
	font-size: 12px;
	margin-left: 20px;
	position: relative;
	width: 120px;
	padding: 5px;
}
.tool-tip span {
	color: #a00;
}
.tool-tip .right {
	position: absolute;
	left: -10px;
	top: 50%;
	margin-top: -5px;
}
.tool-tip .bottom {
	position: absolute;
	top: -10px;
	left: 5px;
}
.validator-checkmark {
	width: 15px;
	height: 13px;
	background: url(/images/UI/img-check.png) no-repeat;
	margin-left: 5px;
	display: none;
}
.text-box {
	border: 1px solid #a7a7a7;
	padding: 0 3px;
	font-weight: normal;
}
.text-box.text-box-small {
	height: 18px;
	font-size: 11px;
	line-height: 18px;
	border: 1px solid #a7a7a7;
}
.text-box.text-box-medium {
	height: 21px;
	font-size: 12px;
	line-height: 19px;
}
.text-box.text-box-large {
	height: 25px;
	line-height: 24px;
	font-size: 13px;
}
.text-box.text-area-medium {
	line-height: 19px;
}
.btn-control,
.btn-control:active,
.btn-control:link,
.btn-control:visited,
.btn-control:hover {
	border: 1px solid #777;
	padding: 0 6px;
	color: #000;
	text-decoration: none;
	background-color: #ccc;
	text-align: center;
	font-weight: normal;
	cursor: pointer;
	background-position: top;
	display: inline-block;
}
.btn-control:hover {
	background-position: bottom;
	border-color: #888;
	text-decoration: none;
}
.btn-control.disabled {
	border: 1px solid #ccc;
	cursor: default;
	color: #a7a7a7;
	background-position: center;
}
.btn-control.btn-control-small {
	height: 18px;
	line-height: 18px;
	font-size: 11px;
	background-image: url(/images/StyleGuide/btn-control-small-tile.png);
}
.btn-control.btn-control-medium {
	height: 21px;
	line-height: 21px;
	font-size: 12px;
	background-image: url(/images/StyleGuide/btn-control-medium-tile.png);
}
.btn-control.btn-control-large {
	height: 25px;
	line-height: 24px;
	font-size: 13px;
	padding: 0 7px;
	background-image: url(/images/StyleGuide/btn-control-large-tile.png);
}
a.btn-control.top-level {
	font-weight: bold;
}
.btn-large,
.btn-medium,
.btn-small {
	margin: 0;
	display: inline-block;
	zoom: 1;
	text-align: center;
	font-weight: normal;
	text-decoration: none;
	border-width: 1px;
	border-style: solid;
	cursor: pointer;
}
.btn-large {
	padding: 9px 13px 0 13px;
	height: 39px;
	min-width: 70px;
	font-size: 23px;
	line-height: 27px;
	background-position: left 0;
}
input.btn-large {
	padding: 9px 13px;
	height: 50px;
}
.btn-large:hover,
.btn-medium:hover,
.btn-small:hover {
	text-decoration: none;
}
.btn-large:hover {
	background-position: left -48px;
}
.btn-medium {
	padding: 1px 13px 3px 13px;
	height: 28px;
	min-width: 62px;
	font-size: 20px;
	background-position: left -96px;
	line-height: 1.3em;
}
.btn-medium:hover {
	background-position: left -128px;
}
.btn-small {
	padding: 1px 7px 0 7px;
	height: 20px;
	min-width: 40px;
	font-size: 14px;
	line-height: 18px;
	background-position: left -160px;
}
.btn-small:hover {
	background-position: left -181px;
}
.btn-primary,
.btn-primary:link,
.btn-primary:active,
.btn-primary:visited {
	border-color: #007001;
	background-color: #007001;
	background-image: url(/ide/assets/bg-btn-green.png) !important;
	color: white;
}
.btn-neutral,
.btn-neutral:link,
.btn-neutral:active,
.btn-neutral:visited {
	border-color: #0852b7;
	background-color: #0852b7;
	background-image: url(/ide/assets/bg-btn-blue.png);
	color: white;
}
.btn-negative,
.btn-negative:link,
.btn-negative:active,
.btn-negative:visited {
	border-color: #565656;
	background-color: #565656;
	background-image: url(/ide/assets/bg-btn-gray.png);
	color: white;
}
.btn-disabled-primary,
.btn-disabled-primary:hover,
.btn-disabled-neutral,
.btn-disabled-neutral:hover,
.btn-disabled-negative,
.btn-disabled-negative:hover {
	cursor: default;
	background-position: left -202px;
}
.btn-disabled-primary,
.btn-disabled-primary:link,
.btn-disabled-primary:active,
.btn-disabled-primary:visited {
	background-color: #99c699;
	background-image: url(/ide/assets/bg-btn-green.png);
	border-color: #99c699;
	color: white;
}
.btn-disabled-neutral,
.btn-disabled-neutral:link,
.btn-disabled-neutral:active,
.btn-disabled-neutral:visited {
	background-color: #9cbae2;
	background-image: url(/ide/assets/bg-btn-blue.png);
	border-color: #9cbae2;
	color: white;
}
.btn-none {
	display: none;
}
.btn-disabled-negative,
.btn-disabled-negative:link,
.btn-disabled-negative:active,
.btn-disabled-negative:visited {
	background-color: #bbbcbb;
	background-image: url(/ide/assets/bg-btn-gray.png);
	border-color: #bbbcbb;
	color: white;
}
.btn-text {
	display: none;
	margin: 0;
	position: relative;
	color: #fff;
}
.btn-text:hover {
	text-decoration: none;
}
.btn-large .btn-text {
	bottom: 26px;
}
.btn-medium .btn-text {
	bottom: 22px;
}
.btn-small .btn-text {
	bottom: 17px;
}
.btn-large-green-play,
.btn-play,
.btn-play:active,
.btn-play:visited,
.btn-play:link {
	padding-left: 60px;
	border-color: #007001;
	background: url(/ide/assets/bg-lg-green-play.png) no-repeat left top;
	color: white;
	min-width: 26px;
}
.blue-arrow {
	background: url(/ide/assets/bg-btn-blue-arrow-md.png) right top;
	border-color: #0852b7;
	color: white;
	padding-right: 36px;
	min-width: 41px;
}
.gray-arrow {
	background: url(/ide/assets/bg-btn-gray-arrow-md.png) right top;
	border-color: #565656;
	color: #222;
	padding-right: 36px;
	min-width: 41px;
}
.gray-arrow:hover,
.blue-arrow:hover {
	background-position: right -64px;
}
.disabled-blue-arrow,
.disabled-blue-arrow:hover {
	background: url(/ide/assets/bg-btn-blue-arrow-md.png) right -32px;
	border-color: #9cbae2;
	color: #99a7b1;
	padding-right: 36px;
	cursor: default;
	min-width: 41px;
}
.disabled-gray-arrow,
.disabled-gray-arrow:hover {
	background: url(/ide/assets/bg-btn-gray-arrow-md.png) right -32px;
	border-color: #bbbcbb;
	color: #a5a5a5;
	padding-right: 36px;
	cursor: default;
	min-width: 41px;
}
.pager.first,
.pager.last {
	display: none;
}
.pager.previous {
	display: inline-block;
	background: url('/images/Buttons/Arrows/btn-silver-left-27.png') no-repeat top left;
	width: 27px;
	height: 27px;
	border: 0;
}
.pager.next {
	display: inline-block;
	background: url('/images/Buttons/Arrows/btn-silver-right-27.png') no-repeat top left;
	width: 27px;
	height: 27px;
	border: 0;
}
.page.text {
	position: relative;
	top: -8px;
	padding: 5px;
	*top: -4px;
}
.pager.previous:hover,
.pager.next:hover {
	background-position: bottom left;
	cursor: pointer;
}
.pager.disabled,
.pager.disabled:hover {
	background-position: 0 -27px;
	cursor: default;
}
.dropdown {
	position: relative;
	text-align: left;
	display: block;
	float: left;
}
.dropdown .button {
	background: url(/images/buttons/bg-drop_down_btn.png) no-repeat right top;
	font-size: 13px;
	color: #000;
	text-align: center;
	display: block;
	position: relative;
	z-index: 2;
	height: 21px;
	padding: 4px 17px 0 8px;
	border: 1px solid #777;
	bottom: -1px;
}
.dropdown .button:hover {
	background-position: right center;
	border-color: #888;
	text-decoration: none;
}
.dropdown .button.active {
	background-position: right bottom;
	border-bottom: none;
}
.dropdown .button.gear {
	background-image: url(/images/BuildPage/btn-gear_sprite_27px.png);
	height: 27px;
	border: none;
	bottom: 0;
	width: 40px;
	padding: 0;
}
.dropdown .dropdown-list {
	background-color: #efefef;
	border: 1px solid #777;
	position: absolute;
	top: 26px;
	z-index: 1;
	font-size: 12px;
	font-weight: normal;
	display: none;
	margin-top: 0;
	margin-bottom: 0;
	margin-left: 0;
	padding-left: 0;
	white-space: nowrap;
}
.dropdown .dropdown-list li {
	display: block;
	text-decoration: none;
	color: #000;
	padding: 4px 8px;
	margin: 0;
	cursor: pointer;
}
.dropdown .dropdown-list li:hover {
	background-color: #0055b3;
	color: #fff;
}
.dropdown .dropdown-list li a {
	display: block;
	color: inherit;
	text-decoration: none;
}
span.robux,
div.robux {
	background: url('/images/Icons/img-robux.png') no-repeat 0 1px;
	color: #060;
	font-weight: bold;
	padding: 0 0 2px 20px;
	font-size: 12px;
}
span.tickets,
div.tickets {
	background: url('/images/Tickets.png') no-repeat 0 1px;
	color: #a61;
	padding: 0 0 2px 20px;
	font-weight: bold;
	font-size: 12px;
}
.robux-text {
	color: #060;
	font-weight: bold;
}
.form-outer {
	overflow: hidden;
	width: 100%;
	clear: both;
}
.form-inner.label-column {
	float: left;
	margin-right: 5px;
}
.form-label {
	font-size: 14px;
	color: #343434;
	font-weight: 600;
}
.form-inner.input-column {
	overflow: hidden;
	text-align: left;
}
.form-select {
	margin: 0 5px 0;
	height: 21px;
}
.error-message {
	color: black;
	background-color: #fae5e5;
	border: solid 1px #c00;
	margin-bottom: 10px;
	text-align: left;
	padding: 3px 10px;
}
.footnote {
	color: #666;
}
.urgent-text {
	color: #c00;
	font-weight: bold;
}
.warning-text {
	color: #c00;
}
.search-match {
	background-color: #ffa;
}
a[disabled='disabled'],
a[disabled='disabled']:hover,
a[disabled],
a[disabled]:hover {
	text-decoration: none;
	cursor: default;
}
.stat-label {
	font-size: 12px;
	color: #999;
	line-height: 1.5em;
}
.stat {
	font-size: 12px;
	color: #000;
	line-height: 1.5em;
}
.hint-text {
	font-style: italic;
	color: #ccc;
}
.invisible {
	display: none;
}
.selected-text {
	font-weight: bold;
}
.status-confirm {
	background-color: #e5effa;
	border: 1px solid #06c;
	padding: 5px 10px;
}
.status-error {
	background-color: #fae5e5;
	border: 1px solid #c00;
	padding: 5px 10px;
}
.info-tool-tip {
	background: url(/images/Buttons/questionmark-12x12.png) no-repeat;
	padding-left: 13px;
}
.tab-container {
	overflow: hidden;
	padding-left: 5px;
}
.redesign .tab_white_31h_container .ajax__tab_inner,
.tab,
.tab-container > div {
	float: left;
	background-color: #d6d6d6;
	padding: 7px;
	border: 1px solid #9e9e9e;
	font-weight: bold;
	font-size: 15px;
	margin: 4px 2px 0 1px;
	border-bottom-width: 0;
	position: relative;
	top: -1px;
}
.redesign .tab_white_31h_container .ajax__tab_hover .ajax__tab_inner,
.tab:hover,
.tab-container > div:hover {
	background-color: #e9e9e9;
	cursor: pointer;
}
.tab a {
	color: #343434;
	font-size: 15px;
	font-weight: bold;
}
.redesign .tab_white_31h_container .ajax__tab_active .ajax__tab_inner,
.tab.active,
.tab-container > .tab-active,
.tab-container > .tab-active:hover {
	background-color: #fff;
	margin-top: 0;
	padding: 9px 7px;
	border-bottom: 0;
	position: relative;
	border-color: #ccc;
	z-index: 1;
	margin: 0 1px 0 0;
	top: 1px;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
}
.tab-content,
.tab-container + div > div {
	top: -1px;
	position: relative;
	background-color: #fff;
	border-top: 1px solid #ccc;
	display: none;
}
.tab-container + div > div {
	padding: 21px 15px;
}
.tab-container + div > .tab-active {
	display: block;
}
.arrow {
	cursor: pointer;
	background-repeat: no-repeat;
	width: 17px;
	height: 50px;
	display: inline-block;
}
.arrow:hover {
	background-position: 0 -51px;
}
.arrow.disabled {
	cursor: default;
	background-position: 0 -102px;
	width: 17px;
	height: 50px;
}
.arrow.left {
	background-image: url(/images/GamesPage/arrow_left.png);
}
.arrow.right {
	background-image: url(/images/GamesPage/arrow_right.png);
}
.online-player {
	font-size: 12px;
	color: #393;
}
.verticaltab {
	text-decoration: none;
	padding: 1px;
}
.verticaltab:hover {
	background: #efefef;
}
.verticaltab.disabled:hover {
	background: none;
	border: none;
	text-decoration: none;
	cursor: default;
}
.verticaltab.selected {
	background: #efefef;
	border: 1px solid #ccc;
	font-weight: bold;
	border-right: none;
	text-align: left;
	padding: 0;
}
.verticaltab a {
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	font-size: 14px;
	color: #343434;
	position: relative;
	display: block;
	z-index: 2;
	height: 33px;
	line-height: 33px;
	text-decoration: none;
	padding-left: 15px;
}
.verticaltab a:hover,
.verticaltab a:active {
	text-decoration: none;
}
.verticaltab.disabled a:hover {
	cursor: default;
}
.validation-summary-errors {
	background-color: #fae5e5;
	border: 1px solid #c00;
	padding: 5px 0 5px 5px;
	font-size: 12px;
	font-weight: normal;
	text-align: left;
	margin-bottom: 10px;
}
.validation-summary-errors ul {
	padding: 0;
	margin: 0;
	list-style-type: none;
}
.hide-overflow {
	overflow: hidden;
}
/* ~/CSS/ide/fonts/SourceSansPro.css */
@font-face {
	font-family: 'Source Sans Pro';
	font-style: normal;
	font-weight: 300;
	src:
		local('Source Sans Pro Light'),
		local('SourceSansPro-Light'),
		url('/ide/fonts/source-sans-pro-v9-latin-300.woff2') format('woff2'),
		url('/ide/fonts/source-sans-pro-v9-latin-300.woff') format('woff'),
		url('/ide/fonts/source-sans-pro-v9-latin-300.svg#SourceSansPro') format('svg');
}
@font-face {
	font-family: 'Source Sans Pro';
	font-style: normal;
	font-weight: 400;
	src:
		local('Source Sans Pro'),
		local('SourceSansPro-Regular'),
		url('/ide/fonts/source-sans-pro-v9-latin-regular.woff2') format('woff2'),
		url('/ide/fonts/source-sans-pro-v9-latin-regular.woff') format('woff'),
		url('/ide/fonts/source-sans-pro-v9-latin-regular.svg#SourceSansPro') format('svg');
}
@font-face {
	font-family: 'Source Sans Pro';
	font-style: normal;
	font-weight: 600;
	src:
		local('Source Sans Pro Semibold'),
		local('SourceSansPro-Semibold'),
		url('/ide/fonts/source-sans-pro-v9-latin-600.woff2') format('woff2'),
		url('/ide/fonts/source-sans-pro-v9-latin-600.woff') format('woff'),
		url('/ide/fonts/source-sans-pro-v9-latin-600.svg#SourceSansPro') format('svg');
}
/* ~/CSS/Pages/IDE/IDE.css */
body,
html {
	height: 100%;
	margin: 0;
	padding: 0;
}
body {
	background-color: white;
}
a {
	text-decoration: none;
}
input,
textarea,
button,
a {
	outline: 0;
}
h1,
h2,
h3,
h4,
h5,
h6 {
	margin: 0;
	padding: 0;
	display: inline-block;
}
select {
	border: 1px solid;
	border-color: #a7a7a7;
}
.Message a:link,
.Message a:visited,
.Message a:active {
	color: #095fb5;
	text-decoration: none;
}
a:hover {
	text-decoration: underline;
}
.boxed-body {
	padding: 20px 20px 59px 20px;
}
.footer-button-container {
	position: fixed;
	bottom: 0;
	left: 0;
	right: 0;
	background-color: #fff;
	padding: 10px 20px;
	height: 38px;
}
a.tooltip {
	float: none;
	position: inherit;
}
#ProcessingView {
	width: 200px;
	text-align: center;
}
.processing-text {
	font-size: 14px;
	color: #fff;
	font-weight: bold;
}
/* ~/CSS/Pages/Build/BuildPage.css */
.BuildPageHeaderTopLvl {
	margin-bottom: 7px;
	display: block;
}
.BuildPageContent {
	float: left;
}
#build-page {
	border-spacing: 0;
	border-collapse: collapse;
}
#build-page td {
	padding: 0;
}
#build-page td.menu-area {
	width: 148px;
	vertical-align: top;
	height: 738px;
}
#build-page #MyCreationsTab td.menu-area {
	padding-top: 10px;
}
#build-page td.menu-area a.tab-item {
	display: block;
	padding: 5px 10px;
	color: Black !important;
	font-size: 16px;
}
#build-page td.menu-area a.tab-item:hover {
	text-decoration: none;
	background-color: #efefef;
}
#build-page td.menu-area a.tab-item-selected {
	border-top: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
	border-left: 1px solid #ccc;
	background-color: #efefef;
	padding: 4px 9px;
	font-weight: bold;
}
#build-page td.content-area {
	width: 622px;
	padding: 9px 10px 10px 10px !important;
	vertical-align: top;
	border-right: 1px solid #ccc;
}
#build-page td.no-ads {
	border-right: none;
}
#build-page .separator {
	background-color: #efefef;
	margin: 10px 0;
	height: 1px;
}
#build-page table.section-header {
	margin-bottom: 26px;
	width: 622px;
	border-spacing: 0;
}
#build-page table.section-header td {
	vertical-align: top;
	height: 45px;
}
#build-page table.section-header label.sort-label {
	font-size: 14px;
	font-weight: 600;
}
#build-page table.section-header input[type='checkbox'] {
	vertical-align: middle;
	position: relative;
	bottom: 1px;
	margin-right: 4px;
	margin-left: 5px;
}
#build-page table.section-header label.checkbox-label {
	float: initial;
	color: Black;
	font-size: 12px;
}
td.content-title {
	white-space: nowrap;
}
div.sorts-and-filters .checkbox {
	padding-top: 2px;
	white-space: nowrap;
	padding-right: 6px;
}
#build-page table.section-header div.sorts-and-filters {
	text-align: right;
	position: relative;
	top: 16px;
	padding-left: 20px;
	*left: 20px;
}
#build-page table.section-header div.creation-context-filters-and-sorts {
	text-align: right;
	position: relative;
	top: 14px;
	padding-left: 20px;
	*left: 20px;
}
div.creation-context-filters-and-sorts .checkbox {
	padding-top: 2px;
	white-space: nowrap;
	padding-right: 6px;
}
#build-page .show-active-places-only .checkbox-label {
	padding-left: 0;
}
#build-page div.creation-context-breadcrumb {
	margin-top: 6px;
}
#build-page div.buildpage-loading-container {
	text-align: center;
}
.active-only-checkbox {
	float: right;
	vertical-align: middle;
}
.active-only-checkbox > input[type='checkbox'] {
	margin-top: 0;
}
.show-archive-checkbox {
	float: right;
	vertical-align: middle;
}
.show-archive-checkbox > input[type='checkbox'] {
	margin-top: 0;
}
#build-page .item-table {
	border-spacing: 0;
	border-collapse: collapse;
}
#build-page .item-table td {
	padding: 0;
	vertical-align: top;
}
#build-page .item-table .image-col {
	width: 80px;
	overflow: hidden;
}
#build-page .item-table .name-col {
	padding-top: 11px;
	width: 300px;
}
#build-page .item-table .name-col a.title {
	display: block;
	margin-bottom: 2px;
	overflow: hidden;
}
#build-page .item-table .stats-col-games {
	width: 140px;
	vertical-align: middle;
}
#build-page .item-table .stats-col {
	width: 198px;
	vertical-align: middle;
}
#build-page .item-table .edit-col {
	vertical-align: middle;
	padding-right: 10px;
}
#build-page .item-table .menu-col {
	vertical-align: middle;
	text-align: right;
}
#build-page .item-table .universe-image-col {
	padding-right: 8px;
}
#build-page a[class^='load-more-'] {
	display: block;
	width: 100px;
	margin: 20px auto 0 auto;
}
#build-page .gear-button,
#build-page .gear-button-hover,
#build-page .gear-button-open {
	background: url(/images/BuildPage/btn-gear_sprite_27px.png) no-repeat;
	display: inline-block;
	width: 40px;
	height: 27px;
	border: none;
	position: relative;
	margin-right: 10px;
}
#build-page .gear-hover {
	background-position: 0 -27px;
}
#build-page .gear-open {
	background-position: 0 -54px;
	z-index: 100;
}
#build-page .gear-button {
	left: -1px;
}
#build-page .gear-button-wrapper {
	top: 5px;
	width: 38px;
	background-color: #fff;
	border-left: 1px solid;
	border-right: 1px solid;
	border-color: white;
	height: 37px;
	position: relative;
	margin-right: 5px;
}
#build-page a.item-image img {
	width: 69px;
	object-fit: scale-down;
}
#build-page a.game-image img {
	width: 70px;
}
#build-page a.item-image img,
#build-page a.game-image img {
	height: 69px;
	display: block;
	border: none;
}
#build-page .totals-label {
	line-height: 17px;
	font-size: 13px;
}
#build-page .aside-text {
	margin-left: 10px;
	font-size: 13px;
}
#build-page .aside-text,
#build-page .totals-label {
	color: #999;
}
#build-page .item-table .totals-label span {
	color: black;
	margin-left: 2px;
}
#build-page .details-table {
	border-spacing: 0;
	border-collapse: collapse;
}
#build-page .details-table td.activate-cell {
	width: 115px;
}
#build-page .details-table td.hide-activate-cell {
	display: none;
}
#build-page .details-table td.ad-activate-cell {
	width: 115px;
}
#build-page .details-table td {
	font-size: 13px;
}
#build-page .details-table td > span {
	margin-right: 5px;
	color: #999;
}
#build-page a.place-active,
#build-page a.place-inactive,
span.inactive-image {
	padding-left: 18px;
	font-size: 13px;
	background-image: url('/images/BuildPage/ico-game_privacy.png');
	background-repeat: no-repeat;
	display: block;
	height: 15px;
}
#build-page a.place-active:hover {
	background-position: 0 -15px;
	text-decoration: none;
}
#build-page a.place-inactive,
span.inactive-image {
	background-position: 0 -30px;
}
#build-page span.creator-app {
	color: #888;
	font-size: 12px;
}
span.inactive-image {
	display: inline-block;
}
#build-page a.place-inactive:hover {
	background-position: 0 -45px;
	text-decoration: none;
}
.build-games-container {
	text-align: left;
	padding-top: 12px;
	padding-left: 3px;
}
.build-games-container ul {
	margin-top: 20px;
	list-style: none;
	padding: 0;
	margin-top: 11px;
	margin-bottom: 17px;
}
.build-games-container li {
	margin-bottom: 14px;
}
.build-drop-down {
	*margin-left: -141px;
	*margin-top: 34px;
}
.run-ad-buttons {
	float: right;
	margin-left: 10px;
}
.bid-amount-box {
	text-align: left;
	float: right;
	margin-left: 10px;
}
.group-fund-box {
	float: right;
	margin-top: 1px;
}
.bid-amount-text {
	padding-top: 5px;
	margin-right: 5px;
}
#upload-iframe {
	width: 100%;
	height: 180px;
	margin-left: 10px;
}
#upload-iframe.place-specific-assets {
	height: 230px;
}
#upload-plugin-iframe {
	width: 100%;
	height: 220px;
	margin-left: 10px;
}
#universe-create-container label {
	min-width: 100px;
	display: inline-block;
}
.universe-list-container {
	margin-top: 20px;
	padding-top: 20px;
}
.universe-create-container {
	margin-bottom: 30px;
	margin-left: 20px;
	margin-top: 10px;
}
#universe-create-container label[for='description'] {
	vertical-align: top;
}
#universe-create-container .submit {
	margin-top: 5px;
}
#universe-create-container .loading-image {
	padding-top: 9px;
	padding-bottom: 8px;
}
#universe-create-container .error {
	color: red;
	display: inline-block;
	vertical-align: top;
}
.item-universe {
	text-overflow: ellipsis;
	width: 350px;
	display: block;
	overflow: hidden;
}
#build-page .item-table .universe-name-col {
	vertical-align: middle;
	width: 457px;
}
#build-page .item-table .universename-context-col {
	padding-top: 28px;
	width: 400px;
}
#universe-create-container .form-row {
	margin-bottom: 10px;
}
.root-place-selector {
	width: 200px;
}
.content-area .create-new-button {
	margin-bottom: 10px;
}
#build-page .spacer {
	height: 15px;
	width: 10px;
}
#build-page .groups-dropdown-container {
	margin-bottom: 15px;
}
#build-page .groups-dropdown-container select {
	width: 135px;
}
#build-page .option select {
	min-width: 150px;
}
#build-page .item-template {
	display: none;
}
/* ~/CSS/Base/CSS/Modals.css */
.BCModalImage {
	margin-left: 12px;
	margin-bottom: 12px;
	float: left;
}
#BCMessageDiv.BCMessage {
	margin-top: 35px;
	margin-left: 12px;
	font-weight: bold;
	font-size: 15px;
	float: left;
	width: 250px;
}
.PremiumModalIcon {
	margin: 40px;
	float: left;
}
.GenericModal .Title,
.ConfirmationModal .Title {
	font-weight: bold;
	font-size: 27px;
	color: #343434;
	margin: 5px;
	letter-spacing: -1px;
}
.GenericModal {
	padding: 5px;
}
.GenericModalBody {
	background-color: #fff;
	padding: 10px;
}
.GenericModal .Message {
	display: inline-block;
	width: 275px;
	vertical-align: middle;
	font-weight: bold;
	font-size: 15px;
	letter-spacing: 0;
	font-size-adjust: none;
	font-stretch: normal;
	margin-bottom: 5px;
	float: none;
}
.GenericModal.noImage .Message {
	width: 100%;
	text-align: center;
}
.GenericModal div.ImageContainer {
	display: inline-block;
	height: 110px;
	width: 110px;
	overflow: hidden;
	vertical-align: middle;
	margin-left: -15px;
}
.GenericModal.noImage div.ImageContainer {
	display: none;
}
.GenericModal img.GenericModalImage {
	display: inline-block;
	max-height: 110px;
	max-width: 110px;
}
.GenericModal .GenericModalButtonContainer {
	text-align: center;
	margin: 5px auto;
}
.largeModal .GenericModalBody {
	position: relative;
}
.largeModal div.ImageContainer {
	position: absolute;
	left: 35px;
	top: 50%;
	margin-top: -55px;
}
div.GenericModalErrorMessage {
	font-size: 12px;
	font-weight: normal;
	text-align: left;
}
a.genericmodal-close {
	margin-left: 400px;
}
.ConfirmationModalButtonContainer {
	clear: both;
	text-align: center;
	padding-bottom: 20px;
	padding-top: 1px;
	height: 50px;
}
.ConfirmationModalButtonContainer a {
	margin-right: 6px;
	cursor: pointer;
}
.ConfirmationModalFooter {
	letter-spacing: normal;
	color: #666;
	font:
		normal 12px Arial,
		Helvetica,
		Sans-Serif;
	text-align: center;
	padding: 0 10px 7px;
}
.ConfirmationModal.noImage .Message {
	max-width: 395px;
	width: 395px;
	text-align: center;
	position: relative;
	left: 0;
	top: 4px;
}
.ConfirmationModal .Message {
	margin-top: 0;
	float: none;
	width: 270px;
	position: relative;
	top: 30%;
	left: 127px;
	vertical-align: middle;
	font-weight: bold;
	font-size: 15px;
	letter-spacing: 0;
	font-size-adjust: none;
	font-stretch: normal;
	text-align: left;
}
@media (max-width: 480px) {
	.ConfirmationModal .Message {
		width: 50%;
	}
}
.ConfirmationModal div.ImageContainer {
	position: absolute;
	height: 110px;
	width: 110px;
}
.ConfirmationModal.noImage div.ImageContainer {
	display: none;
}
.ConfirmationModal img.GenericModalImage {
	display: inline-block;
	max-height: 110px;
	max-width: 110px;
}
div.ConfirmationModal div.GenericModalBody {
	padding: 0;
}
div.ConfirmationModal.noImage div.TopBody {
	overflow: hidden;
	padding: 15px 15px 20px 15px;
	height: auto;
}
div.ConfirmationModal div.TopBody {
	padding: 15px 15px 22px 15px;
	min-height: 110px;
}
div.ConfirmationModal {
	padding: 5px;
}
.LanguageInstructions {
	width: 415px;
	height: 157px;
	margin: 10px auto;
	background: url('/images/LanguageChangeInstructions.jpg');
}
.unifiedModal {
	background-color: #e1e1e1;
	font-weight: bold;
	font-size: 27px;
	color: #343434;
	border: 2px solid #272727;
	text-align: center;
	position: relative;
}
.unifiedModalContent {
	text-align: left;
	background-color: White;
	font-weight: bold;
	font-size: 15px;
	margin: 0 5px 5px 5px;
	letter-spacing: normal;
}
.unifiedModalSubtext {
	color: #666;
	font-weight: bold;
	font-size: 12px;
	border: none;
	letter-spacing: normal;
	cursor: pointer;
	text-align: center;
	margin-top: 10px;
	padding-bottom: 5px;
}
.smallModal {
	width: 425px;
}
.closeBtnCircle_20h:hover {
	background-position: 0 20px;
}
.closeBtnCircle_20h {
	width: 20px;
	height: 20px;
	cursor: pointer;
	position: absolute;
	top: 5px;
	left: 5px;
	background: url(/images/Buttons/btn-x.png);
}
.unifiedModal .smallModal .closeBtnCircle_20h {
	margin-left: 395px;
}
@media (max-width: 480px) {
	.smallModal {
		width: auto;
	}
}

</style>
    
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600" rel="stylesheet" type="text/css">
    <script type='text/javascript' src='https://js.rbxcdn.com/3719f3fb35135d05cf6b72d5b0f46333.js'></script>

    <script type='text/javascript'>
Roblox.config.externalResources = [];
Roblox.config.paths['Pages.Catalog'] = 'https://js.rbxcdn.com/943dbead6327ef7e601925fc45ffbeb0.js';
Roblox.config.paths['Pages.CatalogShared'] = 'https://js.rbxcdn.com/496e8f05b3aabfcd72a147ddb49aaf1e.js';
Roblox.config.paths['Widgets.AvatarImage'] = 'https://js.rbxcdn.com/6bac93e9bb6716f32f09db749cec330b.js';
Roblox.config.paths['Widgets.DropdownMenu'] = 'https://js.rbxcdn.com/7b436bae917789c0b84f40fdebd25d97.js';
Roblox.config.paths['Widgets.GroupImage'] = 'https://js.rbxcdn.com/33d82b98045d49ec5a1f635d14cc7010.js';
Roblox.config.paths['Widgets.HierarchicalDropdown'] = 'https://js.rbxcdn.com/3368571372da9b2e1713bb54ca42a65a.js';
Roblox.config.paths['Widgets.ItemImage'] = 'https://js.rbxcdn.com/e79fc9c586a76e2eabcddc240298e52c.js';
Roblox.config.paths['Widgets.PlaceImage'] = 'https://js.rbxcdn.com/31df1ed92170ebf3231defcd9b841008.js';
Roblox.config.paths['Widgets.SurveyModal'] = 'https://js.rbxcdn.com/d6e979598c460090eafb6d38231159f6.js';
</script>
     

    <script type='text/javascript' src='https://js.rbxcdn.com/79e749bac5810474fd3195d27f63e209.js'></script>

    <script type="text/javascript">
if (typeof(Roblox) === "undefined") { Roblox = {}; }
Roblox.Endpoints = Roblox.Endpoints || {};
Roblox.Endpoints.Urls = Roblox.Endpoints.Urls || {};
Roblox.Endpoints.Urls['/api/item.ashx'] = 'https://devopstest1.aftwld.xyz/api/item.ashx';
Roblox.Endpoints.Urls['/asset/'] = 'https://assetgame.aftwld.xyz/asset/';
Roblox.Endpoints.Urls['/client-status/set'] = 'https://devopstest1.aftwld.xyz/client-status/set';
Roblox.Endpoints.Urls['/client-status'] = 'https://devopstest1.aftwld.xyz/client-status';
Roblox.Endpoints.Urls['/game/'] = 'https://assetgame.aftwld.xyz/game/';
Roblox.Endpoints.Urls['/game-auth/getauthticket'] = 'https://devopstest1.aftwld.xyz/game-auth/getauthticket';
Roblox.Endpoints.Urls['/game/edit.ashx'] = 'https://assetgame.aftwld.xyz/game/edit.ashx';
Roblox.Endpoints.Urls['/game/getauthticket'] = 'https://assetgame.aftwld.xyz/game/getauthticket';
Roblox.Endpoints.Urls['/game/placelauncher.ashx'] = 'https://assetgame.aftwld.xyz/game/placelauncher.ashx';
Roblox.Endpoints.Urls['/game/preloader'] = 'https://assetgame.aftwld.xyz/game/preloader';
Roblox.Endpoints.Urls['/game/report-stats'] = 'https://assetgame.aftwld.xyz/game/report-stats';
Roblox.Endpoints.Urls['/game/report-event'] = 'https://assetgame.aftwld.xyz/game/report-event';
Roblox.Endpoints.Urls['/game/updateprerollcount'] = 'https://assetgame.aftwld.xyz/game/updateprerollcount';
Roblox.Endpoints.Urls['/login/default.aspx'] = 'https://devopstest1.aftwld.xyz/login/default.aspx';
Roblox.Endpoints.Urls['/my/character.aspx'] = 'https://devopstest1.aftwld.xyz/my/character.aspx';
Roblox.Endpoints.Urls['/my/money.aspx'] = 'https://devopstest1.aftwld.xyz/my/money.aspx';
Roblox.Endpoints.Urls['/chat/chat'] = 'https://devopstest1.aftwld.xyz/chat/chat';
Roblox.Endpoints.Urls['/presence/users'] = 'https://devopstest1.aftwld.xyz/presence/users';
Roblox.Endpoints.Urls['/presence/user'] = 'https://devopstest1.aftwld.xyz/presence/user';
Roblox.Endpoints.Urls['/friends/list'] = 'https://devopstest1.aftwld.xyz/friends/list';
Roblox.Endpoints.Urls['/navigation/getCount'] = 'https://devopstest1.aftwld.xyz/navigation/getCount';
Roblox.Endpoints.Urls['/catalog/browse.aspx'] = 'https://devopstest1.aftwld.xyz/catalog/browse.aspx';
Roblox.Endpoints.Urls['/catalog/html'] = 'https://search.aftwld.xyz/catalog/html';
Roblox.Endpoints.Urls['/catalog/json'] = 'https://search.aftwld.xyz/catalog/json';
Roblox.Endpoints.Urls['/catalog/contents'] = 'https://search.aftwld.xyz/catalog/contents';
Roblox.Endpoints.Urls['/catalog/lists.aspx'] = 'https://search.aftwld.xyz/catalog/lists.aspx';
Roblox.Endpoints.Urls['/asset-hash-thumbnail/image'] = 'https://assetgame.aftwld.xyz/asset-hash-thumbnail/image';
Roblox.Endpoints.Urls['/asset-hash-thumbnail/json'] = 'https://assetgame.aftwld.xyz/asset-hash-thumbnail/json';
Roblox.Endpoints.Urls['/asset-thumbnail-3d/json'] = 'https://assetgame.aftwld.xyz/asset-thumbnail-3d/json';
Roblox.Endpoints.Urls['/asset-thumbnail/image'] = 'https://assetgame.aftwld.xyz/asset-thumbnail/image';
Roblox.Endpoints.Urls['/asset-thumbnail/json'] = 'https://assetgame.aftwld.xyz/asset-thumbnail/json';
Roblox.Endpoints.Urls['/asset-thumbnail/url'] = 'https://assetgame.aftwld.xyz/asset-thumbnail/url';
Roblox.Endpoints.Urls['/asset/request-thumbnail-fix'] = 'https://assetgame.aftwld.xyz/asset/request-thumbnail-fix';
Roblox.Endpoints.Urls['/avatar-thumbnail-3d/json'] = 'https://devopstest1.aftwld.xyz/avatar-thumbnail-3d/json';
Roblox.Endpoints.Urls['/avatar-thumbnail/image'] = 'https://devopstest1.aftwld.xyz/avatar-thumbnail/image';
Roblox.Endpoints.Urls['/avatar-thumbnail/json'] = 'https://devopstest1.aftwld.xyz/avatar-thumbnail/json';
Roblox.Endpoints.Urls['/avatar-thumbnails'] = 'https://devopstest1.aftwld.xyz/avatar-thumbnails';
Roblox.Endpoints.Urls['/avatar/request-thumbnail-fix'] = 'https://devopstest1.aftwld.xyz/avatar/request-thumbnail-fix';
Roblox.Endpoints.Urls['/bust-thumbnail/json'] = 'https://devopstest1.aftwld.xyz/bust-thumbnail/json';
Roblox.Endpoints.Urls['/group-thumbnails'] = 'https://devopstest1.aftwld.xyz/group-thumbnails';
Roblox.Endpoints.Urls['/groups/getprimarygroupinfo.ashx'] = 'https://devopstest1.aftwld.xyz/groups/getprimarygroupinfo.ashx';
Roblox.Endpoints.Urls['/headshot-thumbnail/json'] = 'https://devopstest1.aftwld.xyz/headshot-thumbnail/json';
Roblox.Endpoints.Urls['/item-thumbnails'] = 'https://devopstest1.aftwld.xyz/item-thumbnails';
Roblox.Endpoints.Urls['/outfit-thumbnail/json'] = 'https://devopstest1.aftwld.xyz/outfit-thumbnail/json';
Roblox.Endpoints.Urls['/place-thumbnails'] = 'https://devopstest1.aftwld.xyz/place-thumbnails';
Roblox.Endpoints.Urls['/thumbnail/asset/'] = 'https://devopstest1.aftwld.xyz/thumbnail/asset/';
Roblox.Endpoints.Urls['/thumbnail/avatar-headshot'] = 'https://devopstest1.aftwld.xyz/thumbnail/avatar-headshot';
Roblox.Endpoints.Urls['/thumbnail/avatar-headshots'] = 'https://devopstest1.aftwld.xyz/thumbnail/avatar-headshots';
Roblox.Endpoints.Urls['/thumbnail/user-avatar'] = 'https://devopstest1.aftwld.xyz/thumbnail/user-avatar';
Roblox.Endpoints.Urls['/thumbnail/resolve-hash'] = 'https://devopstest1.aftwld.xyz/thumbnail/resolve-hash';
Roblox.Endpoints.Urls['/thumbnail/place'] = 'https://devopstest1.aftwld.xyz/thumbnail/place';
Roblox.Endpoints.Urls['/thumbnail/get-asset-media'] = 'https://devopstest1.aftwld.xyz/thumbnail/get-asset-media';
Roblox.Endpoints.Urls['/thumbnail/remove-asset-media'] = 'https://devopstest1.aftwld.xyz/thumbnail/remove-asset-media';
Roblox.Endpoints.Urls['/thumbnail/set-asset-media-sort-order'] = 'https://devopstest1.aftwld.xyz/thumbnail/set-asset-media-sort-order';
Roblox.Endpoints.Urls['/thumbnail/place-thumbnails'] = 'https://devopstest1.aftwld.xyz/thumbnail/place-thumbnails';
Roblox.Endpoints.Urls['/thumbnail/place-thumbnails-partial'] = 'https://devopstest1.aftwld.xyz/thumbnail/place-thumbnails-partial';
Roblox.Endpoints.Urls['/thumbnail_holder/g'] = 'https://devopstest1.aftwld.xyz/thumbnail_holder/g';
Roblox.Endpoints.Urls['/users/{id}/profile'] = 'https://devopstest1.aftwld.xyz/users/{id}/profile';
Roblox.Endpoints.Urls['/service-workers/push-notifications'] = 'https://devopstest1.aftwld.xyz/service-workers/push-notifications';
Roblox.Endpoints.Urls['/notification-stream/notification-stream-data'] = 'https://devopstest1.aftwld.xyz/notification-stream/notification-stream-data';
Roblox.Endpoints.Urls['/api/friends/acceptfriendrequest'] = 'https://devopstest1.aftwld.xyz/api/friends/acceptfriendrequest';
Roblox.Endpoints.Urls['/api/friends/declinefriendrequest'] = 'https://devopstest1.aftwld.xyz/api/friends/declinefriendrequest';
Roblox.Endpoints.addCrossDomainOptionsToAllRequests = true;
</script>

    <script type="text/javascript">
if (typeof(Roblox) === "undefined") { Roblox = {}; }
Roblox.Endpoints = Roblox.Endpoints || {};
Roblox.Endpoints.Urls = Roblox.Endpoints.Urls || {};
</script>


    <input name="__RequestVerificationToken" type="hidden" value="lVroj8zxiUtfiE1OEYLhtZUPAgCYBCHuf4-mgF1LPlZxJ5MU9hwKN1X3jz9jCy8whZ91kDw0XJzux1LaRChFk3WKLRY1" />
    
    <meta name="csrf-token" data-token="puGF+AOAR/47" />

</head>
<body>
    

<div class="boxed-body">
    <h3>Choose an existing <?= $assetTypeNameLower ?> to overwrite, or create a new <?= $assetTypeNameLower ?>.</h3>
    <div>
        <div id="assetList" class="content asset-list tab-active">
            <div class="asset model" id="newasset" onclick="document.location.href ='https://devopstest1.aftwld.xyz/ide/publish/new<?= $assetTypeNameLower ?>';">
                <a class="model-image">
                    <img id="newModelImage" class="modelThumbnail" src="https://images.rbxcdn.com/062d582034de086290214f59685f9090.png" alt="Create New" />
                </a>
                <p class="item-name-container ellipsis-overflow">(Create New)</p>
            </div>
            
            <?php
            $stmt = $pdo->prepare("SELECT * FROM assets WHERE AssetType = :assetTypeId AND CreatorID = :userId");
            $stmt->bindParam(':assetTypeId', $assetTypeId);
            $stmt->bindValue(':userId', $userInfo['UserId']);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $asset){
                echo'<div class="asset model" id="newasset" onclick="document.location.href =' . "'https://devopstest1.aftwld.xyz/ide/publish/$assetTypeNameLower?placeId=" . $asset["AssetID"] . "'" . ';">
                <a class="model-image">
                    <img id="newModelImage" class="modelThumbnail" src="/Thumbs/Asset.ashx?assetId='.$asset["AssetID"].'&width=84&height=70" alt="" />
                </a>
                <p class="item-name-container ellipsis-overflow">' . htmlspecialchars($asset["Name"]) . '</p>
            </div>';
            }
            ?>
        </div>
    </div>
    <div id="Close" class="footer-button-container divider-top">
        <a  class="btn-medium btn-negative" id="closeButton">Cancel</a>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        Roblox.PublishAs.Resources = {
            accept: "Verify",
            decline: "Cancel"
        };

        Roblox.PublishAs.Initialize();
    });
</script>


    <script type="text/javascript">function urchinTracker() {}</script>


<div class="ConfirmationModal modalPopup unifiedModal smallModal" data-modal-handle="confirmation" style="display:none;">
    <a class="genericmodal-close ImageButton closeBtnCircle_20h"></a>
    <div class="Title"></div>
    <div class="GenericModalBody">
        <div class="TopBody">
            <div class="ImageContainer roblox-item-image" data-image-size="small" data-no-overlays data-no-click>
                <img class="GenericModalImage" alt="generic image" />
            </div>
            <div class="Message"></div>
        </div>
        <div class="ConfirmationModalButtonContainer GenericModalButtonContainer">
            <a href id="roblox-confirm-btn"><span></span></a>
            <a href id="roblox-decline-btn"><span></span></a>
        </div>
        <div class="ConfirmationModalFooter">
        
        </div>  
    </div>  
    <script type="text/javascript">
        Roblox = Roblox || {};
        Roblox.Resources = Roblox.Resources || {};
        
        //<sl:translate>
        Roblox.Resources.GenericConfirmation = {
            yes: "Yes",
            No: "No",
            Confirm: "Confirm",
            Cancel: "Cancel"
        };
        //</sl:translate>
    </script>
</div>
    
    
</body>
</html>
