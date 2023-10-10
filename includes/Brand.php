<?php

namespace Mike;

class Brand extends Base
{
    const FILE_NAME = 'brands.csv';
    const TAXONOMY = 'pwb-brand';

    public function import()
    {
        // Read import file
        $fileImport = $this->getImportFile(static::FILE_NAME);
        $items = $this->parseCsv($fileImport);

        //Get all brands then create mapping
        $mapping = static::getBrandMapping();

        //Get post ID from SKU
        foreach ($items as $item) {
            if (!empty($item['sku'])) {
                $productId = static::getProductIdBySku($item['sku']);
                if (!empty($productId)) {
                    $brands = [];
                    if (!empty($item['brand'])) {
                        $brands[] = $mapping[$item['brand']];
                    }
                    //wp_set_post_terms($post_id, $tags, 'post_tag')
                    wp_set_post_terms($productId, $brands, static::TAXONOMY);
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
            if (!empty($item['Vendor']) && !empty($item['SKU'])) {
                $data[] = trim($item['Vendor']);
            }
        }

        $data = array_unique($data);
        $brands = [];
        $brandIndex = 0;
        foreach ($data as $brandItem) {
            $brands[$brandIndex] = ['name' => $brandItem];
            $brandIndex++;
        }


//        \Debug\Debug::log(__FILE__ . ':' . __LINE__);
//        \Debug\Debug::log(json_encode($brands));

        if (!empty($brands)) {
            /** @var \Symfony\Component\Serializer\Encoder\CsvEncoder $csvEncoder */
            $csvEncoder = new \Symfony\Component\Serializer\Encoder\CsvEncoder();
            $this->exportDataToFile(static::FILE_NAME, $csvEncoder->encode($brands, 'csv'));
        }
    }

    public function exportDataToFile($fileName, $data)
    {
        $filePath = $this->getImportFile($fileName);
        file_put_contents($filePath, $data);
    }
}