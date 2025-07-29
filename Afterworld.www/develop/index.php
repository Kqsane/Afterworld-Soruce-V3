<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
if (!isset($_COOKIE["_ROBLOSECURITY"])) {
    header("Location: /newlogin");
    exit;
}
$tabs = [
    9  => 'Places',
    11 => 'Shirts',
    2  => 'T-Shirts',
    12 => 'Pants',
    10 => 'Models',
    13 => 'Decals',
    3  => 'Audio',
];
$currentView = filter_input(INPUT_GET, 'View', FILTER_VALIDATE_INT);
if (!isset($tabs[$currentView])) {
    $currentView = 9;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Develop - Afterworld</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/CSS/Base/CSS/main___1cacbba05e42ebf55ef7a6de7f5dd3f0_m.css">
    <link rel="stylesheet" href="/CSS/Pages/Legacy/Navigation.css">
    <link rel="stylesheet" href="/CSS/Pages/Build/BuildPage.css">
    <link rel="stylesheet" href="/CSS/Pages/Build/Develop.css">
    <link rel="stylesheet" href="/CSS/Pages/Build/DropDownMenus.css">
    <link rel="stylesheet" href="/CSS/Pages/Build/StudioWidget.css">
    <link rel="stylesheet" href="/CSS/Pages/Build/Upload.css">
    <script type="text/javascript" src="/js/jquery/jquery-1.7.2.min.js"></script> 
    <script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js"></script>
    <script type="text/javascript">
      window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-1.11.1.js'><\/script>");
    </script>
    <script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.migrate/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript">
      window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-migrate-1.2.1.js'><\/script>");
    </script>
    <style>
        .build-col { vertical-align: middle; }
    </style>
<script>
$(document).ready(function() {
    $('#build-new-button').on('click', function() {
        $('#build-new-dropdown-menu').toggle(); // Toggle visibility
    });
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#build-new-button, #build-new-dropdown-menu').length) {
            $('#build-new-dropdown-menu').hide();
        }
    });
});
</script>

</head>
<body id="rbx-body">
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Header.php'; ?>
    <div class="nav-content nav-no-left" style="margin-left: 0px; width: 100%;">
        <div class="nav-content-inner">
            <div id="MasterContainer">
                <noscript>
                    <div class="SystemAlert">
                        <div class="SystemAlertText">Please enable Javascript to use all the features on this site.</div>
                    </div>
                </noscript>

                <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Alert.php'; ?>

                <div id="BodyWrapper">
                    <div id="RepositionBody">
                        <div id="Body" style="width:970px">
                            <div id="MyCreationsTab" class="tab-active">
                                <div class="BuildPageContent">
                                    <input type="hidden" id="assetTypeId" value="<?= $currentView ?>">
                                    <input type="hidden" id="isTgaUploadEnabled" value="True">

                                    <table id="build-page" data-asset-type-id="<?= $currentView ?>" data-showcases-enabled="true" data-edit-opens-studio="True">
                                        <tbody>
                                            <tr>
                                                <td class="menu-area divider-right">
                                                    <div id="build-new-button" class="btn-medium btn-primary">Build New &#9662;</div>
                                                    <div id="build-new-dropdown-menu">
                                                        <a href="/My/NewPlace.aspx">Place</a>
                                                        <a href="#">Personal Server</a>
                                                        <a href="#">Shirt</a>
                                                        <a href="#">T-Shirt</a>
                                                        <a href="#">Pants</a>
                                                        <a href="#">Model</a>
                                                        <a href="#">Decal</a>
                                                    </div>
                                                    <div class="tab-bar">
                                                        <?php foreach ($tabs as $view => $label): ?>
                                                            <a href="/develop?View=<?= $view ?>" class="tab-item<?= $view === $currentView ? ' tab-item-selected' : '' ?>">
                                                                <?= htmlspecialchars($label) ?>
                                                            </a>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div id="StudioWidget">
                                                        <div class="widget-name">
                                                            <h3>AFTWLD Studio</h3>
                                                        </div>
                                                        <div class="content">
                                                            <div id="LeftColumn">
                                                                <div class="studio-icon">
                                                                    <img src="/Images/RobloxStudio.png" alt="Studio Icon">
                                                                </div>
                                                            </div>
                                                            <div id="RightColumn">
                                                                <ul>
                                                                    <li><a href="https://setup.aftwld.xyz/RobloxStudioLauncherBeta.exe" download>Download</a></li>
                                                                    <li><a href="https://devopstest1.aftwld.xyz/Forum/default.aspx">Forum</a></li>
                                                                    <li><a href="https://wiki.aftwld.xyz/">Wiki</a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="tab-content" style="padding-left:15px;">
                                                    <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/develop/views/{$currentView}.php"; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
