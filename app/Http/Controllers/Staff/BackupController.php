<?php

namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Http\Requests;
use Artisan;
use Log;
use Storage;


class BackupController extends Controller
{
    public function index(Request $request)
    {
        $disk = Storage::disk(config('backup.backup.destination.disks.name')[0]);

        $files = $disk->files(config('backup.backup.name'));
        $backups = [];

        foreach ($files as $k => $f) 
        {

            if (substr($f, -4) == '.zip' && $disk->exists($f)) {
                $backups[] = [
                    'file_path' => $f,
                    'file_name' => str_replace(config('backup.backup.name') . '/', '', $f),
                    'file_size' => $this->human_filesize($disk->size($f)),
                    'last_modified' => $this->getDate($disk->lastModified($f)),
                    'age' => $this->getAge($disk->lastModified($f))
                ];
            }
        }

        $backupsarray = array_reverse($backups);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $backupcol = collect($backupsarray);
        $perPage = 7;
        $currentPageItems = $backupcol->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $backups= new LengthAwarePaginator($currentPageItems , count($backupcol), $perPage);

        $backups->setPath($request->url());

        return view("staff.backup")->with('backups', $backups);
    }

    public function create()
    {
        try 
        {
            Artisan::call('backup:run', ['--only-db' => true, '--disable-notifications' => true]);
            $output = Artisan::output();

            Log::info("Backpack\BackupManager -- new backup started from admin interface \r\n" . $output);

            return redirect()->back();
        } 
        catch (Exception $e) 
        {
            Flash::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function download($file_name)
    {
        $file = config('backup.backup.name') . '/' . $file_name;
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        if ($disk->exists($file)) 
        {
            $fs = Storage::disk(config('backup.backup.destination.disks')[0])->getDriver();
            $stream = $fs->readStream($file);

            return \Response::stream(function () use ($stream) 
            {
                fpassthru($stream);
            }, 200, [
                "Content-Type" => $fs->getMimetype($file),
                "Content-Length" => $fs->getSize($file),
                "Content-disposition" => "attachment; filename=\"" . basename($file) . "\"",
            ]);
        } else {
            abort(404, "The backup file doesn't exist.");
        }
    }

    public function delete($file_name)
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        if ($disk->exists(config('backup.backup.name') . '/' . $file_name)) {
            $disk->delete(config('backup.backup.name') . '/' . $file_name);
            return redirect()->back();
        } else {
            abort(404, "The backup file doesn't exist.");
        }
    }

    public function search(Request $request)
    {
        $search = $request->search;

        if($search == "")
        {
            return Redirect::to('staff/preferences/backup');
        }
        else
        {
            $disk = Storage::disk(config('backup.backup.destination.disks.name')[0]);

            $files = $disk->files(config('backup.backup.name'));
            $backups = [];

            foreach ($files as $k => $f) 
            {

                if (substr($f, -4) == '.zip' && $disk->exists($f)) {
                    $backups[] = [
                        'file_path' => $f,
                        'file_name' => str_replace(config('backup.backup.name') . '/', '', $f),
                        'file_size' => $this->human_filesize($disk->size($f)),
                        'last_modified' => $this->getDate($disk->lastModified($f)),
                        'age' => $this->getAge($disk->lastModified($f))
                    ];
                }
            }

            foreach ($backups as $backup)
            {
                $date = date('F d, Y', strtotime($backup['last_modified']));
                if($date == $search)
                {
                    $backupsearch[] = [
                    'file_path' => $backup['file_path'],
                    'file_name' => $backup['file_name'],
                    'file_size' => $backup['file_size'],
                    'last_modified' => $backup['last_modified'],
                    'age' => $backup['age'],
                    ];
                }
            }

            if(isset($backupsearch))
            {
                $backupsarray = array_reverse($backupsearch);
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $backupcol = collect($backupsarray);
                $perPage = 7;
                $currentPageItems = $backupcol->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
                $backups= new LengthAwarePaginator($currentPageItems , count($backupcol), $perPage);

                $backups->setPath($request->url());
                $backups->appends($request->only('search'));

                $totalcount = count($backupsarray);
                $count = count($backups);
                return view("staff.backup")->with(['backups' => $backups, 'search' => $search, 'count' => $count, 'totalcount' => $totalcount]); 
            }
            else
            {
                $backupsarray= [];

                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $backupcol = collect($backupsarray);
                $perPage = 7;
                $currentPageItems = $backupcol->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
                $backups= new LengthAwarePaginator($currentPageItems , count($backupcol), $perPage);

                $backups->setPath($request->url());
                
                $totalcount = count($backupsarray);
                $count = count($backups);
                return view("staff.backup")->with(['backups' => $backups, 'search' => $search, 'count' => $count, 'totalcount' => $totalcount]); 
            }
        }
    }

    public function human_filesize($bytes, $decimals =2)
    {
        if($bytes < 1024)
        {
            return $bytes . ' B';
        }
        
        $factor = floor(log($bytes, 1024));
    
        return sprintf("%.{$decimals}f ", $bytes / pow(1024, $factor)) . ['B', 'KB', 'MB', 'GB', 'TB', 'PB'][$factor];
    }

    public function getDate($date_modify)
    {
        return Carbon::createFromTimeStamp($date_modify)->format('F d, Y  g:i A');
    }

    public function getAge($date_modify)
    {
        $createdate = Carbon::createFromTimestamp($date_modify)->toDateTimeString(); 
        $datediff = Carbon::parse($createdate);

        return $datediff->diffForHumans(null,true);

    }
}