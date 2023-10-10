<?php

namespace Mike;

class Base
{
    const FILE_NAME = 'importer.csv';
    const TAXONOMY = 'product_cat';

    public function getImportFile($fileName)
    {
        return MIKE_IMPORTER_ABSPATH . "files/{$fileName}";
    }

    public function parseCsv($file)
    {
        $csvContent = $this->readCsv($file);
        if ($csvContent) {
            $csvEncoder = new \Symfony\Component\Serializer\Encoder\CsvEncoder();
            return $csvEncoder->decode($csvContent, 'csv');
        }
    }

    public function readCsv($file)
    {
        if (file_exists($file)) {
            return file_get_contents($file);
        }
        return '';
    }

    public function import()
    {
    }

    public function export()
    {
    }

    public static function getAllTerms()
    {
        return get_terms(
            static::TAXONOMY, [
                'hide_empty' => false,
            ]
        );
    }

    public static function getTermMapping()
    {
        # {Name: ID}
        $terms = static::getAllTerms();
        $mapping = [];
        foreach ($terms as $term) {
            $mapping[$term->name] = $term->term_id;
        }
        return $mapping;
    }

    public function exportDataToFile($fileName, $data)
    {
        $filePath = $this->getImportFile($fileName);
        file_put_contents($filePath, $data);
    }
}