<?php

namespace App\Http\Services;

use App\Models\Loans\Loan;
use App\Models\Payments\Payment;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LoanService
{
    public function GetLoan(int $loanId, User $user): Loan
    {
        $loan = Loan::find($loanId);

        if (!$loan)
        throw new NotFoundHttpException("Задолженость не найдена");

        if ($loan->user_id != $user->id)
        abort(403, "Недоступно");

        return $loan;
    }

    public function GetEvent(int $eventId, int $loanId, User $user)
    {
        $loan = $this->GetLoan($loanId, $user);
        $event = $loan->events()->where('id', $eventId)->first();

        if (!$event)
        throw new NotFoundHttpException("Событие не найдено");

        return $event;
    }

    public function GetPayment(int $paymentId, int $loanId, User $user): Payment
    {
        $loan = $this->GetLoan($loanId, $user);
        $payment = $loan->payments->where('id', $paymentId)->first();
        
        if (!$payment)
        throw new NotFoundHttpException("Платёж не найден");

        return $payment;
    }
}
