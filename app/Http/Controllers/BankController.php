<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BankController extends Controller
{

    public function deposit()
    {
        $amount = request()->post()["amount"] ?? "0";
        if (!is_numeric($amount) || $amount <= 0) {
            return [
                "message" => "Lūdzu ievadiet derīgu summu",
                "error" => true
            ];
        }
        $bank = \Auth::user()->bank;
        $mstat = \Auth::user()->getStat('money');

        if ($mstat->value < $amount) {
            return [
                "message" => "Jums nepietiek līdzekļu",
                "error" => true,
            ];
        }

        $mstat->value -= $amount;
        $finalAmount = floor($amount * ((100 - $bank->getFee()) / 100));
        $diff = $amount - $finalAmount;
        $bank->money += $finalAmount;

        \Auth::user()->push();

        return [
            "message" => "Veiksmīgi ieguldijāt \${$finalAmount}, banka paņēma \${$diff} par komisiju!",
            "status" => 200
        ];
    }

    public function withdraw()
    {
        $amount = request()->post()["amount"] ?? "0";
        if (!is_numeric($amount) || $amount <= 0) {
            return [
                "message" => "Lūdzu ievadiet derīgu summu",
                "error" => true
            ];
        }
        $bank = \Auth::user()->bank;

        if ($bank->money < $amount) {
            return [
                "message" => "Jums nepietiek līdzekļu",
                "error" => true
            ];
        }

        \Auth::user()->getStat('money')->value += $amount;
        $bank->money -= $amount;

        \Auth::user()->push();

        return [
            "message" => "Veiksmīgi izņēmāt \${$amount} no bankas!",
            "status" => 200
        ];
    }

    public function index()
    {
        return view('bank');
    }
}
