<?php

namespace Src\admin\customer\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

final class GET_AdminCustomerCreateWebController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('admin/customers/Create');
    }
}
