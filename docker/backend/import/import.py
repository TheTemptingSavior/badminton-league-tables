import sqlite3
import json

def get_team_id(team_id):
    if team_id == 1:  # Melton Mowbray
        return 3
    elif team_id == 2:  # Rockingham
        return 4
    elif team_id == 3:  # Stamford Badminton A
        return 5
    elif team_id == 4:  # Stamford Badminton B
        return 6
    elif team_id == 5:  # Stamford Community
        return 7
    elif team_id == 6:  # Uppingham
        return 8
    elif team_id == 17:  # Meltonshire A
        return 1
    elif team_id == 18:  # Melthonshire B
        return 2


conn = sqlite3.connect("/home/ethan/Downloads/database.sqlite")
cursor = conn.cursor()


rows = cursor.execute("SELECT * FROM mensgames")
with open("./scorecards.csv", "w") as f:
    for row in rows:
        line = ""
        home_players = json.loads(row[3])
        away_players = json.loads(row[4])
        set1 = json.loads(row[9])
        set2 = json.loads(row[10])
        set3 = json.loads(row[11])
        set4 = json.loads(row[12])
        set5 = json.loads(row[13])
        set6 = json.loads(row[14])
        set7 = json.loads(row[15])
        set8 = json.loads(row[16])
        set9 = json.loads(row[17])

        line += "%d," % get_team_id(row[1])  # home_team
        line += "%d," % get_team_id(row[2])  # away_team
        line += "%s," % row[5]  # date_played
        line += "%d," % row[6]  # home_points
        line += "%d," % row[7]  # away_points
        line += "%s," % home_players["player1"]  # home_player_1
        line += "%s," % home_players["player2"]  # home_player_2
        line += "%s," % home_players["player3"]  # home_player_3
        line += "%s," % home_players["player4"]  # home_player_4
        line += "%s," % home_players["player5"]  # home_player_5
        line += "%s," % home_players["player6"]  # home_player_6
        line += "%s," % away_players["player1"]  # away_player_1
        line += "%s," % away_players["player2"]  # away_player_2
        line += "%s," % away_players["player3"]  # away_player_3
        line += "%s," % away_players["player4"]  # away_player_4
        line += "%s," % away_players["player5"]  # away_player_5
        line += "%s," % away_players["player6"]  # away_player_6
        line += "%d," % int(set1["game1"][0]) if set1["game1"][0] is not None and len(set1["game1"][0]) != 0 else "NULL,"  # game_one_v_one_home_one
        line += "%d," % int(set1["game1"][1]) if set1["game1"][1] is not None and len(set1["game1"][1]) != 0 else "NULL,"  # game_one_v_one_away_one
        line += "%d," % int(set1["game2"][0]) if set1["game2"][0] is not None and len(set1["game2"][0]) != 0 else "NULL,"  # game_one_v_one_home_two
        line += "%d," % int(set1["game2"][1]) if set1["game2"][1] is not None and len(set1["game2"][1]) != 0 else "NULL,"  # game_one_v_one_away_two
        line += "%d," % int(set1["game3"][0]) if set1["game3"][0] is not None and len(set1["game3"][0]) != 0 else "NULL,"  # game_one_v_one_home_three
        line += "%d," % int(set1["game3"][1]) if set1["game3"][1] is not None and len(set1["game3"][1]) != 0 else "NULL,"  # game_one_v_one_away_three
        line += "%d," % int(set2["game1"][0]) if set2["game1"][0] is not None and len(set2["game1"][0]) != 0 else "NULL,"  # game_one_v_two_home_one
        line += "%d," % int(set2["game1"][1]) if set2["game1"][1] is not None and len(set2["game1"][1]) != 0 else "NULL,"  # game_one_v_two_away_one
        line += "%d," % int(set2["game2"][0]) if set2["game2"][0] is not None and len(set2["game2"][0]) != 0 else "NULL,"  # game_one_v_two_home_two
        line += "%d," % int(set2["game2"][1]) if set2["game2"][1] is not None and len(set2["game2"][1]) != 0 else "NULL,"  # game_one_v_two_away_two
        line += "%d," % int(set2["game3"][0]) if set2["game3"][0] is not None and len(set2["game3"][0]) != 0 else "NULL,"  # game_one_v_two_home_three
        line += "%d," % int(set2["game3"][1]) if set2["game3"][1] is not None and len(set2["game3"][1]) != 0 else "NULL,"  # game_one_v_two_away_three
        line += "%d," % int(set3["game1"][0]) if set3["game1"][0] is not None and len(set3["game1"][0]) != 0 else "NULL,"  # game_one_v_three_home_one
        line += "%d," % int(set3["game1"][1]) if set3["game1"][1] is not None and len(set3["game1"][1]) != 0 else "NULL,"  # game_one_v_three_away_one
        line += "%d," % int(set3["game2"][0]) if set3["game2"][0] is not None and len(set3["game2"][0]) != 0 else "NULL,"  # game_one_v_three_home_two
        line += "%d," % int(set3["game2"][1]) if set3["game2"][1] is not None and len(set3["game2"][1]) != 0 else "NULL,"  # game_one_v_three_away_two
        line += "%d," % int(set3["game3"][0]) if set3["game3"][0] is not None and len(set3["game3"][0]) != 0 else "NULL,"  # game_one_v_three_home_three
        line += "%d," % int(set3["game3"][1]) if set3["game3"][1] is not None and len(set3["game3"][1]) != 0 else "NULL,"  # game_one_v_three_away_three

        line += "%d," % int(set4["game1"][0]) if set4["game1"][0] is not None and len(set4["game1"][0]) != 0 else "NULL,"  # game_two_v_one_home_one
        line += "%d," % int(set4["game1"][1]) if set4["game1"][1] is not None and len(set4["game1"][1]) != 0 else "NULL,"  # game_two_v_one_away_one
        line += "%d," % int(set4["game2"][0]) if set4["game2"][0] is not None and len(set4["game2"][0]) != 0 else "NULL,"  # game_two_v_one_home_two
        line += "%d," % int(set4["game2"][1]) if set4["game2"][1] is not None and len(set4["game2"][1]) != 0 else "NULL,"  # game_two_v_one_away_two
        line += "%d," % int(set4["game3"][0]) if set4["game3"][0] is not None and len(set4["game3"][0]) != 0 else "NULL,"  # game_two_v_one_home_three
        line += "%d," % int(set4["game3"][1]) if set4["game3"][1] is not None and len(set4["game3"][1]) != 0 else "NULL,"  # game_two_v_one_away_three
        line += "%d," % int(set5["game1"][0]) if set5["game1"][0] is not None and len(set5["game1"][0]) != 0 else "NULL,"  # game_two_v_two_home_one
        line += "%d," % int(set5["game1"][1]) if set5["game1"][1] is not None and len(set5["game1"][1]) != 0 else "NULL,"  # game_two_v_two_away_one
        line += "%d," % int(set5["game2"][0]) if set5["game2"][0] is not None and len(set5["game2"][0]) != 0 else "NULL,"  # game_two_v_two_home_two
        line += "%d," % int(set5["game2"][1]) if set5["game2"][1] is not None and len(set5["game2"][1]) != 0 else "NULL,"  # game_two_v_two_away_two
        line += "%d," % int(set5["game3"][0]) if set5["game3"][0] is not None and len(set5["game3"][0]) != 0 else "NULL,"  # game_two_v_two_home_three
        line += "%d," % int(set5["game3"][1]) if set5["game3"][1] is not None and len(set5["game3"][1]) != 0 else "NULL,"  # game_two_v_two_away_three
        line += "%d," % int(set6["game1"][0]) if set6["game1"][0] is not None and len(set6["game1"][0]) != 0 else "NULL,"  # game_two_v_three_home_one
        line += "%d," % int(set6["game1"][1]) if set6["game1"][1] is not None and len(set6["game1"][1]) != 0 else "NULL,"  # game_two_v_three_away_one
        line += "%d," % int(set6["game2"][0]) if set6["game2"][0] is not None and len(set6["game2"][0]) != 0 else "NULL,"  # game_two_v_three_home_two
        line += "%d," % int(set6["game2"][1]) if set6["game2"][1] is not None and len(set6["game2"][1]) != 0 else "NULL,"  # game_two_v_three_away_two
        line += "%d," % int(set6["game3"][0]) if set6["game3"][0] is not None and len(set6["game3"][0]) != 0 else "NULL,"  # game_two_v_three_home_three
        line += "%d," % int(set6["game3"][1]) if set6["game3"][1] is not None and len(set6["game3"][1]) != 0 else "NULL,"  # game_two_v_three_away_three

        line += "%d," % int(set7["game1"][0]) if set7["game1"][0] is not None and len(set7["game1"][0]) != 0 else "NULL,"  # game_three_v_one_home_one
        line += "%d," % int(set7["game1"][1]) if set7["game1"][1] is not None and len(set7["game1"][1]) != 0 else "NULL,"  # game_three_v_one_away_one
        line += "%d," % int(set7["game2"][0]) if set7["game2"][0] is not None and len(set7["game2"][0]) != 0 else "NULL,"  # game_three_v_one_home_two
        line += "%d," % int(set7["game2"][1]) if set7["game2"][1] is not None and len(set7["game2"][1]) != 0 else "NULL,"  # game_three_v_one_away_two
        line += "%d," % int(set7["game3"][0]) if set7["game3"][0] is not None and len(set7["game3"][0]) != 0 else "NULL,"  # game_three_v_one_home_three
        line += "%d," % int(set7["game3"][1]) if set7["game3"][1] is not None and len(set7["game3"][1]) != 0 else "NULL,"  # game_three_v_one_away_three
        line += "%d," % int(set8["game1"][0]) if set8["game1"][0] is not None and len(set8["game1"][0]) != 0 else "NULL,"  # game_three_v_two_home_one
        line += "%d," % int(set8["game1"][1]) if set8["game1"][1] is not None and len(set8["game1"][1]) != 0 else "NULL,"  # game_three_v_two_away_one
        line += "%d," % int(set8["game2"][0]) if set8["game2"][0] is not None and len(set8["game2"][0]) != 0 else "NULL,"  # game_three_v_two_home_two
        line += "%d," % int(set8["game2"][1]) if set8["game2"][1] is not None and len(set8["game2"][1]) != 0 else "NULL,"  # game_three_v_two_away_two
        line += "%d," % int(set8["game3"][0]) if set8["game3"][0] is not None and len(set8["game3"][0]) != 0 else "NULL,"  # game_three_v_two_home_three
        line += "%d," % int(set8["game3"][1]) if set8["game3"][1] is not None and len(set8["game3"][1]) != 0 else "NULL,"  # game_three_v_two_away_three
        line += "%d," % int(set9["game1"][0]) if set9["game1"][0] is not None and len(set9["game1"][0]) != 0 else "NULL,"  # game_three_v_three_home_one
        line += "%d," % int(set9["game1"][1]) if set9["game1"][1] is not None and len(set9["game1"][1]) != 0 else "NULL,"  # game_three_v_three_away_one
        line += "%d," % int(set9["game2"][0]) if set9["game2"][0] is not None and len(set9["game2"][0]) != 0 else "NULL,"  # game_three_v_three_home_two
        line += "%d," % int(set9["game2"][1]) if set9["game2"][1] is not None and len(set9["game2"][1]) != 0 else "NULL,"  # game_three_v_three_away_two
        line += "%d," % int(set9["game3"][0]) if set9["game3"][0] is not None and len(set9["game3"][0]) != 0 else "NULL,"  # game_three_v_three_home_three
        line += "%d" % int(set9["game3"][1]) if set9["game3"][1] is not None and len(set9["game3"][1]) != 0 else "NULL"  # game_three_v_three_away_three

        f.write(line + "\n")

