#&################################################################################
#&##		Created by Markus Lajos                                       
#&##		🔹 Contact: markus@ynm.hu                                     
#&##		🔹 Website: https://ynm.hu			            
#&##		🔹 All rights reserved.                                       
#&##							            
#&##  This software is developed by Markus Lajos. Unauthorized use, modification, 
#&##  or redistribution of this code is prohibited without prior written consent. 
#&##"For any injury or environmental emergency, call your local emergency number."
#&################################################################################ 

set YnM_dirname "YnM"
set YnM_tmp "$YnM_dirname/.tmp"
set YnM_uptime "$YnM_tmp/YnMUptime.ynm"
set YnM_Timers "$YnM_tmp/YnMTimers.ynm"

if {![file isdirectory $YnM_tmp]} {
    exec mkdir -p $YnM_tmp
}

set YnM_files [list $YnM_uptime $YnM_Timers]

foreach f $YnM_files {
    if {![file exists $f]} {
        set file [open $f w]
        close $file
    }
}

if {![file exists $YnM_uptime]} {
    set YnMBotStart [clock seconds]
    set file [open $YnM_uptime w]
    puts $file $YnMBotStart
    close $file
} else {
    set file [open $YnM_uptime r]
    set YnMBotStart [gets $file]
    close $file
    
    if {![string is integer -strict $YnMBotStart]} {
        set YnMBotStart [clock seconds]
        set file [open $YnM_uptime w]
        puts $file $YnMBotStart
        close $file
    }
}

if {![file exists $YnM_Timers]} {
    set YnMCheckTimerRunning 0
    set file [open $YnM_Timers w]
    puts $file $YnMCheckTimerRunning
    close $file
} else {
    set file [open $YnM_Timers r]
    set YnMCheckTimerRunning [gets $file]
    close $file
}

set YnMGlobal {YnM_dirname YnM_files YnM_tmp YnM_uptime YnMBotStart}

set YnMUptime [exec uptime -s]
set YnMStartTime [clock scan $YnMUptime]
set YnMCurrentTime [clock seconds]
set YnMServerUptimeSeconds [expr {$YnMCurrentTime - $YnMStartTime}]

set YnMDays [expr {$YnMServerUptimeSeconds / 86400}]
set YnMHours [expr {($YnMServerUptimeSeconds % 86400) / 3600}]
set YnMMinutes [expr {($YnMServerUptimeSeconds % 3600) / 60}]
set YnMServerUptime "${YnMDays}d ${YnMHours}h ${YnMMinutes}m"

set YnMonTime [expr {$YnMCurrentTime - $YnMBotStart}]
set YnMOnTime_days [expr {$YnMonTime / 86400}]
set YnMOnTime_hours [expr {($YnMonTime % 86400) / 3600}]
set YnMOnTime_minutes [expr {($YnMonTime % 3600) / 60}]
set YnMOnTimeFormatted "${YnMOnTime_days}d ${YnMOnTime_hours}h ${YnMOnTime_minutes}m"

set YnMGlobal [concat $YnMGlobal {YnM_Web YnM_Timers YnM_Upload YnMServerUptime YnMBotStart YnMOnTimeFormatted 
YnMonTime YnMUptime YnMStartTime YnMCurrentTime YnMServerUptimeSeconds YnMDays YnMHours YnMMinutes
YnMMysql_Update }]


source "$YnM_dirname/db-ynm.tcl"
source "$YnM_dirname/mysql-ynm.tcl"
source "$YnM_dirname/chan-ynm.tcl"
source "$YnM_dirname/timers-ynm.tcl"

foreach var $YnMGlobal {
   # puts "Globális változó: $var"
}


putlog "\00304\[YnM-Core\] \003 \002 Betöltve "
