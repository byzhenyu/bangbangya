<?php
$modules = array(
    'agent' => array(
        'label' => '基本资料',
        'action' => '',
        'items' => array(
            array('label' => '基本资料管理', 'action' => U('Agent/setAgent')),
            array('label' => '学习币分销设置', 'action' => U('Agent/setDistribution')),
        )
    ),
    'teacher' => array('label' => '教师管理' , 'action' => '' , 'items' => array(
        array('label' => '添加教师', 'action' => U('Teacher/editTeacher')),
        array('label' => '教师列表', 'action' => U('Teacher/listTeacher')),
        )),
    'activatecode' => array('label' => '激活码管理', 'action' => '', 'items' => array(

        array('label' => '激活码列表', 'action' => U('ActivateCode/listActivateCode')),
    )),

);
return $modules;