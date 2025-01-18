##################################################################################
##		Created by Markus Lajos                                     
##		🔹 Contact: markus@ynm.hu                                   
##		🔹 Website: https://ynm.hu			          
##		🔹 All rights reserved.                                     
##							         
##This software is developed by Markus Lajos. Unauthorized use, modification,   
##or redistribution of this code is prohibited without prior written consent.   
##"For any injury or environmental emergency, call your local emergency number."
##################################################################################

#!/usr/bin/env tclsh
# Debug üzenet
#puts "Bot script indítása..."

# Database configuration


# Szerver uptime lekérése
proc get_server_uptime {} {
    # A 'uptime -s' kimenet az indítás ideje (pl. "2025-01-18 08:00:00")
    set uptime [exec uptime -s]
    
    # A 'uptime -s' kimenet az indítás idejét tartalmazza, ezt beolvassuk
    set start_time [clock scan $uptime]
    
    # A jelenlegi idő
    set current_time [clock seconds]
    
    # Az uptime különbsége másodpercekben
    set server_uptime [expr {$current_time - $start_time}]
    
    # Számoljuk ki a napokat, órákat, perceket (másodperceket kihagyva)
    set days [expr {$server_uptime / 86400}]
    set hours [expr {($server_uptime % 86400) / 3600}]
    set minutes [expr {($server_uptime % 3600) / 60}]
    
    # Az emberi olvasható formátum (másodpercek nélkül)
    set human_readable_uptime "${days}d ${hours}h ${minutes}m"
    
    # Visszatérünk a formázott uptime-mal
    return $human_readable_uptime
}

# Az uptime rögzítése
proc record_uptime {} {
    global bot_start_time mysql_ho mysql_us mysql_pa mysql_db
    set botname "YnM-Egg-Web"
    
    #puts "Uptime rögzítése kezdődik..."
    
    # Connect to database
    if {[catch {mysql_connect $mysql_db $mysql_ho $mysql_us $mysql_pa} mysql_conn]} {
    #    puts "Hiba történt a MySQL kapcsolódáskor! Hiba: $mysql_conn"
        return
    }
    #puts "MySQL kapcsolat létrejött"
    
    # Alapértelmezett uptime érték
    set ontime [expr {[clock seconds] - $bot_start_time}]
    
    # Az 'ontime' átalakítása napok, órák, percek formátumba
    set ontime_days [expr {$ontime / 86400}]
    set ontime_hours [expr {($ontime % 86400) / 3600}]
    set ontime_minutes [expr {($ontime % 3600) / 60}]
    
    set formatted_ontime "${ontime_days}d ${ontime_hours}h ${ontime_minutes}m"
    
    # Szerver uptime lekérése
    set server_uptime [get_server_uptime]
    
    # Get current timestamp
    set timestamp [clock seconds]
    
    #puts "Rögzített uptime: $formatted_ontime, timestamp: $timestamp"
    
    set version "Eggdrop"
    
    # Az UPDATE parancs, ami mindig felülírja a rekordot
    set query "UPDATE \`On-Time/Up-Time\` SET \`BotNick\` = '$botname', \`OnTime\` = '$formatted_ontime', \`Server_Uptime\` = '$server_uptime', \`Version\` = '$version' WHERE \`BotNick\` = '$botname'"

    #puts "Rekord frissítése..."
    
    # Ha hiba történik a mysql_query parancs végrehajtása során
    if {[catch {mysql_query $query} result]} {
    #    puts "Hiba történt az uptime rögzítésekor bot: $botname, hiba: $result"
    } else {
    #    puts "Uptime frissítve bot: $botname, botnick: $botname, uptime: $formatted_ontime"
    }
    
    # Close connection
    if {[mysql_connected]} {
        mysql_close
    #    puts "MySQL kapcsolat lezárva"
    }
}

# Timer procedure - runs every 5 minutes (300000 milliseconds)
proc check_timer {} {
    record_uptime
    after 3000000 check_timer
    puts "Timer fut: következő ellenőrzés 5 perc múlva"
}

# Initialize the timer
check_timer

# Bot status loop - status check every minute
proc main_loop {} {
    puts "Bot még mindig fut..."
    after 3600000 main_loop
}

# Start the main loop
main_loop
