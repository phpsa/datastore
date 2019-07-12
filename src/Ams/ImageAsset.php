<?php
namespace Phpsa\Datastore\Ams;

use Phpsa\Datastore\Asset;
use Intervention\Image\Facades\Image;

class ImageAsset extends Asset {

	/**
	 * Asset Type
	 *
	 * @var string
	 */
	public $type = ImageAsset::class;

	/**
	 * Namespace for the asset
	 *
	 * @var string
	 */
	public $namespace = 'property';

	/**
	 * Undocumented function
	 *
	 * @param string $filename
	 * @param int $width
	 * @param int $height
	 *
	 * @return string
	 */
	public static function getImageUrl(string $filename, int $width = null, int $height = null) :string
	{
		$imagePath = public_path().'/vendor/phpsa-datastore/';

		if ($width !== null || $height !== null) {
			$path = 'thumbs/' . (int) $width .  'x' . (int) $height  . '.' . $filename;

			if(!is_file($imagePath . $path)){
				try{
					Image::make($imagePath . 'img/' . $filename)
						->resize($width, $height, function ($constraint)
							{
								$constraint->aspectRatio();
							})
							->save($imagePath . $path);
				}catch(\Exception $e){
					//Silent fallback as the image does not exist!
				}
			}
		}else{
			$path = 'img/' . $filename;
		}
		return '/vendor/phpsa-datastore/' . $path;

	}

	/**
	 * Gets default markup for the asset
	 *
	 * @param array $data
	 *
	 * @return string
	 */
	public static function html($data) :string
	{
		return '<img src="' . self::getImageUrl($data['value']) . '" />';
    }



}
