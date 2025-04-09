<?php

namespace App\Http\Controllers;

use App\Http\Filters\Filter;
use App\Http\Resources\Loans\LoanResource;
use App\Models\Loans\Loan;
use App\Models\Loans\LoanEvent;
use App\Models\Loans\LoanEventType;
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

    public function GetEvents(Request $request): JsonResponse
    {
        $filter = new Filter($request, LoanEvent::query(), ["description"]);
        $results = $filter->apply()->getResult();
        return $this->paginatedResponse($results['total'], $results['filtered'], "loan_events", $results['query']->get());
    }


    public function GetEventTypes(Request $request)
    {
        $filter = new Filter($request, LoanEventType::where('active', true), ["content"]);
        $results = $filter->apply()->getResult();
        return $this->paginatedResponse($results['total'], $results['filtered'], "loan_event_types", $results['query']->get());
    }
}
