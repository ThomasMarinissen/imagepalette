<?php
namespace \Th\Color;

/**
 * Class XYZ
 *
 * @author Thomas Marinissen
 */
class XYZ extends \Th\Color {

    /**
     * The x value
     *
     * @var float
     */
    private $x;

    /**
     * The y value
     *
     * @var float
     */
    private $y;

    /**
     * The z value
     *
     * @var float
     */
    private $z;

    /**
     * XYZ constructor.
     *
     * @param float
     * @param float
     * @param float
     */
    public function __construct($x, $y, $z) {
        // set the XYZ values
        $this->r = $x;
        $this->g = $y;
        $this->b = $z;
    }

    /**
     * Static method to create an XYZ Color object from a hex color code
     *
     * @param   string                      The hex color code
     * @return  \Th\Color\XYZ               The XYZ color object
     */
    public static function fromHex($color) {
        return \Th\Color\RGB::fromHex($color)->toXYZ();
    }

    /**
     * Get the x value
     *
     * @return mixed
     */
    public function x() {
        return $this->x;
    }

    /**
     * Get the y value
     *
     * @return mixed
     */
    public function y() {
        return $this->y;
    }

    /**
     * Get the z value
     *
     * @return mixed
     */
    public function z() {
        return $this->z;
    }

    /**
     * Convert the XYZ to a to Lab color
     *
     * @return  \Th\Color\LAB
     */
    public function toLab() {
        // get the modified x, y and z value
        $x = $this->x() / 95.047;
        $y = $this->y() / 100;
        $z = $this->z() / 108.883;

        // apply the epsilon and kappa values to the x, y and z
        $epsilon = 216/24389;
        $kappa = 24389/27;
        $x = $x > $epsilon ? pow($x, 1/3) : ($kappa * $x + 16) / 116;
        $y = $y > $epsilon ? pow($y, 1/3) : ($kappa * $y + 16) / 116;
        $z = $z > $epsilon ? pow($z, 1/3) : ($kappa * $z + 16) / 116;

        // set the LAB color values
        $l = max(0, 116 * $y - 16);
        $a = 500 * ($x - $y);
        $b = 200 * ($y - $z);

        // create the color and return it
        return new \Th\Color\LAB($l, $a, $b);
    }
}