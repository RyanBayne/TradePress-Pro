# TradePress Pro to Trader.SEES Rename Script

Write-Host "Starting TradePress Pro -> Trader.SEES rename process..." -ForegroundColor Green

$fileCount = 0
$phpFiles = Get-ChildItem -Path . -Filter "*.php" -Recurse -File

Write-Host "`nFound $($phpFiles.Count) PHP files to process" -ForegroundColor Cyan

$replacements = @(
    @{ Find = 'TRADEPRESS_PRO_VERSION'; Replace = 'TRADERSEES_VERSION' }
    @{ Find = 'TRADEPRESS_PRO_FILE'; Replace = 'TRADERSEES_FILE' }
    @{ Find = 'TRADEPRESS_PRO_BASENAME'; Replace = 'TRADERSEES_BASENAME' }
    @{ Find = 'TRADEPRESS_PRO_DIR'; Replace = 'TRADERSEES_DIR' }
    @{ Find = 'TRADEPRESS_PRO_URL'; Replace = 'TRADERSEES_URL' }
    @{ Find = 'TRADEPRESS_PRO_'; Replace = 'TRADERSEES_' }
    @{ Find = 'TradePress_Pro'; Replace = 'TraderSEES' }
    @{ Find = 'tradepress_pro_'; Replace = 'tradersees_' }
    @{ Find = 'tradepress-pro'; Replace = 'trader-sees' }
    @{ Find = "'tradepress-pro'"; Replace = "'trader-sees'" }
    @{ Find = '"tradepress-pro"'; Replace = '"trader-sees"' }
)

foreach ($file in $phpFiles) {
    $content = Get-Content -Path $file.FullName -Raw -Encoding UTF8
    $originalContent = $content
    $fileModified = $false
    
    foreach ($replacement in $replacements) {
        if ($content -match [regex]::Escape($replacement.Find)) {
            $content = $content -replace [regex]::Escape($replacement.Find), $replacement.Replace
            $fileModified = $true
        }
    }
    
    if ($fileModified -and ($content -ne $originalContent)) {
        Set-Content -Path $file.FullName -Value $content -Encoding UTF8 -NoNewline
        $fileCount++
        
        if ($fileCount % 10 -eq 0) {
            Write-Host "Processed $fileCount files..." -ForegroundColor Gray
        }
    }
}

Write-Host "`n=== PHP FILES COMPLETE ===" -ForegroundColor Green
Write-Host "Modified $fileCount PHP files" -ForegroundColor Cyan

# Process JS files
$jsFiles = Get-ChildItem -Path . -Filter "*.js" -Recurse -File
$jsCount = 0

foreach ($file in $jsFiles) {
    $content = Get-Content -Path $file.FullName -Raw -Encoding UTF8
    $originalContent = $content
    
    $content = $content -replace 'tradepress-pro', 'trader-sees'
    $content = $content -replace 'tradepressPro', 'traderSEES'
    
    if ($content -ne $originalContent) {
        Set-Content -Path $file.FullName -Value $content -Encoding UTF8 -NoNewline
        $jsCount++
    }
}

Write-Host "Modified $jsCount JavaScript files" -ForegroundColor Cyan

# Process CSS files
$cssFiles = Get-ChildItem -Path . -Filter "*.css" -Recurse -File
$cssCount = 0

foreach ($file in $cssFiles) {
    $content = Get-Content -Path $file.FullName -Raw -Encoding UTF8
    $originalContent = $content
    
    $content = $content -replace 'tradepress-pro', 'trader-sees'
    
    if ($content -ne $originalContent) {
        Set-Content -Path $file.FullName -Value $content -Encoding UTF8 -NoNewline
        $cssCount++
    }
}

Write-Host "Modified $cssCount CSS files" -ForegroundColor Cyan

Write-Host "`n=== SUMMARY ===" -ForegroundColor Green
Write-Host "PHP files modified: $fileCount" -ForegroundColor White
Write-Host "JS files modified: $jsCount" -ForegroundColor White
Write-Host "CSS files modified: $cssCount" -ForegroundColor White
Write-Host "`nNext: Rename main file and update plugin header" -ForegroundColor Yellow
