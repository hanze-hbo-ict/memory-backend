# Wijzigen van preferences van de admin-user
curl -H @player_token -X POST -d '{"id":1,"api":"dogs","color_found":"#ff0","color_closed":"#0ff"}' localhost:8000/api/player/1/preferences

