<?php
namespace Admin\Model;

/**
 * 代码模型
 */
class CodeModel {
    private $author = '';

    private function getTable($table_name) {
        $table = [];
        $db_name = C('DB_NAME');

        $result = M()->query("select * from INFORMATION_SCHEMA.TABLES "
            . "where table_schema = '$db_name' and table_name = '$table_name'");
        if ($result) {
            $table = $result[0];
        }
        return $table;
    }

    private function getModelName($table_name) {
        $db_prefix = C('DB_PREFIX');
        $model_str = strtolower(substr($table_name, strlen($db_prefix)));
        $model_name = parse_name($model_str, 1);
        return $model_name;
    }

    private function getColumns($table_name) {
        $db_name = C('DB_NAME');
        $columns = M()->query("select * from INFORMATION_SCHEMA.COLUMNS "
            . "where table_schema = '$db_name' and table_name = '$table_name'");
        return $columns;
    }

    private function buildModel($model_name, $model_comment, $pk, $fields) {
        $dir = APP_PATH . 'Admin/Model/';
        $file = $dir .  $model_name . 'Model.class.php';

        if (file_exists($file) && filesize($file)) {
            return "{$file} 存在且有内容！";
        }

        $model_name_lower = lcfirst($model_name);
        $date = date('Y/n/j', time());
        $field_names = array_column($fields, 'column_name');
        array_unshift($field_names, $pk);
        $field_names_str = "'" . join("', '", $field_names) . "'";

        $validate_str = "";
        foreach ($fields as $field) {
            $name = $field['column_name'];
            $comment = $field['column_comment'];
            $type = $field['data_type'];
            $max = $field['character_maximum_length'];

            $validate_str .= "        array('{$name}', 'require', '请输入 {$comment} ', 1, 'regex', 3),\n";
            if (strstr($type, 'char') !== false) {
                $validate_str .= "        array('{$name}', '0,{$max}', '您输入的 {$comment} 过长，超过了 {$max} 个字符数限制', 1, 'length', 3),\n";
            }
        }

        $str = "<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description
 * @Author         {$this->author}
 * @Date           {$date}
 * @CreateBy       PhpStorm
 */
namespace Admin\Model;

use Think\Model;
 
/**
 * {$model_comment}模型
 */
class {$model_name}Model extends Model {
    protected #insertFields = array({$field_names_str});
    protected #updateFields = array({$field_names_str});

    protected #_validate = array(
{$validate_str}
    );

    public function get{$model_name}List(#where = [], #field = '', #order = '') {
        #count = #this->where(#where)->count();
        #page = get_page(#count);
        #list = #this->field(#field)->where(#where)->limit(#page['limit'])->order(#order)->select();
        return array(
            'list' => #list,
            'page' => #page['page']
        );
    }
    
}
        ";

