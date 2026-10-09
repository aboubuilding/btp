<?php
namespace App\Domain\Socle\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as PdfInstance;

class PdfService
{
    public function download(string $view, array $data, string $filename, array $options = [])
    {
        return $this->build($view, $data, $options)->download("{$filename}.pdf");
    }

    public function stream(string $view, array $data, string $filename, array $options = [])
    {
        return $this->build($view, $data, $options)->stream("{$filename}.pdf");
    }

    public function save(string $view, array $data, string $path, array $options = []): string
    {
        $fullPath = storage_path('app/' . $path);
        $this->build($view, $data, $options)->save($fullPath);
        return $path;
    }

    public function output(string $view, array $data, array $options = []): string
    {
        return $this->build($view, $data, $options)->output();
    }

    private function build(string $view, array $data, array $options): PdfInstance
    {
        return Pdf::loadView($view, $data)
            ->setPaper($options['paper'] ?? 'a4', $options['orientation'] ?? 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
                'defaultFont'          => 'DejaVu Sans',
            ]);
    }
}