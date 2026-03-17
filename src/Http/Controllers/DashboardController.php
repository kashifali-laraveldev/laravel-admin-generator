<?php

namespace Bitsoftsol\LaravelAdministration\Http\Controllers;

use App\Http\Controllers\Controller;
use Bitsoftsol\LaravelAdministration\Models\User;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    /**
     * Crud the form for displaying the all models for laravel admin crud.
     */
    public function dashboard()
    {
        // If user is not authenticated then show login page on /admin url
        if(!Auth::check())
        {
            $errors = [];
            return view("laravel-admin::auth.login ")->withErrors($errors);
        }

        // Fetch all users count
        $usersCount = User::get()->count();

        // Fetch all users count registered today
        $newUsersCount = User::where('created_at', 'LIKE', date('Y-m-d') . "%")->count();

        // Fetch all users count that are not active
        $unConfirmedUsersCount = User::where('is_active', false)->count();

        // Fetch all users count that are active
        $confirmedUsersCount = User::where('is_active', true)->count();

        // Latest Registered Users
        $lastestUserList = User::orderBy('id', 'desc')->take(6)->get();

        // Make userGraph x and y axis and get from this private method
        $UserGraph = $this->getUserGraph();

        // If user is authenticated then show dashboard page on /admin url
        return view('laravel-admin::dashboard.index', get_defined_vars());
    }

    /*
        @getUserGraph
        This graph returns the last twelve month from current month.
        and returns the total users created in each month of last 12 months
    */
    private function getUserGraph()
    {
        // initializethe dates array
        $dates = [];

        // Get current date and get the starting day of last year from current day
        $start = (new DateTime(date('Y-m-d', strtotime("-1 Year"))))->modify('first day of this month');

        // Get current date and get the ending day of last year from current day
        $end = (new DateTime(date('Y-m-d', strtotime('-1 Month'))))->modify('first day of next month');

        // Get 1 Month Interval Between starting day and ending day of last year, On the month interval
        $interval = DateInterval::createFromDateString('1 Month');

        // Make a period array with start, end and interval parameters
        $period = new DatePeriod($start, $interval, $end);

        // Foreach the loop and get the each month of last year from current day.
        foreach ($period as $key => $dt) {
            // Date name like : Oct, 2022
            $dates[$key]['date_name'] = $dt->format("M, Y");
            // Date value like , 2022-10
            $dates[$key]['date_value'] = $dt->format("Y-m");
        }

        $data = [];

        foreach($dates AS $key => $date)
        {
            // Date name, means month name
            $data['months'][] = $date['date_name'];

            // User Count in this month
            $data['users'][] = User::where('created_at', 'LIKE', $date['date_value']."%")->count();
        }

        return $data;
    }
}
