<?php

namespace Orange\Image;

/**
 * Class Color
 * @package Orange\Image
 * @author Mikhail Kelner
 */
class Color {

    /**
     * @var array
     */
    protected $color = array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0);

    /**
     * @param int $r
     * @param int $g
     * @param int $b
     * @param int $a
     */
    public function __construct($r = 255, $g = 255, $b = 255, $a = 0){
        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
        $this->a = $a;
    }

    /**
     * @param $var
     * @param $val
     * @throws \Exception
     */
    public function __set($var,$val){
        if (!array_key_exists($var,$this->color)){
            throw new \Exception('Incorrect color parameter '.$var);
        }
        if (!is_int($val)){
            throw new \Exception('Incorrect type of parameter '.$var.': '.$val.' ('.gettype($val).')');
        }
        $max_value = ($var == 'a') ? 127 : 255;
        if (!(($val >= 0) && ($val <= $max_value))){
            throw new \Exception('Incorrect value of parameter '.$var.': '.$val.' (allowed values is between 0 and '.$max_value.')');
        }
        $this->color[$var] = $val;
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name){
        if (!array_key_exists($name,$this->color)){
            throw new \Exception('Incorrect color parameter '.$name);
        }
        return $this->color[$name];
    }

    /**
     * @param $src
     * @param bool|false $alpha
     * @return int
     */
    public function getColorResource($src, $alpha = false){
        return $alpha
            ? imagecolorallocatealpha($src, $this->r, $this->g, $this->b, $this->a)
            : imagecolorallocate($src, $this->r, $this->g, $this->b)
        ;
    }

    /**
     * @return string
     */
    public function __toString(){
        return 'rgba('.$this->r.','.$this->g.','.$this->b.','.( abs($this->a - 127) / 127 ).')';
    }



}