<?php
include_once __DIR__ . '/config/main.php';
use TrashBlx\Core\SoapUtils;
$soapUtils = new SoapUtils();
function isUp($portToCheck): bool
{
    exec("netstat -ano | findstr :$portToCheck", $output);
    return !empty($output);
}

$activeRuntimes = [];

while (true)
{
    echo "[Main] Checking For Servers\n";

    $stmt = $pdo->prepare('SELECT * FROM `jobs`');
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($rows) > 0)
    {
        echo "[Job Checker] " . count($rows) . " Servers Found\n";
        foreach ($rows as $row)
        {
            echo isUp($row["soapport"]) . PHP_EOL;

            if (!isUp($row["soapport"]) && (time() - $row["startTime"]) > 10)
            {
                $pid = $row["pid"] ?? 0;
                $port = $row["soapport"] ?? 0;

                $soapUtils->killRcc($pid);

                $stmt = $pdo->prepare('DELETE FROM `jobs` WHERE `soapport` = :port');
                $stmt->bindParam(":port", $port, PDO::PARAM_INT);
                $stmt->execute();

                echo "[Job Deleter] Deleted 1 Broken Server(s)\n";
            }
        }
    } else {
        echo "[Job Checker] No Running Servers Found\n";
    }
    $rccs = $soapUtils->getAllRccs();

    foreach ($rccs as $rcc)
    {
        $pid = $rcc["pid"];

        $stmt = $pdo->prepare("SELECT * FROM `jobs` WHERE `pid` = :pid");
        $stmt->bindParam(":pid", $pid, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result)
        {
            if (!isset($activeRuntimes[$pid]))
            {
                echo "[Worker] Launching orphan RCC cleanup for PID $pid\n";
                $cmd = "php " . __DIR__ . "/rcc_cleanup_worker.php {$pid} 0 0 0 > NUL 2>&1 &";
                exec($cmd);
                $activeRuntimes[$pid] = true;
            }
        }
        else
        {
            if (!isset($activeRuntimes[$pid]))
            {
                $soapport = $result["soapport"];
                $jobid = $result["jobid"];
                $isRenderer = $result["isRenderer"];

                echo "[Worker] Launching player-check cleanup for PID $pid\n";
                $cmd = "php " . __DIR__ . "/rcc_cleanup_worker.php {$pid} {$soapport} {$jobid} {$isRenderer} > NUL 2>&1 &";
                exec($cmd);
                $activeRuntimes[$pid] = true;
            }
        }
    }

    usleep(100_000);
}
