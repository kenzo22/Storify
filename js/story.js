function date_format(origin_date)
{
  var temp_array = origin_date.split(' ');
  switch(temp_array[1])
  {
	case('Jan'):temp_array[1] = 1;
	break;
	case('Feb'):temp_array[1] = 2;
	break;
	case('Mar'):temp_array[1] = 3;
	break;
	case('Apr'):temp_array[1] = 4;
	break;
	case('May'):temp_array[1] = 5;
	break;
	case('Jun'):temp_array[1] = 6;
	break;
	case('Jul'):temp_array[1] = 7;
	break;
	case('Aug'):temp_array[1] = 8;
	break;
	case('Sep'):temp_array[1] = 9;
	break;
	case('Oct'):temp_array[1] = 10;
	break;
	case('Nov'):temp_array[1] = 11;
	break;
	case('Dec'):temp_array[1] = 12;
	break;
	default:temp_array[1] = temp_array[1];
	break;
  }
  var time_array = temp_array[3].split(':');
  temp_array[3] = time_array[0]+':'+time_array[1];
  return temp_array[5]+'-'+temp_array[1]+'-'+temp_array[2]+' '+temp_array[3];
}