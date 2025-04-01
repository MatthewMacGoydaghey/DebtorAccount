<?php

namespace App\Http\Controllers;

use App\Http\Filters\Filter;
use App\Http\Resources\Loans\LoanResource;
use App\Models\Loans\Loan;
use App\Models\Loans\LoanStatuse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoanController extends Controller
{

    public function GetLoans(Request $request): JsonResponse
    {
        $filter = new Filter($request, Loan::where('user_id', $request->user()->id), ["lender"]);
        $results = $filter->apply()->getResult();
        return $this->paginatedResponse($results['total'], $results['filtered'], "loans", LoanResource::collection($results['query']->orderBy('id', 'desc')->get()));
    }

    public function GetStatuses(Request $request): JsonResponse
    {
        $filter = new Filter($request, LoanStatuse::query(), ["name"]);
        $results = $filter->apply()->getResult();
        return $this->paginatedResponse($results['total'], $results['filtered'], "loan_statuses", $results['query']->get());
    }
}
