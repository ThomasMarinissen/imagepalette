<?php
namespace \Th\Color;

/**
 * Class LAB
 *
 * @author Thomas Marinissen
 */
class LAB extends \Th\Color {

    /**
     * The l value
     *
     * @var float
     */
    private $l;

    /**
     * The a value
     *
     * @var float
     */
    private $a;

    /**
     * The b value
     *
     * @var float
     */
    private $b;

    /**
     * LAB constructor.
     *
     * @param float
     * @param float
     * @param float
     */
    public function __construct($l, $a, $b) {
        // set the rgb values
        $this->l = $l;
        $this->a = $a;
        $this->b = $b;
    }

    /**
     * Static method to create an XYZ Color object from a hex color code
     *
     * @param   string                      The hex color code
     * @return  \Th\Color\LAB               The LAB color object
     */
    public static function fromHex($color) {
        return \Th\Color\RGB::fromHex($color)->toLAB();
    }

    /**
     * Get the l value
     *
     * @return mixed
     */
    public function l() {
        return $this->l;
    }

    /**
     * Get the a value
     *
     * @return mixed
     */
    public function a() {
        return $this->a;
    }

    /**
     * Get the b value
     *
     * @return mixed
     */
    public function b() {
        return $this->b;
    }

}