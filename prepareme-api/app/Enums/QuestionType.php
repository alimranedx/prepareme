<?php

namespace App\Enums;

enum QuestionType: string
{
    case SHORT_ANSWER = 'short_answer';
    case MCQ = 'mcq';
    case TRUE_FALSE = 'true_false';
}
