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



proc YnMMysql_Update {} {
    global YnMGlobal
    # Globális
    foreach var $YnMGlobal {
        global $var
    }
  if {[strfind YnM_Upload [timers]]} {foreach timr [timers] {if {[strfind YnM_Upload $timr]} {killtimer [lindex $timr 2]}}}
  timer ${YnMMysqlUpdate} YnM_Upload
}
timer 1 YnMMysql_Update
     
set YnMGlobal [concat $YnMGlobal {YnMMysql_Update}]
putlog "\00304\[YnM-Timers\]\003 \002 Betöltve "