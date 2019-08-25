<?php 
$menuG = [
    'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
    'items' => [
        ['label' => 'Menu', 'options' => ['class' => 'header']],
        ['label' => 'หน้าหลัก', 'icon' => 'home', 'url' => ['site/index']],
        ['label' => 'หนังสือเวียน', 'icon' => 'briefcase', 'url' => ['cletter/index']],
        ['label' => 'WebLink', 'icon' => 'link', 'url' => ['web_link/index']],
        ['label' => 'เขียนใบลา', 'icon' => 'briefcase', 'url' => ['bila/index']],
        ['label' => 'สร้าง  QrCode', 'icon' => ' fa-qrcode', 'url' => ['qrgen/index']], 
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
        ['label' => 'ปฏิทินใบลา', 'icon' => 'briefcase', 'url' => ['bila/cal']],
        ['label' => 'ตารางเวร', 'icon' => 'fa fa-user-o', 'url' => ['/ven/index']],
        ['label' => 'สร้าง  QrCode', 'icon' => ' fa-qrcode', 'url' => ['qrgen/index']], 
        ['label' => 'เบิกของ CartX', 'icon' => 'shopping-cart', 'url' => '/cartx/web'], 
        ['label' => 'ตั้งค่า', 'options' => ['class' => 'header']],
        ['label' => 'Profile', 'icon' => 'id-card-o', 'url' => ['user/profile']],  
                            
    ],
];

$menu_operator = [
    'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
    'items' => [
        ['label' => 'Menu', 'options' => ['class' => 'header']],
        ['label' => 'หน้าหลัก', 'icon' => 'home', 'url' => ['site/index']],
        ['label' => 'หนังสือเวียน', 'icon' => 'bullhorn', 'url' => ['cletter/index']],
        ['label' => 'WebLink', 'icon' => 'link', 'url' => ['web_link/index']],
        ['label' => 'เขียนใบลา', 'icon' => 'briefcase', 'url' => ['bila/index']],
        ['label' => 'ปฏิทินใบลา', 'icon' => 'briefcase', 'url' => ['bila/cal']],
        ['label' => 'ตารางเวร', 'icon' => 'fa fa-user-o', 'url' => ['/ven/index']],
        ['label' => 'สร้าง  QrCode', 'icon' => ' fa-qrcode', 'url' => ['qrgen/index']], 

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
            ['label' => 'ปฏิทินใบลา', 'icon' => 'briefcase', 'url' => ['bila/cal']],
            ['label' => 'ผู้ลงนาม', 'icon' => 'briefcase', 'url' => ['bila/sbn_index']],
        ]],
        ['label' => 'สร้าง  QrCode', 'icon' => ' fa-qrcode', 'url' => ['qrgen/index']],  
        ['label' => 'เบิกของ CartX', 'icon' => 'shopping-cart', 'url' => '/cartx/web'],       
        // ['label' => 'ตารางเวร', 'icon' => 'shopping-cart', 'url' => ['/ven/index']],
        ['label' => 'ตารางเวร', 'icon' => 'users', 'url' => '#',
            'items' => [
                ['label' => 'ตารางเวร', 'icon' => 'fa fa-user-o', 'url' => ['/ven/index']],
                ['label' => 'ใบเปลี่ยนเวร', 'icon' => 'fa fa-user-o', 'url' => ['/ven/change_index']],
                ['label' => 'จัดเวร จับ-ค้น (กลางคืน)', 'icon' => ' fa fa-user-o', 'url' => ['ven/admin_index']],
                ['label' => 'ผู้อยู่เวร', 'icon' => ' fa fa-user-o', 'url' => ['ven/user']],
                ['label' => 'คำสั่ง', 'icon' => ' fa fa-user-o', 'url' => ['ven/com_index']],
            ],
        ],

        ['label' => 'ตั้งค่า', 'options' => ['class' => 'header']],
        ['label' => 'Profile', 'icon' => 'id-card-o', 'url' => ['user/profile']],    
        ['label' => 'Line + ตั้งค่าพื้นฐาน', 'icon' => ' fa fa-whatsapp', 'url' => ['line/line_index']],    
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

if(!Yii::$app->user->isGuest){
    switch (Yii::$app->user->identity->role) {
        case 9:
            echo dmstr\widgets\Menu::widget($menu_admin);
            break;
        case 2:
            echo dmstr\widgets\Menu::widget($menu_operator);
            break;
        // case "green":
        //     echo "Your favorite color is green!";
        //     break;
        default:
            echo dmstr\widgets\Menu::widget($menu) ;
    }
}else{
    echo dmstr\widgets\Menu::widget($menuG) ;
}

?>

?>