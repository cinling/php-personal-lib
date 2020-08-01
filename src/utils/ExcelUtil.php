<?php


namespace cin\personalLib\utils;


use PhpOffice\PhpSpreadsheet\Style\Color;

class ExcelUtil
{
    /**
     * 将数字转为excel中的字母。
     * 转换结果如下：
     *      1 => A
     *      26 => Z
     *      27 => AA
     * @param $num
     * @return string
     */
    public static function numToLetter($num) {
        if ($num === 0) {
            return "";
        }
        $num--;
        $letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        $index = (int)floor($num % 26);
        $result = $letters[$index];
        $result = self::numToLetter(intval($num / 26)) . $result;

        return $result;
    }

    /**
     * 将excel中的字母转为数字。
     * @param string $letter excel表列字母
     * @return int
     */
    public static function letterToNum($letter) {
        if (empty($letter)) {
            return 0;
        }
        $letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $len = strlen($letter);

        $num = 0;
        for ($i = 0; $i < $len; $i++) {
            $tmpLetter = $letter[$len - $i - 1];
            $bit = strpos($letters, $tmpLetter) + 1;
            $num += $bit * pow(26, $i);
        }
        return $num;
    }

    /**
     * 可以使用颜色常量初始化一个 excel 对象所需的 颜色对象
     * @param string $color 如："#FF00E3"、"FF00FF"
     * @return Color
     */
    public static function getColorObject($color) {
        if (StringUtil::startWidth($color, "#")) {
            $color = str_replace("#", "", $color);
        }

        $color = "FF" . $color;

        return new Color($color);
    }
}
