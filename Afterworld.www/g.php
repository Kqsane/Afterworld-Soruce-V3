<?php
// Real time rencder
require __DIR__.'/config/main.php';
echo("Initialized");

use COM;
foreach (glob(__DIR__. '/Assemblies/Roblox/Grid/Rcc/*.php') as $filename) {
    require_once $filename;
}
use Roblox\Grid\Rcc\Job;
use Roblox\Grid\Rcc\RCCServiceSoap;
use Roblox\Grid\Rcc\ScriptExecution;
class SoapUtils
{
    public function isPortInUse(int $Port) : bool
    {
        exec('netstat -a -n -o | find "' . $Port . '"', $output);

        if (empty($output))
        {
            return false;
        } else {
            return true;
        }
    }

   public function openJobGS(int $Port, int $placeId, int $soapPort, int $creatorid = 1)
{
    if (self::isPortInUse($soapPort) || self::isPortInUse($Port)) {
        exit("Port already in use.");
    }

    $gsScript = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/config/classes/includes/gameserver.txt");
    $gsScript = str_replace(
        ["%placeId%", "%port%", "%url%", "%domain%", "%creatorid%"],
        [$placeId, $Port, "aftwld.xyz", "aftwld.xyz", $creatorid],
        $gsScript
    );

    $jobId = vsprintf('%s%s%s%s-%s%s-%s%s-%s%s-%s%s%s%s%s%s%s', str_split(bin2hex(random_bytes(16)), 1));

    // Define where to store the PID
    $pidFile = "C:\\temp\\rcc_$soapPort.pid";

    // Make sure the directory exists
    if (!file_exists("C:\\temp")) {
        mkdir("C:\\temp", 0777, true);
    }

    // Start RCCService using PowerShell and capture the PID
    $psCommand = "powershell -Command \"Start-Process -FilePath 'C:\\\\RCC\\\\RCCService.exe' -ArgumentList '-console $soapPort' -PassThru | Select-Object -ExpandProperty Id | Out-File -Encoding ASCII -FilePath '$pidFile'\"";
    exec($psCommand);

    // Wait until the port is available
    $retries = 0;
    while (!self::isPortInUse($soapPort) && $retries++ < 20) {
        sleep(1);
    }

    // Read PID from file
    $pid = null;
    if (file_exists($pidFile)) {
        $pid = (int)trim(file_get_contents($pidFile));
    }

    if (!$pid) {
        exit("Failed to obtain RCCService PID.");
    }

    // Try connecting to the RCCService
    $rccSoap = new RCCServiceSoap("127.0.0.1", $soapPort);
    if ($rccSoap instanceof \SoapFault) {
        self::killRcc($pid);
        exit("Failed to start RCCService.");
    }

    $job = new Job($jobId, 9999999999999);
    $gsScript = new ScriptExecution($jobId . "-Script", $gsScript);
    $result = $rccSoap->OpenJobEx($job, $gsScript);

    return [
        "JobId" => $jobId,
        "isRunning" => true,
        "soapport" => $soapPort,
        "pid" => $pid
    ];
}

    public function renderUser($userId)
{
    $port = rand(6500, 7000);

    while (self::isPortInUse($port)) {
        $port = rand(6500, 7000);
    }

    $jobId = vsprintf('%s%s%s%s-%s%s-%s%s-%s%s-%s%s%s%s%s%s%s', str_split(bin2hex(random_bytes(16)), 1));

    if (!self::isPortInUse($port)) {

        // Define where to store the PID
        $pidFile = "C:\\temp\\rcc_user_$port.pid";

        // Make sure the directory exists
        if (!file_exists("C:\\temp")) {
            mkdir("C:\\temp", 0777, true);
        }

        // Start RCCService using PowerShell and capture the PID
        $psCommand = "powershell -Command \"Start-Process -FilePath 'C:\\\\RCC\\\\RCCService.exe' -ArgumentList '-console $port' -PassThru | Select-Object -ExpandProperty Id | Out-File -Encoding ASCII -FilePath '$pidFile'\"";
        exec($psCommand);

        // Wait until the port is available
        $retries = 0;
        while (!self::isPortInUse($port) && $retries++ < 20) {
            sleep(1);
        }

        // Read PID from file
        $pid = null;
        if (file_exists($pidFile)) {
            $pid = (int)trim(file_get_contents($pidFile));
        }

        if (!$pid) {
            exit("Failed to obtain RCCService PID for renderUser.");
        }

        $rccSoap = new RCCServiceSoap("127.0.0.1", $port);
        $job = new Job($jobId, 99999999);

        $avatarScript = file_get_contents(__DIR__ . "/config/classes/includes/avatar.lua");
        $avatarScript = str_replace("%userid%", $userId, $avatarScript);
        $avatarScript = str_replace("%url%", "aftwld.xyz", $avatarScript);

        $avatarScript = new ScriptExecution($jobId, $avatarScript);
        $avatarResult = $rccSoap->BatchJobEx($job, $avatarScript);

        while (empty($avatarResult)) {
            sleep(1);
        }

        // Clean up the RCC process by PID
        self::killRcc($pid);

        return $avatarResult;
    }
}

    public function killRcc(int $pid)
    {
		if ($pid > 0) {
			exec("taskkill /f /PID $pid");
		}
    }
}
$getusers = $pdo->prepare("SELECT * FROM users");
$getusers->execute();
$getusers= $getusers->fetchAll(PDO::FETCH_ASSOC);
echo("Fetched users");
$soapUtils = new SoapUtils();
foreach($getusers as $user){
echo("Rendering ".$user["Username"]);
if(file_exists(__DIR__ . "/Thumbs/RenderedUsers/". $user['UserId'].".png")){ echo "Ignoring"; continue; }

$render = $soapUtils->renderUser($user['UserId']);
$decrypted = base64_decode($render[0]);
$path = __DIR__ . "/Thumbs/RenderedUsers/". $user['UserId'].".png";
		file_put_contents($path, $decrypted);
}