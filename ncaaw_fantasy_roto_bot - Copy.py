import mysql.connector
from mysql.connector import errorcode

def start_connection():
    try:
        cnx = mysql.connector.connect(user='', password='',
                                host='31.220.54.225',
                                database='ncaaw_fantasy_roto')

    #print any errors and exit
    except mysql.connector.Error as err:
        if err.errno == errorcode.ER_ACCESS_DENIED_ERROR:
            print("Something is wrong with your user name or password")
        elif err.errno == errorcode.ER_BAD_DB_ERROR:
            print("Database does not exist")
        else:
            print(err)
        exit()

    # set TZ to PT
    cursor = cnx.cursor()
    cursor.execute("SET time_zone='-08:00'")
    cursor.close()

    return cnx

def close_connection(cnx):
    # close connection before we leave
    cnx.close()

def get_latest_update():
    # open the connection to do stuff
    cnx = start_connection()

    # set cursor parameters
    cursor = cnx.cursor(dictionary=True)

    query = ("SELECT * FROM ncaaw_fantasy_roto.last_update LIMIT 1")

    cursor.execute(query)

    for row in cursor:
        response = row['latest_update']

    cursor.close()

    # we're done, close the connection
    close_connection(cnx)

    return response

def get_todays_games():
    # open the connection to do stuff
    cnx = start_connection()

    # set cursor parameters
    cursor = cnx.cursor(dictionary=True)

    query = ("SELECT * FROM ncaaw_fantasy_roto.upcoming_today")

    cursor.execute(query)

    response = []
    for row in cursor:
        dict = {
            "school_name" : row['school_name'],
            "date" : row['date'],
            "opponent" : row['opponent'],
            "home" : "vs" if row['home'] == 1 else "at",
            "notes" : row['notes'],
            "opponent_school_id" : row['opponent_school_id']
        }
        response.append(dict)

    cursor.close()

    # we're done, close the connection
    close_connection(cnx)

    return response

def get_rankings():
    # gets the rankings for the week, manual update?

    # base table
    #Jeanette Stanford     2
    #Shelby   Notre Dame   7
    #Mary     UNC          8
    #B        Iowa         9
    #Kurt     Louisville  10
    #Kenneth  NC State    13
    #Nick     Maryland    14
    #Vim      Arizona     15
    #Peter    Oregon      18
    #Monica   Texas       19
    #Dave     UCLA        20
    #Logan    Tennessee   23
    preformattedTeams = {
        "Stanford": "`Jeanette Stanford    ",
        "Notre Dame": "`Shelby   Notre Dame  ",
        "UNC": "`Mary     UNC         ",
        "Iowa": "`B        Iowa        ",
        "Louisville": "`Kurt     Louisville  ",
        "NC State": "`Kenneth  NC State    ",
        "Maryland": "`Nick     Maryland    ",
        "Arizona": "`Vim      Arizona     ",
        "Oregon": "`Peter    Oregon      ",
        "Texas": "`Monica   Texas       ",
        "UCLA": "`Dave     UCLA        ",
        "Tennessee": "`Logan    Tennessee   "
    }

    # open the connection to do stuff
    cnx = start_connection()

    # set cursor parameters
    cursor = cnx.cursor(dictionary=True)

    query = ("SELECT * FROM ncaaw_fantasy_roto.rankings_week_four")

    cursor.execute(query)

    response = []
    for row in cursor:
        dict = {
            "manager" : row['manager'],
            "school" : row['school'],
            "rank" : row['rank']
        }
        response.append(dict)

    cursor.close()

    # we're done, close the connection
    close_connection(cnx)

    # build the table as a string that gets returned
    output = ""
    for teamDict in response:
        output += preformattedTeams[teamDict["school"]]
        rank = teamDict["rank"]
        if rank < 10:
            output += " " + str(rank)
        elif rank > 99:
            output += "NR"
        else:
            output += str(rank)
        output += "`\n"

    return output

# Start your app
if __name__ == "__main__":
    
    print("/get_latest_update")
    print(get_latest_update())
    print()
    print("/get_todays_games")
    print(get_todays_games())
    print()
    print("/get_rankings")
    print(get_rankings())