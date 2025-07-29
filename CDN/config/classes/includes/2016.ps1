$username = "YourLocalUsername"
$password = ConvertTo-SecureString "YourPassword" -AsPlainText -Force
$cred = New-Object System.Management.Automation.PSCredential ($username, $password)

$startInfo = New-Object System.Diagnostics.ProcessStartInfo
$startInfo.FileName = "C:\RCC\2016\RCCService.exe"
$startInfo.Arguments = "-console $args[0]"
$startInfo.UseShellExecute = $false
$startInfo.WorkingDirectory = "C:\RCC\2016"
$startInfo.UserName = $cred.UserName
$startInfo.Password = $cred.Password

$p = [System.Diagnostics.Process]::Start($startInfo)
$p.Id | Out-File -Encoding ASCII -FilePath $args[1]
