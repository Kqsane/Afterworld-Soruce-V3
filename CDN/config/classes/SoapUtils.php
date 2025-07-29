<?php
// NOTICE: rewrote all of this completely
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
        if (!empty($text)) {
            file_put_contents(__DIR__ . "/log.txt", $text . PHP_EOL, FILE_APPEND);
        }
    }

    public function isPortInUse(int $port): bool
    {
        return (bool) @fsockopen("127.0.0.1", $port, $errno, $errstr, 0.5);
    }

    public function openJobGS(
        int $port,
        int $placeId,
        int $soapPort,
        int $creatorId = 1,
        int $clientYear = 2015
        ): array {
        $this->logText("Starting Job on port $port | placeId=$placeId | soapPort=$soapPort | clientYear=$clientYear");

        if ($this->isPortInUse($soapPort) || $this->isPortInUse($port)) {
            exit("Port already in use.");
        }

        $scriptPath = $_SERVER["DOCUMENT_ROOT"] . "/config/classes/includes/";
        // $gsFile = $clientYear === 2016 ? "gameserver16.txt" : "gameserver.txt";
		$gsFile = "gameserver.txt";
        $gsScript = file_get_contents($scriptPath . $gsFile);
        if (!$gsScript) exit("Failed to load gameserver script.");
        $gsScript = str_replace(
            ["%placeId%", "%port%", "%url%", "%domain%", "%creatorid%"],
            [$placeId, $port, "aftwld.xyz", "aftwld.xyz", $creatorId],
            $gsScript
        );
        $jobId = jobId();
        $pidDir = "C:\\temp";
        $pidFile = "$pidDir\\rcc_{$soapPort}.pid";
        if (!is_dir($pidDir)) {
            mkdir($pidDir, 0777, true);
        }

        // we don't need 2016 no more as we are gonna use a multi corescript 2016 source client
        // $rccPath = $clientYear === 2016 ? "C:\\RCC\\2016\\RCCService.exe" : "C:\\RCC\\RCCService.exe";
		$rccPath = "C:\\RCC\\RCCService.exe";
        $psCommand = <<<CMD
        powershell -Command "Start-Process -FilePath '$rccPath' -ArgumentList '-console $soapPort' -PassThru | Select-Object -ExpandProperty Id | Out-File -Encoding ASCII -FilePath '$pidFile'"
        CMD;
        exec($psCommand);

        for ($i = 0; $i < 20 && !$this->isPortInUse($soapPort); $i++) {
            sleep(1);
        }

        if (!file_exists($pidFile) || !($pid = (int)trim(file_get_contents($pidFile)))) {
            exit("Failed to obtain pid for AFTEREngine.");
        }

        $rccSoap = new RCCServiceSoap("127.0.0.1", $soapPort);
        if (!$rccSoap || $rccSoap instanceof \SoapFault) {
            self::killRcc($pid);
            exit("Failed to connect to AFTEREngine.");
        }

        $job = new Job($jobId, 9999999999999);
        $script = new ScriptExecution($jobId . "-Script", $gsScript);
        $rccSoap->OpenJobEx($job, $script);

        return [
            "JobId" => $jobId,
            "isRunning" => true,
            "soapPort" => $soapPort,
            "pid" => $pid
        ];
    }

    // enhanced and now has user id logging
    public function renderUser(int $userId)
    {
        global $pdo;
        $this->logText("Render user $userId"); 
        $port = rand(6500, 7000);
        while (self::isPortInUse($port)) {
            $port = rand(6500, 7000);
        }
		
        $jobId = jobId();
        $startTime = time();
        $pidDir = "C:\\temp";
        $pidFile = "$pidDir\\rcc_user_{$port}.pid";

        if (!is_dir($pidDir)) {
            mkdir($pidDir, 0777, true);
        }

        $psCommand = <<<PS
        powershell -Command "Start-Process -FilePath 'C:\\RCC\\RCCService.exe' -ArgumentList '-console $port' -PassThru | Select-Object -ExpandProperty Id | Out-File -Encoding ASCII -FilePath '$pidFile'"
        PS;
        exec($psCommand);

        for ($retries = 0; $retries < 20 && !self::isPortInUse($port); $retries++) {
            sleep(1);
        }

        if (!file_exists($pidFile) || !($pid = (int)trim(file_get_contents($pidFile)))) {
            exit("Failed to start RCCService for user renderer.");
        }

        $stmt = $pdo->prepare("INSERT INTO jobs (`jobid`, `placeId`, `soapport`, `port`, `isRunning`, `startTime`, `pid`, `playerList`, `isRenderer`) VALUES (:jobId, 0, :soapPort, 0, 1, :startTime, :pid, '[]', 1)");
        $stmt->execute([':jobId' => $jobId, ':soapPort' => $port, ':startTime' => $startTime, ':pid' => $pid]);
        $scriptPath = $_SERVER["DOCUMENT_ROOT"] . "/config/classes/includes/avatar.lua";
        $lua = file_get_contents($scriptPath);
        if (!$lua) {
            self::killRcc($pid);
            exit("Avatar script missing.");
        }

        $lua = str_replace(['%userid%', '%url%'], [$userId, "aftwld.com"], $lua);
        $rccSoap = new RCCServiceSoap("127.0.0.1", $port);
        $job = new Job($jobId, 99999999);
        $script = new ScriptExecution($jobId, $lua);
        $avatarResult = $rccSoap->BatchJobEx($job, $script);
        $retries = 0;
		
        while (empty($avatarResult) && $retries++ < 5) {
            sleep(1);
        }
        self::killRcc($pid);
        $pdo->prepare("DELETE FROM jobs WHERE jobid = :jobId")->execute([':jobId' => $jobId]);
        return $avatarResult ?: null;
    }

    public function renderAsset(int $assetId, int $assetType)
    {
        global $pdo;
        $this->logText("Render asset ID $assetId (type $assetType)");
        $port = rand(6500, 7000);
        while (self::isPortInUse($port)) {
            $port = rand(6500, 7000);
        }

        $jobId = jobId();
        $startTime = time();
        $pidDir = "C:\\temp";
        $pidFile = "$pidDir\\rcc_user_{$port}.pid";
    
        if (!is_dir($pidDir)) {
            mkdir($pidDir, 0777, true);
        }

        $psCommand = <<<PS
        powershell -Command "Start-Process -FilePath 'C:\\RCC\\RCCService.exe' -ArgumentList '-console $port' -PassThru | Select-Object -ExpandProperty Id | Out-File -Encoding ASCII -FilePath '$pidFile'"
        PS;
        exec($psCommand);
	
        for ($i = 0; $i < 20 && !self::isPortInUse($port); $i++) {
            sleep(1);
        }

        if (!file_exists($pidFile) || !($pid = (int)trim(file_get_contents($pidFile)))) {
            exit("Failed to obtain pid for AFTEREngine.");
        }
        $stmt = $pdo->prepare("INSERT INTO jobs (`jobid`, `placeId`, `soapport`, `port`, `isRunning`, `startTime`, `pid`, `playerList`, `isRenderer`) VALUES (:jobId, 0, :soapPort, 0, 1, :startTime, :pid, '[]', 1)");
        $stmt->execute([':jobId' => $jobId, ':soapPort' => $port, ':startTime' => $startTime, ':pid' => $pid]);
        // NOTICE: if you want to add a new asset type well now u can without worrying about messed up spaghetti code
		$scriptMap = [
            8 => "Hat.lua",
            9 => "Place.lua",
            10 => "Model.lua",
            11 => "Shirt.lua",
            12 => "Pants.lua",
            13 => "Decal.lua",
            17 => "Head.lua",
            18 => "Decal.lua",
            19 => "Gear.lua"
        ];
        $scriptFile = $scriptMap[$assetType] ?? "BodyPart.lua";
        $scriptPath = $_SERVER["DOCUMENT_ROOT"] . "/config/classes/includes/$scriptFile";
        if (!file_exists($scriptPath)) {
            self::killRcc($pid);
            exit("Asset script not found: $scriptFile");
        }

        $scriptCode = file_get_contents($scriptPath);
        $scriptCode = str_replace(["%assetid%", "%url%"], [$assetId, "https://devopstest1.aftwld.xyz"], $scriptCode);
        $rccSoap = new RCCServiceSoap("127.0.0.1", $port);
        $job = new Job($jobId, 99999999);
        $script = new ScriptExecution($jobId, $scriptCode);
        $result = $rccSoap->BatchJobEx($job, $script);
	
        for ($i = 0; $i < 5 && empty($result); $i++) {
            sleep(1);
        }
        self::killRcc($pid);
        $pdo->prepare("DELETE FROM jobs WHERE jobid = :jobId")->execute([':jobId' => $jobId]);
        return $result ?: null;
    }

    public function renderUser3D(int $userId)
    {
        global $pdo;
        $this->logText("Render 3D user $userId");
        $port = rand(6500, 7000);
        while (self::isPortInUse($port)) {
            $port = rand(6500, 7000);
        }
	
        $jobId = jobId();
        $startTime = time();
        $pidDir = "C:\\temp";
        $pidFile = "$pidDir\\rcc_user_{$port}.pid";
        if (!is_dir($pidDir)) {
            mkdir($pidDir, 0777, true);
        }

        $psCommand = <<<PS
        powershell -Command "Start-Process -FilePath 'C:\\RCC\\RCCService.exe' -ArgumentList '-console $port' -PassThru | Select-Object -ExpandProperty Id | Out-File -Encoding ASCII -FilePath '$pidFile'"
        PS;
        exec($psCommand);
	
        for ($i = 0; $i < 20 && !self::isPortInUse($port); $i++) {
            sleep(1);
        }
		
        if (!file_exists($pidFile) || !($pid = (int)trim(file_get_contents($pidFile)))) {
            exit("Failed to obtain pid for AFTEREngine.");
        }

        $stmt = $pdo->prepare("INSERT INTO jobs (`jobid`, `placeId`, `soapport`, `port`, `isRunning`, `startTime`, `pid`, `playerList`, `isRenderer`) VALUES (:jobId, 0, :soapPort, 0, 1, :startTime, :pid, '[]', 1)");
        $stmt->execute([':jobId' => $jobId, ':soapPort' => $port, ':startTime' => $startTime, ':pid' => $pid]);
        $scriptPath = $_SERVER["DOCUMENT_ROOT"] . "/config/classes/includes/avatar3D.lua";
        if (!file_exists($scriptPath)) {
            self::killRcc($pid);
            exit("avatar3D.lua not found.");
        }
        $lua = file_get_contents($scriptPath);
        $lua = str_replace(['%userid%', '%url%'], [$userId, 'aftwld.com'], $lua);
        $rccSoap = new RCCServiceSoap("127.0.0.1", $port);
        $job = new Job($jobId, 99999999);
        $script = new ScriptExecution($jobId, $lua);
        $result = $rccSoap->BatchJobEx($job, $script);
    
    	for ($i = 0; $i < 5 && empty($result); $i++) {
            sleep(1);
        }
        self::killRcc($pid);
        $pdo->prepare("DELETE FROM jobs WHERE jobid = :jobId")->execute([':jobId' => $jobId]);
        return $result ?: null;
    }

    public function killRcc(int $pid): void
    {
        $this->logText("Killing RCC process with PID: $pid");
        if ($pid > 0) {
            exec("taskkill /F /PID $pid 2>NUL");
        }
    }

    public function getPlayerCount(int $soapPort, string $jobId): int
    {
        $this->logText("Fetching player count for Job ID: $jobId on port $soapPort");
        try {
            $rccSoap = new RCCServiceSoap("127.0.0.1", $soapPort);
            $lua = 'return #game:GetService("Players"):GetPlayers()';
            $script = new ScriptExecution($jobId, $lua);
            $result = $rccSoap->ExecuteEx($jobId, $script);

            if (isset($result->LuaValue) && is_numeric($result->LuaValue->value)) {
                return (int)$result->LuaValue->value;
            }
        } catch (\Exception $e) {
            $this->logText("Error getting player count: " . $e->getMessage());
        }
        return 0;
    }


    public function getAllRccs(): array
    {
        exec('tasklist /FI "IMAGENAME eq RCCService.exe"', $output);
        $processes = [];
        foreach ($output as $line) {
            if (stripos($line, "RCCService.exe") !== false) {
                $parts = preg_split('/\s+/', trim($line));
                if (count($parts) >= 2 && is_numeric($parts[1])) {
                    $processes[] = [
                        "name" => $parts[0],
                        "pid" => (int)$parts[1]
                    ];
                }
            }
        }
        return $processes;
    }
}
