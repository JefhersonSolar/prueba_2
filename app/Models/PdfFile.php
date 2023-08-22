<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PdfFile extends Model
{
    use HasFactory;

    protected $fillable = ['file_name', 'file_path'];

    /**
     * Store the PDF file and create a new PdfFile record.
     *
     * @param \Illuminate\Http\UploadedFile $pdfFile
     * @return PdfFile
     */
    public static function storePdf($pdfFile)
    {
        $fileName = $pdfFile->getClientOriginalName();
        $filePath = $pdfFile->store('pdfs', 'public');

        return self::create([
            'file_name' => $fileName,
            'file_path' => $filePath,
        ]);
    }

    /**
     * Get the full URL of the PDF file.
     *
     * @return string
     */
    public function getUrl()
    {
        return asset('storage/' . $this->file_path);
    }
}
