<?php
if(isset($_GET['q'])){
	header("Location: /games/?Keyword={$_GET['q']}");
}else{
	header("Location: /Catalog");
}