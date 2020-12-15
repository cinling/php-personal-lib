<?php


namespace cin\personalLib\vos;

use cin\personalLib\interfaces\Arrayable;
use cin\personalLib\interfaces\Errorable;
use cin\personalLib\interfaces\Verifiable;
use cin\personalLib\traits\ErrorTrait;
use cin\personalLib\traits\LabelTrait;
use cin\personalLib\utils\ArrayUtil;
use cin\personalLib\utils\ExcelUtil;
use cin\personalLib\utils\JsonUtil;
use cin\personalLib\utils\StringUtil;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Class BaseVo 基础类型的vo数据
 * @package cin\personalLib\vos
 */
class BaseVo implements Arrayable, Verifiable, Errorable
{
    use ErrorTrait;
    use LabelTrait;

    /**
     * @var array 配置
     */
    protected $__config = [];

    /**
     * 初始化列表 或 字典
     * @param mixed[] $rows
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
     * @param $json
     * @return static[]
     */
    public static function initListByJson($json) {
        $rows = JsonUtil::decode($json);
        return static::initList($rows);
    }

    /**
     * 导出为excel数据
     * @param string $excelFilename 导出文件名（不包含后缀）
     * @param static[] $vos 导出数据
     * @param string $sheetTitle 表格标签的名字（只有一个标签）
     * @param bool $autoSetWidth 自动设置宽度
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public static function export($excelFilename, $vos = [], $sheetTitle = "sheet1", $autoSetWidth = true) {
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
            $sheet->setTitle($sheetTitle);
            foreach ($columnsLenArr as $columnIndex => $len) {
                $letter = ExcelUtil::numToLetter($columnIndex + 1);
                $sheet->getColumnDimension($letter)->setWidth($len);
            }

        }
        $writer = new Xlsx($spreadsheet);
        Header("Content-type:  application/octet-stream ");
        Header("Accept-Ranges:  bytes ");
        Header("Content-Disposition:  attachment;  filename=\"" . $excelFilename . ".xlsx\"");
        header('Cache-Control: max-age=0');//禁止缓存
        $writer->save('php://output');
        exit();
    }

    /**
     * 到處為 excel 數據。可以帶圖片
     * TODO 未完成的方法
     * @deprecated 咱不可用
     * @param string $excelFilename excel 文件名（導出文件的名字）
     * @param static[] $vos
     * @param string $sheetTitle 默認工作表名字
     * @param array $imageFields
     * @param int $imageWidth 圖片寬度和高度。所有圖片都相同
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public static function exportWithImage($excelFilename, $vos = [], $sheetTitle = "sheet1", $imageFields = [], $imageWidth = 80) {
        // 增大可用内存
        ini_set('memory_limit', '1024M');

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
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

//        // 写入表头
//        $colIndex = 1;
//        foreach ($labels as $label) {
//            $letter = ExcelUtil::numToLetter($colIndex);
//            $sheet->getCell($letter . "1")->setValue($label);
//            $colIndex++;
//        }

//        // 写入数值
//        $rowIndex = 2;
//        foreach ($vos as $vo) {
//            $colIndex = 1;
//            foreach ($labels as $prop => $value) {
//                if (!$vo->hasProp($prop)) {
//                    continue;
//                }
//                $letter = ExcelUtil::numToLetter($colIndex);
//                $sheet->getCell($letter . $rowIndex)->setValue($value);
//
//                $colIndex++;
//            }
//            $rowIndex++;
//        }
        $writer = new Xlsx($spreadsheet);
        Header("Content-type:  application/octet-stream ");
        Header("Accept-Ranges:  bytes ");
        Header("Content-Disposition:  attachment;  filename=\"" . $excelFilename . ".xlsx\"");
        header('Cache-Control: max-age=0');//禁止缓存
        $writer->save('php://output');
        exit();
    }

    /**
     * @param $row
     * @return static
     */
    public static function init($row) {
        $vo = new static();
        $vo->setAttrs($row);
        return $vo;
    }

    /**
     * @param $json
     * @return static
     */
    public static function initByJson($json) {
        $row = JsonUtil::decode($json);
        return static::init($row);
    }

    /**
     * BaseVo constructor.
     * @param array $config 预留构造函数。防止被使用
     */
    public function __construct($config = []) {
        $this->__config = $config;
        $this->onInit();
    }

    /**
     * 初始化数据
     */
    protected function onInit() {

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
    public function toArray() {
        $attrs = get_object_vars($this);

        // 排除 __ 开头的变量
        foreach ($attrs as $prop => $value) {
            if (StringUtil::startWidth($prop, "__")) {
                unset($attrs[$prop]);
            }
        }
        return ArrayUtil::toArray($attrs);
    }

    /**
     * 判断对象中有无该属性
     * @param $prop
     * @return bool
     */
    public function hasProp($prop) {
        return property_exists($this, $prop);
    }

    /**
     * 验证是否合法
     * @return bool
     */
    public function valid() {
        $rules = $this->rules();
        foreach ($rules as $ruleVo) {
            foreach ($ruleVo->props as $prop) {
                $label = $this->label($prop);

                if (!property_exists($this, $prop)) {
                    $this->addError("不存在的字段名：" . $label);
                    continue;
                }
                foreach ($ruleVo->handles as $handle) {
                    $handle($this, $prop, $label, $this->$prop);
                }
            }
        }
        return !$this->hasError();
    }

    /**
     * @return RuleVo[]
     */
    public function rules() {
        return [];
    }
}
