<?php

namespace App\Http\Controllers;

use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MaterialController extends Controller
{
    public $material;

    /**
     * MaterialController constructor.
     * @param $material
     */
    public function __construct(Application $application)
    {
        $this->material = $application->material;
    }

    public function image()
    {
        $image = $this->material->uploadImage(public_path().'/images/avatar2.png');
        dd($image);
    }

    public function audio()
    {
        $audio = $this->material->uploadVoice(public_path().'/material/start.wma');
        dd($audio);
    }
}
