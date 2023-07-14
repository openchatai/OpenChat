<?php

namespace App\Http\Services;

use App\Http\Requests\UploadJsonFilesRequest;
use App\Models\Chatbot;
use App\Models\JsonDataSource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class HandleJsonDataSource
{
    public function __construct(private readonly Chatbot $bot, private $files)
    {
    }

    public function handle(): JsonDataSource
    {
        $dataSource = new JsonDataSource();
        $dataSource->setChatbotId($this->bot->getId());
        $dataSource->setId(Uuid::uuid4());

        $files = $this->files;
        $filesUrls = [];
        $folderName = Str::random(20);
        foreach ($files as $file) {
            $extension = $file->getClientOriginalExtension();
            if($extension == "json")
            {
                $fileName = Str::random(20) . '.' . $extension;
                // random folder name
                try {
                    $file->storeAs($folderName, $fileName, ['disk' => 'shared_volume']);
                    $filesUrls[] = $fileName;
                } catch (\Exception $e) {
                    // Handle exception
                }
            }
            else if ($extension == "zip")
            {
                $zip = new \ZipArchive();

                $fileName = Str::random(20) . '.' . $extension;
                $file->storeAs('', $fileName, ['disk' => 'shared_volume']);

                // Open the ZIP archive
                $pathToFile = '/app/shared_data/' . $fileName;
                $result = $zip->open($pathToFile);
                if ($result === true) {
                    // Extract all files to the specified directory
                    $zip->extractTo('/app/shared_data/' . $folderName . "/");

                    // Get the list of file paths
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $filesUrls[] = $zip->getNameIndex($i);
                    }

                    // Close the ZIP archive
                    $zip->close();
                } else {
                    echo "Failed to open the ZIP archive with error code: $result $pathToFile";
                }
            }
        }

        $dataSource->setFiles($filesUrls);
        $dataSource->setFolderName($folderName);

        $dataSource->save();
        return $dataSource;
    }
}
