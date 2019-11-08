<?php //

	function DateThai($strDate)
	{
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิถุนายน","กรกฎาคม","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strMonthThai $strYear";
	}
?>



            <p>เดือน <?php echo DateThai($year.'-'.$month)?></p>
            <h3><?php print_r($M1); ?> เรื่อง</h3>
       
        

