const d = document;
const baseUrl = 'https://smartmanager.wibiocard.com/api'
const channel = 'NFC'
let apiKey
var _reader;
var _cardId;
var _cardType;
let _supportedCards;
let _target = document.getElementById('spinner-wrapper');

document.addEventListener("DOMContentLoaded", (event) => {
    listReaders();
});

async function listReaders() {
    showSpinner()
    try {
        apiKey = await navigator.webcard.apiKey();
        if(!apiKey)
            throw 'ApiKey not found';
        d.querySelector("#h_mess").innerHTML = "WibioWebcard Extension loaded";
        _supportedCards = await getSupportedCards();
        let readers = await navigator.webcard.readers();
            d.querySelector("#t_mess").innerHTML = readers.length + " readers detected";
            d.querySelector("#f_mess").innerHTML = "Put your card on the smartcard reader to start working!";
            d.querySelector("#f_mess").classList.replace("text-danger", "text-success");
            d.querySelector("#b_mess").innerHTML = "";
            var ul = document.createElement('ul');
            ul.classList.add('list-group');
            readers.forEach((reader, index) => {
                const li = document.createElement('li');
                if (reader.atr != "" && _supportedCards.find(c => c.Atr.replaceAll('-', '') == reader.atr))
                {
                    li.classList.add('list-group-item', 'list-group-item-success');
                    li.innerHTML = '<i class="bi bi-credit-card text-success"></i> <span>' + reader.name + '</span><span class="float-end text-sm">Card detected</span>';
                    loadInterface(reader);
                }
                else if (reader.atr != "")
                {
                    li.classList.add('list-group-item', 'list-group-item-danger');
                    li.innerHTML = '<i class="bi bi-credit-card text-danger"></i> <span>' + reader.name +  '</span><span class="float-end text-sm">not supported card</span>';
                }
                else
                {
                    li.classList.add('list-group-item', 'list-group-item-secondary');
                    li.innerHTML = '<i class="bi bi-x-octagon text-secondary"></i> <span>' + reader.name +  '</span><span class="float-end text-sm">Empty slot</span>';
                }
                ul.appendChild(li);
            });
            d.querySelector("#b_mess").appendChild(ul);
    } catch(ex){
        d.querySelector("#f_mess").innerHTML = ex.message;
        d.querySelector("#f_mess").classList.replace("text-success", "text-danger");
    }
    finally {
        hideSpinner()
    }

    navigator.webcard.cardInserted = function (reader) {
        loadInterface(reader);
    }

    navigator.webcard.cardRemoved = function (reader) {
        unloadInterface(reader);
    }
}

async function getSupportedCards(renew = false)
{
    if(!renew && _supportedCards && _supportedCards.length){
        return _supportedCards;
    }
    try {
        const response = await fetch(`${baseUrl}/getSupportedCards`, {
            method: 'GET',
            headers: {
                'X-Authorization': apiKey
            }
        });
        const data = await response.json();
        _supportedCards = [...(data.Cards || [])];
        return _supportedCards;
    } catch(err){
        console.error(err);
    }
    throw 'No supported cards found'
}

async function checkCardByAtr(Atr){
    try {
        const response = await fetch(`${baseUrl}/checkCardByAtr/${channel}/${Atr}`, {
            method: 'GET',
            headers: {
            'X-Authorization': apiKey
            }
        })
        return await response.json()
    } catch(err) {
        console.error(err);
    }
    throw 'Check card by atr failed'
}

async function loadInterface(reader)
{
    showSpinner();
    manageMessages("#h_mess", "s", "Card detected");
    manageMessages("#t_mess", null, "Card inserted in " + reader.name);
    manageMessages("#b_mess", null, "ATR detected");
    manageMessages("#f_mess", "s", "Wait while recognize card type!");
    try{
        const check = await checkCardByAtr(reader.atr);
        if(!check || !check.Id)
            throw 'Card id not found';
        if(Array.isArray(check.Id) && check.Id.length > 1) {
            if(!check.GetVersion)
                throw 'Needed command GetVersion not found'
            const idIdx = await execOnReader(reader, {
                name: 'GetVersion',
                command: check.GetVersion
            })
            _cardId = check.Id[idIdx[0].result.cmdIdx]
            _cardType = check.Type[idIdx[0].result.cmdIdx];
        } else {
            _cardId = check.Id[0]
            _cardType = check.Type[0];
        }
        _reader = reader;
        fetchPartialPage('/authentica'+_cardType, '#card_div');
        hideSpinner();
    } catch(err){
        manageMessages("#b_mess", "d", ex.message);
    }
}

function unloadInterface(reader)
{
    if (reader == _reader)
    {
        manageMessages("#h_mess", "d", "Card removed");
        manageMessages("#t_mess", null, "Card removed in " + reader.name);
        manageMessages("#b_mess", null, "");
        manageMessages("#b_mess", "d", "Put your card on the reader to continue working");
        d.querySelector('#card_div').innerHTML = "";
        _cardId = null;
        _cardType = null;
        _reader.disconnect();
        _reader = null;
    }
}

