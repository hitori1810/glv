<?php

    class Integer {

        public function toText($amt) {
            if (is_numeric($amt)) {
                $sign = $amt >= 0 ? '' : 'Negative ';
                $rs = $sign . $this->toQuadrillions(abs($amt)) . " đồng chẵn.";
                $rs = str_replace('  ',' ',$rs);
                $rs = ucfirst(mb_strtolower($rs,'UTF-8'));
                $rs = str_replace('mươi một','mươi mốt',$rs);
                return $rs;
            } else {
                throw new Exception('Only numeric values are allowed.');
            }
        }

        private function toOnes($amt) {
            $words = array(
                0 => 'Không',
                1 => 'Một',
                2 => 'Hai',
                3 => 'Ba',
                4 => 'Bốn',
                5 => 'Năm',
                6 => 'Sáu',
                7 => 'Bảy',
                8 => 'Tám',
                9 => 'Chín'
            );

            if ($amt >= 0 && $amt < 10)
                return $words[$amt];
            else
                throw new ArrayIndexOutOfBoundsException('Array Index not defined');
        }

        private function toTens($amt) { // handles 10 - 99
            $firstDigit = intval($amt / 10);
            $remainder = $amt % 10;

            if ($firstDigit == 1) {
                $words = array(
                    0 => 'Mười',
                    1 => 'Mười Một',
                    2 => 'Mười Hai',
                    3 => 'Mười Ba',
                    4 => 'Mười Bốn',
                    5 => 'Mười Lăm',
                    6 => 'Mười Sáu',
                    7 => 'Mười Bảy',
                    8 => 'Mười Tám',
                    9 => 'Mười Chín'
                );

                return $words[$remainder];
            } else if ($firstDigit >= 2 && $firstDigit <= 9) {
                $words = array(
                    2 => 'Hai Mươi',
                    3 => 'Ba Mươi',
                    4 => 'Bốn Mươi',
                    5 => 'Năm Mươi',
                    6 => 'Sáu Mươi',
                    7 => 'Bảy Mươi',
                    8 => 'Tám Mươi',
                    9 => 'Chín Mươi'
                );

                $rest = $remainder == 0 ? '' : $this->toOnes($remainder);
                if($remainder == 5) $rest = 'lăm';
                return $words[$firstDigit] . ' ' . $rest;
            }
            else
                return $this->toOnes($amt);
        }

        private function toHundreds($amt) {
            $ones = intval($amt / 100);
            $remainder = $amt % 100;

            if ($ones >= 1 && $ones < 10) {
                $rest = $remainder == 0 ? '' : $this->toTens($remainder);
                return $this->toOnes($ones) . ' Trăm ' . $rest;
            }
            else
                return $this->toTens($amt);
        }

        private function toThousands($amt) {
            $hundreds = intval($amt / 1000);
            $remainder = $amt % 1000;

            if ($hundreds >= 1 && $hundreds < 1000) {
                $rest = $remainder == 0 ? '' : $this->toHundreds($remainder);
                return $this->toHundreds($hundreds) . ' Nghìn ' . $rest;
            }
            else
                return $this->toHundreds($amt);
        }

        private function toMillions($amt) {
            $hundreds = intval($amt / pow(1000, 2));
            $remainder = $amt % pow(1000, 2);

            if ($hundreds >= 1 && $hundreds < 1000) {
                $rest = $remainder == 0 ? '' : $this->toThousands($remainder);
                return $this->toHundreds($hundreds) . ' Triệu ' . $rest;
            }
            else
                return $this->toThousands($amt);
        }

        private function toBillions($amt) {
            $hundreds = intval($amt / pow(1000, 3));
            /* Note:taking the modulos results in a negative value, but
            this seems to work pretty fine */

            $remainder = $amt - $hundreds * pow(1000, 3);

            if ($hundreds >= 1 && $hundreds < 1000) {
                $rest = $remainder == 0 ? '' : $this->toMillions($remainder);
                return $this->toHundreds($hundreds) . ' Tỷ ' . $rest;
            }
            else
                return $this->toMillions($amt);
        }

        private function toTrillions($amt) {
            $hundreds = intval($amt / pow(1000, 4));
            $remainder = $amt - $hundreds * pow(1000, 4);

            if ($hundreds >= 1 && $hundreds < 1000) {
                $rest = $remainder == 0 ? '' : $this->toBillions($remainder);
                return $this->toHundreds($hundreds) . ' Nghìn Tỷ ' . $rest;
            }
            else
                return $this->toBillions($amt);
        }

        private function toQuadrillions($amt) {
            $hundreds = intval($amt / pow(1000, 5));
            $remainder = $amt - $hundreds * pow(1000, 5);

            if ($hundreds >= 1 && $hundreds < 1000) {
                $rest = $remainder == 0 ? '' : $this->toTrillions($remainder);
                return $this->toHundreds($hundreds) . ' Nghìn Triệu Triệu ' . $rest;
            }
            else
                return $this->toTrillions($amt);
        }

    }
?>