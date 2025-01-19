# Aanmaken van een paar spelletjes

curl -X POST -d '{"id":1,"score":123}' localhost:8000/game/save
curl -X POST -d '{"id":2,"score":312}' localhost:8000/game/save
curl -X POST -d '{"id":3,"score":412}' localhost:8000/game/save
curl -X POST -d '{"id":4,"score":231}' localhost:8000/game/save

curl -X POST -d '{"id":1,"score":321,"api":"dogs",  "color_found":"red", "color_closed":"rebeccapurple" }' localhost:8000/game/save
curl -X POST -d '{"id":2,"score":312,"api":"cats",  "color_found":"green", "color_closed":"yellow" }' localhost:8000/game/save
curl -X POST -d '{"id":3,"score":131,"api":"clouds","color_found":"blue", "color_closed":"black" }' localhost:8000/game/save
curl -X POST -d '{"id":4,"score":412,"api":"people","color_found":"rebeccapurple", "color_closed":"white" }' localhost:8000/game/save