        $str = str_replace('#', '$', $str);
        $handle = fopen($file, "w");
        fwrite($handle, $str);
        fclose($handle);
        return "{$file} 创建成功！";
    }

    private function buildEditView($model_name, $model_comment, $pk, $fields) {
        $dir = APP_PATH . 'Admin/View/' . $model_name . '/';
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        $file = $dir . 'edit' . $model_name . '.html';
        if (file_exists($file) && filesize($file)) {
            return "{$file} 存在且有内容！";
        }

        $model_name_lower = lcfirst($model_name);
        $date = date('Y/n/j', time());

        $group_str = "";
        foreach ($fields as $field) {
            $name = $field['column_name'];
            $comment = $field['column_comment'];
            $type = $field['data_type'];

            if (strstr($type, 'int') !== false
                || strstr($type, 'float') !== false
                || strstr($type, 'double') !== false) {
                $input_type = 'number';
            } else {
                $input_type = 'text';
            }
            $group_str .= "
                            <div class=\"am-form-group\">
                                <label for=\"{$name}\" class=\"am-u-sm-3 am-form-label\">
                                    {$comment}
                                    <span class=\"tpl-form-line-small-title must-input\">*</span>
                                </label>
                                <div class=\"am-u-sm-7 am-u-sm-pull-2\">
                                    <input type=\"{$input_type}\" id=\"{$name}\" name=\"{$name}\" value=\"{#info['{$name}']}\"
                                           placeholder=\"请输入{$comment}\">
                                    <small></small>
                                </div>
                            </div>
            ";
        }

        $str = "<extend name=\"Common/base\"/>

<block name=\"style\">

</block>

<block name=\"body\">
    <div class=\"row-content am-cf\">
        <div class=\"row\">
            <div class=\"am-u-sm-12 am-u-md-12 am-u-lg-12\">
                <div class=\"widget am-cf\">
                    <div class=\"widget-head am-cf\">
                        <div class=\"widget-title am-fl\">{#info['{$pk}'] ? '编辑' : '添加'}{$model_comment}</div>
                    </div>
                    <div class=\"widget-body am-fr\">
                        <form action=\"__ACTION__\" method=\"post\" class=\"ajaxForm am-form tpl-form-border-form tpl-form-border-br\">
                            {$group_str}
                            <div class=\"am-form-group\">
                                <div class=\"am-u-sm-9 am-u-sm-push-3\">
                                    <input type=\"hidden\" name=\"{$pk}\" value=\"{#info['{$pk}']}\">
                                    <button type=\"submit\" class=\"am-btn am-btn-primary tpl-btn-bg-color-success\">提交</button>
                                    <button type=\"button\" class=\"am-btn am-btn-primary am-btn-warning\" onclick=\"goback();\">返回</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</block>

<block name=\"script\">

</block>
        ";

        $str = str_replace('#', '$', $str);
        $handle = fopen($file, "w");
        fwrite($handle, $str);
        fclose($handle);
        return "{$file} 创建成功！";
    }

    private function buildListView($model_name, $model_comment, $pk, $fields) {
        $dir = APP_PATH . 'Admin/View/' . $model_name . '/';
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        $file = $dir . 'list' . $model_name . '.html';
        if (file_exists($file) && filesize($file)) {
            return "{$file} 存在且有内容！";
        }

        $model_name_lower = lcfirst($model_name);
        $date = date('Y/n/j', time());

        $th_str = "";
        $td_str = "";
        foreach ($fields as $field) {
            $name = $field['column_name'];
            $comment = $field['column_comment'];
            $type = $field['data_type'];
            $th_str .= "
									<th width=\"10%\">{$comment}</th>";
            $td_str .= "
										<td>{#v['{$name}']}</td>";
        }

        $str = "<extend name=\"Common/base\"/>

<block name=\"style\">

</block>

<block name=\"body\">
	<div class=\"row-content am-cf\">
		<div class=\"row\">
			<div class=\"am-u-sm-12 am-u-md-12 am-u-lg-12\">
				<div class=\"widget am-cf\">
					<div class=\"widget-head am-cf\">
						<div class=\"widget-title am-cf\">{$model_comment}列表</div>
					</div>
					<div class=\"widget-body am-fr\">
						<div class=\"am-u-sm-12 am-u-md-8 am-u-lg-8\">
							<div class=\"am-form-group\">
								<div class=\"am-btn-toolbar\">
									<div class=\"am-btn-group am-btn-group-xs\">
										<a type=\"button\" class=\"am-btn am-btn-default am-btn-success\"
										   href=\"{:U('{$model_name}/edit{$model_name}')}\">
											<span class=\"am-icon-plus\"></span> 新增
										</a>
										<button type=\"button\" class=\"am-btn am-btn-default am-btn-danger\"
												onclick=\"javascript:recycle('chkbId', '确认删除?! 删除后无法恢复!', true)\">
											<span class=\"am-icon-trash-o\"></span> 批量删除
										</button>
									</div>
								</div>
							</div>
						</div>
						<div class=\"am-u-sm-12\">
							<table width=\"100%\" class=\"am-table am-table-compact am-table-striped tpl-table-black\">
								<thead>
								<tr>
									<th width=\"50\"><input class=\"check-all\" type=\"checkbox\"></th>
									<th width=\"70\">序号</th>
									{$th_str}
									<th width=\"10%\">操作</th>
								</tr>
								</thead>
								<tbody>
								<foreach name=\"list\" item=\"v\">
									<tr class=\"gradeX\">
										<td><input class=\"ids\" type=\"checkbox\" name=\"chkbId\" value=\"{#v['{$pk}']}\"></td>
										<td>{#v['{$pk}']}</td>
										{$td_str}
										<td class=\"f-14\">
											<div class=\"tpl-table-black-operation\">
												<a href=\"{:U('{$model_name}/edit{$model_name}', array('{$pk}' => #v['{$pk}']))}\">
													<i class=\"am-icon-pencil\"></i> 编辑
												</a>
												<a href=\"javascript:void(0);\"
												   onclick=\"javascript:recycle({#v['{$pk}']}, '确认删除?!此步骤无法恢复!', true)\"
												   class=\"tpl-table-black-operation-del\">
													<i class=\"am-icon-trash\"></i> 删除
												</a>
											</div>
										</td>
									</tr>
								</foreach>
								</tbody>
							</table>
							<empty name=\"list\">
								<h4>aOh! 没有相关内容!</h4>
							</empty>
						</div>
						<div class=\"am-u-lg-12 am-cf\">
							<div class=\"am-fr\">
								<div class=\"am-pagination tpl-pagination\">
									{#page}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</block>

<block name=\"script\">
	<script type=\"text/javascript\">

	</script>
</block>
        ";

        $str = str_replace('#', '$', $str);
        $handle = fopen($file, "w");
        fwrite($handle, $str);
        fclose($handle);
        return "{$file} 创建成功！";
    }

    private function buildView($model_name, $model_comment, $pk, $fields) {
        return $this->buildEditView($model_name, $model_comment, $pk, $fields) . '</br>'
            . $this->buildListView($model_name, $model_comment, $pk, $fields);
    }

    private function buildController($model_name, $model_comment, $pk, $fields) {
        $dir = APP_PATH . 'Admin/Controller/';
        $file = $dir . $model_name . 'Controller.class.php';

        if (file_exists($file) && filesize($file)) {
            return "{$file} 存在且有内容！";
        }

        $model_name_lower = lcfirst($model_name);
        $date = date('Y/n/j', time());

        $str = "<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description
 * @Author         {$this->author}
 * @Date           {$date}
 * @CreateBy       PhpStorm
 */
namespace Admin\Controller;

/**
 * {$model_comment}控制器
 */
class {$model_name}Controller extends CommonController {
    public function list{$model_name}(){
        #data = D('Admin/{$model_name}')->get{$model_name}List();
        #this->assign('list', #data['list']);
        #this->assign('page', #data['page']);
        #this->display();
    }
    
    public function edit{$model_name}(){
        #{$pk} = I('{$pk}');
        #{$model_name_lower}Model = D('Admin/{$model_name}');
        
        if (IS_POST) {
            if (#{$model_name_lower}Model->create() === false) {
                #this->ajaxReturn(V(0, #{$model_name_lower}Model->getError()));
            }
            if (#{$pk}) {
                if (#{$model_name_lower}Model->save() !== false) {
                    #this->ajaxReturn(V(1, '编辑成功'));
                }
            } else {
                if (#{$model_name_lower}Model->add() !== false) {
                    #this->ajaxReturn(V(1, '添加成功'));
                }
            }
            #this->ajaxReturn(V(0, #{$model_name_lower}Model->getDbError()));
        }
        
        #info = #{$model_name_lower}Model->find(#{$pk});
        #this->assign('info', #info);
        #this->display();
    }
    
    public function del(){
        #this->_del('{$model_name}', '{$pk}');
    }
}
        ";

        $str = str_replace('#', '$', $str);
        $handle = fopen($file, "w");
        fwrite($handle, $str);
        fclose($handle);
        return "{$file} 创建成功！";
    }

    public function buildCode($table_name, $model, $view, $controller) {
        if (empty($table_name)) {
            return '请输入表名！';
        }
        $table = $this->getTable($table_name);
        if (empty($table)) {
            return '您输入的表名不存在！';
        }
        $model_name = $this->getModelName($table['table_name']);
        $model_comment = $table['table_comment'];
        $columns = $this->getColumns($table_name);
        $pk = '';
        $fields = [];
        foreach ($columns as $column) {
            if ($column['column_key'] === 'PRI') {
                $pk = $column['column_name'];
            } else {
                $fields[] = $column;
            }
        }

        $info = '';
        if ($model) {
            $info .= $this->buildModel($model_name, $model_comment, $pk, $fields);
            $info .= '<br/>';
        }
        if ($view) {
            $info .= $this->buildView($model_name, $model_comment, $pk, $fields);
            $info .= '<br/>';
        }
        if ($controller) {
            $info .= $this->buildController($model_name, $model_comment, $pk, $fields);
            $info .= '<br/>';
        }
        return $info;
    }
}