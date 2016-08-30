<?php

namespace Orange\Image;

/**
 * Class Image
 * @package Orange\Image
 * @author Mikhail Kelner
 */
class Image {

    /**
     * @var resource
     */
    private $source;
    /**
     * @var string
     */
    private $filepath;
    /**
     * @var int
     */
    private $height;
    /**
     * @var int
     */
    private $width;
    /**
     * @var string
     */
    private $type;

    /**
     * @var Color
     */
    private $defaultBackgroundColor;

    /**
     * @param string $filepath
     * @param int $width
     * @param int $height
     */
    public function __construct($filepath = null,$width = null,$height = null){
		$this->filepath = $filepath;
		if (is_null($this->filepath) || !is_file($this->filepath)){
			$this->width = intval($width);
			$this->height = intval($height);
			$this->type = null;
		} else {
			$image_size = getimagesize($this->filepath);
			$this->width = intval($image_size[0]);
			$this->height = intval($image_size[1]);
			$this->type = image_type_to_mime_type($image_size[2]);
		}
		$this->create();
	}

    /**
     * @return int
     */
    public function getWidth(){
		return $this->width;
	}

    /**
     * @return int
     */
    public function getHeight(){
		return $this->height;
	}

    /**
     * @return string
     */
    public function getFilepath(){
		return $this->filepath;
	}

    /**
     * @param int $jpeg_quality
     * @return Image
     */
    public function echoImage($jpeg_quality = null){
        $type = $this->getType(false);
        if (is_null($type)){
            $type = 'image/png';
        }
        header('Content-type: '.$type);
        if ($type == 'image/png') {
            imagepng($this->source);
        } elseif ($type == 'image/jpeg') {
            imagejpeg($this->source,null,is_null($jpeg_quality) ? 100 : intval($jpeg_quality));
        } elseif ($type == 'image/gif') {
            imagegif($this->source);
        } else {
            imagepng($this->source);
        }
        flush();
        return $this;
	}

    /**
     * @param $type
     * @return Image
     */
    public function setType($type){
        if ($type == 'image/png') {
            imagealphablending($this->source, false);
            imagesavealpha($this->source, true);
        }
        $this->type = $type;
        return $this;
    }

    /**
     * @param bool $ext
     * @return null|string
     */
    public function getType($ext = true){
		if ($this->type == 'image/png') {
			return $ext ? 'png' : $this->type;
		} elseif ($this->type == 'image/jpeg') {
			return $ext ? 'jpg' : $this->type;
		} elseif ($this->type == 'image/gif') {
			return $ext ? 'gif' : $this->type;
		} else {
			return null;
		}
	}

    /**
     * Create image from file
     */
    private function create(){
		if ($this->type == 'image/png') {
			$this->source = imagecreatefrompng($this->filepath);
			imagealphablending($this->source, false);
			imagesavealpha($this->source, true);
		} elseif ($this->type == 'image/jpeg') {
			$this->source = imagecreatefromjpeg($this->filepath);
		} elseif ($this->type == 'image/gif') {
			$this->source = imagecreatefromgif($this->filepath);
		} else {
            $this->source = (($this->width > 0) && ($this->height > 0))
                ? $this->getEmptyImageResource($this->width, $this->height)
                : false
            ;
		}
	}

    /**
     * @param string $path
     * @param strimg $type
     * @param int $jpeg_quality
     * @param bool $set_new_path
     * @return Image
     */
    public function save($path = null,$type = null,$jpeg_quality = 100,$set_new_path = false){
		if ($this->source){
			if (is_null($type)){
				$type = $this->type;
			}
			if (is_null($path)){
				$path = $this->filepath;
			} else {
				if ($set_new_path){
					$this->filepath = $path;
				}
			}
			if ($type == 'image/png') {
				return imagepng($this->source,$path);
			} elseif ($type == 'image/jpeg') {
				return imagejpeg($this->source,$path,intval($jpeg_quality));
			} elseif ($type == 'image/gif') {
				return imagegif($this->source,$path);
			} else {
				return imagepng($this->source,$path);
			}
		} else {
			return false;
		}
		return $this;
	}