async function getCommand(cardId, commandName) {
    try {
        const response = await fetch(`${baseUrl}/generateCommand/${cardId}/${channel}/${commandName}`, {
            method: 'GET',
            headers: {
            'X-Authorization': apiKey
            }
        })
        return await response.json()
    } catch(err) {
        manageMessages("#b_mess", "d", "Error during command generation. Check your internet connection and try again");
    }
}

async function verifyTokenByEmail(token) {
    try {
        let email =  d.querySelector("#user_email").innerHTML;
        const response = await fetch(`${baseUrl}/verifyTokenByEmail/${token}/${email}`, {
            method: 'GET',
            headers: {
            'X-Authorization': apiKey
            }
        })
        return await response.text()
    } catch(err) {
        manageMessages("#b_mess", "d", "Error during verification request. Check your internet connection and try again");
    }
}

async function execOnReader(reader, commands) {
    try{
        if (!reader)
            throw 'Card not found on reader';
        await reader.connect(true);
        const results = []
        if(!Array.isArray(commands))
            commands = [commands]
        for(const c of commands){
            let startTime = new Date();
            try {
                const commandResult = await reader.transceive(c.command, c.params);
                results.push({
                    result: commandResult,
                    status: 'ok',
                    name: c.name
                })
            } catch(err){
                console.warn('Error during apdu execution', err)
                results.push({
                    status: 'incomplete',
                    name: c.name
                })
            }
            results[results.length - 1].elapsed = new Date() - startTime;
        }
        return results
    } catch(ex) {
        manageMessages("#b_mess", "d", ex.message);
    } finally {
        reader.disconnect();
    }
}

function showSpinner(){
    _target.style.display = 'block';
}

function hideSpinner(){
    _target.style.display = 'none';
}

function manageMessages(dest, type, message)
{
    d.querySelector(dest).innerHTML = message;
    if (type == "s")
        d.querySelector(dest).classList.replace("text-danger", "text-success");
    else if (type == "d")
        d.querySelector(dest).classList.replace("text-success", "text-danger");
}

function fetchPartialPage(url, dest)
{
    fetch(url).then(function (response) {
        return response.text();
    }).then(function (html) {
        var parser = new DOMParser();
        var doc = parser.parseFromString(html, 'text/html');
        d.querySelector(dest).innerHTML = doc.body.innerHTML;
        d.querySelector("#b_mess").innerHTML = "Card data loaded";
        executePartialScript(_cardType);
    }).catch(function (err) {
        manageMessages("#b_mess", "d", "Error loading card interface. Check your internet connection and try again");
    }
    );
}

const countDownClock = (number = 100, format = 'seconds') => {
    let countdown;
    timer(number);
    function timer(seconds) {
        const now = Date.now();
        const then = now + seconds * 1000;
        countdown = setInterval(() => {
            const secondsLeft = Math.round((then - Date.now()) / 1000);
            if(secondsLeft <= 0) {
                clearInterval(countdown);
                d.querySelector('#opt').innerHTML = '';
                return;
            };
            displayTimeLeft(secondsLeft);
        },1000);
    }
    function displayTimeLeft(seconds) {
        d.querySelector('.seconds').textContent = seconds % 60 < 10 ? `0${seconds % 60}` : seconds % 60;
    }
}

async function runCommand(commandName, cardId, reader, params = null) {
    try {
        const command = await getCommand(cardId, commandName);
        if(!command)
            throw 'Command not found';
        return await execOnReader(reader, {
            name: commandName,
            command: command.Response,
            params: params
        });
    } catch(ex) {
        manageMessages("#b_mess", "d", ex.message);
    }
}

async function executePartialScript(card_type)
{
    switch(card_type)
    {
        case 'F':
            showSpinner();
            try{
                let result;
                result = await runCommand("SelectBeCard", _cardId, _reader);
                if (result[0].status != "ok")
                    throw 'Applet not found';
                d.querySelector("#f_mess").innerHTML = "Card selected";
                d.querySelector("#f_mess").classList.replace("text-danger", "text-success");
                result = await runCommand("ReadSequenceInfo", _cardId, _reader);
                if (result[0].status == "ok")
                {
                    d.querySelector("#b_mess").innerHTML = "Card data loaded";
                    result[0].result.cmdRes.forEach((sequence, index) => {
                        d.querySelector("#sequences").add(new Option(sequence, sequence));
                    })
                }
            } catch(ex) {
                manageMessages("#b_mess", "d", ex.message);
            } finally {
                hideSpinner();
            }
            d.querySelector('#getOtp').addEventListener('click', async function(event){
                try{
                    let sequence = d.querySelector("#sequences").value;
                    if (!sequence)
                        throw "Select a sequence to get OTP";
                    if (await verifyTokenByEmail(sequence).result == "0")
                        throw "OTP sequence not associated to your email";
                    let result;
                    result = await runCommand("SelectBeCard", _cardId, _reader);
                    result = await runCommand("ReadOtpToken", _cardId, _reader, {"OtpMode": sequence});
                    if (result[0].status == "ok")
                    {
                        d.querySelector("#otp").innerHTML = result[0].result.cmdRes[0];
                        countDownClock(30);
                    }
                    else
                        throw "OTP not generated by card";
                } catch(ex) {
                    manageMessages("#b_mess", "d", ex.message);
                }
            });
            break;
        case 'C':
            break;
        default:
            break;
    }

}

