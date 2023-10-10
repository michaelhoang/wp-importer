<?php

namespace Mike;

class Category extends Base
{
    const FILE_NAME = 'categories.csv';
    const TAXONOMY = 'product_cat';

    public function import()
    {
        // Read import file
        $fileImport = $this->getImportFile(static::FILE_NAME);
        $items = $this->parseCsv($fileImport);

        //Get post ID from SKU
        $termsMapping = static::getTermMapping();
        foreach ($items as $item) {
            if (!empty($item['name'])) {
                $itemName = trim($item['name']);
                if (empty($termsMapping[$itemName])) {
                    wp_insert_term($itemName, static::TAXONOMY);
                    exit;
                }
            }
        }
    }

    public function export()
    {
        // Read import file
        $fileImport = $this->getImportFile('shopify.csv');
        $items = $this->parseCsv($fileImport);

        $data = [];
        foreach ($items as $item) {
            if (!empty($item['Type']) && !empty($item['SKU'])) {
                $data[] = trim($item['Type']);
            }
        }

        $data = array_unique($data);
        $categories = [];
        $categoryIndex = 0;
        foreach ($data as $categoryItem) {
            $categories[$categoryIndex] = ['name' => $categoryItem];
            $categoryIndex++;
        }

        if (!empty($categories)) {
            /** @var \Symfony\Component\Serializer\Encoder\CsvEncoder $csvEncoder */
            $csvEncoder = new \Symfony\Component\Serializer\Encoder\CsvEncoder();
            $this->exportDataToFile(static::FILE_NAME, $csvEncoder->encode($categories, 'csv'));
        }
    }
}