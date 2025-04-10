<?php

namespace App\Http\Controllers;

use App\Http\Filters\Filter;
use App\Http\Resources\Loans\LoanEventResource;
use App\Http\Resources\Loans\LoanEventTypeResource;
use App\Http\Resources\Loans\LoanResource;
use App\Http\Services\LoanService;
use App\Models\Loans\Loan;
use App\Models\Loans\LoanEventType;
use App\Models\Loans\LoanStatuse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoanController extends Controller
{

    public function __construct(
        protected LoanService $loanService,
        ) {}


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

    public function GetEvents(Request $request): JsonResponse
    {
        $loan = $this->loanService->GetLoan($request->id, $request->user());
        $filter = new Filter($request, $loan->events()->getQuery(), ["description"]);
        $results = $filter->apply()->getResult();
        return $this->paginatedResponse($results['total'], $results['filtered'], "loan_events", LoanEventResource::collection($results['query']->get()));
    }

    public function GetEventTypes(Request $request)
    {
        $filter = new Filter($request, LoanEventType::where('active', true), ["content"]);
        $results = $filter->apply()->getResult();
        return $this->paginatedResponse($results['total'], $results['filtered'], "loan_event_types", LoanEventTypeResource::collection($results['query']->get()));
    }
}
