<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ScanHistory;

class ScanHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $scans = ScanHistory::with(['results.acneType','skinType'])
            ->where('user_id', $user->id)
            ->orderByDesc('scan_timestamp')
            ->paginate(10);

        return view('scan_history', compact('scans'));
    }
}


