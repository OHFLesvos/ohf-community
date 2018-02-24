<?php

namespace App\Http\Controllers;

use App\Person;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $widgetClasses = [
            \App\Widgets\UsersWidget::class,
            \App\Widgets\ChangeLogWidget::class,
        ];
        $widgets = [];
        foreach($widgetClasses as $w) {
            $widget = new $w();
            if ($widget->authorize()) {
                $view = view($widget->view(), $widget->args());
                $widgets[] = $view->render();
            }
        }

        $args = [];
        $args['widgets'] = $widgets;

        if (Auth::user()->can('list', Person::class)) {
            $args['num_people'] = Person::count();
			$args['num_people_added_today'] = Person::whereDate('created_at', '=', Carbon::today())->count();
        }
        if (Gate::allows('do-bank-withdrawals') || Gate::allows('view-bank-reports')) {
            $args['num_transactions_today'] = Transaction::whereDate('created_at', '=', Carbon::today())->where('transactionable_type', 'App\Person')->count();
            $args['num_people_served_today'] = BankController::getNumberOfPersonsServedToday();
            $args['transaction_value_today'] = BankController::getTransactionValueToday();
        }
        $args['other'] = collect([
            [
                'route' => 'calendar',
                'icon' => 'calendar',
                'name' => 'Calendar / Scheduler',
                'gate' => 'view-calendar',
            ],
        ])->filter(function($e){ return Gate::allows($e['gate']); });
        return view('welcome', $args);
    }
}
