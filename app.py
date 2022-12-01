import os
import time
import datetime

import ncaaw_fantasy_roto_bot
from slack_bolt import App
from slack_bolt.adapter.socket_mode import SocketModeHandler

# helper functions
def log_cmd(input):
    t = time.localtime()
    current_time = time.strftime("%H:%M:%S", t)
    print(current_time + ": " + input)

# Initializes your app with your bot token and socket mode handler
app = App(
    token=os.environ.get("SLACK_BOT_TOKEN"),
    # signing_secret=os.environ.get("SLACK_SIGNING_SECRET") # not required for socket mode
)

# filter to stop spamming unhandled messages requests
@app.event("message")
def handle_message_events(body, logger):
    logger.info(body)

# Listens to incoming messages that contain "hello"
@app.message("hello")
def message_hello(message, say):
    # say() sends a message to the channel where the event was triggered
    say(
        blocks=[
            {
                "type": "section",
                "text": {"type": "mrkdwn", "text": f"Hey there <@{message['user']}>!"},
                "accessory": {
                    "type": "button",
                    "text": {"type": "plain_text", "text": "Click Me"},
                    "action_id": "button_click"
                }
            }
        ],
        text=f"Hey there <@{message['user']}>!"
    )

@app.action("button_click")
def action_button_click(body, ack, say):
    # Acknowledge the action
    ack()
    say(f"<@{body['user']['id']}> clicked the button")

@app.command("/latest_update")
def get_latest_update(ack, respond, command):
    # Acknowledge command request
    ack()
    log_cmd("/latest_update")
    latest = str(ncaaw_fantasy_roto_bot.get_latest_update())
    respond(latest)

@app.command("/get_todays_games")
def get_todays_games(ack, say, command):
    # Acknowledge command request
    ack()
    log_cmd("/get_todays_games")
    gamesList = ncaaw_fantasy_roto_bot.get_todays_games()
    blockList = [
        {
			"type": "header",
			"text": {
				"type": "plain_text",
				"text": "Games for " + datetime.date.today().strftime("%b %d"),
				"emoji": True
			}
		}
    ]
    if len(gamesList) == 0:
        blockList.append(
            {
                "type": "section",
                "text":
                {
                    "type": "mrkdwn",
                    "text": "No games today"
                }
            }
        )
    for gamesDict in gamesList:
        # build response section
        outText = gamesDict["school_name"] + " " + gamesDict["home"] + " " + gamesDict["opponent"];
        if gamesDict["opponent_school_id"] > 0:
            outText = "_" + outText + "_"; # italicize when managers are going head to head
        if len(gamesDict["notes"]) > 0:
            outText = " (" + gamesDict["notes"] + ")";
        blockList.append(
            {
                "type": "section",
                "text":
                {
                    "type": "mrkdwn",
                    "text": outText
                }
            }
        )
    
    # post the message
    say(
        blocks = blockList,
        text = 'The games for today'
    )

@app.command("/get_rankings")
def get_rankings(ack, say, command):
    # Acknowledge command request
    ack()
    log_cmd("/get_rankings")
    formattedRankings = ncaaw_fantasy_roto_bot.get_rankings()
    blockList = [
        {
			"type": "header",
			"text": {
				"type": "plain_text",
				"text": "Rankings through games played Nov 27",
				"emoji": True
			}
		}
    ]
    blockList.append(
        {
            "type": "section",
            "text":
            {
                "type": "mrkdwn",
                "text": formattedRankings
            }
        }
    )
    
    say(
        blocks = blockList,
        text = 'The games for today'
    )

# Start your app
if __name__ == "__main__":
    SocketModeHandler(app, os.environ["SLACK_APP_TOKEN"]).start()
