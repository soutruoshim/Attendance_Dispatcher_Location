<?php

namespace App\Repositories;

use App\Helpers\AppHelper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardRepository
{
    public function getCompanyDashboardDetail($companyId, $date)
    {
        $currentDate = AppHelper::getCurrentDateInYmdFormat();

        $totalCompanyEmployee = DB::table('users')
            ->select('company_id', DB::raw('COUNT(id) as total_employee'))
            ->whereNull('deleted_at')
            ->where('status', 'verified')
            ->where('is_active', 1)
            ->groupBy('company_id');

        $totalBranchs = DB::table('branches')
            ->select('company_id', DB::raw('COUNT(id) as total_branchs'))
            ->where('is_active', 1)
            ->groupBy('company_id');

        $totalDepartments = DB::table('departments')
            ->select('company_id', DB::raw('COUNT(id) as total_departments'))
            ->where('is_active', 1)
            ->groupBy('company_id');    

        $totalCheckedInEmployee = DB::table('attendances')
            ->select('company_id', DB::raw('COUNT(id) as total_checked_in_employee'))
            ->whereDate('attendance_date', $currentDate)
            ->whereNotNull('check_in_at')
            ->groupBy('company_id');

        $totalCheckedOutEmployee = DB::table('attendances')
            ->select('company_id', DB::raw('COUNT(id) as total_checked_out_employee'))
            ->whereDate('attendance_date', $currentDate)
            ->whereNotNull('check_in_at')
            ->whereNotNull('check_out_at')
            ->groupBy('company_id');

        $onLeaveEmployee = DB::table('leave_requests_master')
            ->select('company_id', DB::raw('count(id) as total_on_leave'))
            ->whereDate('leave_from', '<=', $currentDate)
            ->whereDate('leave_to', '>=', $currentDate)
            ->where('status', 'approved')
            ->groupBy('company_id');

        $pendingLeavesRequests = DB::table('leave_requests_master')
            ->select('company_id', DB::raw('count(id) as total_pending_leave_requests'))
            ->where('status', 'pending');
        if (isset($date['start_date'])) {
            $pendingLeavesRequests->whereBetween('leave_requested_date', [$date['start_date'], $date['end_date']]);
        } else {
            $pendingLeavesRequests->whereYear('leave_requested_date', $date['year']);
        }
        $pendingLeavesRequests->groupBy('company_id');

//        $urgentLeavesRequests = DB::table('leave_requests_master')
//            ->select('company_id', DB::raw('count(id) as total_urgent_leave_request'))
//            ->whereDate('leave_requested_date', $currentDate)
//            ->where('early_exit', 1)
//            ->where('status', 'pending')
//            ->groupBy('company_id');

        $companyPaidLeaves = DB::table('leave_types')
            ->select('company_id', DB::raw('sum(leave_allocated) as total_paid_leaves'))
            ->whereNotNull('leave_allocated')
            ->where('is_active', '1')
            ->groupBy('company_id');

        $totalHolidaysInYear = DB::table('holidays')
            ->select('company_id', DB::raw('count(id) as total_holidays'))
            ->where('is_active', '1');
        $totalHolidaysInYear->whereMonth('event_date', Carbon::today()->month);
        // if (isset($date['start_date'])) {
        //     $totalHolidaysInYear->whereBetween('event_date', [$date['start_date'], $date['end_date']]);
        // } else {
        //     $totalHolidaysInYear->whereYear('event_date', $date['year']);
        // }
        $totalHolidaysInYear->groupBy('company_id');

        



        $projects = DB::table('projects')
            ->select('users.company_id as company_id', DB::raw('count(projects.id) as total_projects'))
            ->leftJoin('users', function ($join) {
                $join->on('projects.created_by', '=', 'users.id');
            })
            ->groupBy('users.company_id');


        return DB::table('companies')->select(
            'companies.name as company_name',
            'company_employee.total_employee',
            'checked_in_employee.total_checked_in_employee',
            'checked_out_employee.total_checked_out_employee',
            'holidays.total_holidays',
            'on_leave_today.total_on_leave',
            'paid_leaves.total_paid_leaves',
            'pending_leave_requests.total_pending_leave_requests',
            'departments.total_departments',
            'branchs.total_branchs',
//            'urgent_leave_requests.total_urgent_leave_request',
            'projects.total_projects'
        )
            ->leftJoinSub($totalCompanyEmployee, 'company_employee', function ($join) {
                $join->on('companies.id', '=', 'company_employee.company_id');
            })

            ->leftJoinSub($totalDepartments, 'departments', function ($join) {
                $join->on('companies.id', '=', 'departments.company_id');
            })
            ->leftJoinSub($totalBranchs, 'branchs', function ($join) {
                $join->on('companies.id', '=', 'branchs.company_id');
            })
            ->leftJoinSub($totalCheckedInEmployee, 'checked_in_employee', function ($join) {
                $join->on('companies.id', '=', 'checked_in_employee.company_id');
            })
            ->leftJoinSub($totalCheckedOutEmployee, 'checked_out_employee', function ($join) {
                $join->on('companies.id', '=', 'checked_out_employee.company_id');
            })
            ->leftJoinSub($totalHolidaysInYear, 'holidays', function ($join) {
                $join->on('companies.id', '=', 'holidays.company_id');
            })
            ->leftJoinSub($onLeaveEmployee, 'on_leave_today', function ($join) {
                $join->on('companies.id', '=', 'on_leave_today.company_id');
            })
            ->leftJoinSub($companyPaidLeaves, 'paid_leaves', function ($join) {
                $join->on('companies.id', '=', 'paid_leaves.company_id');
            })
            ->leftJoinSub($pendingLeavesRequests, 'pending_leave_requests', function ($join) {
                $join->on('companies.id', '=', 'pending_leave_requests.company_id');
            })
//            ->leftJoinSub($urgentLeavesRequests, 'urgent_leave_requests', function ($join) {
//                $join->on('companies.id', '=', 'urgent_leave_requests.company_id');
//            })
            ->leftJoinSub($projects, 'projects', function ($join) {
                $join->on('companies.id', '=', 'projects.company_id');
            })
            ->where('companies.is_active', 1)
            ->where('companies.id', $companyId)
            ->first();

    }

    public function getHolidayThisMonth($companyId)
    {
        //DB::enableQueryLog();
        $totalHolidaysInMonth = DB::table('holidays')
        ->select('id', 'event', 'event_date')
        ->whereMonth('event_date', Carbon::today()->month)
        ->where('is_active', '1')
        ->get();
        //dd(DB::getQueryLog());

        return $totalHolidaysInMonth;

    }

}


