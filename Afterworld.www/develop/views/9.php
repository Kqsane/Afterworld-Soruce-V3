<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
$places      = [];
$activeCount = 0;
$userId      = null;
if (!empty($_COOKIE['_ROBLOSECURITY'])) {
    $info = getuserinfo($_COOKIE['_ROBLOSECURITY']);
    if (is_array($info) && !empty($info['UserId'])) {
        $userId = (int)$info['UserId'];
        $stmt = $pdo->prepare("SELECT AssetId AS placeId, name AS placeName, UniverseId AS universeId, Visits, Updated_At FROM assets WHERE CreatorId = :uid AND AssetType = 9 ORDER BY Updated_At DESC");
        $stmt->execute([':uid' => $userId]);
        $places = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$placeCount = count($places);
    }
}
?>

<td class="content-area">
    <table class="section-header">
        <tbody>
        <tr>
            <td class="content-title">
                <div>
                    <h2 class="header-text">Places</h2>
                    <span class="aside-text"><?php echo (int)$placeCount; ?> of 10 active slots used</span>
                </div>
            </td>
            <td>
                <div class="creation-context-filters-and-sorts" data-fetchplaceurl="/build/gamesbycontext?groupId=">
                    <div class="option">
                        <label class="sort-label">Sort by:</label>
                        <select class="place-creationcontext-drop-down" size="1">
                            <option value="RecentlyUpdated"> Recently Updated </option>
                        </select>
                    </div>
                    <div class="option">
                        <label class="checkbox-label active-only-checkbox"><input type="checkbox">Active First</label>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="creation-context-breadcrumb" style="display:none">
            <td style="height:21px">
                <div class="breadCrumb creation-context-breadcrumb">
                    <a href="#breadcrumbs=gamecontext" class="breadCrumbContext">Context</a>
                    <span class="context-game-separator" style="display:none"> » </span>
                    <a href="#breadcrumbs=game" class="breadCrumbGame" style="display:none">Game</a>
                </div>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="items-container games-container">
        <span id="verifiedEmail" style="display:none"></span>
        <span id="assetLinks" style="display:none" data-asset-links-enabled="True"></span>

        <?php if ($places): ?>
            <?php foreach ($places as $place):
                $pid = (int)$place['placeId'];
                $pname = htmlspecialchars($place['placeName'], ENT_QUOTES, 'UTF-8');
                $pnameURL = rawurlencode($place['placeName']);
                $uid = (int)$place['universeId'];
                $visits = (int)$place['Visits'];
                $updated = date('n/j/Y', is_numeric($place['Updated_At']) ? (int)$place['Updated_At'] : strtotime($place['UpdatedAt']));
                $active = !empty($place['Active']);
                ?>
                <table class="item-table" data-item-id="<?= $pid ?>" data-type="game" data-universeid="<?= $uid ?>">
                    <tbody>
                    <tr>
                        <td class="image-col">
                            <a href="/games/<?= $pid ?>/<?= $pnameURL ?>" class="game-image">
                                <img src="/Thumbs/Asset.ashx?assetId=<?= $pid ?>" alt="<?= $pname ?>">
                            </a>
                        </td>

                        <td class="name-col">
                            <a class="title" href="/games/<?= $pid ?>/<?= $pnameURL ?>"><?= $pname ?></a>
                            <table class="details-table"><tbody><tr>
                                <td class="activate-cell">
                                    <a class="<?= $active ? 'place-active' : 'place-inactive' ?>" href="/universes/configure?id=<?= $uid ?>">
                                        <?= $active ? 'Active' : 'Inactive' ?>
                                    </a>
                                </td>
                                <td class="item-date"><span>Updated: <?= $updated ?></span></td>
                            </tr></tbody></table>
                        </td>

                        <td class="stats-col-games">
                            <div class="totals-label">Total Visitors:<span><?= $visits ?></span></div>
                            <div class="totals-label">Last 7 days:<span>0</span></div>
                        </td>

                        <td class="edit-col">
                            <a class="roblox-edit-button btn-control btn-control-large" href="javascript:editGameInStudio(<?= $pid ?>)">Edit</a>
                        </td>
                        <td class="build-col">
                            <a class="roblox-build-button btn-control btn-control-large" href="javascript:void(0)" onclick="editGameInStudio(<?= $pid ?>)">Build</a>
                        </td>
                        <td class="menu-col">
                            <div class="gear-button-wrapper"><a href="#" class="gear-button" data-place-id="<?= $pid ?>"></a></div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="separator"></div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="margin:15px;">You have no places yet.</p>
        <?php endif; ?>
    </div>
</td>

		
<script>
$('.gear-button').on('click', function (e) {
    e.preventDefault();
    const $button = $(this);
    const placeId = $button.data('place-id');
    const $menu = $('#build-dropdown-menu');
    if ($menu.is(':visible') && $menu.data('active-place') == placeId) {
        $menu.hide().data('active-place', null);
        return;
    }
    const offset = $button.offset();
    $menu.css({
        top: offset.top + $button.outerHeight(),
        left: offset.left,
        position: 'absolute'
    });
    $menu.find('[data-href-template]').each(function () {
        const template = $(this).data('href-template');
        $(this).attr('href', template.replace('0', placeId));
    });
    $menu.show().data('active-place', placeId);
});

</script>
