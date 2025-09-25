<?php
// test-cloudinary-simple.php
require __DIR__.'/vendor/autoload.php';

// Load environment variables manually
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$cloudName = getenv('CLOUDINARY_CLOUD_NAME');
$apiKey = getenv('CLOUDINARY_API_KEY');
$apiSecret = getenv('CLOUDINARY_API_SECRET');

echo "Cloud Name: $cloudName\n";
echo "API Key: $apiKey\n";
echo "API Secret: " . ($apiSecret ? '***' . substr($apiSecret, -4) : 'NOT SET') . "\n";

try {
    $cloudinary = new Cloudinary\Cloudinary([
        'cloud' => [
            'cloud_name' => $cloudName,
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
        ],
        'url' => [
            'secure' => true
        ]
    ]);
    
    // Create a test file
    $testFile = __DIR__.'/test.txt';
    file_put_contents($testFile, 'Test content for Cloudinary');
    
    // Upload the test file
    $result = $cloudinary->uploadApi()->upload($testFile, [
        'public_id' => 'test_upload',
        'folder' => 'test'
    ]);
    
    echo "SUCCESS: File uploaded to Cloudinary\n";
    echo "URL: " . $result['secure_url'] . "\n";
    
    // Clean up
    unlink($testFile);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "TRACE: " . $e->getTraceAsString() . "\n";
}