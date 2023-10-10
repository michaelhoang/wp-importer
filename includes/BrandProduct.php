<?php

namespace Mike;

class BrandProduct extends Base
{
    const FILE_NAME = 'brand-products.csv';
    const TAXONOMY = 'pwb-brand';

    public function import()
    {
        // Read import file
        $fileImport = $this->getImportFile(static::FILE_NAME);
        $items = $this->parseCsv($fileImport);

        //Get all brands then create mapping
        $mapping = static::getTermMapping();

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
        $dataIndex = 0;
        //Get post ID from SKU
        foreach ($items as $item) {
            if (!empty($item['Vendor']) && !empty($item['SKU'])) {
                $data[$dataIndex]['sku'] = trim($item['SKU']);
                $data[$dataIndex]['brand'] = trim($item['Vendor']);
                $dataIndex++;
            }
        }
        if (!empty($data)) {
            /** @var \Symfony\Component\Serializer\Encoder\CsvEncoder $csvEncoder */
            $csvEncoder = new \Symfony\Component\Serializer\Encoder\CsvEncoder();
            $this->exportDataToFile(static::FILE_NAME, $csvEncoder->encode($data, 'csv'));
        }
    }

    public static function getProductIdBySku($sku)
    {
        return wc_get_product_id_by_sku($sku);
    }
}