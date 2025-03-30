<?php

namespace App\Enum;

enum EventType:string
{
    case BOARDGAMES_DEMO = "boardgames_demo";
    case ROLE_PLAY = "role_play";
    case FESTIVAL = "festival";
    case GAMING_SALES = "gaming_sales";
    case TOURNAMENT = "tournament";
}