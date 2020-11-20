<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Store;
use App\Weapon;

class StoreController extends Controller
{

    /** @var string */
    private $error;
    
    public function property() 
    {
        return view('property');
    }

    public function inventory()
    {
        return view('inventory', [
            'weapons' => Store::find(Store::WEAPON_STORE)->inventory->weapons
        ]);
    }

    public function sufficientFunds($item, int $amount) : bool {
        $price = $item->price * $amount;
        if($price > \Auth::user()->getMoney()) {
            $this->error = [
                "message" => "Nav pietiekami līdzekļi"
            ];
            return false;
        }
        return true;
    }

    public function buy($item, int $amount) {
        $inv = \Auth::user()->inventory;

        if(!$this->sufficientFunds($item, $amount)) {
            return $this->error;
        }

        if($item instanceof Weapon) {
            $has = $inv->weapons->where('name', $item->name);
            if($has->count() > 0) {
                $has->first()->amount += $amount;
                $has->first()->save();
            } else {
                $dupe = $item->replicate();
                unset($dupe->id);
                $dupe->amount = $amount;
                $dupe->inventory_id = $inv->id;
                \App\Weapon::insert($dupe->toArray());
            }
        } else {
            throw new \InvalidArgumentException("Unknown item $item");
        }

        $money = $item->price * $amount;
        \Auth::user()->takeMoney($money);

        return [
            "Veiksmīgi iegādājāties {$item->name} x {$amount} par \${$money}"
        ];
    }

    public function sell($item, int $amount) {
        $inv = \Auth::user()->inventory;

        if($item instanceof Weapon) {
            $has = $inv->weapons->where('name', $item->name)->first();
            if($has) {
                if($has->amount < $amount) {
                    return [
                        "message" => "Jums nav pietiekams daudzums ar šo preci",
                        "error" => true
                    ];
                }
                $has->amount -= $amount;
                $has->save();
            } else {
                return [
                    "message" => "Jums nav šīs preces",
                    "error" => true
                ];
            }
        } else {
            throw new \InvalidArgumentException("Unknown item $item");
        }

        $money = $item->price * $amount;
        \Auth::user()->giveMoney($money);

        return [
            "message" => "Jūs pārdevāt {$item->name} x {$amount} par $money"
        ];
    }

    public function processRequest() {
        $request = request()->post();

        $store = Store::find($request["store_id"]);
        if($store) {
            $item = $store->inventory->weapons->where('name', $request["item"])->first();
            if(!$item) {
                return [
                    "message" => "Kļūda: nepazīstama manta ({$request['item']}), sazinieties ar administratoru",
                    "error" => true
                ];
            }
            switch($request["action"]) {
                case "buy":
                    return $this->buy($item, $request["amount"] ?? 1);
                break;
                case "sell":
                    return $this->sell($item, $request["amount"] ?? 1);
                break;
                default:
                    return [
                        "message" => "Kļūda: sazinieties ar administratoru",
                        "error" => true
                    ];
                break;
            }
        }
    }

}
