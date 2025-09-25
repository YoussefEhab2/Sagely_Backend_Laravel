<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = Illuminate\Http\Request::capture());

echo "Environment Variables:\n";
echo "CLOUDINARY_CLOUD_NAME: " . env('CLOUDINARY_CLOUD_NAME') . "\n";
echo "CLOUDINARY_API_KEY: " . env('CLOUDINARY_API_KEY') . "\n";
echo "CLOUDINARY_API_SECRET: " . (env('CLOUDINARY_API_SECRET') ? '***' . substr(env('CLOUDINARY_API_SECRET'), -4) : 'NULL') . "\n";
echo "CLOUDINARY_URL: " . (env('CLOUDINARY_URL') ? '***' . substr(env('CLOUDINARY_URL'), -20) : 'NULL') . "\n";

echo "\nConfig Values:\n";
$config = $app->make('config');
echo "cloudinary.cloud_name: " . $config->get('cloudinary.cloud_name') . "\n";
echo "cloudinary.api_key: " . $config->get('cloudinary.api_key') . "\n";
echo "cloudinary.api_secret: " . ($config->get('cloudinary.api_secret') ? '***' : 'NULL') . "\n";

echo "\nDirect Environment Access:\n";
echo "getenv('CLOUDINARY_CLOUD_NAME'): " . getenv('CLOUDINARY_CLOUD_NAME') . "\n";
echo "getenv('CLOUDINARY_API_KEY'): " . getenv('CLOUDINARY_API_KEY') . "\n";
echo "getenv('CLOUDINARY_API_SECRET'): " . (getenv('CLOUDINARY_API_SECRET') ? '***' : 'NULL') . "\n";

// Test creating Cloudinary instance with direct env values
echo "\nTesting Direct Cloudinary Initialization:\n";
try {
    $cloudinary = new \Cloudinary\Cloudinary([
        'cloud' => [
            'cloud_name' => getenv('CLOUDINARY_CLOUD_NAME'),
            'api_key' => getenv('CLOUDINARY_API_KEY'),
            'api_secret' => getenv('CLOUDINARY_API_SECRET'),
        ],
        'url' => [
            'secure' => true
        ]
    ]);
    
    echo "SUCCESS: Cloudinary instance created\n";
    
    // Test upload
    $testFile = __DIR__.'/test.txt';
    file_put_contents($testFile, 'Test content');
    
    $result = $cloudinary->uploadApi()->upload($testFile, [
        'public_id' => 'test_direct',
        'folder' => 'test'
    ]);
    
    echo "SUCCESS: File uploaded\n";
    echo "URL: " . $result['secure_url'] . "\n";
    
    unlink($testFile);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}