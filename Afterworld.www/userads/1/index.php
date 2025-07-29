<?php
$width    = 728;
$height   = 90;
$fallback = '/images/AdvertisementFallback2.jpg';
$imgSrc   = $ad['src']  ?? $fallback;
$linkHref = $ad['href'] ?? '/Upgrades/BuildersClubMemberships.aspx';
$imgAlt   = $ad['alt']  ?? 'Get Builders Club Now!';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>
        AFTERWORLD
    </title>
    <link rel="stylesheet"
          href="/CSS/Base/CSS/page___b32ef8da65611ba6f519ba1b160d63e8_m.css">
    <style>
        body            { margin: 0; }
        body.banner     { text-align: center; }
        a               { color: gray; text-decoration: none; }
        a.ad            { display: inline-block; }
        body.other a.ad { display: block; }
        a.ad img        { display: block; border: none; }
        a:hover         { text-decoration: underline; }
    </style>
</head>

<body class="abp banner">
    <a class="ad" href="<?= htmlspecialchars($linkHref) ?>" title="<?= htmlspecialchars($imgAlt) ?>" target="_top" style="width:<?= $width ?>px; height:<?= $height ?>px;">
        <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($imgAlt) ?>" width="<?= $width ?>" height="<?= $height ?>">
    </a>
    <div class="ad-annotations" style="width:<?= $width ?>px">
        <span class="ad-identification">Advertisement</span>
        <a class="BadAdButton" target="_top" href="/abusereport/Asset?id=1" title="Click to report an offensive ad">
            Report
        </a>
    </div>
</body>
</html>
