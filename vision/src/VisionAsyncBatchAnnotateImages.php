<?php
/*
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/*
 * DO NOT EDIT! This is a generated sample ("LongRunningRequest",  "vision_async_batch_annotate_images")
 */

// sample-metadata
//   title: Async Batch Image Annotation
//   description: Perform async batch image annotation
//   usage: php samples/V1/VisionAsyncBatchAnnotateImages.php [--input_image_uri "gs://cloud-samples-data/vision/label/wakeupcat.jpg"] [--output_uri "gs://your-bucket/prefix/"]
// [START vision_async_batch_annotate_images]
require __DIR__.'/../../vendor/autoload.php';

use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\GcsDestination;
use Google\Cloud\Vision\V1\Image;
use Google\Cloud\Vision\V1\ImageSource;
use Google\Cloud\Vision\V1\OutputConfig;

/** Perform async batch image annotation */
function sampleAsyncBatchAnnotateImages($inputImageUri, $outputUri)
{
    // [START vision_async_batch_annotate_images_core]

    $imageAnnotatorClient = new ImageAnnotatorClient();

    // $inputImageUri = 'gs://cloud-samples-data/vision/label/wakeupcat.jpg';
    // $outputUri = 'gs://your-bucket/prefix/';
    $source = new ImageSource();
    $source->setImageUri($inputImageUri);
    $image = new Image();
    $image->setSource($source);
    $type = Type::LABEL_DETECTION;
    $featuresElement = new Feature();
    $featuresElement->setType($type);
    $type2 = Type::IMAGE_PROPERTIES;
    $featuresElement2 = new Feature();
    $featuresElement2->setType($type2);
    $features = [$featuresElement, $featuresElement2];
    $requestsElement = new AnnotateImageRequest();
    $requestsElement->setImage($image);
    $requestsElement->setFeatures($features);
    $requests = [$requestsElement];
    $gcsDestination = new GcsDestination();
    $gcsDestination->setUri($outputUri);

    // The max number of responses to output in each JSON file
    $batchSize = 2;
    $outputConfig = new OutputConfig();
    $outputConfig->setGcsDestination($gcsDestination);
    $outputConfig->setBatchSize($batchSize);

    try {
        $operationResponse = $imageAnnotatorClient->asyncBatchAnnotateImages($requests, $outputConfig);
        $operationResponse->pollUntilComplete();
        if ($operationResponse->operationSucceeded()) {
            $response = $operationResponse->getResult();
            // The output is written to GCS with the provided output_uri as prefix
            $gcsOutputUri = $response->getOutputConfig()->getGcsDestination()->getUri();
            printf('Output written to GCS with prefix: %s'.PHP_EOL, $gcsOutputUri);
        } else {
            $error = $operationResponse->getError();
            // handleError($error)
        }
    } finally {
        $imageAnnotatorClient->close();
    }

    // [END vision_async_batch_annotate_images_core]
}
// [END vision_async_batch_annotate_images]

$opts = [
    'input_image_uri::',
    'output_uri::',
];

$defaultOptions = [
    'input_image_uri' => 'gs://cloud-samples-data/vision/label/wakeupcat.jpg',
    'output_uri' => 'gs://your-bucket/prefix/',
];

$options = getopt('', $opts);
$options += $defaultOptions;

$inputImageUri = $options['input_image_uri'];
$outputUri = $options['output_uri'];

sampleAsyncBatchAnnotateImages($inputImageUri, $outputUri);
