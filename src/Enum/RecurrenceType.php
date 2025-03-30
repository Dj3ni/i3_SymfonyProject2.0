<?php

namespace App\Enum;

enum RecurrenceType:string
{
    case DAILY = "Daily";
    case WEEKLY = "Weekly";
    case MONTHLY = "Monthly";
    case YEARLY = "Yearly";
    case NONE = "None";
}