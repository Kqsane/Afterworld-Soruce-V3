<?php
namespace TrashBlx\Core;

use COM;
use Roblox\Grid\Rcc\Job;
use Roblox\Grid\Rcc\RCCServiceSoap;
use Roblox\Grid\Rcc\ScriptExecution;
use PDO;

class SoapUtils
{
    private function logText(string $text): void
    {
        if (!empty($text))
        {
            file_put_contents(__DIR__ . "\log.txt", $text . PHP_EOL, FILE_APPEND);
        }
        return;
    }

    public function isPortInUse(int $port): bool
    {
        $connection = @fsockopen("127.0.0.1", $port, $errno, $errstr, 0.5);

        if ($connection) {
            fclose($connection);
            return true;
        }

        return false;
    }

    public function openJobGS(int $Port, int $placeId, int $soapPort, int $creatorid = 1, int $clientYear = 2015): array
    {
        $this->logText("GS job: port=$Port, placeId=$placeId, soapPort=$soapPort, clientYear=$clientYear");

        if (self::isPortInUse($soapPort) || self::isPortInUse($Port)) {
            exit("Port already in use.");
        }

        $gsScriptPath = $_SERVER["DOCUMENT_ROOT"] . "/config/classes/includes/gameserver16.txt";
        if (!file_exists($gsScriptPath)) {
            exit("Game server script not found.");
        }

        $gsScript = file_get_contents($gsScriptPath);
        $gsScript = str_replace(
            ["%placeId%", "%port%", "%url%", "%domain%", "%creatorid%"],
            [$placeId, $Port, "aftwld.xyz", "aftwld.xyz", $creatorid],
            $gsScript
        );

        $jobId = jobId();
        $pidFile = "C:\\temp\\rcc_$soapPort.pid";

        if (!is_dir("C:\\temp")) {
            mkdir("C:\\temp", 0777, true);
        }

        $rccExe = "C:\\\\RCC\\\\RCCService.exe";
        $psCommand = "powershell -Command \"Start-Process -FilePath '$rccExe' -ArgumentList '-console $soapPort' -PassThru | Select-Object -ExpandProperty Id | Out-File -Encoding ASCII -FilePath '$pidFile'\"";
        exec($psCommand);
        $retries = 0;
        while (!self::isPortInUse($soapPort) && $retries++ < 20) {
            sleep(1);
        }

        if (!file_exists($pidFile)) {
            exit("PID file not created. RCCService may have failed to start.");
        }

        $pid = (int)trim(file_get_contents($pidFile));
        if (!$pid) {
            exit("Failed to read RCCService PID.");
        }

        try {
            $rccSoap = new RCCServiceSoap("127.0.0.1", $soapPort);
        } catch (\SoapFault $e) {
            self::killRcc($pid);
            $this->logText("SOAP error (GS): " . $e->getMessage());
            exit("Failed to start RCCService.");
        }

        $job = new Job($jobId, 9999999999999);
        $scriptExec = new ScriptExecution($jobId . "-Script", $gsScript);
        $rccSoap->OpenJobEx($job, $scriptExec);

        return [
            "JobId" => $jobId,
            "isRunning" => true,
            "soapport" => $soapPort,
            "pid" => $pid
        ];
    }

