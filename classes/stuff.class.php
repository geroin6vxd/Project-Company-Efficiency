<?

class class_stuff
{
	public function set_month_to_timestamp($month)
	{
		return strtotime("00:00:01 01 ".trim($month));
	}
	public function get_month_from_timestamp($timestamp){
		return  $monthName = date('F, Y', $timestamp);
	}
	public function translate_payment_type($type){
		if($type == "SUB_CONTRACTORS")return ("Sub-contractors"); else
		if($type == "TRAVELLING")return ("Travelling"); else
		if($type == "CASH")return ("Cash"); else
		if($type == "OTHER_COSTS")return ("Other costs (computer, telephone e.t.c)"); else
		if($type == "FREE_LANCERS")return ("Freelancers"); else
		return ("");
	}
	public function format($num){
		return number_format($num, 2, ',', ' ');
	}
}

$stuff = new class_stuff();
?>

