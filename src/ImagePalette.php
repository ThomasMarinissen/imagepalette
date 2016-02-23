<?php

// the class namespace
namespace Th;

class ImagePalette {

    /**
     * The image properties
     *
     * @var array|null
     */
    private $imageProperties = null;

    /**
     * ImagePalette constructor.
     *
     * @param   string          The path to the image
     */
    public function __construct($imagePath) {
        // store the image path
        $this->setFile($imagePath);
    }

    /**
     * Get the image file path
     *
     * @return string
     */
    public function file() {
        return $this->file;
    }

    /**
     * Get the image as instance of the Imagick class
     *
     * @return \Imagick             The image
     */
    public function image() {
        return $this->image;
    }

    /**
     * Get the image width
     *
     * @return int          The image width
     */
    public function width() {
        // get the image properties
        $imageProperties = $this->imageProperties();

        // return the width
        return $imageProperties[0];
    }

    /**
     * Get the image height
     *
     * @return int          The image height
     */
    public function height() {
        // get the image properties
        $imageProperties = $this->imageProperties();

        // return the height
        return $imageProperties[1];
    }

    public function colorMap() {
        // get the image
        $image = $this->image();

        // get the image height
        $height = $this->height();

        // get the image width
        $width = $this->width();

        // iterate over every pixel in the image and get the color for every pixel
        $colors = [];
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                // get the color of the pixel
                $color = $image->getImagePixelColor($x,$y);

                // get the rgb value for the color
                $rgb = $color->getColor();

                // get the hex value for the color
                $hex = '#' . dechex($rgb['r']) . dechex($rgb['g']) . dechex($rgb['b']);

                // if the color is not set in the colors array, add the counter for it
                if (!isset($colors[$hex])) {
                    $colors[$hex] = 0;
                }

                // add 1 to the color count
                $colors[$hex]++;
            }

            var_dump($colors); die;
        }

        // get the image colors
        $colors = $image->getImageColors();



        var_dump($colors);
        die;
    }

    /**
     * Set the image file
     *
     * @param   string                                      The image file to set
     * @return  \Th\ImagePalette                            The instance of this, to make chaining possible
     *
     * @throws \Exception                                   Thrown if the image is not a valid image, or if the image is not supported
     */
    private function setFile($file) {
        // set the initial file
        $this->file = $file;

        // reset the image properties
        $this->imageProperties = null;

        // check if the file is a valid image
        $this->validImage();

        // set the image
        $this->setImage();

        // return the instance of this to make chaining possible
        return $this;
    }

    /**
     * Set the image
     *
     * @return  \Th\ImagePalette                         The instance of this, to make chaining possible
     */
    private function setImage() {
        // set the image
        $this->image = new \Imagick($this->file());

        // return the instance of this to make chaining possible
        return $this;
    }

    /**
     * Function to check if the image is valid
     *
     * @return boolean                  Whether an image is a valid image
     *
     * @throws \Exception               Thrown if the image is not a valid image, or if the image is not supported
     */
    private function validImage() {
        // get the image properties
        $imageProperties = $this->imageProperties();

        // get the image type
        $imageType = $imageProperties[2];

        // if the image type is not one of the supported image types, throw a
        // new exception
        if (!in_array($imageType , array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
            throw new \Exception('Image type is not supprted');
        }

        // done, return whether the file is an image and of the supported type
        return in_array($imageType , array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG));
    }

    /**
     * Function to get the image properties
     *
     * @return  array           The image properties as an array
     *
     * @throws  \Exception      Thrown whenever the image is not a valid image
     */
    private function imageProperties() {
        // if the image properties have been set before, return them
        if (!is_null($this->imageProperties)) {
            return $this->imageProperties;
        }

        // get the image properties
        $imageProperties = getimagesize($this->file());

        // if there are no image properties, throw a new exception
        if ($imageProperties === false) {
            throw new \Exception('File is not a valid image');
        }

        // done, set and return the image properties
        return $this->imageProperties = $imageProperties;
    }

}

$image = new ImagePalette('http://o.aolcdn.com/smp/is/wp-content/submissions/uploads/emilia@stylemepretty.com/23692/christian_oth_studio_110813bacmic0195$!x900.jpg');
$image->colorMap();

