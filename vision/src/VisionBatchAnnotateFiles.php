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
 * DO NOT EDIT! This is a generated sample ("Request",  "vision_batch_annotate_files")
 */

// sample-metadata
//   title:
//   description: Perform batch file annotation
//   usage: php samples/V1/VisionBatchAnnotateFiles.php [--file_path "resources/kafka.pdf"]
// [START vision_batch_annotate_files]
require __DIR__.'/../../vendor/autoload.php';

use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\AnnotateFileRequest;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\InputConfig;

/**
 * Perform batch file annotation.
 *
 * @param string $filePath Path to local pdf file, e.g. /path/document.pdf
 */
function sampleBatchAnnotateFiles($filePath)
{
    // [START vision_batch_annotate_files_core]

    $imageAnnotatorClient = new ImageAnnotatorClient();

    // $filePath = 'resources/kafka.pdf';

    // Supported mime_type: application/pdf, image/tiff, image/gif
    $mimeType = 'application/pdf';
    $content = file_get_contents($filePath);
    $inputConfig = new InputConfig();
    $inputConfig->setMimeType($mimeType);
    $inputConfig->setContent($content);
    $type = Type::DOCUMENT_TEXT_DETECTION;
    $featuresElement = new Feature();
    $featuresElement->setType($type);
    $features = [$featuresElement];

    // The service can process up to 5 pages per document file. Here we specify the first, second, and
    // last page of the document to be processed.
    $pagesElement = 1;
    $pagesElement2 = 2;
    $pagesElement3 = -1;
    $pages = [$pagesElement, $pagesElement2, $pagesElement3];
    $requestsElement = new AnnotateFileRequest();
    $requestsElement->setInputConfig($inputConfig);
    $requestsElement->setFeatures($features);
    $requestsElement->setPages($pages);
    $requests = [$requestsElement];

    try {
        $response = $imageAnnotatorClient->batchAnnotateFiles($requests);
        foreach ($response->getResponses()[0]->getResponses() as $imageResponse) {
            printf('Full text: %s'.PHP_EOL, $imageResponse->getFullTextAnnotation()->getText());
            foreach ($imageResponse->getFullTextAnnotation()->getPages() as $page) {
                foreach ($page->getBlocks() as $block) {
                    printf("\nBlock confidence: %s".PHP_EOL, $block->getConfidence());
                    foreach ($block->getParagraphs() as $par) {
                        printf("\tParagraph confidence: %s".PHP_EOL, $par->getConfidence());
                        foreach ($par->getWords() as $word) {
                            printf("\t\tWord confidence: %s".PHP_EOL, $word->getConfidence());
                            foreach ($word->getSymbols() as $symbol) {
                                printf("\t\t\tSymbol: %s, (confidence: %s)".PHP_EOL, $symbol->getText(), $symbol->getConfidence());
                            }
                        }
                    }
                }
            }
        }
    } finally {
        $imageAnnotatorClient->close();
    }

    // [END vision_batch_annotate_files_core]
}
// [END vision_batch_annotate_files]

$opts = [
    'file_path::',
];

$defaultOptions = [
    'file_path' => 'resources/kafka.pdf',
];

$options = getopt('', $opts);
$options += $defaultOptions;

$filePath = $options['file_path'];

sampleBatchAnnotateFiles($filePath);
