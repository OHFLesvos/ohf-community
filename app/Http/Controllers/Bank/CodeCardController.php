<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bank\CreateCodeCard;
use App\Util\Bank\CodeCardCreator;
use Setting;

class CodeCardController extends Controller
{
    /**
     * Show view for preparing new code card sheet.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bank.prepareCodeCard');
    }

    /**
     * Create new code card sheet and return PDF for download.
     *
     * @param  \App\Http\Requests\People\Bank\CreateCodeCard  $request
     * @return \Illuminate\Http\Response
     */
    public function download(CreateCodeCard $request)
    {
        $cardCreator = new CodeCardCreator();
        $cardCreator->setLogo(Setting::get('bank.code_card.logo'));
        $cardCreator->setLabel(Setting::get('bank.code_card.label'));
        $cardCreator->create($request->amount);
    }
}
