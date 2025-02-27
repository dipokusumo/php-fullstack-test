<?php
require '../../vendor/autoload.php';

use Aws\S3\S3Client;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class S3Service {
    private $s3;
    private $bucket;

    public function __construct() {
        $this->s3 = new S3Client([
            'region'  => $_ENV['AWS_REGION'],
            'version' => 'latest',
            'credentials' => [
                'key'    => $_ENV['AWS_ACCESS_KEY_ID'],
                'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'],
            ]
        ]);
        $this->bucket = $_ENV['AWS_BUCKET_NAME'];
    }

    public function uploadFile($file) {
        $upload = $this->s3->putObject([
            'Bucket' => $this->bucket,
            'Key'    => 'client_logos/' . basename($file['name']),
            'SourceFile' => $file['tmp_name'],
            'ACL'    => 'public-read'
        ]);
        return $upload['ObjectURL'];
    }
}
?>