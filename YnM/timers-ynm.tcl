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
proc YnMMysql_Update {args} {
    global YnMGlobal

    # Globális változók betöltése
    foreach var $YnMGlobal {
        global $var
    }

    # Ellenőrizzük, hogy van-e már időzítő az YnM_Upload számára
    if {[lsearch -exact [timers] YnM_Upload] != -1} {
        foreach timr [timers] {
            # Töröljük a megfelelő időzítőt
            if {[lsearch -exact $timr YnM_Upload] != -1} {
                killtimer [lindex $timr 2]
	  }
        }
    }

    # Új időzítő létrehozása
    timer $YnMMysqlUpdate YnM_Upload
}

timer 0 YnMMysql_Update
putlog "\00304\[YnM-Timers\]\003 \002 Betöltve "
