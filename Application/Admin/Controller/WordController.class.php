<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description
 * @Author
 * @Date           2018/8/30
 * @CreateBy       PhpStorm
 */
namespace Admin\Controller;

/**
 * 单词词库控制器
 */
class WordController extends CommonController {
    public function listWord() {
        $selected_type = I('selected_type');
        if ($selected_type) {
            $where['words_type'] = $selected_type;
        }
        $keyword = I('keyword');
        if ($keyword) {
            $where['words'] = ['like', "%$keyword%"];
        }
        $data = D('Admin/Word')->getWordList($where);

        $this->assign('list', $data['list']);
        $this->assign('page', $data['page']);
        $this->display();
    }

    public function import() {
        if (IS_GET) {
            $this->display();
        } else {
            $excel_file = I('excel_file');
            if (empty($excel_file)) {
                $this->ajaxReturn(V(0, '请选择文件'));
            }
            $ext = substr(strrchr($excel_file, '.'), 1);
            if ($ext != 'xls' && $ext != 'xlsx') {
                $this->ajaxReturn(V(0, '上传的文件格式不正确'));
            }
            if (!file_exists('.' . $excel_file)) {
                $this->ajaxReturn(V(0, '文件不存在', $excel_file));
            }
            $array = $this->importExcel('.' . $excel_file);
            $content = $array['data'][0]['Content'];
            if (!$content) {
                $this->ajaxReturn(V(0, '请在Excel中填写需要上传的数据'));
            }
            $content = array_values($content);
            unset($content[0]);
            $content = array_values($content);

            $num = 0; //用户上传数量
            $alldata = array();
            $WordModel = D('Word');
            foreach ($content as $k => $v) {
                $data = array();
                $data['words'] = $v[1];
                $data['phonetic_alphabet'] = $v[2];
                $data['syllable'] = $v[3];
                $data['pronunciation'] = $v[4];
                $data['chinese'] = $v[5];

                if ($WordModel->where(['words' => $data['words']])->count()) {
                    continue;
                }

                $alldata[] = $data;
                $num++;
            }
            M()->startTrans();
            foreach ($alldata as $key => $value) {
                $result = $WordModel->add($value);
                if ($result <= 0) {
                    M()->rollback(); // 事务回滚
                    $this->ajaxReturn(V(0, '写入数据库失败'));
                }
            }
            M()->commit();
            $this->ajaxReturn(V(1, '导入成功'));
        }
    }

    public function editWord() {
        $id = I('id');
        $wordModel = D('Admin/Word');

        if (IS_POST) {
            if ($wordModel->create() === false) {
                $this->ajaxReturn(V(0, $wordModel->getError()));
            }
            if ($id) {
                if ($wordModel->save() !== false) {
                    $this->ajaxReturn(V(1, '编辑成功'));
                }
            } else {
                if ($wordModel->add() !== false) {
                    $this->ajaxReturn(V(1, '添加成功'));
                }
            }
            $this->ajaxReturn(V(0, $wordModel->getDbError()));
        }

        $info = $wordModel->find($id);

        $this->assign('info', $info);
        $this->display();
    }

    public function del() {
        $this->_del('Word', 'id');
    }