    /**
     * @param Color $color
     */
    public function setDefaultBackgroundColor($color){
        $this->defaultBackgroundColor = $color;
    }

    /**
     * @param int $width
     * @param int $height
     * @param Color $background
     * @return resource
     */
    private function getEmptyImageResource($width,$height,$background = null){
        $tmp = imagecreatetruecolor($width, $height);
		if ($this->type == 'image/png'){
			imagealphablending($tmp, false);
			imagesavealpha($tmp, true);
		}
        if (is_null($background)) {
            $background = isset($this->defaultBackgroundColor) ? $this->defaultBackgroundColor : new Color();
        }
        imagefill($tmp, 0, 0, $background->getColorResource($tmp, ($this->type == 'image/png')));
		return $tmp;
	}

    /**
     * @param int $size
     * @param bool $contain
     * @return Image
     */
    public function square($size,$contain = false){
		$this->rectangle($size, $size, $contain);
        return $this;
	}

    /**
     * @param int $width
     * @param int $height
     * @param bool $contain
     * @return Image
     */
    public function rectangle($width,$height,$contain = false){
		if ($this->source && $this->width && $this->height){
			$ratio_base = $this->width / $this->height;
			$ratio_new  = $width / $height;
			if ($ratio_base > $ratio_new){
				if ($contain){
					$this->resize($width, round($width / $ratio_base));
				} else {
					$this->resize(round($ratio_base * $height), $height);
				}
			} else {
				if ($contain){
					$this->resize($ratio_base * $height, $height);
				} else {
					$this->resize($width, round($width / $ratio_base));
				}
			}
			$this->crop($width,$height,0.5,0.5,true);
		}
		return $this;
	}

    /**
     * @param int $width
     * @param int $height
     * @return Image
     */
    public function resize($width = null,$height = null){
		if ($this->source){
			if (is_null($width)){
				if (is_null($height)){
					$width = $this->width;
					$height = $this->height;
				} else {
					$width = round($this->width / $this->height * $height);
				}
			} else {
				if (is_null($height)){
					$height = round($this->height / $this->width * $width);
				}
			}
			$tmp = $this->getEmptyImageResource($width, $height);
			imagecopyresampled($tmp,$this->source,0,0,0,0,$width,$height,$this->width,$this->height);
			$this->source = $tmp;
			$this->width = round($width);
			$this->height = round($height);
		}
        return $this;
	}

    /**
     * @param int $width
     * @param int $height
     * @param int $offset_x
     * @param int $offset_y
     * @param bool $percent_mode
     * @return Image
     */
    public function crop($width = null,$height = null,$offset_x = 0,$offset_y = 0,$percent_mode = false){
		if ($this->source){

			if (is_null($width)){
				if (is_null($height)){
					$width = $this->width;
					$height = $this->height;
				} else {
					$width = round($this->width / $this->height * $height);
				}
			} else {
				if (is_null($height)){
					$height = round($this->height / $this->width * $width);
				}
			}

			$tmp = $this->getEmptyImageResource($width, $height);

			$ratio_base = $this->width / $this->height;
			$ratio_new  = $width / $height;

			if ($ratio_base < $ratio_new){
				$width2 = round($this->height * $ratio_base);
				$height2 = $height;
			} else {
				$width2 = $width;
				$height2 = round($this->width / $ratio_base);
			}

			if ($percent_mode){
				$offset_x = round( ($width - $this->width) * $offset_x);
				$offset_y = round( ($height - $this->height) * $offset_y);
			}

			imagecopyresampled(
				$tmp,
                $this->source,
				$offset_x > 0 ? abs($offset_x) : 0,
				$offset_y > 0 ? abs($offset_y) : 0,
				$offset_x < 0 ? abs($offset_x) : 0,
				$offset_y < 0 ? abs($offset_y) : 0,
				min($this->width,$width2),
                min($this->height,$height2),
                min($this->width,$width2),
                min($this->height,$height2)
			);

			$this->source = $tmp;
			$this->width = $width;
			$this->height = $height;

		}
        return $this;
	}

