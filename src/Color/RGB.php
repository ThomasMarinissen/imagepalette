<?php
namespace \Th\Color;

/**
 * Class RGB
 *
 * @author Thomas Marinissen
 */
class RGB extends \Th\Color {

    /**
     * The r value
     *
     * @var float
     */
    private $r;

    /**
     * The g value
     *
     * @var float
     */
    private $g;

    /**
     * The b value
     *
     * @var float
     */
    private $b;

    /**
     * RGB constructor.
     *
     * @param float
     * @param float
     * @param float
     */
    public function __construct($r, $g, $b) {
        // set the rgb values
        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
    }

    /**
     * Static method to create an RGB Color object from a hex color code
     *
     * @param   string                      The hex color code
     * @return  \Th\Color\RGB               The RGB color object
     */
    public static function fromHex($color) {
        // remove a possible # from the hexadecimal color code
        $color = str_replace('#', '', $color);

        // if the code is only 3 character of length, convert it to a 6 letters code
        if(strlen($color) == 3) {
            $color = substr($color, 0, 1) . substr($color, 0, 1) . substr($color, 1, 1) . substr($color, 1, 1) . substr($color, 2, 1) . substr($color, 2, 1);
        }

        // set the rgb values
        $r = (int) hexdec(substr($color, 0, 2));
        $g = (int) hexdec(substr($color, 2, 2));
        $b = (int) hexdec(substr($color, 4, 2));

        // done, return the color object
        return new self($r, $g, $b);
    }

    /**
     * Get the r value
     *
     * @return mixed
     */
    public function r() {
        return $this->r;
    }

    /**
     * Get the g value
     *
     * @return mixed
     */
    public function g() {
        return $this->g;
    }

    /**
     * Get the b value
     *
     * @return mixed
     */
    public function b() {
        return $this->b;
    }

    /**
     * Convert the RGB to a to LAB color
     *
     * @return  \Th\Color\LAB
     */
    public function toLab() {
        // return the LAB color
        return $this->toXyz()->toLAB();
    }

    /**
     * Convert the RGB color value into an XYZ color value
     *
     * @return \Th\Color\XYZ
     */
    public function toXYZ() {
        // get the rgb value
        $point = [$this->r, $this->g, $this->b];

        //rgb to xyz
        foreach($point as &$p){
            $p = $p/255;
            $p = ($p > 0.04045 ? pow(($p + 0.055) / 1.055, 2.4) : $p / 12.92) * 100;
        }

        // get the modified values for the r, g and b
        $red = $point[0];
        $green = $point[1];
        $blue = $point[2];

        // calculate the x, y and z value
        $x = $red * 0.4124 + $green * 0.3576 + $blue * 0.1805;
        $y = $red * 0.2126 + $green * 0.7152 + $blue * 0.0722;
        $z = $red * 0.0193 + $green * 0.1192 + $blue * 0.9505;

        // create the color and return it
        return new \Th\Color\XYZ($x, $y, $z);
    }
}