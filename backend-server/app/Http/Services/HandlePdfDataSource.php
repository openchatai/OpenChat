<?php

namespace App\Http\Services;

use App\Http\Requests\UploadPdfFilesRequest;
use App\Models\Chatbot;
use App\Models\PdfDataSource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class HandlePdfDataSource
{
    public function __construct(private readonly Chatbot $bot, private $files)
    {
    }

    public function handle(): PdfDataSource
    {
        $dataSource = new PdfDataSource();
        $dataSource->setChatbotId($this->bot->getId());
        $dataSource->setId(Uuid::uuid4());

        $files = $this->files;
        $filesUrls = [];
        $folderName = Str::random(20);
        foreach ($files as $file) {
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::random(20) . '.' . $extension;
            // random folder name
            try {
                $file->storeAs($folderName, $fileName, ['disk' => 'shared_volume']);
                $filesUrls[] = $fileName;
            } catch (\Exception $e) {
                // Handle exception
            }
        }

        $dataSource->setFiles($filesUrls);
        $dataSource->setFolderName($folderName);

        $dataSource->save();
        return $dataSource;
    }
}
