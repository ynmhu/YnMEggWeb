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
source "$YnM_dirname/db-ynm.tcl"
source "$YnM_dirname/live.ynm.tcl"
source "$YnM_dirname/test.ynm.tcl" 

putlog "\00304\[YnM-Core\] \003 \002 Betöltve "
