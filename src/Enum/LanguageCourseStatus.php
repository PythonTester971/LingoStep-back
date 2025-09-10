<?php
// src/Enum/Status.php
namespace App\Enum;

enum LanguageCourseStatus: string
{
  case NOT_STARTED = 'not started';
  case IN_PROGRESS = 'in progress';
  case COMPLETED = 'completed';
}
