<?php

namespace App\Http\Controllers\Api\Announcement;

use App\Http\Controllers\Controller;
use App\Models\AnnouncementText;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;

class AnnouncementTextController extends Controller
{
    use ResponseJson;
    public function index()
    {
        $announcements = AnnouncementText::select('id', 'main_text', 'sub_text', 'section', 'created_at')
        ->groupBy('section', 'id', 'main_text', 'sub_text', 'created_at')
        ->get();
        return $this->responseJson(['announcements' => $announcements]);
    }

    public function upsert(Request $request,?AnnouncementText $announcement = null)
    {
        $sub_text = json_encode($request->sub_text);
        $data =  [
            'main_text' => $request->main_text,
            'sub_text' => $sub_text,
            'section' => $request->section,
            'create_at' => now()
        ];
        $text = AnnouncementText::updateOrCreate(['id' => $announcement?->id],$data);

        return $this->responseJson([
            'announcement' => $text
        ]);
    }

    public function destroy(AnnouncementText $announcement)
    {
        $announcement->delete();
        return $this->responseJson(['message' => 'announcement was deleted successfully!']);
    }
}
