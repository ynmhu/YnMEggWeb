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
#&# On change here Need! RESTART###
set mysql_ho "localhost"
set mysql_us "ai"
set mysql_pa ".."
set mysql_db "ynmegg"
#set mysql_conn [mysql_connect $mysql_db $mysql_ho $mysql_us $mysql_pa]
#&################################################################################
#bind pub - !ynm YnM_Web
set YnMNick "YnM-Egg-Web"
set YnMVersion "Eggdrop+Web"
set YnMAuthor "Markus"
set YnMServer "YnM-IrC"
#&################################################################################
set YnMMysqlUpdate 60
set ynmegg(api) "https://ai.ynm.hu/api.php" ; #Api Server
set ynmegg(key) "......" ;      #Api  Key
set ynmegg_start_file "YnM/.tmp/ynmegg_start.txt"

#&################################################################################
#set ynmcom "ynmcon"


set YnMGlobal [concat $YnMGlobal {mysql_ho mysql_us mysql_pa mysql_db mysql_conn 
ynmcom YnMNick YnMVersion YnMAuthor YnMServer YnMMysqlUpdate YnMWebDcc}]
putlog "\00304\[YnM-DataBase\]\003 \002 Betöltve "
