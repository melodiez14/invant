<?php

class FileController extends BaseController
{
    public function __construct()
    {
        $this->beforeFilter('auth');
    }
    /**
     * Download file
     * @param  string   $file     file name in disk
     * @param  string   $filename file name for user download
     * @return response
     */
    public function anyDownload($file = 'blank.png', $filename = 'none')
    {
        $path = Config::get('idms.upload_path').$file;
        $upload = Upload::where('file_name', '=', $file)->first();
        // Error 404 if file doesn't exist
        if ( ! file_exists($path)) {
            App::abort(404);
        }

        $mime = $upload->mime;
        $filename .= '.'.$upload->extension;

        return Response::download($path, $filename, ['Content-Type' => $mime]);
    }

    /**
     * Display file (eg. image)
     * @param  string   $file filename
     * @return response
     */
    public function getShow($file = 'blank.png')
    {
        $path = Config::get('idms.uploadpath') . $file;
        $upload = Upload::where('file_name', '=', $file)->first();

        // Error 404 if file doesn't exist
        if ( ! file_exists($path)) {
            App::abort(404);
        }

        // Get file mime type, to make it simple, grab mime from db.
        // Mime is saved when upload file.
        $file = new Symfony\Component\HttpFoundation\File\File($path);

        $mime = ($upload ? $upload->mime : $file->getMimeType());

        // This request for display in page (eg. image)
        // Return the file contents with a 200 success response
        return Response::make(File::get($path), 200, array(
            'Content-Type' => $mime
        ));

    }

}
