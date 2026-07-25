<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FonnteService;

class TestController extends Controller
{
    public function test()
    {
        $send = FonnteService::sendMessage('081374292559', 'Hallo! Pesan dari test lagi nih 🚀');

        dd($send);
    }
}
