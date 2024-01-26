<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function random($type, $length)
    {
        $result = "";
        if ($type == 'char') {
            $char = 'ABCDEFGHJKLMNPRTUVWXYZ';
            $max        = strlen($char) - 1;
            for ($i = 0; $i < $length; $i++) {
                $rand = mt_rand(0, $max);
                $result .= $char[$rand];
            }
            return $result;
        } elseif ($type == 'num') {
            $char = '0123456789';
            $max        = strlen($char) - 1;
            for ($i = 0; $i < $length; $i++) {
                $rand = mt_rand(0, $max);
                $result .= $char[$rand];
            }
            return $result;
        } elseif ($type == 'mix') {
            $char = 'A1B2C3D4E5F6G7H8J9KLMNPRTUVWXYZ';
            $max = strlen($char) - 1;
            for ($i = 0; $i < $length; $i++) {
                $rand = mt_rand(0, $max);
                $result .= $char[$rand];
            }
            return $result;
        }
    }

    function multiArrSearch($val, $arr)
    {
        foreach ($arr as $arrKey => $arrVal) {
            $cek = array_search($val, $arrVal);
            if ($cek) {
                return true;
            } else {
                return false;
            }
        }
    }

    function upload_image($file, $path)
    {
        $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
        $replace = substr($file, 0, strpos($file, ',') + 1);
        $image = str_replace($replace, '', $file);
        $image = str_replace(' ', '+', $image);
        $imageName = time() . '-' . $this->random('mix', 5) . '.' . $extension;

        $path = public_path() . $path . $imageName;
        File::put($path, base64_decode($image));

        return $imageName;
    }

    function delete_file($filename, $path)
    {
        $file = public_path() . $path . $filename;
        unlink($file);
        return;
    }
}
