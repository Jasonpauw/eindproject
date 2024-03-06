let params = new URLSearchParams(window.location.search);
let userid = params.get("userId");
let hash = params.get("hash")

let correctPokemon = [];
let guessedPokemon = [];
const INPUT = document.querySelector('#search')
const START = document.querySelector('#start');
const STOP = document.querySelector('#stop');
const SCORE = document.querySelector('#score');

const TOTAL = 151;

let timeDuration = 300;
let minutes = Math.floor(timeDuration / 60);
let seconds = timeDuration % 60;
let tableDataId = 1;
let currentscore = 0;
let countdown = null;

setPokemonFromAPi();

async function setPokemonFromAPi () {
    let response = await fetch('https://pokeapi.co/api/v2/generation/1');
    let json = await response.json();


    json.pokemon_species.forEach((element, index) => {
        correctPokemon.push(element.name);
        const tr = document.createElement('tr');
        tr.setAttribute('id', 'pokemon' + tableDataId)
        tableDataId ++;
        const td = document.createElement('td');
        td.innerText = index + 1;
        tr.appendChild(td);
        document.querySelector('#pokemon_table').appendChild(tr);
    })    
}
async function timerStart(){

    timeDuration = 300;

    countdown = setInterval(async function () {

        minutes = Math.floor(timeDuration / 60);
        seconds = timeDuration % 60;

        document.querySelector("#timer").innerText = "Timer " +minutes+":"+seconds;

        if (timeDuration <= 0) {
            clearInterval(countdown);
            alert("Time's up!");
            postScore();
        } else {
            timeDuration = timeDuration - 1;
        }
    }, 1000);

}
async function postScore(){
    const response = await fetch('https://64903.cvoatweb.be/api/score', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({link: "/games/pokeman/index.html",
            userid: userid,
            hash: hash,
            points: currentscore,
            time: minutes.toString() + ':' + seconds.toString() + 'minutes'})
    });
}


START.addEventListener('click', (event) => {
    timerStart()
    INPUT.disabled = false;
    START.disabled = true;
    STOP.disabled = false;
})
STOP.addEventListener('click', (event) => {
    STOP.disabled = true;
    postScore();
})


SCORE.innerText = 'score = ' + currentscore + '/' + TOTAL;

    INPUT.addEventListener('input',async (e) =>  {
        let isValueAMon = correctPokemon.find((pokemon) => pokemon === e.target.value.toLowerCase());
        if(isValueAMon === undefined) {
            return;
        }
        let guessedAlready = guessedPokemon.find((pokemon) => pokemon === e.target.value.toLowerCase());
        if(guessedAlready !== undefined) {
            return;
        }
        currentscore += 1;
        guessedPokemon.push(isValueAMon);
        if(guessedPokemon.includes('pikachu')) {
            const response = await fetch('https://64903.cvoatweb.be/api/achievement', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({link: "/games/pokeman/index.html",
                    userid: userid,
                    hash: hash,
                    title: 'mascot!!',
                    description: 'you guessed the mascot!!!',
                    picture: 'https://assets.pokemon.com/assets/cms2/img/pokedex/full/025.png'})
            });
        }
        if(guessedPokemon.includes('charizard')) {
            const response = await fetch('https://64903.cvoatweb.be/api/achievement', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({link: "/games/pokeman/index.html",
                    userid: userid,
                    hash: hash,
                    title: 'best pokemon',
                    description: 'you guessed the best pokemon!!!',
                    picture: 'https://assets.pokemon.com/assets/cms2/img/pokedex/full/006.png'})
            });
        }
        if(currentscore === TOTAL){
            await postScore();
            const response = await fetch('https://64903.cvoatweb.be/api/achievement', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({link: "/games/pokeman/index.html",
                    userid: userid,
                    hash: hash,
                    title: 'max points',
                    description: 'you guessed all pokemon',
                    picture: 'https://en.pokemart.be/wp-content/uploads/2022/06/pokedex.png'})
            });
        }
        INPUT.value = '';
        const td = document.createElement('td');
        td.innerText = isValueAMon;
        document.querySelector('#pokemon' + currentscore).appendChild(td);
        SCORE.innerText = 'score = ' + currentscore + '/' + TOTAL;
    })

    