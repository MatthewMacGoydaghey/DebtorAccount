<?php

namespace App\Http\Services;

use App\Models\Loans\Loan;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LoanService
{
    public function GetLoan(int $id, User $user): Loan
    {
        $loan = Loan::find($id);

        if (!$loan) throw new NotFoundHttpException("Задолженость не найдена");
        if ($loan->user_id != $user->id) abort(403, "Недоступно");

        return $loan;
    }
}
