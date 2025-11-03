# PowerShell script to update .env with Zoho Mail SMTP settings
# Run this script and provide your email when prompted

Write-Host "Zoho Mail SMTP Configuration Setup" -ForegroundColor Cyan
Write-Host "====================================" -ForegroundColor Cyan
Write-Host ""

$email = Read-Host "Enter your Zoho Mail email address (e.g., yourname@yourdomain.com)"
$port = Read-Host "Choose port: 1 for SSL (465 - Recommended) or 2 for TLS (587) [Default: 1]"

if ($port -eq "2" -or $port -eq "587") {
    $mailPort = "587"
    $mailEncryption = "tls"
    Write-Host "Using TLS (Port 587)" -ForegroundColor Green
} else {
    $mailPort = "465"
    $mailEncryption = "ssl"
    Write-Host "Using SSL (Port 465)" -ForegroundColor Green
}

Write-Host ""
Write-Host "IMPORTANT: If you have 2FA enabled, you MUST use an App Password!" -ForegroundColor Yellow
Write-Host "Get it from: Zoho Account > Security > App Passwords" -ForegroundColor Yellow
Write-Host ""
$password = Read-Host "Enter your password or App Password" -AsSecureString
$passwordPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($password))

Write-Host ""
Write-Host "Updating .env file..." -ForegroundColor Cyan

# Read .env file
$envPath = ".env"
if (Test-Path $envPath) {
    $content = Get-Content $envPath
    
    # Replace MAIL settings
    $newContent = $content | ForEach-Object {
        if ($_ -match '^MAIL_MAILER=') {
            "MAIL_MAILER=smtp"
        } elseif ($_ -match '^MAIL_HOST=') {
            "MAIL_HOST=smtp.zoho.com"
        } elseif ($_ -match '^MAIL_PORT=') {
            "MAIL_PORT=$mailPort"
        } elseif ($_ -match '^MAIL_USERNAME=') {
            "MAIL_USERNAME=$email"
        } elseif ($_ -match '^MAIL_PASSWORD=') {
            "MAIL_PASSWORD=$passwordPlain"
        } elseif ($_ -match '^MAIL_ENCRYPTION=') {
            "MAIL_ENCRYPTION=$mailEncryption"
        } elseif ($_ -match '^MAIL_FROM_ADDRESS=') {
            "MAIL_FROM_ADDRESS=`"$email`""
        } elseif ($_ -match '^MAIL_FROM_NAME=') {
            "MAIL_FROM_NAME=`"Gurukula Kangri Alumni`""
        } else {
            $_
        }
    }
    
    $newContent | Set-Content $envPath
    Write-Host ".env file updated successfully!" -ForegroundColor Green
    Write-Host ""
    Write-Host "Next steps:" -ForegroundColor Cyan
    Write-Host "1. Run: php artisan config:clear" -ForegroundColor Yellow
    Write-Host "2. Test by resending event invitations" -ForegroundColor Yellow
} else {
    Write-Host ".env file not found!" -ForegroundColor Red
}


