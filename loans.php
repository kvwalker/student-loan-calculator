<?php

class LoanObject
{
    public $amount;
    public $interest;
    public $minMonthly;

    public function __construct($amount, $interest, $minMonthly) {
        $this->amount = $amount;
        $this->interest = $interest;
        $this->minMonthly = $minMonthly;
    }
}

class Loan
{
    public $months = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    public function allTimeLeft($allLoans, $monthlyPayment) {
        $realMonthly = $monthlyPayment;
        foreach ($allLoans as $singleLoan) {
            $realMonthly -= $singleLoan->minMonthly;
        }

        $prevLoan = $this->singleTimeLeft($allLoans[0], $realMonthly);
        $realMonthly += $allLoans[0]->minMonthly;
        $allTimes = $prevLoan[0];
        for ($i = 1; $i < count($allLoans); $i++) {
            $currentLoan = $this->singleTimeLeft($allLoans[$i], $realMonthly, $prevLoan);
            $prevLoan = $currentLoan;
            $realMonthly += $allLoans[$i]->minMonthly;
        }

        return $currentLoan[0];
    }

    public function singleTimeLeft(LoanObject $singleLoan, $monthlyPayment, $prevLoan = [0, 0]) {
        $dailyInterest = $singleLoan->interest * $singleLoan->amount/365;

        $loanAmount = $singleLoan->amount - $prevLoan[1];
        $i = 0;
        while ($loanAmount > 0) {
            $loanAmount += $this->months[$i + 2]*$dailyInterest;
            $loanAmount = $loanAmount - $singleLoan->minMonthly;
            if ($i >= $prevLoan[0]) {
                $loanAmount -= $monthlyPayment;
            }
            $i++;
       }

        return [$i, $loanAmount*-1];
    }
}

$l = new Loan();

$myLoan0 = new LoanObject(3669.88, 0.0404, 53.27);
$myLoan1 = new LoanObject(2083.82, 0.0404, 22.31);
$myLoan2 = new LoanObject(3736.02, 0.0361, 39.22);
$myLoan3 = new LoanObject(2067.36, 0.0361, 21.7);
$myLoan4 = new LoanObject(5970.54, 0.0351, 62.38);

$myLoans = [$myLoan0, $myLoan1, $myLoan2, $myLoan3, $myLoan4];

$pay = 1985;
$monthsLeft = $l->allTimeLeft($myLoans, $pay);
echo("months left: " . $monthsLeft . "\n");
echo("money saved over year: " . (2000-$pay)*$monthsLeft . "\n");
