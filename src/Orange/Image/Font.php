<?php

namespace Orange\Image;

/**
 * Class Font
 * @package Orange\Image
 * @author Mikhail Kelner
 */
class Font {

    /**
     * @var string
     */
    private $fontfile;
    /**
     * @var Color
     */
    private $color;
    /**
     * @var string
     */
    private $size;

    /**
     * @param string $fontfile
     * @param Color $color
     * @param string $size
     */
    public function __construct($fontfile, $color, $size){
        $this->fontfile = $fontfile;
        $this->color = $color;
        $this->size = $size;
	}

    /**
     * @return string
     */
    public function getFontfile(){
		return $this->fontfile;
	}

    /**
     * @return Color
     */
    public function getColor(){
		return $this->color;
	}

    /**
     * @return string
     */
    public function getSize(){
		return $this->size;
	}
	
}