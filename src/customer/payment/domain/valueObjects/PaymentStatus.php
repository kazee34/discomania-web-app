<?php

namespace Src\customer\payment\domain\valueObjects;

enum PaymentStatus: string
{
    case Completed = 'completed';
    case Failed = 'failed';
}
