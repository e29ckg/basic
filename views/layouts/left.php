<?php 
$menuG = [
    'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
    'items' => [
        ['label' => 'Menu', 'options' => ['class' => 'header']],
        ['label' => 'หนังสือเวียน', 'icon' => 'pencil', 'url' => ['cletter/index']],
        ['label' => 'WebLink', 'icon' => 'pencil', 'url' => ['web_link/index']],
        ['label' => '#', 'options' => ['class' => 'header']],
        ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],                    
    ],
];

$menu = [
    'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
    'items' => [
        ['label' => 'Menu', 'options' => ['class' => 'header']],
        ['label' => 'หนังสือเวียน', 'icon' => 'pencil', 'url' => ['cletter/index']],
        ['label' => 'WebLink', 'icon' => 'pencil', 'url' => ['web_link/index']],
        ['label' => 'เขียนใบลา', 'icon' => 'pencil', 'url' => ['bila/index']],
        ['label' => 'ตั้งค่า', 'options' => ['class' => 'header']],
        ['label' => 'Profile', 'icon' => 'id-card-o', 'url' => ['user/profile']],
        
                            
    ],
];

$menu_admin = [
    'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
    'items' => [
        ['label' => 'Menu', 'options' => ['class' => 'header']],
        
        ['label' => 'หนังสือเวียน', 'icon' => 'pencil', 'url' => '#',
          'items' => [
            ['label' => 'หนังสือเวียน', 'icon' => 'pencil', 'url' => ['cletter/index_admin']],
            ['label' => 'ประเภทหนังสือ', 'icon' => 'pencil', 'url' => ['cletter/caid_index']],
            ['label' => 'Line', 'icon' => 'pencil', 'url' => ['cletter/line_index']],
        ]], 
        ['label' => 'WebLink', 'icon' => 'pencil', 'url' => '#',
          'items' => [
            ['label' => 'WebLink', 'icon' => 'pencil', 'url' => ['web_link/index']],
            ['label' => 'จัดการ WebLink', 'icon' => 'pencil', 'url' => ['web_link/index_admin']],
        ]], 
        ['label' => 'โปรแกรมใบลา', 'icon' => 'pencil', 'url' => '#',
          'items' => [
            ['label' => 'เขียนใบลา', 'icon' => 'pencil', 'url' => ['bila/index']],
            ['label' => 'ใบลาทั้งหมด', 'icon' => 'pencil', 'url' => ['bila/admin']],
            ['label' => 'ผู้ลงนาม', 'icon' => 'pencil', 'url' => ['bila/sbn_index']],
        ]],        
        
        ['label' => 'ตั้งค่า', 'options' => ['class' => 'header']],
        ['label' => 'Profile', 'icon' => 'id-card-o', 'url' => ['user/profile']],
        ['label' => 'สมาชิก', 'icon' => 'users', 'url' => '#',
            'items' => [
                ['label' => 'จัดการสมาชิก', 'icon' => 'fa fa-user-o', 'url' => ['user/user_index']],
                // ['label' => 'จัดการสมาชิก2', 'icon' => 'fa fa-user-o', 'url' => ['user/user_index2']],
                // ['label' => 'เพิ่มสมาชิก', 'icon' => 'fa fa-user-o', 'url' => ['user/reg']],
                ['label' => 'จัดการตำแหน่ง', 'icon' => 'fa fa-user-o', 'url' => ['user/dep']],
                ['label' => 'จัดการคำนำหน้าชื่อ', 'icon' => 'fa fa-user-o', 'url' => ['user/fname']],
            ],
        ],
        ['label' => 'Line', 'icon' => 'id-card-o', 'url' => ['line/line_index']],
        // ['label' => 'Login', 'url' => ['site/login']],
        
        ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                            
    ],
];
    if(Yii::$app->user->isGuest){
            echo dmstr\widgets\Menu::widget($menuG) ;
        }elseif(Yii::$app->user->id ==1 ){
            echo dmstr\widgets\Menu::widget($menu_admin);
        }else{
            echo dmstr\widgets\Menu::widget($menu) ;
    }

?>