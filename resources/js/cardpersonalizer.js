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
        d.querySelector("#f_mess").innerHTML = ex;
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
    } catch(ex){
        console.error(ex);
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
    } catch(ex) {
        console.error(ex);
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
            await reader.connect(true);
            const idIdx = await execOnReader(reader, {
                name: 'GetVersion',
                command: check.GetVersion
            })
            reader.disconnect();
            _cardId = check.Id[idIdx[0].result.cmdIdx]
            _cardType = check.Type[idIdx[0].result.cmdIdx];
        } else {
            _cardId = check.Id[0]
            _cardType = check.Type[0];
        }
        _reader = reader;
        executePartialScript(_cardType);
        hideSpinner();
    } catch(ex){
        manageMessages("#b_mess", "d", ex);
    }
}

function unloadInterface(reader)
{
    if (reader == _reader)
    {
        manageMessages("#h_mess", "d", "Card removed");
        manageMessages("#t_mess", null, "Card removed in " + reader.name);
        manageMessages("#b_mess", null, "");
        manageMessages("#f_mess", "d", "Put your card on the reader to continue working");
        d.querySelector('#card_div').innerHTML = "";
        _cardId = null;
        _cardType = null;
        _reader.disconnect();
        _reader = null;
    }
}

async function getCommand(cardId, commandName)
{
    try {
        const response = await fetch(`${baseUrl}/generateCommand/${cardId}/${channel}/${commandName}`, {
            method: 'GET',
            headers: {
            'X-Authorization': apiKey
            }
        })
        return await response.json()
    } catch(ex) {
        manageMessages("#b_mess", "d", "Error during command generation. Check your internet connection and try again");
    }
}

async function generateCommand(cardId, commandName, request)
{
    try {
        const response = await fetch(`${baseUrl}/generateCommand/${cardId}/${channel}/${commandName}`, {
            method: 'POST',
            headers: {
                'X-Authorization': apiKey,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(request)
        })
        return await response.json()
    } catch(ex) {
        manageMessages("#b_mess", "d", "Error during command generation. Check your internet connection and try again");
    }
}

async function execOnReader(reader, commands) {
    try{
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
            } catch(ex){
                console.log(ex);
                results.push({
                    status: 'incomplete',
                    name: c.name
                })
            }
            results[results.length - 1].elapsed = new Date() - startTime;
        }
        return results
    } catch(ex) {
        manageMessages("#b_mess", "d", ex);
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
    {
        d.querySelector(dest).classList.replace("text-danger", "text-success");
        d.querySelector(dest).classList.replace("bg-danger", "bg-success");
        d.querySelector(dest).classList.replace("bg-light-danger", "bg-light-success");
    }
    else if (type == "d")
    {
        d.querySelector(dest).classList.replace("text-success", "text-danger");
        d.querySelector(dest).classList.replace("bg-success", "bg-danger");
        d.querySelector(dest).classList.replace("bg-light-success", "bg-light-danger");
    }
}

async function runCommand(commandName, cardId, reader, params = null, request = null) {
    try {
        const command = (!request)?await getCommand(cardId, commandName, request):await generateCommand(cardId, commandName, request);
        if(!command)
            throw 'Command not found';
        return await execOnReader(reader, {
            name: commandName,
            command: command.Response,
            params: params
        });
    } catch(ex) {
        manageMessages("#b_mess", "d", ex);
    }
}

async function executePartialScript(card_type)
{
    switch(card_type)
    {
        case 'F':
            let result;
            try{
                showSpinner();
                if (!_reader)
                    throw 'Card not found on reader';
                await _reader.connect(true);
                result = await runCommand("SelectBeCard", _cardId, _reader);
                if (result[0].status != "ok")
                    throw 'Applet not found';
                manageMessages("#f_mess", "s", "Card selected");
                manageMessages("#b_mess", "s", "");
                result = await runCommand("ReadSequenceInfo", _cardId, _reader);
            } catch(ex) {
                manageMessages("#b_mess", "d", ex);
            } finally {
                hideSpinner();
                _reader.disconnect();
            }
            if (result[0].status == "ok")
            {
                manageMessages("#f_mess", "d", "Card already in use, to perform this operation you need a factory resetted card");
                d.querySelector("#run_perso").disabled = true
                break;
            }
            else
            {
                manageMessages("#f_mess", "s", "Card personalization available, this card is empty");
                d.querySelector("#run_perso").disabled = false
                d.querySelector('#run_perso').addEventListener('click', async function(e){
                    e.preventDefault();
                    try{
                        await _reader.connect(true);
                        result = await runCommand("SelectBeCard", _cardId, _reader);
                        if (result[0].status != "ok")
                            throw 'Applet not found';
                        result = await runCommand("PersonalizeF", _cardId, _reader, null, {"token": d.querySelector("#token").value});
                        if (result[0].status != "ok")
                            throw 'Card personalization error';
                        result = await runCommand("ReadSequenceInfo", _cardId, _reader);
                        if (result[0].status == "ok")
                        {
                            d.querySelector("#b_mess").innerHTML = "Card data updated successfully";
                            result[0].result.cmdRes.forEach((sequence, index) => {
                                d.querySelector("#b_mess").innerHTML += sequence;
                            })
                        }
                        else
                            throw "Card personalization error";

                    } catch(ex) {
                        manageMessages("#b_mess", "d", ex);
                    } finally {
                        hideSpinner();
                        _reader.disconnect();
                    }
                });
            }
            break;
        default:
            manageMessages("#b_mess", "d", "Card type doesn't support cloud personalization");
            break;
    }

}

