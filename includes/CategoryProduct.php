<?php

namespace Mike;

class CategoryProduct extends Base
{
    const FILE_NAME = 'category-products.csv';
    const TAXONOMY = 'product_cat';

    public function export()
    {
        // Read import file
        $fileImport = $this->getImportFile('shopify.csv');
        $items = $this->parseCsv($fileImport);

        $data = [];
        $dataIndex = 0;
        //Get post ID from SKU
        foreach ($items as $item) {
            if (!empty($item['Type']) && !empty($item['SKU'])) {
                $data[$dataIndex]['SKU'] = trim($item['SKU']);
                $data[$dataIndex]['Categories'] = trim($item['Type']);
                $dataIndex++;
            }
        }
        if (!empty($data)) {
            /** @var \Symfony\Component\Serializer\Encoder\CsvEncoder $csvEncoder */
            $csvEncoder = new \Symfony\Component\Serializer\Encoder\CsvEncoder();
            $this->exportDataToFile(static::FILE_NAME, $csvEncoder->encode($data, 'csv'));
        }
    }
}