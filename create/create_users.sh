# Aanmaken van spelers

curl -X POST -d '{"name":"Henk", "email":"henk@henk", "password":"henk"}' localhost:8000/register
curl -X POST -d '{"name":"Karel", "email":"karel@karel", "password":"karel"}' localhost:8000/register
curl -X POST -d '{"name":"Fenna", "email":"fenna@fenna", "password":"fenna"}' localhost:8000/register
curl -X POST -d '{"name":"Chantal", "email":"chantal@chantal", "password":"chantal"}' localhost:8000/register

#Voor windows
curl -method POST -Body '{"name":"Chantal", "email":"chantal@chantal", "password":"chantal"}' -Uri http://localhost:8000/register 
