<!DOCTYPE html>
<html>

<head>
    <title>Attendance Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    
</head>
<body>
    <div class="container">
        <div class="row">
        <div class="col align-self-start">
            <br>
            <button type="button" class="btn btn-outline-info" onClick="printdiv('printable_div_id');">Print</button>
          </div>
        </div>
    </div>
    <div class="container" id="printable_div_id">
     
       
        <div class="row">
            <div class="col align-self-end">
                <br>
                <br>
                <img src="https://hr-dispatch.online/uploads/company/logo/Thumb-65503308645ec_logo.png" style="object-fit: cover" width="120" height="120" alt="">
                
              </div>
            <div class="col col-md-auto">
                <h4 style="text-align: center">KINGDOM OF CAMBODIA</h4>
                <h3 style="text-align: center">Nation - Religion - King</h3>
                <p style="text-align: center"><img height="15" src="{{asset('assets/images/bar_v.png') }}" alt=""></p>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12 align-self-center">
                    <h4 style="text-align: center;">{{ $title }} of <b>{{ucfirst($userDetail->name)}}</b></h4>
            </div>
           
            <div class="col-12 align-self-center">
                <h5 style="text-align: center;">Attendance Month: <b>{{ $month }}/{{$year}}</b></h5>
        </div>
        </div>
        <br>
        <div class="row">
            <div class="col">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th> Date </th>
                            <th scope="col">On Duty</th>
                            <th scope="col">Off Duty</th>
                            <th style="text-align: center;" >Check In At</th>
                            <th style="text-align: center;" >Check Out At</th>
                            <th style="text-align: center;">Worked Hour</th>
                            <th style="text-align: center;">Status</th>
                            <th style="text-align: center;">Attendance By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $changeColor = [
                            0 => 'danger',
                            1 => 'success',
                        ]
                        ?>
                        @forelse($attendanceDetail as $key => $value)
                                <tr>

                                    <td>{{ \App\Helpers\AppHelper::weekDay($value['attendance_date']) }}, {{\App\Helpers\AttendanceHelper::formattedAttendanceDate($value['attendance_date'])}}</td>
                                    <td>08:00 AM</td>
                                    <td>05:30 PM</td>
                                    @if(isset($value['check_in_at']))

                                        @if($value['check_in_at'])
                                            <td class="text-center">
                                                <span>
                                                    {{ ($value['check_in_at']) ? \App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceAdminView($value['check_in_at']):''}}
                                                </span>
                                            </td>
                                        @else
                                            <td></td>
                                        @endif


                                        @if($value['check_out_at'])
                                            <td class="text-center">
                                                <span>
                                                   {{  ($value['check_out_at']) ? \App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceAdminView($value['check_out_at']) : ''}}
                                                </span>
                                            </td>
                                        @else
                                            <td></td>
                                        @endif

                                        <td class="text-center">
                                            @if($value['check_out_at'])
                                                <span>
                                                    {{\App\Helpers\AttendanceHelper::getWorkedHourInHourAndMinute($value['check_in_at'],$value['check_out_at'])}}
                                                </span>
                                            @endif
                                         </td>

                                        @if(!is_null($value['attendance_status']))
                                            <td class="text-center">
                                                <span>
                                                {{($value['attendance_status'] == \App\Models\Attendance::ATTENDANCE_APPROVED) ? 'Approved':'Rejected'}}
                                                </span>
                                            </td>
                                        @else
                                            <td>
                                                   <span>
                                                        Pending
                                                    </span>
                                            </td>
                                        @endif

                                        @if($value['created_by'])
                                            <td class="text-center">
                                                    <span>
                                                        {{ ($value['user_id'] == $value['created_by'] )  ? 'Self' : 'Admin'}}
                                                    </span>
                                            </td>
                                        @else
                                            <td>
                                            </td>
                                        @endif
                                    @else
                                        <td class="text-center"> <i class="link-icon" data-feather="x"></i></td>
                                        <td class="text-center"> <i class="link-icon" data-feather="x"></i></td>
                                        <td class="text-center"> <i class="link-icon" data-feather="x"></i></td>
                                        <?php
                                            $reason = (\App\Helpers\AttendanceHelper::getHolidayOrLeaveDetail($value['attendance_date'], $userDetail->id));
                                        ?>
                                        @if($reason)
                                            <td class="text-center">
                                                <span>
                                                    {{$reason}}
                                                </span>
                                            </td>
                                        @else
                                        <td>
                                        </td>
                                        @endif
                                        <td class="text-center"> <i class="link-icon" data-feather="x"></i></td>
                                       
                                    @endif

                
                                </tr>

                        @empty
                            <tr>
                                <td colspan="100%">
                                    <p class="text-center"><b>No records found!</b></p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                </table>
            </div>
        </div>
        <div class="row justify-content-end">
            <div class="col col-md-auto align-self-end">
                <p style="text-align: end">Phnom Penh City, Date:  {{ $date }}</p>
                <p style="text-align: end; font-weight: bold;">COO OF CENTRIC KERNEL co.ltd</p>
            </div>
        </div>
    </div>
        
        <script>
            function printdiv(elem) {
              var header_str = '<html><head><title>' + document.title  + '</title></head><body>';
              var footer_str = '</body></html>';
              var new_str = document.getElementById(elem).innerHTML;
              var old_str = document.body.innerHTML;
              document.body.innerHTML = header_str + new_str + footer_str;
              window.print();
              document.body.innerHTML = old_str;
              return false;
            }
            </script>
</body>
</html>