    /**
     * @param Color|Image|null $background
     * @return Image
     * @throws \Exception
     */
    public function setBackgroundToImage($background = null){
        if (is_null($background)) {
            $bgImage = $this->getEmptyImageResource($this->width, $this->height);
        } elseif ($background instanceof Image){
            $bgImage = $background->getResource();
        } elseif ($background instanceof Color) {
            $bgImage = $this->getEmptyImageResource($this->width, $this->height, $background);
        } else {
            throw new \Exception('Unknown type of background');
        }
		imagecopy($bgImage,$this->source,0,0,0,0,$this->width,$this->height);
		$this->source = $bgImage;
        return $this;
	}

    /**
     * @param Image $image
     * @param int $x
     * @param int $y
     * @return Image
     * @throws \Exception
     */
    public function putImage($image, $x, $y){
        if (!($image instanceof Image)){
            throw new \Exception('Unknown type of image');
        }
        imagecopy($this->source,$image->getResource(),$x,$y,0,0,$image->getWidth(),$image->getHeight());
        return $this;
    }

    /**
     * @param string $text
     * @param Font $font
     * @param int $x
     * @param int $y
     * @param int $angle
     * @return Image
     * @throws \Exception
     */
    public function putText($text, $font, $x, $y, $angle = 0){
        imagealphablending($this->source, true);
        imagettftext(
            $this->source,
            $font->getSize(),
            $angle,
            $x,
            $y,
            $font->getColor()->getColorResource($this->source),
            $font->getFontfile(),
            $text
        );
        return $this;
    }

    /**
     * @return resource
     */
    public function getResource(){
        return $this->source;
    }

    /**
     * @param int $x
     * @param int $y
     * @return array
     */
    public function getPixelColorArray($x,$y){
        return imagecolorsforindex($this->source, imagecolorat($this->source, $x, $y));
    }

    /**
     * @param int $x
     * @param int $y
     * @return Color
     */
    public function getPixelColor($x,$y){
        $rgba = $this->getPixelColorArray($x, $y);
        return new Color($rgba['red'],$rgba['green'],$rgba['blue'],$rgba['alpha']);
    }

    /**
     * @param int $x
     * @param int $y
     * @param Color $color
     * @return Image
     */
    public function setPixelColor($x,$y,$color){
        imagesetpixel($this->source, $x, $y, $color->getColorResource($this->source, ($this->type == 'image/png')));
        return $this;
    }

    /**
     * @param $filter
     * @param int|float $arg1
     * @param int|float $arg2
     * @param int|float $arg3
     * @param int|float $arg4
     * @return Image
     */
    public function filter($filter,$arg1 = null,$arg2 = null,$arg3 = null,$arg4 = null){
        imagefilter($this->source, $filter, $arg1, $arg2, $arg3, $arg4);
        return $this;
    }

    /**
     * @param int $x_start
     * @param int $y_start
     * @param int $x_end
     * @param int $y_end
     * @return Color
     */
    public function getAverageColor($x_start = 0,$y_start = 0,$x_end = null,$y_end = null){
        if (is_null($x_end)){
            $x_end = $this->width - 1;
        }
        if (is_null($y_end)){
            $y_end = $this->height - 1;
        }
        $rA = $gA = $bA = $aA = array();
        for ($x_pos = $x_start;$x_pos <= $x_end;$x_pos++){
            for ($y_pos = $y_start;$y_pos <= $y_end;$y_pos++){
                $rgba = $this->getPixelColorArray($x_pos,$y_pos);
                $rA[] = $rgba['red'];
                $gA[] = $rgba['green'];
                $bA[] = $rgba['blue'];
                $aA[] = $rgba['alpha'];
            }
        }
        $count = count($rA);
        return $count
            ? new Color(
                intval(round(array_sum($rA) / $count)),
                intval(round(array_sum($gA) / $count)),
                intval(round(array_sum($bA) / $count)),
                intval(round(array_sum($aA) / $count))
            )
            : new Color()
        ;
    }

}