    //导入EXCEL
    private function importExcel($file) {
        if (!file_exists($file)) {
            return array("error" => 0, 'message' => 'file not found!');
        }
        Vendor("PHPExcel.PHPExcel.IOFactory");
        ini_set('max_execution_time', '0');
        try {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if ($extension == 'xlsx') {
                $objReader = new \PHPExcel_Reader_Excel2007();
                $PHPReader = $objReader->load($file);
            } else if ($extension == 'xls') {
                $objReader = new \PHPExcel_Reader_Excel5();
                $PHPReader = $objReader->load($file);
            }
        } catch (Exception $e) {}
        if (!isset($PHPReader)) {
            return array("error" => 0, 'message' => 'read error!');
        }

        $allWorksheets = $PHPReader->getAllSheets();
        $i = 0;
        foreach ($allWorksheets as $objWorksheet) {
            $sheetname = $objWorksheet->getTitle();
            $allRow = $objWorksheet->getHighestRow(); //how many rows
            $highestColumn = $objWorksheet->getHighestColumn(); //how many columns
            $allColumn = \PHPExcel_Cell::columnIndexFromString($highestColumn);
            // $array[$i]["Title"] = $sheetname;
            // $array[$i]["Cols"] = $allColumn;
            // $array[$i]["Rows"] = $allRow;
            $arr = array();
            $isMergeCell = array();
            foreach ($objWorksheet->getMergeCells() as $cells) {
//merge cells
                foreach (\PHPExcel_Cell::extractAllCellReferencesInRange($cells) as $cellReference) {
                    $isMergeCell[$cellReference] = true;
                }
            }
            for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
                $row = array();
                for ($currentColumn = 0; $currentColumn < $allColumn; $currentColumn++) {
                    ;
                    $cell = $objWorksheet->getCellByColumnAndRow($currentColumn, $currentRow);
                    $afCol = \PHPExcel_Cell::stringFromColumnIndex($currentColumn + 1);
                    $bfCol = \PHPExcel_Cell::stringFromColumnIndex($currentColumn - 1);
                    $col = \PHPExcel_Cell::stringFromColumnIndex($currentColumn);
                    $address = $col . $currentRow;
                    $value = $objWorksheet->getCell($address)->getValue();
                    if (is_object($value)) {
                        $value = $value->__toString();
                    }

                    if (substr($value, 0, 1) == '=') {
                        return array("error" => 0, 'message' => 'can not use the formula!');
                        exit;
                    }
                    if ($cell->getDataType() == \PHPExcel_Cell_DataType::TYPE_NUMERIC) {
                        $cellstyleformat = $cell->getParent()->getStyle($cell->getCoordinate())->getNumberFormat();
                        $formatcode = $cellstyleformat->getFormatCode();
                        if (preg_match('/^([$[A-Z]*-[0-9A-F]*])*[hmsdy]/i', $formatcode)) {
                            $value = gmdate("Y-m-d", \PHPExcel_Shared_Date::ExcelToPHP($value));
                        } else {
                            $value = \PHPExcel_Style_NumberFormat::toFormattedString($value, $formatcode);
                        }
                    }
                    if ($cell->getDataType() == \PHPExcel_Cell_DataType::TYPE_NUMERIC) {
                        $cellstyleformat = $cell->getParent()->getStyle($cell->getCoordinate())->getNumberFormat();
                        $formatcode = $cellstyleformat->getFormatCode();
                        if (preg_match('/^([$[A-Z]*-[0-9A-F]*])*[hmsdy]/i', $formatcode)) {
                            $value = gmdate("Y-m-d", \PHPExcel_Shared_Date::ExcelToPHP($value));
                        } else {
                            $value = \PHPExcel_Style_NumberFormat::toFormattedString($value, $formatcode);
                        }
                    }
                    if ($isMergeCell[$col . $currentRow] && $isMergeCell[$afCol . $currentRow] && !empty($value)) {
                        $temp = $value;
                    } elseif ($isMergeCell[$col . $currentRow] && $isMergeCell[$col . ($currentRow - 1)] && empty($value)) {
                        $value = $arr[$currentRow - 1][$currentColumn];
                    } elseif ($isMergeCell[$col . $currentRow] && $isMergeCell[$bfCol . $currentRow] && empty($value)) {
                        $value = $temp;
                    }
                    $row[$currentColumn] = $value;
                }
                $arr[$currentRow] = $row;
            }
            $array[$i]["Content"] = $arr;
            $i++;
        }
        // spl_autoload_register(array('Think','autoload'));//must, resolve ThinkPHP and PHPExcel conflicts
        unset($objWorksheet);
        unset($PHPReader);
        unset($PHPExcel);
        unlink($file);

        return array("error" => 1, "data" => $array);
    }
}
