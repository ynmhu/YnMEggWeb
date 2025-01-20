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


proc YnM_Upload {} {
    global YnMGlobal
    # Deklaráld a változókat globálisként
    foreach var $YnMGlobal {
        global $var
    }
    
    # MySQL kapcsolódás próbálkozás
    if {[catch {mysql_connect $mysql_db $mysql_ho $mysql_us $mysql_pa} mysql_conn]} {
    #    puts "Hiba történt a MySQL kapcsolódásnál: $mysql_conn"
        return
    }

    set query "UPDATE \`On-Time/Up-Time\` SET \`Nick\` = '$YnMNick', \`OnTime\` = '$YnMOnTimeFormatted', \`UpTime\` = '$YnMServerUptime', \`Author\` = '$YnMAuthor', \`Server\` = '$YnMServer', \`Version\` = '$YnMVersion' WHERE \`Nick\` = '$YnMNick'"
    putserv "PRIVMSG #Eggdrop :$query"
    
    if {[catch {mysql_query $query} result]} {
    #    puts "Hiba történt a MySQL lekérdezés végrehajtása közben: $result"
    } else {
    #    puts "Sikeresen frissítve az adatbázis."
    }

    # Kapcsolódás lezárása
    if {[mysql_connected]} {
        mysql_close
    }
    YnMMysql_Update
}

proc YnM_Web {nick uhost hand chan text} {
    YnM_Upload
}
putlog "\00304\[YnM-Mysql\] \003 \002 Betöltve "