<?php
namespace Phpsa\Datastore\Ams;
use Phpsa\Datastore\Asset;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;

class ImageAsset extends Asset {

    public $type = ImageAsset::class;
	public $namespace = 'property';

	public static function getImageUrl($filename, $width = null, $height = null){

		$imagePath = public_path().'/vendor/phpsa-datastore/';

		if ($width || $height) {
			$path = 'thumbs/' . $width .  'x' . $height  . '.' . $filename;

			if(!is_file($imagePath . $path)){
				try{
					Image::make(public_path().'/vendor/phpsa-datastore/img/' . $filename)->resize($width,$height, function ($constraint) {
						$constraint->aspectRatio();
					})->save($imagePath . $path);
				}catch(Exception $e){
					//Silent fallback as the image does not exist!
				}
			}
		}else{
			$path = 'img/' . $filename;
		}
		return '/vendor/phpsa-datastore/' . $path;



	}

    public static function html($data) {

		return '<img src="' . self::getImageUrl($data['value']) . '" />';
    }



}
