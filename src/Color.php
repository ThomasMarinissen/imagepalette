<?php
namespace Th;

/**
 * Abstract base color class
 *
 * @author Thomas Marinissen
 */
abstract class Color {

    /**
     * Get the difference between this color and a given color using the CIEDE2000 algorithm
     * Information about the CIEDE2000 algorithm and the steps implemented to calculate the difference between
     * the 2 colors can be found at http://www.ece.rochester.edu/~gsharma/ciede2000/ciede2000noteCRNA.pdf
     *
     * @param   \Th\Color           The color to calculate the difference for compared to the current color
     * @return  float               The difference between this color and the given color
     */
    public function difference(\Th\Color $color) {
        // if this color is not an instance of the lab color, convert it to a lab color
        $thisColor = $this;
        if (!$this instanceof \Th\Color\Lab) {
            $thisColor = $this->toLab();
        }

        // if the given color is not a lab color yet, convert it
        if (!$color instanceof \Th\Color\Lab) {
            $color = $color->toLab();
        }

        // get the Lab values for this color
        $L1 = $thisColor->l();
        $a1 = $thisColor->a();
        $b1 = $thisColor->b();

        // get Lab values for the given color
        $L2 = $color->l();
        $a2 = $color->a();
        $b2 = $color->b();

        // weight factors
        $kL = $kC = $kH = 1;

        // 2
        $C1 = sqrt(pow($a1, 2) + pow($b1, 2));
        $C2 = sqrt(pow($a2, 2) + pow($b2, 2));

        // 3
        $aC1C2 = ($C1 + $C2) / 2;

        // 4
        $G = 0.5 * (1 - sqrt(pow($aC1C2 , 7) / (pow($aC1C2, 7) + pow(25, 7))));

        // 5
        $a1p = (1 + $G) * $a1;
        $a2p = (1 + $G) * $a2;

        // 6
        $C1p = sqrt(pow($a1p, 2) + pow($b1, 2));
        $C2p = sqrt(pow($a2p, 2) + pow($b2, 2));

        // 7
        $h1p = $this->hp($b1, $a1p);
        $h2p = $this->hp($b2, $a2p);

        // 8
        $dLp = $L2 - $L1;

        // 9
        $dCp = $C2p - $C1p;

        // 10
        if($C1 * $C2 == 0)  {
            $dhp = 0;
        } elseif (abs($h2p - $h1p) <= 180) {
            $dhp = $h2p - $h1p;
        } elseif (($h2p-$h1p) > 180) {
            $dhp = $h2p - $h1p - 360;
        } elseif (($h2p - $h1p) < -180) {
            $dhp = $h2p - $h1p + 360;
        } else {
            throw new Exception('Invalid delta');
        }

        // 11
        $dHp = 2 * sqrt($C1p * $C2p) * sin($this->radians($dhp) / 2);

        // 12
        $aL = ($L1 + $L2) / 2;

        // 13
        $aCp = ($C1p + $C2p) / 2;

        // 14
        if ($C1 * $C2 == 0) {
            $aHp = $h1p + $h2p;
        }
        elseif (abs($h1p - $h2p)<= 180) {
            $aHp = ($h1p + $h2p) / 2;
        }
        elseif ((abs($h1p - $h2p) > 180) && (($h1p + $h2p) < 360)) {
            $aHp = ($h1p + $h2p + 360) / 2;
        }
        elseif ((abs($h1p - $h2p) > 180) && (($h1p + $h2p) >= 360)) {
            $aHp = ($h1p + $h2p - 360) / 2;
        }
        else {
            throw new Exception('Invalid bar');
        }

        // 15
        $T = 1 - 0.17 * cos($this->radians($aHp - 30))
            + 0.24 * cos($this->radians(2 * $aHp))
            + 0.32 * cos($this->radians(3 * $aHp + 6))
            - 0.20 * cos($this->radians(4 * $aHp - 63));

        // 16
        $dRo = 30 * exp(-(pow(($aHp - 275) / 25, 2)));

        // 17
        $RC = sqrt((pow($aCp, 7)) / (pow($aCp, 7) + pow(25, 7)));

        // 18
        $SL = 1 + ((0.015 * pow($aL - 50, 2)) / sqrt(20 + pow($aL - 50, 2)));

        // 19
        $SC = 1 + 0.045 * $aCp;

        // 20
        $SH = 1 + 0.015 * $aCp * $T;

        // 21
        $RT = -2 * $RC * sin($this->radians(2 * $dRo));

        // 22 => done, return the difference between this color and the given color
        return sqrt(
            pow($dLp / ($SL * $kL), 2)
            + pow($dCp / ($SC * $kC), 2)
            + pow($dHp / ($SH * $kH), 2)
            + $RT * ($dCp / ($SC * $kC)) * ($dHp / ($SH * $kH))
        );
    }

    /**
     * Calculate the modified hue
     *
     * @param   int|float
     * @param   int|float
     * @return  int|float
     */
    private function hp($b, $ap) {
        if ($b == 0 && $ap == 0) {
            return 0;
        }

        // calculate the modified hue
        $hp = atan2($b, $ap) * 180 / pi();

        // if the modified hue is 0 or above, return it
        if ($hp >= 0) {
            return $hp;
        }

        // done, return the modified hue
        return $hp + 360;
    }

    /**
     * Helper function to calculate the degrees radians
     *
     * @param   int|float               The number to calculate the radians for
     * @return  int|float               The calculated radians
     */
    private function radians($number) {
        return $number * pi() / 180;
    }

}