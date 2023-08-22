<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PdfFile;

class PdfController extends Controller
{
    /**
     * Store a newly uploaded PDF file and return its ID and URL.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Verifica si se ha enviado un archivo PDF en la solicitud
        if ($request->hasFile('pdf')) {
            // Obtiene el archivo PDF de la solicitud
            $pdfFile = $request->file('pdf');

            // Almacena el PDF utilizando el método storePdf del modelo PdfFile
            $pdf = PdfFile::storePdf($pdfFile);

            // Devuelve una respuesta JSON con un mensaje de éxito, ID y URL del PDF
            return response()->json([
                'message' => 'PDF uploaded successfully',
                'pdf_id' => $pdf->id,
                'pdf_url' => $pdf->getUrl(), // Agregamos la URL de descarga
            ], 201);
        }

        // Devuelve una respuesta JSON de error si no se ha cargado un archivo PDF
        return response()->json(['message' => 'No PDF file uploaded'], 400);
    }

    /**
     * Get the URL of a specific PDF by its ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUrl($id)
    {
        // Busca un PDF por su ID en la base de datos
        $pdf = PdfFile::find($id);

        // Verifica si se encontró un PDF
        if (!$pdf) {
            // Devuelve una respuesta JSON de error si el PDF no se encuentra
            return response()->json(['message' => 'PDF not found'], 404);
        }

        // Devuelve una respuesta JSON con la URL de descarga del PDF
        return response()->json(['url' => $pdf->getUrl()]);
    }

    /**
     * Download a specific PDF by its ID.
     *
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\Response
     */
    public function downloadPdf($id)
    {
        // Busca un PDF por su ID en la base de datos
        $pdf = PdfFile::find($id);

        // Verifica si se encontró un PDF
        if (!$pdf) {
            // Aborta la solicitud con un código de error 404 si el PDF no se encuentra
            abort(404);
        }

        // Obtiene la ruta completa del archivo PDF en el sistema de almacenamiento
        $filePath = storage_path('app/public/' . $pdf->file_path);

        // Devuelve el archivo PDF como una respuesta de descarga
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf', // Establece el tipo de contenido como PDF
            'Content-Disposition' => 'inline; filename="' . $pdf->file_name . '"', // Establece el encabezado de disposición
        ]);
    }
}
