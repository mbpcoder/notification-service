<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ArtisanController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function database(Request $request)
    {
        $tableName = $request->get('table');
        $limit = $request->get('limit', 100);
        $offset = $request->get('offset', 0);

        $command = 'command:database';

        if ($tableName !== null) {
            $command .= ' --table=' . $tableName;
        }
        $command .= ' --limit=' . $limit;
        $command .= ' --offset=' . $offset;

        Artisan::call($command);
    }

    public function getLog(Request $request)
    {
        $fileName = $request->get('file_name');

        if ($fileName !== null) {
            $path = storage_path('logs/' . $fileName);

            if (file_exists($path)) {
                return file_get_contents($path);
            }
        }


        $path = storage_path('logs');
        $files = File::allFiles($path);
        foreach ($files as $_file) {
            echo $_file->getRelativePathname() . PHP_EOL;
        }
        return 'Success';
    }

    public function cacheClear(Request $request)
    {
        return cache()->flush();
    }
}
