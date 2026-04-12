# Aanmaken van een paar spelletjes


curl -H @admin_token -X POST -d '{"score":321,"api":"dogs",  "color_found":"red", "color_closed":"rebeccapurple" }' localhost:8000/game/save
curl -H @admin_token -X POST -d '{"score":312,"api":"cats",  "color_found":"green", "color_closed":"yellow" }' localhost:8000/game/save
curl -H @admin_token -X POST -d '{"score":131,"api":"clouds","color_found":"blue", "color_closed":"black" }' localhost:8000/game/save
curl -H @admin_token -X POST -d '{"score":412,"api":"people","color_found":"rebeccapurple", "color_closed":"white" }' localhost:8000/game/save


curl -H @player_token -X POST -d '{"score":32,"api":"picsum",  "color_found":"#ff00ff", "color_closed":"#421232" }' localhost:8000/game/save
curl -H @player_token -X POST -d '{"score":124,"api":"cats","color_found":"#ee22aa", "color_closed":"#1c1c1c" }' localhost:8000/game/save
curl -H @player_token -X POST -d '{"score":143,"api":"cataas",  "color_found":"#1c1c1c", "color_closed":"#ee22aa" }' localhost:8000/game/save
curl -H @player_token -X POST -d '{"score":432,"api":"minions","color_found":"#421232", "color_closed":"#ff00ff" }' localhost:8000/game/save