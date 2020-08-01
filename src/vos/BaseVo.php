<?php


namespace cin\personalLib\vos;

use cin\personalLib\interfaces\ArrayAbleInterface;
use cin\personalLib\utils\ArrayUtil;
use cin\personalLib\utils\ExcelUtil;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Class BaseVo 基础类型的vo数据
 * @package cin\personalLib\vos
 */
class BaseVo implements ArrayAbleInterface
{
    /**
     * 初始化列表 或 字典
     * @param array[] $rows
     * @return static[]
     */
    public static function initList($rows) {
        $voList = [];
        foreach ($rows as $key => $row) {
            $vo = new static();
            $vo->setAttrs($row);
            $voList[$key] = $vo;
        }
        return $voList;
    }

    /**
     * 导出为excel数据
     * @param string $filename 导出文件名（不包含后缀）
     * @param static[] $vos 导出数据
     * @param string $title 表格标签的名字（只有一个标签）
     * @param bool $autoSetWidth 自动设置宽度
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public static function export($filename, $vos = [], $title = "sheet1", $autoSetWidth = true) {
        if (count($vos) === 0) {
            $vos[] = new static();
        }
        $labels = [];
        foreach ($vos as $vo) {
            $arr = $vo->toArray();
            foreach ($arr as $key => $value) { // 将所有属性转为label。如果没有配置属性的翻译，则使用key作为字段
                $labels[$key] = $key;
            }
            $tmpLabels = $vo->labels();
            foreach ($tmpLabels as $key => $label) {
                $labels[$key] = $label;
            }
            // 排除不需要导出的字段
            $excludeProp = $vo->excludeExportProps();
            foreach ($excludeProp as $prop) {
                unset($labels[$prop]);
            }
            break;
        }
        $formArr = [array_values($labels)];
        foreach ($vos as $vo) {
            $row = [];
            $arr = $vo->toArray();
            unset($vo);
            foreach ($labels as $key => $value) {
                if (strlen(strval($arr[$key])) !== 0) {
                    $row[] = (string)$arr[$key];
                } else {
                    $row[] = "";
                }
            }
            $formArr[] = $row;
        }

        // 增大可用内存
        ini_set('memory_limit', '1024M');

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->fromArray($formArr);
        if ($autoSetWidth) {
            // 列宽数组
            $columnsLenArr = [];
            foreach ($formArr as $rows) {
                foreach ($rows as $columnIndex => $value) {
                    if (!isset($columnsLenArr[$columnIndex])) {
                        $columnsLenArr[$columnIndex] = 1;
                    }
                    $len = strlen($value);
                    $columnsLenArr[$columnIndex] = $len > $columnsLenArr[$columnIndex] ? $len : $columnsLenArr[$columnIndex];
                }
            }
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle($title);
            foreach ($columnsLenArr as $columnIndex => $len) {
                $letter = ExcelUtil::numToLetter($columnIndex + 1);
                $sheet->getColumnDimension($letter)->setWidth($len);
            }

        }
        $writer = new Xlsx($spreadsheet);
        Header("Content-type:  application/octet-stream ");
        Header("Accept-Ranges:  bytes ");
        Header("Content-Disposition:  attachment;  filename=\"" . $filename . ".xlsx\"");
        header('Cache-Control: max-age=0');//禁止缓存
        $writer->save('php://output');
        exit();
    }

    /**
     * BaseVo constructor.
     * @param array $config 预留构造函数。防止被使用
     */
    public function __construct($config = [])
    {
    }

    /**
     * 字段对应的标签
     * @return array
     */
    public function labels() {
        return [];
    }

    /**
     * 获取属性对应的标签名字
     * @param string $prop
     * @return string
     */
    public function label($prop) {
        $labels = $this->labels();
        return isset($labels[$prop]) ? $labels[$prop] : $prop;
    }

    /**
     * 排除的导出属性
     * @return string[]
     */
    public function excludeExportProps() {
        return [];
    }

    /**
     * 使用关系数组初始化对象数据
     * @param array $attrs
     */
    public function setAttrs($attrs) {
        foreach ($attrs as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * 转换为数组
     * @return array
     */
    public function toArray()
    {
        return ArrayUtil::toArray($this);
    }
}
