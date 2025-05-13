<?php

namespace App\Domain\Enum;

enum FieldType: string
{
    case TEXT = "text";
    case SUB_HEADING = "sub_heading";
	case DATE = "date";
}
