$ProjectRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$MariaDb = "C:\xampp\mysql\bin\mysqld.exe"
$MySqlAdmin = "C:\xampp\mysql\bin\mysqladmin.exe"
$Config = Join-Path $ProjectRoot "mysql-data\my.ini"

if (-not (Test-Path $MariaDb)) {
    Write-Error "MariaDB executable not found at $MariaDb"
    exit 1
}

if (-not (Test-Path $Config)) {
    Write-Error "Project MariaDB config not found at $Config"
    exit 1
}

$alreadyRunning = netstat -ano | Select-String ":3310"
if (-not $alreadyRunning) {
    Start-Process -FilePath $MariaDb -ArgumentList "--defaults-file=$Config" -WindowStyle Hidden
    Start-Sleep -Seconds 2
}

& $MySqlAdmin -h 127.0.0.1 -u root -P 3310 ping