    public function renderUser($userId)
    {
        global $pdo;

        $this->logText("render user");
        $port = rand(6500, 7000);
    
        while (self::isPortInUse($port)) {
            $port = rand(6500, 7000);
        }

        $jobId = jobId();

        if (!self::isPortInUse($port)) {
            $pidFile = "C:\\temp\\rcc_user_$port.pid";
            if (!file_exists("C:\\temp")) {
                mkdir("C:\\temp", 0777, true);
            }

            $psCommand = "powershell -Command \"Start-Process -FilePath 'C:\\\\RCC\\\\RCCService.exe' -ArgumentList '-console $port' -WindowStyle Normal -PassThru | Select-Object -ExpandProperty Id | Out-File -Encoding ASCII -FilePath '$pidFile'\"";
            exec($psCommand);

            $retries = 0;
            while (!self::isPortInUse($port) && $retries++ < 20) {
                sleep(1);
            }

            $pid = null;
            if (file_exists($pidFile)) {
                $pid = (int)trim(file_get_contents($pidFile));
            }

            if (!$pid) {
                exit("Failed to obtain RCCService PID for renderUser.");
            }

            $stmt = $pdo->prepare("INSERT INTO jobs (`jobid`, `placeId`, `soapport`, `port`, `isRunning`, `startTime`, `pid`, `playerList`, `isRenderer`) 
                VALUES (:jobId, 0, :soapPort, 0, 1, :startTime, :pid, '[]', 1)");
            $stmt->bindParam(":jobId", $jobId, PDO::PARAM_STR);
            $stmt->bindParam(":soapPort", $port, PDO::PARAM_INT);
            $startTime = time();
            $stmt->bindParam(":startTime", $startTime, PDO::PARAM_INT);
            $stmt->bindParam(":pid", $pid, PDO::PARAM_INT);
            $stmt->execute();
            $rccSoap = new RCCServiceSoap("127.0.0.1", $port);
            $job = new Job($jobId, 99999999);
            $avatarScript = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/config/classes/includes/avatar.lua");
            $avatarScript = str_replace("%userid%", addslashes($userId), $avatarScript);
            $avatarScript = str_replace("%url%", addslashes("aftwld.xyz"), $avatarScript);
            $scriptExecution = new ScriptExecution($jobId, $avatarScript);
            $maxRetries = 20;
            $avatarResult = null;
            for ($i = 0; $i < $maxRetries; $i++) {
                $avatarResult = $rccSoap->BatchJobEx($job, $scriptExecution);
                if (!empty($avatarResult)) {
                    break;
                }
                sleep(1);
            }

            $this->logText("Raw avatarResult: " . var_export($avatarResult, true));
            self::killRcc($pid);

            $stmt = $pdo->prepare("DELETE FROM `jobs` WHERE `jobid` = :jobId ");
            $stmt->bindParam(":jobId", $jobId, PDO::PARAM_STR);
            $stmt->execute();

            if (empty($avatarResult)) {
                $this->logText("Render failed or returned empty result.");
                return null;
            }

            return $avatarResult;
        }

        $this->logText("Port $port is in use, could not start RCCService.");
        return null;
    }

    public function renderAsset($assetid, $assettype)
    {
        global $pdo;

        $this->logText("render asset");
        $port = rand(6500, 7000);

        while (self::isPortInUse($port)) {
            $port = rand(6500, 7000);
        }

        $jobId = jobId();

        if (!self::isPortInUse($port)) {
            $pidFile = "C:\\temp\\rcc_user_$port.pid";
            if (!file_exists("C:\\temp")) {
                mkdir("C:\\temp", 0777, true);
            }

            $psCommand = "powershell -Command \"Start-Process -FilePath 'C:\\\\RCC\\\\RCCService.exe' -ArgumentList '-console $port' -PassThru | Select-Object -ExpandProperty Id | Out-File -Encoding ASCII -FilePath '$pidFile'\"";
            exec($psCommand);

            $retries = 0;
            while (!self::isPortInUse($port) && $retries++ < 20) {
                sleep(1);
            }

            $pid = null;
            if (file_exists($pidFile)) {
                $pid = (int)trim(file_get_contents($pidFile));
            }

            if (!$pid) {
                exit("Failed to obtain RCCService PID for renderUser.");
            }

            $stmt = $pdo->prepare("INSERT INTO jobs (`jobid`, `placeId`, `soapport`, `port`, `isRunning`, `startTime`, `pid`, `playerList`, `isRenderer`) VALUE (:jobId, 0, :soapPort, 0, 1, :startTime, :pid, \"[]\", 1)");
            $stmt->bindParam(":jobId", $jobId, PDO::PARAM_STR);
            $stmt->bindParam(":soapPort", $port, PDO::PARAM_INT);
            $startTime = time();
            $stmt->bindParam(":startTime", $startTime, PDO::PARAM_INT);
            $stmt->bindParam(":pid", $pid, PDO::PARAM_INT);
            $stmt->execute();

            $rccSoap = new RCCServiceSoap("127.0.0.1", $port);
            $job = new Job($jobId, 99999999);
            $baseurl = "http://devopstest1.aftwld.xyz";
            switch ($assettype) {
                case 8:
                    $avatarScript = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/config/classes/includes/Hat.lua");
                    $avatarScript = str_replace("%assetid%", $assetid, $avatarScript);
                    $avatarScript = str_replace("%url%", $baseurl, $avatarScript);
                    break;
                case 9:
                    $avatarScript = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/config/classes/includes/Place.lua");
                    $avatarScript = str_replace("%assetid%", $assetid, $avatarScript);
                    $avatarScript = str_replace("%url%", $baseurl, $avatarScript);
                    break;
                case 10:
                    $avatarScript = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/config/classes/includes/Model.lua");
                    $avatarScript = str_replace("%assetid%", $assetid, $avatarScript);
                    $avatarScript = str_replace("%url%", $baseurl, $avatarScript);
                    break;
                case 11:
                    $avatarScript = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/config/classes/includes/Shirt.lua");
                    $avatarScript = str_replace("%assetid%", $assetid, $avatarScript);
                    $avatarScript = str_replace("%url%", $baseurl, $avatarScript);
                    break;
                case 12:
                    $avatarScript = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/config/classes/includes/Pants.lua");
                    $avatarScript = str_replace("%assetid%", $assetid, $avatarScript);
                    $avatarScript = str_replace("%url%", $baseurl, $avatarScript);
                    break;
                case 13:
                    $avatarScript = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/config/classes/includes/Decal.lua");
                    $avatarScript = str_replace("%assetid%", $assetid, $avatarScript);
                    $avatarScript = str_replace("%url%", $baseurl, $avatarScript);
                    break;
                case 17:
                    $avatarScript = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/config/classes/includes/Head.lua");
                    $avatarScript = str_replace("%assetid%", $assetid, $avatarScript);
                    $avatarScript = str_replace("%url%", $baseurl, $avatarScript);
                    break;
                case 18:
                    $avatarScript = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/config/classes/includes/Decal.lua");
                    $avatarScript = str_replace("%assetid%", $assetid, $avatarScript);
                    $avatarScript = str_replace("%url%", $baseurl, $avatarScript);
                    break;
                case 19:
                    $avatarScript = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/config/classes/includes/Gear.lua");
                    $avatarScript = str_replace("%assetid%", $assetid, $avatarScript);
                    $avatarScript = str_replace("%url%", $baseurl, $avatarScript);
                    break;
                default:
                    $avatarScript = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/config/classes/includes/BodyPart.lua");
                    $avatarScript = str_replace("%assetid%", $assetid, $avatarScript);
                    $avatarScript = str_replace("%url%", $baseurl, $avatarScript);
                    break;
            }
            $avatarScript = new ScriptExecution($jobId, $avatarScript);
            $avatarResult = $rccSoap->BatchJobEx($job, $avatarScript);

            while (empty($avatarResult)) {
                sleep(1);
            }

            self::killRcc($pid);

            $stmt = $pdo->prepare("DELETE FROM `jobs` WHERE `jobid` = :jobId ");
            $stmt->bindParam(":jobId", $jobId, PDO::PARAM_STR);
            $stmt->execute();

            return $avatarResult;
        }
    }
    public function renderUser3D($userId)
    {
        global $pdo;

        $this->logText("render user 3d");

        $port = rand(6500, 7000);
        while (self::isPortInUse($port)) {
            $port = rand(6500, 7000);
        }

        $jobId = jobId();
        if (!self::isPortInUse($port)) {
            $pidFile = "C:\\temp\\rcc_user_$port.pid";

            if (!file_exists("C:\\temp")) {
                mkdir("C:\\temp", 0777, true);
            }

            $psCommand = "powershell -Command \"Start-Process -FilePath 'C:\\\\RCC\\\\RCCService.exe' -ArgumentList '-console $port' -PassThru | Select-Object -ExpandProperty Id | Out-File -Encoding ASCII -FilePath '$pidFile'\"";
            exec($psCommand);

            $retries = 0;
            while (!self::isPortInUse($port) && $retries++ < 20) {
                sleep(1);
            }

            $pid = null;
            if (file_exists($pidFile)) {
                $pid = (int)trim(file_get_contents($pidFile));
            }

            if (!$pid) {
                exit("Failed to obtain RCCService PID for renderUser3D.");
            }

            $stmt = $pdo->prepare("INSERT INTO jobs (`jobid`, `placeId`, `soapport`, `port`, `isRunning`, `startTime`, `pid`, `playerList`, `isRenderer`) 
                VALUES (:jobId, 0, :soapPort, 0, 1, :startTime, :pid, '[]', 1)");
            $stmt->bindParam(":jobId", $jobId, PDO::PARAM_STR);
            $stmt->bindParam(":soapPort", $port, PDO::PARAM_INT);
            $startTime = time();
            $stmt->bindParam(":startTime", $startTime, PDO::PARAM_INT);
            $stmt->bindParam(":pid", $pid, PDO::PARAM_INT);
            $stmt->execute();

            $rccSoap = new RCCServiceSoap("127.0.0.1", $port);
            $job = new Job($jobId, 99999999);

            $avatarScript = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/config/classes/includes/avatar3D.lua");
            $avatarScript = str_replace("%userid%", addslashes($userId), $avatarScript);
            $avatarScript = str_replace("%url%", addslashes("aftwld.xyz"), $avatarScript);

            $scriptExecution = new ScriptExecution($jobId, $avatarScript);

            $maxRetries = 20;
            $avatarResult = null;
            for ($i = 0; $i < $maxRetries; $i++) {
                $avatarResult = $rccSoap->BatchJobEx($job, $scriptExecution);
                if (!empty($avatarResult)) {
                    break;
                }
                sleep(1);
            }

            $this->logText("Raw avatarResult: " . var_export($avatarResult, true));
            self::killRcc($pid);

            $stmt = $pdo->prepare("DELETE FROM `jobs` WHERE `jobid` = :jobId ");
            $stmt->bindParam(":jobId", $jobId, PDO::PARAM_STR);
            $stmt->execute();

            if (empty($avatarResult)) {
                $this->logText("Render3D failed or returned empty result.");
                return null;
            }

            return $avatarResult;
        }

        $this->logText("Port $port is in use, could not start RCCService.");
        return null;
    }

    public function killRcc(int $pid)
    {
        $this->logText("kill rcc");
        if ($pid > 0) {
            exec("taskkill /f /PID $pid");
        }
    }

    public function getPlayerCount(int $soapPort, string $jobId)
    {
        $rccSoap = new RCCServiceSoap("127.0.0.1", $soapPort);

        $script = "return #game:GetService(\"Players\"):GetPlayers()";
        $script = new ScriptExecution($jobId, $script);
        $scriptResult = $rccSoap->ExecuteEx($jobId, $script);

        if (isset($scriptResult->LuaValue))
        {
            return $scriptResult->LuaValue->value;
        }
    }

    public function getAllRccs()
    {
        exec("tasklist /FI \"IMAGENAME eq RCCService.exe\"", $output);

        $processes = [];

        foreach ($output as $line)
        {
            if (stripos($line, "RCCService.exe") !== false)
            {
                $parts = preg_split('/\s+/', $line);

                if (count($parts) >= 2)
                {
                    $processes[] = [
                        "name" => $parts[0] ?? "",
                        "pid" => $parts[1] ?? 0
                    ];
                }
            }
        }

        return $processes;
    }
}
