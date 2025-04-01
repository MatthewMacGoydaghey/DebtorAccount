<?php

namespace App\Http\Controllers;

use App\Http\Filters\Filter;
use App\Http\Resources\Loans\LoanResource;
use App\Models\Loans\Loan;
use App\Models\Loans\LoanStatuse;
use Illuminate\Http\Request;

class LoanController extends Controller
{

    public function GetLoans(Request $request)
    {
        $results = new Filter($request, Loan::query(), ["lender"])->getResult();
        return $this->paginatedResponse($results['total'], $results['filtered'], "loans", LoanResource::collection($results['query']));
    }

    public function GetStatuses(Request $request)
    {
        $results = new Filter($request, LoanStatuse::query(), ["name"])->getResult();
        return $this->paginatedResponse($results['total'], $results['filtered'], "loan_statuses", $results['query']);
    }
}
