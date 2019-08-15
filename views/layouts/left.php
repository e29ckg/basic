<?php 
$menuG = [
    'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
    'items' => [
        ['label' => 'Menu', 'options' => ['class' => 'header']],
        ['label' => 'หน้าหลัก', 'icon' => 'home', 'url' => ['site/index']],
        ['label' => 'หนังสือเวียน', 'icon' => 'briefcase', 'url' => ['cletter/index']],
        ['label' => 'WebLink', 'icon' => 'link', 'url' => ['web_link/index']],
        ['label' => 'เขียนใบลา', 'icon' => 'briefcase', 'url' => ['bila/index']],
        ['label' => 'เบิกของ CartX', 'icon' => 'shopping-cart', 'url' => '/cartx/web'], 
        ['label' => '#', 'options' => ['class' => 'header']],
        ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],                    
    ],
];

$menu = [
    'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
    'items' => [
        ['label' => 'Menu', 'options' => ['class' => 'header']],
        ['label' => 'หน้าหลัก', 'icon' => 'home', 'url' => ['site/index']],
        ['label' => 'หนังสือเวียน', 'icon' => 'bullhorn', 'url' => ['cletter/index']],
        ['label' => 'WebLink', 'icon' => 'link', 'url' => ['web_link/index']],
        ['label' => 'เขียนใบลา', 'icon' => 'briefcase', 'url' => ['bila/index']],
        ['label' => 'เบิกของ CartX', 'icon' => 'shopping-cart', 'url' => '/cartx/web'], 
        ['label' => 'ตั้งค่า', 'options' => ['class' => 'header']],
        ['label' => 'Profile', 'icon' => 'id-card-o', 'url' => ['user/profile']],  
                            
    ],
];

$menu_admin = [
    'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
    'items' => [
        ['label' => 'Menu', 'options' => ['class' => 'header']],
        ['label' => 'หน้าหลัก', 'icon' => 'home', 'url' => ['site/index'],
        // 'template' => '<a href="{url}" title="{label}"><i class="fa fa-lg fa-fw fa-wrench"></i> <span class="menu-item-parent">{label}</span></a>',
    ],
        ['label' => 'หนังสือเวียน', 'icon' => 'bullhorn', 'url' => '#',
          'items' => [
            ['label' => 'หนังสือเวียน', 'icon' => 'bullhorn', 'url' => ['cletter/index']],
            ['label' => 'จัดการ หนังสือเวียน', 'icon' => 'bullhorn', 'url' => ['cletter/index_admin']],
            ['label' => 'ประเภทหนังสือ', 'icon' => 'bullhorn', 'url' => ['cletter/caid_index']],
        ]], 
        ['label' => 'WebLink', 'icon' => 'link', 'url' => '#',
          'items' => [
            ['label' => 'WebLink', 'icon' => 'link', 'url' => ['web_link/index']],
            ['label' => 'จัดการ WebLink', 'icon' => 'link', 'url' => ['web_link/index_admin']],
        ]], 
        ['label' => 'โปรแกรมใบลา', 'icon' => 'briefcase', 'url' => '#',
          'items' => [
            ['label' => 'เขียนใบลา', 'icon' => 'briefcase', 'url' => ['bila/index']],
            ['label' => 'ใบลาทั้งหมด', 'icon' => 'briefcase', 'url' => ['bila/admin']],
            ['label' => 'ผู้ลงนาม', 'icon' => 'briefcase', 'url' => ['bila/sbn_index']],
        ]], 
        ['label' => 'เบิกของ CartX', 'icon' => 'shopping-cart', 'url' => '/cartx/web'],       
        
        ['label' => 'ตั้งค่า', 'options' => ['class' => 'header']],
        ['label' => 'Profile', 'icon' => 'id-card-o', 'url' => ['user/profile']],
        ['label' => 'Line', 'icon' => ' fa fa-whatsapp', 'url' => ['line/line_index']],
        ['label' => 'สมาชิก', 'icon' => 'users', 'url' => '#',
            'items' => [
                ['label' => 'จัดการสมาชิก', 'icon' => 'fa fa-user-o', 'url' => ['user/user_index']],
                // ['label' => 'จัดการสมาชิก2', 'icon' => ' fa fa-user-o', 'url' => ['user/user_index2']],
                // ['label' => 'เพิ่มสมาชิก', 'icon' => ' fa fa-user-o', 'url' => ['user/reg']],
                ['label' => 'จัดการตำแหน่ง', 'icon' => ' fa fa-user-o', 'url' => ['user/dep']],
                ['label' => 'จัดการคำนำหน้าชื่อ', 'icon' => ' fa fa-user-o', 'url' => ['user/fname']],
            ],
        ],
        
        // ['label' => 'Login', 'url' => ['site/login']],
        
        ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                            
    ],
];
    if(Yii::$app->user->isGuest){
            echo dmstr\widgets\Menu::widget($menuG) ;
        }elseif(Yii::$app->user->identity->role == 9 ){
            echo dmstr\widgets\Menu::widget($menu_admin);
        }else{
            echo dmstr\widgets\Menu::widget($menu) ;
    }

?>